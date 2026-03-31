<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Car;
use App\Models\City;
use App\Models\Reservation;
use App\Services\ContractPdfService;
use App\Services\ReservationNotificationService;
use App\Services\ReservationPricingService;
use App\Services\UrlObfuscationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationPricingService $pricingService,
        private readonly ContractPdfService $contractPdfService,
        private readonly ReservationNotificationService $notificationService,
        private readonly UrlObfuscationService $urlObfuscationService,
    ) {
    }

    public function create(string $carToken)
    {
        $carId = $this->urlObfuscationService->decodeCarToken($carToken);
        abort_if($carId === null, 404);

        $car = Car::findOrFail($carId);
        $cities = City::where('is_active', true)->orderBy('name')->get();

        return view('reservations.create', compact('car', 'cities', 'carToken'));
    }

    public function store(StoreReservationRequest $request)
    {
        $carId = $this->urlObfuscationService->decodeCarToken((string) $request->input('car_token'));
        abort_if($carId === null, 404);

        $car = Car::findOrFail($carId);
        $city = City::findOrFail($request->input('city_id'));

        if (! $car->isDisponible($request->date_debut, $request->date_fin)) {
            return back()->withInput()->with('error', 'Cette voiture n\'est pas disponible pour ces dates.');
        }

        $pricing = $this->pricingService->calculate($car, $city, $request->date_debut, $request->date_fin);

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'car_id' => $car->id,
            'city_id' => $city->id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prix_location' => $pricing['prix_location'],
            'frais_deplacement' => $pricing['frais_deplacement'],
            'prix_total' => $pricing['prix_total'],
            'statut' => 'en_attente',
            'contract_reference' => $this->generateContractReference(),
        ]);

        $reservation->load(['user', 'car', 'city']);

        try {
            $pdfPath = $this->contractPdfService->generateAndStore($reservation);
            $reservation->update(['contract_pdf_path' => $pdfPath]);
            $reservation->refresh();
        } catch (\Throwable $exception) {
            Log::error('Contract PDF generation failed.', [
                'reservation_id' => $reservation->id,
                'error' => $exception->getMessage(),
            ]);
        }

        $clientEmailSent = $this->notificationService->sendReservationConfirmation($reservation);
        $adminEmailSent = $this->notificationService->sendReservationAdminNotification($reservation);

        $message = 'Reservation enregistree.';
        $flashType = 'success';

        if ($clientEmailSent && $adminEmailSent) {
            $message = 'Reservation enregistree. Email de confirmation envoye au client et notification envoyee a l admin.';
        } elseif ($clientEmailSent && ! $adminEmailSent) {
            $message = 'Reservation enregistree. Email client envoye, notification admin non envoyee.';
            $flashType = 'error';
        } elseif (! $clientEmailSent && $adminEmailSent) {
            $message = 'Reservation enregistree. Notification admin envoyee, email client non envoye.';
            $flashType = 'error';
        } else {
            $message = 'Reservation enregistree, mais les emails n ont pas ete envoyes.';
            $flashType = 'error';
        }

        return redirect()
            ->route('reservations.confirmation', $reservation)
            ->with($flashType, $message);
    }

    public function confirmation(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        $reservation->load(['car', 'city']);

        return view('reservations.confirmation', compact('reservation'));
    }

    public function userReservations()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['car', 'city'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('reservations.user', compact('reservations'));
    }

    public function downloadContract(Reservation $reservation)
    {
        $this->authorize('downloadContract', $reservation);

        return $this->contractPdfService->downloadResponse($reservation);
    }

    private function generateContractReference(): string
    {
        do {
            $reference = 'CTR-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (Reservation::where('contract_reference', $reference)->exists());

        return $reference;
    }
}
