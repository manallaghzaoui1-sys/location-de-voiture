<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('travel_fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'cities_active_idx');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('prix_total');
            $table->decimal('prix_location', 10, 2)->default(0);
            $table->decimal('frais_deplacement', 10, 2)->default(0);
            $table->string('statut')->default('en_attente');
            $table->string('contract_reference')->nullable()->unique();
            $table->string('contract_pdf_path')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();

            $table->index(['car_id', 'statut', 'date_debut', 'date_fin'], 'reservations_car_status_dates_idx');
            $table->index(['user_id', 'created_at'], 'reservations_user_created_idx');
            $table->index(['statut', 'created_at'], 'reservations_status_created_idx');
            $table->index(['city_id', 'created_at'], 'reservations_city_created_idx');
            $table->index(['date_debut', 'date_fin'], 'reservations_dates_idx');
            $table->index(['statut', 'city_id', 'date_debut', 'date_fin', 'created_at'], 'reservations_admin_filters_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('cities');
    }
};
