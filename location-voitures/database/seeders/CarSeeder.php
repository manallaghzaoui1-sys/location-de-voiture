<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'marque' => 'Renault',
                'modele' => 'Clio',
                'annee' => 2022,
                'carburant' => 'Essence',
                'prix_par_jour' => 250,
                'description' => 'Voiture compacte idéale pour la ville, économique et facile à garer.',
                'disponible' => true,
            ],
            [
                'marque' => 'Peugeot',
                'modele' => '208',
                'annee' => 2023,
                'carburant' => 'Diesel',
                'prix_par_jour' => 280,
                'description' => 'Design moderne et confortable, parfaite pour les longs trajets.',
                'disponible' => true,
            ],
            [
                'marque' => 'Dacia',
                'modele' => 'Sandero',
                'annee' => 2022,
                'carburant' => 'Essence',
                'prix_par_jour' => 200,
                'description' => 'Rapport qualité-prix exceptionnel, spacieuse et fiable.',
                'disponible' => true,
            ],
            [
                'marque' => 'Volkswagen',
                'modele' => 'Golf',
                'annee' => 2023,
                'carburant' => 'Essence',
                'prix_par_jour' => 350,
                'description' => 'Berline compacte premium, alliant confort et performance.',
                'disponible' => true,
            ],
            [
                'marque' => 'Toyota',
                'modele' => 'Corolla',
                'annee' => 2023,
                'carburant' => 'Hybride',
                'prix_par_jour' => 400,
                'description' => 'Berline confortable et économique, technologie hybride.',
                'disponible' => true,
            ],
            [
                'marque' => 'BMW',
                'modele' => 'Serie 3',
                'annee' => 2023,
                'carburant' => 'Diesel',
                'prix_par_jour' => 600,
                'description' => 'Luxe et performance, une expérience de conduite exceptionnelle.',
                'disponible' => true,
            ],
        ];

        foreach ($cars as $car) {
            Car::updateOrCreate(
                ['marque' => $car['marque'], 'modele' => $car['modele'], 'annee' => $car['annee']],
                $car
            );
        }
    }
}
