<?php

namespace App\Services;

use App\Models\Car;
use App\Models\City;
use Carbon\Carbon;

class ReservationPricingService
{
    public function calculate(Car $car, City $city, string $dateDebut, string $dateFin): array
    {
        $start = Carbon::parse($dateDebut);
        $end = Carbon::parse($dateFin);
        $days = $start->diffInDays($end);

        $rentalPrice = round($days * (float) $car->prix_par_jour, 2);
        $travelFee = round((float) $city->travel_fee, 2);
        $total = (int) round($rentalPrice + $travelFee);

        return [
            'days' => $days,
            'prix_location' => $rentalPrice,
            'frais_deplacement' => $travelFee,
            'prix_total' => $total,
        ];
    }
}
