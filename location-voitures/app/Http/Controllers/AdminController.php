<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Requests\UpdateCityRequest;
use App\Models\Car;
use App\Models\City;
use App\Models\Reservation;
use App\Models\User;
use App\Services\CarSnapshotService;
use App\Services\ContractFieldConfigService;
use App\Services\ContractPdfService;
use App\Services\ImageStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct(
        private readonly ImageStorageService $imageStorageService,
        private readonly ContractPdfService $contractPdfService,
        private readonly ContractFieldConfigService $contractFieldConfigService,
        private readonly CarSnapshotService $carSnapshotService,
    ) {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $totalCars = Car::count();
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('statut', 'en_attente')->count();
        $confirmedReservations = Reservation::where('statut', 'confirme')->count();
        $monthlyRevenue = Reservation::whereIn('statut', ['confirme', 'termine'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('prix_total');
        $recentReservations = Reservation::with(['user', 'car', 'city'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCars',
            'totalReservations',
            'pendingReservations',
            'confirmedReservations',
            'monthlyRevenue',
            'recentReservations'
        ));
    }

    public function indexCars(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'availability' => ['nullable', 'in:0,1'],
            'fuel' => ['nullable', 'string', 'max:60'],
        ]);

        $search = trim((string) ($validated['search'] ?? ''));
        $availability = (string) ($validated['availability'] ?? '');
        $fuel = trim((string) ($validated['fuel'] ?? ''));

        $cars = Car::query()
            ->when($search !== '', fn ($query) => $query->where(function ($nested) use ($search) {
                $nested->where('marque', 'like', "%{$search}%")
                    ->orWhere('modele', 'like', "%{$search}%");
            }))
            ->when($availability !== '', fn ($query) => $query->where('disponible', $availability === '1'))
            ->when($fuel !== '', fn ($query) => $query->where('carburant', $fuel))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $fuelOptions = Car::query()
            ->select('carburant')
            ->distinct()
            ->orderBy('carburant')
            ->pluck('carburant');

        return view('admin.cars.index', compact('cars', 'search', 'availability', 'fuel', 'fuelOptions'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();
        $data['disponible'] = $request->boolean('disponible');

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageStorageService->storeCarImage($request->file('image'));
        }

        Car::create($data);
        $this->syncCarsSnapshotSafely();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Voiture ajoutee avec succes.');
    }

    public function edit($id)
    {
        $car = Car::findOrFail($id);

        return view('admin.cars.edit', compact('car'));
    }

    public function update(UpdateCarRequest $request, $id)
    {
        $car = Car::findOrFail($id);

        $data = $request->validated();
        $data['disponible'] = $request->boolean('disponible');

        if ($request->hasFile('image')) {
            $this->imageStorageService->deletePublicFile($car->image);
            $data['image'] = $this->imageStorageService->storeCarImage($request->file('image'));
        }

        $car->update($data);
        $this->syncCarsSnapshotSafely();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Voiture modifiee avec succes.');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $this->imageStorageService->deletePublicFile($car->image);
        $car->delete();
        $this->syncCarsSnapshotSafely();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Voiture supprimee avec succes.');
    }

    public function reservations(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'in:en_attente,confirme,annule,termine'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $search = trim((string) ($validated['search'] ?? ''));
        $status = (string) ($validated['status'] ?? '');
        $cityId = (string) ($validated['city_id'] ?? '');
        $dateFrom = (string) ($validated['date_from'] ?? '');
        $dateTo = (string) ($validated['date_to'] ?? '');

        $reservations = Reservation::with(['user', 'car', 'city'])
            ->when($search !== '', fn ($query) => $query->where(function ($nested) use ($search) {
                $nested->where('contract_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
            }))
            ->when($status !== '', fn ($query) => $query->where('statut', $status))
            ->when($cityId !== '', fn ($query) => $query->where('city_id', $cityId))
            ->when($dateFrom !== '', fn ($query) => $query->whereDate('date_debut', '>=', $dateFrom))
            ->when($dateTo !== '', fn ($query) => $query->whereDate('date_fin', '<=', $dateTo))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $cities = City::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.reservations.index', compact('reservations', 'search', 'status', 'cityId', 'dateFrom', 'dateTo', 'cities'));
    }

    public function updateReservationStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'statut' => ['required', 'in:en_attente,confirme,annule,termine'],
        ]);

        $reservation = Reservation::findOrFail($id);
        $this->authorize('updateStatus', $reservation);
        $previousStatus = $reservation->statut;
        $reservation->update($validated);

        if ($validated['statut'] === 'confirme' && $previousStatus !== 'confirme') {
            $reservation->loadMissing(['user', 'car', 'city']);
            $pdfPath = $this->contractPdfService->generateAndStore($reservation);
            $reservation->update(['contract_pdf_path' => $pdfPath]);
        }

        return back()->with('success', 'Statut mis a jour avec succes.');
    }

    public function citiesIndex()
    {
        $cities = City::orderBy('name')->paginate(15);

        return view('admin.cities.index', compact('cities'));
    }

    public function citiesStore(StoreCityRequest $request)
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        City::create($payload);

        return back()->with('success', 'Ville ajoutee avec succes.');
    }

    public function citiesUpdate(UpdateCityRequest $request, City $city)
    {
        $payload = $request->validated();
        $payload['is_active'] = $request->boolean('is_active');

        $city->update($payload);

        return back()->with('success', 'Ville mise a jour avec succes.');
    }

    public function citiesDestroy(City $city)
    {
        if ($city->reservations()->exists()) {
            return back()->with('error', 'Impossible de supprimer une ville deja utilisee dans une reservation.');
        }

        $city->delete();

        return back()->with('success', 'Ville supprimee avec succes.');
    }

    public function downloadContract(Reservation $reservation)
    {
        return $this->contractPdfService->downloadResponse($reservation);
    }

    public function contractFields()
    {
        $fields = $this->contractFieldConfigService->getFields();
        $sources = $this->contractFieldConfigService->availableSources();
        $sections = $this->contractFieldConfigService->availableSections();

        return view('admin.contracts.fields', compact('fields', 'sources', 'sections'));
    }

    public function updateContractFields(Request $request)
    {
        $validated = $request->validate([
            'fields' => ['required', 'array', 'min:1'],
            'fields.*.id' => ['nullable', 'string', 'max:100'],
            'fields.*.label' => ['required', 'string', 'max:120'],
            'fields.*.source' => ['required', 'string', 'max:50'],
            'fields.*.section' => ['required', 'in:parties,vehicle,financial'],
            'fields.*.value' => ['nullable', 'string', 'max:300'],
            'fields.*.enabled' => ['nullable'],
        ]);

        $rawFields = [];

        foreach ($validated['fields'] as $field) {
            $rawFields[] = [
                'id' => $field['id'] ?? null,
                'label' => $field['label'],
                'source' => $field['source'],
                'section' => $field['section'],
                'value' => $field['value'] ?? null,
                'enabled' => array_key_exists('enabled', $field),
            ];
        }

        $this->contractFieldConfigService->saveFields($rawFields);

        return back()->with('success', 'Configuration des champs du contrat mise a jour.');
    }

    public function resetContractFields()
    {
        $this->contractFieldConfigService->resetToDefaults();

        return back()->with('success', 'Champs du contrat reinitialises aux valeurs par defaut.');
    }

    public function downloadClientDocument(User $user, string $type)
    {
        abort_unless(in_array($type, ['cin', 'permis'], true), 404);

        $path = $type === 'cin' ? $user->cin_document_path : $user->permis_document_path;
        abort_if(! $path || ! Storage::disk('local')->exists($path), 404);

        return response()->download(Storage::disk('local')->path($path));
    }

    private function syncCarsSnapshotSafely(): void
    {
        try {
            $this->carSnapshotService->syncFromDatabase();
        } catch (\Throwable $exception) {
            Log::warning('Cars snapshot sync failed after admin catalog update.', [
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
