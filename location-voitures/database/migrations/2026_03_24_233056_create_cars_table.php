<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('marque');
            $table->string('modele');
            $table->integer('annee');
            $table->string('carburant');
            $table->integer('prix_par_jour');
            $table->string('image')->nullable();
            $table->boolean('disponible')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['disponible', 'created_at'], 'cars_available_created_idx');
            $table->index('carburant', 'cars_fuel_idx');
            $table->index(['disponible', 'carburant', 'created_at'], 'cars_available_fuel_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
