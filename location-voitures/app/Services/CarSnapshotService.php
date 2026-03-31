<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class CarSnapshotService
{
    private const SNAPSHOT_PATH = 'database/seed-data/cars.json';
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
                'description',
                'disponible',
            ])
            ->map(static function (Car $car): array {
                return [
                    'marque' => (string) $car->marque,
                    'modele' => (string) $car->modele,
                    'annee' => (int) $car->annee,
                    'carburant' => (string) $car->carburant,
                    'prix_par_jour' => (int) $car->prix_par_jour,
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
                    'description' => $row['description'] ?? null,
                    'disponible' => (bool) ($row['disponible'] ?? true),
                ]
            );
        }

        Cache::forever(self::CACHE_HASH_KEY, $snapshotHash);
    }
}
