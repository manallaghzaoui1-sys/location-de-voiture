@extends('layouts.client')

@section('title', 'Mes réservations')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-calendar-check"></i> Mes réservations</h1>
        <a href="{{ route('cars.index') }}" class="btn btn-primary btn-sm">Nouvelle réservation</a>
    </div>

    @if($reservations->count() > 0)
        <div class="panel-card p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Voiture</th>
                            <th>Ville</th>
                            <th>Période</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Contrat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->contract_reference ?? 'N/A' }}</td>
                                <td>{{ $reservation->car->marque }} {{ $reservation->car->modele }}</td>
                                <td>{{ optional($reservation->city)->name ?? '-' }}</td>
                                <td>{{ $reservation->date_debut->format('d/m/Y') }} - {{ $reservation->date_fin->format('d/m/Y') }}</td>
                                <td><strong>{{ number_format($reservation->prix_total, 2) }} DH</strong></td>
                                <td>
                                    @if($reservation->statut === 'en_attente')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @elseif($reservation->statut === 'confirme')
                                        <span class="badge bg-success">Confirmée</span>
                                    @elseif($reservation->statut === 'termine')
                                        <span class="badge bg-info">Terminée</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('reservations.contract.download', $reservation) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">{{ $reservations->links() }}</div>
    @else
        <div class="panel-card p-4 text-center">
            <p class="mb-3">Vous n'avez aucune réservation pour le moment.</p>
            <a href="{{ route('cars.index') }}" class="btn btn-primary">Voir les véhicules</a>
        </div>
    @endif
</div>
@endsection
