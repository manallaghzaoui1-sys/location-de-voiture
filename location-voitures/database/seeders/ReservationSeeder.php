<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\City;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $client1 = User::where('email', 'client1@example.com')->first();
        $client2 = User::where('email', 'client2@example.com')->first();
        $car1 = Car::orderBy('id')->first();
        $car2 = Car::orderBy('id')->skip(1)->first();
        $city1 = City::where('name', 'Casablanca')->first() ?? City::orderBy('id')->first();
        $city2 = City::where('name', 'Rabat')->first() ?? City::orderBy('id')->skip(1)->first();

        if (! $client1 || ! $client2 || ! $car1 || ! $car2 || ! $city1 || ! $city2) {
            return;
        }

        $reservations = [
            [
                'contract_reference' => 'DEMO-RES-0001',
                'user_id' => $client1->id,
                'car_id' => $car1->id,
                'city_id' => $city1->id,
                'date_debut' => '2026-04-05',
                'date_fin' => '2026-04-08',
                'prix_location' => 750,
                'frais_deplacement' => (float) $city1->travel_fee,
                'prix_total' => 750 + (float) $city1->travel_fee,
                'statut' => 'confirme',
            ],
            [
                'contract_reference' => 'DEMO-RES-0002',
                'user_id' => $client2->id,
                'car_id' => $car2->id,
                'city_id' => $city2->id,
                'date_debut' => '2026-04-10',
                'date_fin' => '2026-04-12',
                'prix_location' => 560,
                'frais_deplacement' => (float) $city2->travel_fee,
                'prix_total' => 560 + (float) $city2->travel_fee,
                'statut' => 'en_attente',
            ],
        ];

        foreach ($reservations as $reservation) {
            Reservation::updateOrCreate(
                ['contract_reference' => $reservation['contract_reference']],
                $reservation
            );
        }
    }
}
