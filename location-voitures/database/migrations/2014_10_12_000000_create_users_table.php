<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->string('password');
            $table->string('role', 20)->default('client');
            $table->string('cin', 30)->nullable()->unique();
            $table->string('numero_permis', 50)->nullable()->unique();
            $table->string('cin_document_path')->nullable();
            $table->string('permis_document_path')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('role', 'users_role_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
