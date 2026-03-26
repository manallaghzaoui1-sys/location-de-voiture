<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalCars = Car::count();
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('statut', 'en_attente')->count();
        $recentReservations = Reservation::with(['user', 'car'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact('totalCars', 'totalReservations', 'pendingReservations', 'recentReservations'));
    }

    public function indexCars()
    {
        $cars = Car::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'marque' => 'required',
            'modele' => 'required',
            'annee' => 'required|integer|min:1990|max:' . date('Y'),
            'carburant' => 'required',
            'prix_par_jour' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('cars', 'public');
            $data['image'] = $path;
        }
        
        Car::create($data);
        
        return redirect()->route('admin.cars.index')
            ->with('success', 'Voiture ajoutée avec succès!');
    }

    public function edit($id)
    {
        $car = Car::findOrFail($id);
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        
        $request->validate([
            'marque' => 'required',
            'modele' => 'required',
            'annee' => 'required|integer',
            'carburant' => 'required',
            'prix_par_jour' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'disponible' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $path = $request->file('image')->store('cars', 'public');
            $data['image'] = $path;
        }
        
        $car->update($data);
        
        return redirect()->route('admin.cars.index')
            ->with('success', 'Voiture modifiée avec succès!');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }
        $car->delete();
        
        return redirect()->route('admin.cars.index')
            ->with('success', 'Voiture supprimée avec succès!');
    }

    public function reservations()
    {
        $reservations = Reservation::with(['user', 'car'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.reservations', compact('reservations'));
    }

    public function updateReservationStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['statut' => $request->statut]);
        
        return back()->with('success', 'Statut mis à jour avec succès!');
    }
}