<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/car/{carToken}', [CarController::class, 'show'])->where('carToken', '[A-Za-z0-9\-]+')->name('cars.show');

Route::middleware('guest:web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register')->name('register.post');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('/reservation/{carToken}', [ReservationController::class, 'create'])->where('carToken', '[A-Za-z0-9\-]+')->name('reservation.create');
    Route::post('/reservation', [ReservationController::class, 'store'])->middleware('throttle:reservation-submit')->name('reservation.store');
    Route::get('/reservation/{reservation}/confirmation', [ReservationController::class, 'confirmation'])->name('reservations.confirmation');
    Route::get('/my-reservations', [ReservationController::class, 'userReservations'])->name('reservations.user');
    Route::get('/my-reservations/{reservation}/contract', [ReservationController::class, 'downloadContract'])
        ->middleware(['signed', 'throttle:contract-download'])
        ->name('reservations.contract.download');
});
