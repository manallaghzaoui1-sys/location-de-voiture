<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientUserSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'email' => 'client1@example.com',
                'name' => 'Client Demo 1',
                'password' => Hash::make('client123'),
                'telephone' => '0600000001',
                'adresse' => 'Casablanca, Maroc',
                'role' => 'client',
                'cin' => 'CLIENT-CIN-001',
                'numero_permis' => 'CLIENT-PERMIS-001',
            ],
            [
                'email' => 'client2@example.com',
                'name' => 'Client Demo 2',
                'password' => Hash::make('client123'),
                'telephone' => '0600000002',
                'adresse' => 'Rabat, Maroc',
                'role' => 'client',
                'cin' => 'CLIENT-CIN-002',
                'numero_permis' => 'CLIENT-PERMIS-002',
            ],
        ];

        foreach ($clients as $client) {
            User::updateOrCreate(
                ['email' => $client['email']],
                $client
            );
        }
    }
}
