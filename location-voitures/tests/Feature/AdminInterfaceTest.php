<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
}
