<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CarSnapshotService
{
    private const SNAPSHOT_PATH = 'database/seed-data/cars.json';
    private const SNAPSHOT_IMAGES_DIR = 'database/seed-data/car-images';
    private const PUBLIC_IMAGES_DIR = 'public/images/images_voiture';
    private const CACHE_HASH_KEY = 'cars_snapshot_applied_hash';

    public function syncFromDatabase(): void
    {
        $absolutePath = base_path(self::SNAPSHOT_PATH);
        $directory = dirname($absolutePath);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $cars = Car::query()
            ->orderBy('id')
            ->get([
                'marque',
                'modele',
                'annee',
                'carburant',
                'prix_par_jour',
                'image',
                'description',
                'disponible',
            ])
            ->map(function (Car $car): array {
                $this->snapshotCarImage($car->image);

                return [
                    'marque' => (string) $car->marque,
                    'modele' => (string) $car->modele,
                    'annee' => (int) $car->annee,
                    'carburant' => (string) $car->carburant,
                    'prix_par_jour' => (int) $car->prix_par_jour,
                    'image' => $car->image ? (string) $car->image : null,
                    'description' => $car->description ? (string) $car->description : null,
                    'disponible' => (bool) $car->disponible,
                ];
            })
            ->values()
            ->all();

        $payload = [
            'updated_at' => now()->toIso8601String(),
            'cars' => $cars,
        ];

        File::put(
            $absolutePath,
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function syncToDatabaseIfChanged(): void
    {
        $absolutePath = base_path(self::SNAPSHOT_PATH);
        if (! File::exists($absolutePath)) {
            return;
        }

        $snapshotHash = md5_file($absolutePath) ?: null;
        if (! $snapshotHash) {
            return;
        }

        if (Cache::get(self::CACHE_HASH_KEY) === $snapshotHash) {
            return;
        }

        $decoded = json_decode((string) File::get($absolutePath), true);
        $rows = is_array($decoded) && isset($decoded['cars']) && is_array($decoded['cars'])
            ? $decoded['cars']
            : [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            if (! isset($row['marque'], $row['modele'], $row['annee'], $row['carburant'], $row['prix_par_jour'])) {
                continue;
            }

            Car::updateOrCreate(
                [
                    'marque' => (string) $row['marque'],
                    'modele' => (string) $row['modele'],
                    'annee' => (int) $row['annee'],
                ],
                [
                    'carburant' => (string) $row['carburant'],
                    'prix_par_jour' => (int) $row['prix_par_jour'],
                    'image' => ! empty($row['image']) ? (string) $row['image'] : null,
                    'description' => $row['description'] ?? null,
                    'disponible' => (bool) ($row['disponible'] ?? true),
                ]
            );

            if (! empty($row['image'])) {
                $this->restoreCarImage((string) $row['image']);
            }
        }

        Cache::forever(self::CACHE_HASH_KEY, $snapshotHash);
    }

    private function snapshotCarImage(?string $filename): void
    {
        if (! is_string($filename) || trim($filename) === '') {
            return;
        }

        $source = base_path(self::PUBLIC_IMAGES_DIR . '/' . $filename);
        if (! File::exists($source)) {
            return;
        }

        $targetDir = base_path(self::SNAPSHOT_IMAGES_DIR);
        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $target = $targetDir . DIRECTORY_SEPARATOR . $filename;
        if (! File::exists($target)) {
            File::copy($source, $target);
        }
    }

    private function restoreCarImage(string $filename): void
    {
        $source = base_path(self::SNAPSHOT_IMAGES_DIR . '/' . $filename);
        if (! File::exists($source)) {
            return;
        }

        $targetDir = base_path(self::PUBLIC_IMAGES_DIR);
        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $target = $targetDir . DIRECTORY_SEPARATOR . $filename;
        if (! File::exists($target)) {
            File::copy($source, $target);
        }
    }
}
