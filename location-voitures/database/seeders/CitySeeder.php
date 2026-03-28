<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Casablanca', 'travel_fee' => 120, 'is_active' => true],
            ['name' => 'Rabat', 'travel_fee' => 90, 'is_active' => true],
            ['name' => 'Marrakech', 'travel_fee' => 150, 'is_active' => true],
            ['name' => 'Fès', 'travel_fee' => 130, 'is_active' => true],
            ['name' => 'Tanger', 'travel_fee' => 180, 'is_active' => true],
            ['name' => 'Agadir', 'travel_fee' => 170, 'is_active' => true],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(['name' => $city['name']], $city);
        }
    }
}
