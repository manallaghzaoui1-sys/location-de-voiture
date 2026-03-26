@extends('layouts.app')

@section('title', 'Tableau de bord Admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>
            <hr>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Véhicules</h5>
                    <h2>{{ $totalCars }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Réservations</h5>
                    <h2>{{ $totalReservations }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Réservations en attente</h5>
                    <h2>{{ $pendingReservations }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Dernières réservations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                32<tr>
                                    <th>Client</th>
                                    <th>Voiture</th>
                                    <th>Dates</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->user->name }}</td>
                                        <td>{{ $reservation->car->marque }} {{ $reservation->car->modele }}</td>
                                        <td>{{ $reservation->date_debut }} → {{ $reservation->date_fin }}</td>
                                        <td>{{ $reservation->prix_total }} DH</td>
                                        <td>
                                            @if($reservation->statut == 'en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif($reservation->statut == 'confirme')
                                                <span class="badge bg-success">Confirmé</span>
                                            @else
                                                <span class="badge bg-danger">Annulé</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.reservation.status', $reservation->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="en_attente" {{ $reservation->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                    <option value="confirme" {{ $reservation->statut == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                                    <option value="annule" {{ $reservation->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune réservation</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Gestion rapide</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.cars.index') }}" class="btn btn-primary">
                        <i class="fas fa-car"></i> Gérer les véhicules
                    </a>
                    <a href="{{ route('admin.reservations') }}" class="btn btn-info">
                        <i class="fas fa-calendar-check"></i> Toutes les réservations
                    </a>
                    <a href="{{ route('admin.cars.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter un véhicule
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection