<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContractFieldConfigService
{
    private const STORAGE_PATH = 'private/settings/contract_fields.json';

    /**
     * @return array<int, array{id:string,label:string,source:string,section:string,enabled:bool,value:?string}>
     */
    public function getFields(): array
    {
        if (! Storage::disk('local')->exists(self::STORAGE_PATH)) {
            return $this->defaultFields();
        }

        $raw = Storage::disk('local')->get(self::STORAGE_PATH);
        $decoded = json_decode($raw, true);

        if (! is_array($decoded) || ! isset($decoded['fields']) || ! is_array($decoded['fields'])) {
            return $this->defaultFields();
        }

        $fields = $this->normalizeFields($decoded['fields']);

        return count($fields) > 0 ? $fields : $this->defaultFields();
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     */
    public function saveFields(array $fields): void
    {
        $normalized = $this->normalizeFields($fields);

        Storage::disk('local')->put(
            self::STORAGE_PATH,
            json_encode(['fields' => $normalized], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function resetToDefaults(): void
    {
        $this->saveFields($this->defaultFields());
    }

    /**
     * @return array<string, string>
     */
    public function availableSources(): array
    {
        return [
            'app_name' => 'Nom loueur (config app)',
            'user_name' => 'Nom locataire',
            'user_cin_permis' => 'CIN + Numero permis',
            'user_phone_email' => 'Telephone + Email',
            'car_brand_model' => 'Marque + Modele',
            'car_fuel_year' => 'Carburant + Annee',
            'reservation_period' => 'Date debut + Date fin',
            'city_name' => 'Ville de livraison',
            'registration_unavailable' => 'Immatriculation (non renseignee)',
            'mileage_unavailable' => 'Kilometrage (non renseigne)',
            'reservation_days' => 'Nombre de jours',
            'reservation_rental_price' => 'Prix location',
            'reservation_travel_fee' => 'Frais deplacement',
            'reservation_total_price' => 'Prix total',
            'custom' => 'Champ personnalise (texte libre)',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function availableSections(): array
    {
        return [
            'parties' => 'Entre les soussignes',
            'vehicle' => 'Nature et date d effet du contrat',
            'financial' => 'Conditions financieres',
        ];
    }

    public function isSupportedSource(string $source): bool
    {
        return array_key_exists($source, $this->availableSources());
    }

    /**
     * @return array<string, array<int, array{label:string,value:string}>>
     */
    public function buildLinesBySection(Reservation $reservation): array
    {
        $lines = [
            'parties' => [],
            'vehicle' => [],
            'financial' => [],
        ];

        foreach ($this->getFields() as $field) {
            if (! $field['enabled']) {
                continue;
            }

            $overrideValue = trim((string) ($field['value'] ?? ''));
            $value = $overrideValue !== ''
                ? $overrideValue
                : ($field['source'] === 'custom'
                    ? ''
                    : $this->resolveSource($field['source'], $reservation));

            if ($value === '') {
                continue;
            }

            $section = $field['section'];
            if (! isset($lines[$section])) {
                $section = 'parties';
            }

            $lines[$section][] = [
                'label' => $field['label'],
                'value' => $value,
            ];
        }

        return $lines;
    }

    /**
     * @return array<int, array{id:string,label:string,source:string,section:string,enabled:bool,value:?string}>
     */
    private function defaultFields(): array
    {
        return [
            ['id' => 'lessor_name', 'label' => 'Le nom du loueur', 'source' => 'app_name', 'section' => 'parties', 'enabled' => true, 'value' => null],
            ['id' => 'tenant_name', 'label' => 'Le nom du locataire', 'source' => 'user_name', 'section' => 'parties', 'enabled' => true, 'value' => null],
            ['id' => 'cin_permis', 'label' => 'CIN / Numero permis', 'source' => 'user_cin_permis', 'section' => 'parties', 'enabled' => true, 'value' => null],
            ['id' => 'phone_email', 'label' => 'Telephone / Email', 'source' => 'user_phone_email', 'section' => 'parties', 'enabled' => true, 'value' => null],

            ['id' => 'vehicle_brand', 'label' => 'Vehicule', 'source' => 'car_brand_model', 'section' => 'vehicle', 'enabled' => true, 'value' => null],
            ['id' => 'fuel_year', 'label' => 'Carburant / Annee', 'source' => 'car_fuel_year', 'section' => 'vehicle', 'enabled' => true, 'value' => null],
            ['id' => 'registration', 'label' => 'Immatriculation', 'source' => 'registration_unavailable', 'section' => 'vehicle', 'enabled' => true, 'value' => null],
            ['id' => 'period', 'label' => 'Periode', 'source' => 'reservation_period', 'section' => 'vehicle', 'enabled' => true, 'value' => null],
            ['id' => 'delivery_city', 'label' => 'Ville de livraison', 'source' => 'city_name', 'section' => 'vehicle', 'enabled' => true, 'value' => null],
            ['id' => 'mileage', 'label' => 'Kilometrage du vehicule', 'source' => 'mileage_unavailable', 'section' => 'vehicle', 'enabled' => true, 'value' => null],

            ['id' => 'days', 'label' => 'Nombre de jours', 'source' => 'reservation_days', 'section' => 'financial', 'enabled' => true, 'value' => null],
            ['id' => 'rental_price', 'label' => 'Prix location', 'source' => 'reservation_rental_price', 'section' => 'financial', 'enabled' => true, 'value' => null],
            ['id' => 'travel_fee', 'label' => 'Frais de deplacement', 'source' => 'reservation_travel_fee', 'section' => 'financial', 'enabled' => true, 'value' => null],
            ['id' => 'total_price', 'label' => 'Prix total', 'source' => 'reservation_total_price', 'section' => 'financial', 'enabled' => true, 'value' => null],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @return array<int, array{id:string,label:string,source:string,section:string,enabled:bool,value:?string}>
     */
    private function normalizeFields(array $fields): array
    {
        $normalized = [];

        foreach ($fields as $index => $field) {
            if (! is_array($field)) {
                continue;
            }

            $label = trim((string) ($field['label'] ?? ''));
            $source = trim((string) ($field['source'] ?? ''));
            $section = trim((string) ($field['section'] ?? 'parties'));
            $id = trim((string) ($field['id'] ?? ''));
            $enabled = filter_var($field['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $value = array_key_exists('value', $field) ? trim((string) $field['value']) : null;

            if ($label === '' || ! $this->isSupportedSource($source)) {
                continue;
            }

            if (! array_key_exists($section, $this->availableSections())) {
                $section = 'parties';
            }

            if ($value === '') {
                $value = null;
            }

            if ($source === 'custom' && $value === null) {
                continue;
            }

            $normalized[] = [
                'id' => $id !== '' ? Str::limit($id, 100, '') : 'field_' . ($index + 1),
                'label' => Str::limit($label, 120, ''),
                'source' => $source,
                'section' => $section,
                'enabled' => (bool) $enabled,
                'value' => $value,
            ];
        }

        return $normalized;
    }

    private function resolveSource(string $source, Reservation $reservation): string
    {
        return match ($source) {
            'app_name' => config('app.name', 'AutoLoc'),
            'user_name' => (string) $reservation->user->name,
            'user_cin_permis' => 'CIN: ' . ($reservation->user->cin ?? 'Non renseigne')
                . ' / Numero permis: ' . ($reservation->user->numero_permis ?? 'Non renseigne'),
            'user_phone_email' => 'Telephone: ' . ($reservation->user->telephone ?? 'Non renseigne')
                . ' / Email: ' . (string) $reservation->user->email,
            'car_brand_model' => (string) $reservation->car->marque . ' ' . (string) $reservation->car->modele,
            'car_fuel_year' => 'Carburant: ' . (string) $reservation->car->carburant
                . ' / Annee: ' . (string) $reservation->car->annee,
            'reservation_period' => 'Du ' . $reservation->date_debut->format('d/m/Y')
                . ' au ' . $reservation->date_fin->format('d/m/Y'),
            'city_name' => (string) (optional($reservation->city)->name ?? 'Non renseignee'),
            'registration_unavailable' => 'Non renseignee',
            'mileage_unavailable' => 'Non renseigne',
            'reservation_days' => (string) $reservation->nombre_jours,
            'reservation_rental_price' => number_format((float) $reservation->prix_location, 2) . ' DH',
            'reservation_travel_fee' => number_format((float) $reservation->frais_deplacement, 2) . ' DH',
            'reservation_total_price' => number_format((float) $reservation->prix_total, 2) . ' DH',
            default => '',
        };
    }
}
