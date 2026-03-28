<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::where('disponible', true)->paginate(9);

        $carsData = $cars->getCollection()
            ->map(function (Car $car) {
                return [
                    'id' => $car->id,
                    'marque' => $car->marque,
                    'modele' => $car->modele,
                    'annee' => $car->annee,
                    'carburant' => $car->carburant,
                    'prix_par_jour' => (float) $car->prix_par_jour,
                    'image_url' => $car->image_url,
                ];
            })
            ->values();

        return view('cars.index', compact('cars', 'carsData'));
    }

    public function show($id)
    {
        $car = Car::findOrFail($id);
        return view('cars.show', compact('car'));
    }
}
