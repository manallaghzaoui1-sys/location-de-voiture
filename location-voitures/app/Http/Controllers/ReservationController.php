<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function create($car_id)
    {
        $car = Car::findOrFail($car_id);
        return view('reservations.create', compact('car'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
        ]);

        $car = Car::findOrFail($request->car_id);
        
        if (!$car->isDisponible($request->date_debut, $request->date_fin)) {
            return back()->with('error', 'Cette voiture n\'est pas disponible pour ces dates.');
        }

        $dateDebut = new \DateTime($request->date_debut);
        $dateFin = new \DateTime($request->date_fin);
        $nombreJours = $dateDebut->diff($dateFin)->days;
        $prixTotal = $nombreJours * $car->prix_par_jour;

        Reservation::create([
            'user_id' => Auth::id(),
            'car_id' => $car->id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'prix_total' => $prixTotal,
            'statut' => 'en_attente'
        ]);

        return redirect()->route('reservations.user')
            ->with('success', 'Votre réservation a été effectuée avec succès!');
    }

    public function userReservations()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('car')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('reservations.user', compact('reservations'));
    }
}