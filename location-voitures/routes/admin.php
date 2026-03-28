<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:admin-login')->name('login.post');
    });

    Route::middleware(['auth:admin', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::get('/cars', [AdminController::class, 'indexCars'])->name('cars.index');
        Route::get('/cars/create', [AdminController::class, 'create'])->name('cars.create');
        Route::post('/cars', [AdminController::class, 'store'])->name('cars.store');
        Route::get('/cars/{id}/edit', [AdminController::class, 'edit'])->name('cars.edit');
        Route::put('/cars/{id}', [AdminController::class, 'update'])->name('cars.update');
        Route::delete('/cars/{id}', [AdminController::class, 'destroy'])->name('cars.destroy');

        Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
        Route::put('/reservation/{id}/status', [AdminController::class, 'updateReservationStatus'])->name('reservation.status');
        Route::get('/reservation/{reservation}/contract', [AdminController::class, 'downloadContract'])->name('reservation.contract.download');

        Route::get('/cities', [AdminController::class, 'citiesIndex'])->name('cities.index');
        Route::post('/cities', [AdminController::class, 'citiesStore'])->name('cities.store');
        Route::put('/cities/{city}', [AdminController::class, 'citiesUpdate'])->name('cities.update');
        Route::delete('/cities/{city}', [AdminController::class, 'citiesDestroy'])->name('cities.destroy');

        Route::get('/users/{user}/documents/{type}', [AdminController::class, 'downloadClientDocument'])->name('users.documents.download');
    });
});
