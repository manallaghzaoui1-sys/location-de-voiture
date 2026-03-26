<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/car/{id}', [CarController::class, 'show'])->name('cars.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/reservation/{car_id}', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/my-reservations', [ReservationController::class, 'userReservations'])->name('reservations.user');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/cars', [AdminController::class, 'indexCars'])->name('cars.index');
    Route::get('/cars/create', [AdminController::class, 'create'])->name('cars.create');
    Route::post('/cars', [AdminController::class, 'store'])->name('cars.store');
    Route::get('/cars/{id}/edit', [AdminController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{id}', [AdminController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{id}', [AdminController::class, 'destroy'])->name('cars.destroy');
    Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
    Route::put('/reservation/{id}/status', [AdminController::class, 'updateReservationStatus'])->name('reservation.status');
});