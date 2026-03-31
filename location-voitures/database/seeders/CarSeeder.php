<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = $this->loadCarsFromSnapshot();

        if (count($cars) === 0) {
            $cars = $this->defaultCars();
        }

        foreach ($cars as $car) {
            $this->restoreSnapshotImage($car['image'] ?? null);

            Car::updateOrCreate(
                ['marque' => $car['marque'], 'modele' => $car['modele'], 'annee' => $car['annee']],
                $car
            );
        }
    }

    private function loadCarsFromSnapshot(): array
    {
        $path = base_path('database/seed-data/cars.json');

        if (! File::exists($path)) {
            return [];
        }

        $decoded = json_decode((string) File::get($path), true);
        if (! is_array($decoded)) {
            return [];
        }

        $rows = $decoded['cars'] ?? null;
        if (! is_array($rows)) {
            return [];
        }

        return array_values(array_filter($rows, static function ($row): bool {
            return is_array($row)
                && isset($row['marque'], $row['modele'], $row['annee'], $row['carburant'], $row['prix_par_jour']);
        }));
    }

    private function defaultCars(): array
    {
        return [
            [
                'marque' => 'Renault',
                'modele' => 'Clio',
                'annee' => 2022,
                'carburant' => 'Essence',
                'prix_par_jour' => 250,
                'image' => null,
                'description' => 'Voiture compacte ideale pour la ville, economique et facile a garer.',
                'disponible' => true,
            ],
            [
                'marque' => 'Peugeot',
                'modele' => '208',
                'annee' => 2023,
                'carburant' => 'Diesel',
                'prix_par_jour' => 280,
                'image' => null,
                'description' => 'Design moderne et confortable, parfaite pour les longs trajets.',
                'disponible' => true,
            ],
            [
                'marque' => 'Dacia',
                'modele' => 'Sandero',
                'annee' => 2022,
                'carburant' => 'Essence',
                'prix_par_jour' => 200,
                'image' => null,
                'description' => 'Rapport qualite-prix exceptionnel, spacieuse et fiable.',
                'disponible' => true,
            ],
            [
                'marque' => 'Volkswagen',
                'modele' => 'Golf',
                'annee' => 2023,
                'carburant' => 'Essence',
                'prix_par_jour' => 350,
                'image' => null,
                'description' => 'Berline compacte premium, alliant confort et performance.',
                'disponible' => true,
            ],
            [
                'marque' => 'Toyota',
                'modele' => 'Corolla',
                'annee' => 2023,
                'carburant' => 'Hybride',
                'prix_par_jour' => 400,
                'image' => null,
                'description' => 'Berline confortable et economique, technologie hybride.',
                'disponible' => true,
            ],
            [
                'marque' => 'BMW',
                'modele' => 'Serie 3',
                'annee' => 2023,
                'carburant' => 'Diesel',
                'prix_par_jour' => 600,
                'image' => null,
                'description' => 'Luxe et performance, une experience de conduite exceptionnelle.',
                'disponible' => true,
            ],
        ];
    }

    private function restoreSnapshotImage(?string $filename): void
    {
        if (! is_string($filename) || trim($filename) === '') {
            return;
        }

        $source = base_path('database/seed-data/car-images/' . $filename);
        if (! File::exists($source)) {
            return;
        }

        $targetDir = public_path('images/images_voiture');
        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $target = $targetDir . DIRECTORY_SEPARATOR . $filename;
        if (! File::exists($target)) {
            File::copy($source, $target);
        }
    }
}
