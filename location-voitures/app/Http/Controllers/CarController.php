<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Services\UrlObfuscationService;

class CarController extends Controller
{
    public function __construct(
        private readonly UrlObfuscationService $urlObfuscationService,
    ) {
    }

    public function index()
    {
        $cars = Car::where('disponible', true)->paginate(9);

        $carsData = $cars->getCollection()
            ->map(function (Car $car) {
                $token = $this->urlObfuscationService->encodeCarId($car->id);

                return [
                    'sort_key' => $car->created_at?->timestamp ?? 0,
                    // Keep both token and id for backward-compatibility with older cached JS bundles.
                    'token' => $token,
                    'id' => $token,
                    'details_url' => route('cars.show', $token),
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

    public function show(string $carToken)
    {
        $carId = $this->urlObfuscationService->decodeCarToken($carToken);
        abort_if($carId === null, 404);

        $car = Car::findOrFail($carId);

        return view('cars.show', compact('car', 'carToken'));
    }
}
