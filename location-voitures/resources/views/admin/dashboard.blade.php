@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-stat-card">
            <span>Total véhicules</span>
            <strong>{{ $totalCars }}</strong>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card">
            <span>Total réservations</span>
            <strong>{{ $totalReservations }}</strong>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card">
            <span>En attente</span>
            <strong>{{ $pendingReservations }}</strong>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-stat-card">
            <span>CA du mois</span>
            <strong>{{ number_format($monthlyRevenue, 2) }} DH</strong>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-panel">
            <div class="admin-panel-head">
                <h5 class="mb-0">Dernières réservations</h5>
                <a href="{{ route('admin.reservations') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Client</th>
                            <th>Voiture</th>
                            <th>Total</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentReservations as $reservation)
                            <tr>
                                <td>{{ $reservation->contract_reference ?? 'N/A' }}</td>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->car->marque }} {{ $reservation->car->modele }}</td>
                                <td>{{ number_format($reservation->prix_total, 2) }} DH</td>
                                <td>
                                    <span class="badge {{ $reservation->statut === 'confirme' ? 'bg-success' : ($reservation->statut === 'en_attente' ? 'bg-warning text-dark' : ($reservation->statut === 'termine' ? 'bg-info' : 'bg-danger')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Aucune réservation.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-panel">
            <h5>Actions rapides</h5>
            <div class="d-grid gap-2 mt-3">
                <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">Ajouter un véhicule</a>
                <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-primary">Gérer véhicules</a>
                <a href="{{ route('admin.reservations') }}" class="btn btn-outline-primary">Gérer réservations</a>
                <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-primary">Gérer villes</a>
            </div>
            <hr>
            <div class="small text-muted">
                Réservations confirmées: <strong>{{ $confirmedReservations }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection
