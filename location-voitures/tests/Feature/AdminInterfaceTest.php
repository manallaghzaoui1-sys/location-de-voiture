<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\City;
use App\Models\Reservation;
use App\Models\User;
use App\Services\ContractFieldConfigService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AdminInterfaceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'cin' => 'BB111111',
            'numero_permis' => 'P-222222',
        ]);

        $this->actingAs($user, 'admin');

        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_access_main_admin_pages(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'cin' => 'CC111111',
            'numero_permis' => 'P-333333',
        ]);

        $this->actingAs($admin, 'admin');

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.cars.index'))->assertOk();
        $this->get(route('admin.reservations'))->assertOk();
        $this->get(route('admin.cities.index'))->assertOk();
        $this->get(route('admin.contract-fields.index'))->assertOk();
    }

    public function test_admin_can_delete_car_without_image(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'cin' => 'DD111111',
            'numero_permis' => 'P-444444',
        ]);

        $car = Car::create([
            'marque' => 'Peugeot',
            'modele' => '208',
            'annee' => 2021,
            'carburant' => 'Essence',
            'prix_par_jour' => 330,
            'image' => null,
            'disponible' => true,
        ]);

        $this->actingAs($admin, 'admin');

        $this->delete(route('admin.cars.destroy', $car->id))
            ->assertRedirect(route('admin.cars.index'));

        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }

    public function test_admin_can_update_contract_fields_and_it_affects_contract_lines(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create([
            'role' => 'admin',
            'cin' => 'EE111111',
            'numero_permis' => 'P-555555',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'cin' => 'EE222222',
            'numero_permis' => 'P-666666',
        ]);

        $car = Car::create([
            'marque' => 'Toyota',
            'modele' => 'Yaris',
            'annee' => 2024,
            'carburant' => 'Essence',
            'prix_par_jour' => 310,
            'disponible' => true,
        ]);

        $city = City::create([
            'name' => 'Meknes',
            'travel_fee' => 80,
            'is_active' => true,
        ]);

        $reservation = Reservation::create([
            'user_id' => $client->id,
            'car_id' => $car->id,
            'city_id' => $city->id,
            'date_debut' => now()->addDays(3)->toDateString(),
            'date_fin' => now()->addDays(5)->toDateString(),
            'prix_total' => 700,
            'prix_location' => 620,
            'frais_deplacement' => 80,
            'statut' => 'en_attente',
            'contract_reference' => 'CTR-ADMIN-FIELDS-001',
        ]);

        $this->actingAs($admin, 'admin');

        $payload = [
            'fields' => [
                [
                    'id' => 'custom_1',
                    'label' => 'Condition speciale',
                    'source' => 'custom',
                    'section' => 'vehicle',
                    'value' => 'Interdit de fumer dans le vehicule',
                    'enabled' => '1',
                ],
                [
                    'id' => 'total_price',
                    'label' => 'Prix total',
                    'source' => 'reservation_total_price',
                    'section' => 'financial',
                    // Disabled on purpose
                ],
            ],
        ];

        $this->put(route('admin.contract-fields.update'), $payload)
            ->assertRedirect();

        $lines = app(ContractFieldConfigService::class)->buildLinesBySection(
            $reservation->load(['user', 'car', 'city'])
        );

        $vehicleValues = collect($lines['vehicle'] ?? [])->pluck('value')->all();
        $financialLabels = collect($lines['financial'] ?? [])->pluck('label')->all();

        $this->assertContains('Interdit de fumer dans le vehicule', $vehicleValues);
        $this->assertNotContains('Prix total', $financialLabels);
    }

    public function test_confirming_reservation_generates_pdf_using_current_contract_configuration(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create([
            'role' => 'admin',
            'cin' => 'FF111111',
            'numero_permis' => 'P-777777',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'cin' => 'FF222222',
            'numero_permis' => 'P-888888',
        ]);

        $car = Car::create([
            'marque' => 'Peugeot',
            'modele' => '2008',
            'annee' => 2024,
            'carburant' => 'Diesel',
            'prix_par_jour' => 390,
            'disponible' => true,
        ]);

        $city = City::create([
            'name' => 'Kenitra',
            'travel_fee' => 95,
            'is_active' => true,
        ]);

        $reservation = Reservation::create([
            'user_id' => $client->id,
            'car_id' => $car->id,
            'city_id' => $city->id,
            'date_debut' => now()->addDays(2)->toDateString(),
            'date_fin' => now()->addDays(6)->toDateString(),
            'prix_total' => 1655,
            'prix_location' => 1560,
            'frais_deplacement' => 95,
            'statut' => 'en_attente',
            'contract_reference' => 'CTR-ADMIN-PDF-001',
        ]);

        $this->actingAs($admin, 'admin');

        $this->put(route('admin.contract-fields.update'), [
            'fields' => [
                [
                    'id' => 'custom_pdf',
                    'label' => 'Info interne',
                    'source' => 'custom',
                    'section' => 'parties',
                    'value' => 'Paiement uniquement carte bancaire',
                    'enabled' => '1',
                ],
            ],
        ])->assertRedirect();

        $this->put(route('admin.reservation.status', $reservation->id), [
            'statut' => 'confirme',
        ])->assertRedirect();

        $reservation->refresh();

        $this->assertSame('confirme', $reservation->statut);
        $this->assertNotNull($reservation->contract_pdf_path);
        $this->assertTrue(Storage::disk('local')->exists($reservation->contract_pdf_path));
    }

    public function test_admin_document_download_requires_signed_url(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'cin' => 'GG111111',
            'numero_permis' => 'P-121212',
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'cin' => 'GG222222',
            'numero_permis' => 'P-343434',
            'cin_document_path' => 'private/identity/mock-cin.pdf',
        ]);

        $this->actingAs($admin, 'admin');

        $this->get(route('admin.users.documents.download', [$client->id, 'cin']))
            ->assertForbidden();

        $signedUrl = URL::temporarySignedRoute(
            'admin.users.documents.download',
            now()->addMinutes(5),
            ['user' => $client->id, 'type' => 'cin']
        );

        // Signature valid, then controller may return 404 if file does not exist.
        $this->get($signedUrl)->assertStatus(404);
    }
}
