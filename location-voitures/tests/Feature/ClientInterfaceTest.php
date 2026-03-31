<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\City;
use App\Models\Reservation;
use App\Models\User;
use App\Services\UrlObfuscationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ClientInterfaceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_pages_are_accessible(): void
    {
        $obfuscation = app(UrlObfuscationService::class);

        $car = Car::create([
            'marque' => 'Dacia',
            'modele' => 'Logan',
            'annee' => 2022,
            'carburant' => 'Essence',
            'prix_par_jour' => 300,
            'disponible' => true,
        ]);

        $this->get(route('home'))->assertOk();
        $this->get(route('cars.index'))->assertOk();
        $this->get(route('cars.show', $obfuscation->encodeCarId($car->id)))->assertOk();
        $this->get('/car/' . $car->id)->assertNotFound();
    }

    public function test_guest_is_redirected_from_protected_client_pages(): void
    {
        $this->get(route('profile.show'))->assertRedirect(route('login'));
        $this->get(route('reservations.user'))->assertRedirect(route('login'));
    }

    public function test_authenticated_client_can_access_profile_and_reservations(): void
    {
        $obfuscation = app(UrlObfuscationService::class);

        $user = User::factory()->create([
            'role' => 'client',
            'cin' => 'AA111111',
            'numero_permis' => 'P-111111',
        ]);

        $car = Car::create([
            'marque' => 'Renault',
            'modele' => 'Clio',
            'annee' => 2023,
            'carburant' => 'Diesel',
            'prix_par_jour' => 350,
            'disponible' => true,
        ]);

        $city = City::create([
            'name' => 'Casablanca',
            'travel_fee' => 120,
            'is_active' => true,
        ]);

        Reservation::create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'city_id' => $city->id,
            'date_debut' => now()->addDays(1)->toDateString(),
            'date_fin' => now()->addDays(3)->toDateString(),
            'prix_total' => 820,
            'prix_location' => 700,
            'frais_deplacement' => 120,
            'statut' => 'en_attente',
            'contract_reference' => 'CTR-TEST-001',
        ]);

        $this->actingAs($user, 'web');

        $this->get(route('profile.show'))->assertOk();
        $this->get(route('reservations.user'))->assertOk();
        $this->get(route('reservation.create', $obfuscation->encodeCarId($car->id)))->assertOk();
    }

    public function test_contract_download_requires_signed_url(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'cin' => 'ZZ111111',
            'numero_permis' => 'P-999999',
        ]);

        $car = Car::create([
            'marque' => 'Hyundai',
            'modele' => 'i20',
            'annee' => 2024,
            'carburant' => 'Essence',
            'prix_par_jour' => 320,
            'disponible' => true,
        ]);

        $city = City::firstOrCreate(
            ['name' => 'Rabat'],
            ['travel_fee' => 90, 'is_active' => true]
        );

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'city_id' => $city->id,
            'date_debut' => now()->addDays(1)->toDateString(),
            'date_fin' => now()->addDays(4)->toDateString(),
            'prix_total' => 1050,
            'prix_location' => 960,
            'frais_deplacement' => 90,
            'statut' => 'confirme',
            'contract_reference' => 'CTR-SIGNED-URL-001',
        ]);

        $this->actingAs($user, 'web');

        $this->get(route('reservations.contract.download', $reservation))
            ->assertForbidden();

        $signedUrl = URL::temporarySignedRoute(
            'reservations.contract.download',
            now()->addMinutes(5),
            ['reservation' => $reservation->id]
        );

        $this->get($signedUrl)->assertOk();
    }
}
