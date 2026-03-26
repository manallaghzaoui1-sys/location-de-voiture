@extends('layouts.app')

@section('title', 'Mes Réservations')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5"><i class="fas fa-calendar-check"></i> Mes Réservations</h1>
    
    @if($reservations->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Voiture</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Nombre jours</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Date réservation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->car->marque }} {{ $reservation->car->modele }}</td>
                            <td>{{ $reservation->date_debut->format('d/m/Y') }}</td>
                            <td>{{ $reservation->date_fin->format('d/m/Y') }}</td>
                            <td>{{ $reservation->nombre_jours }} jours</td>
                            <td><strong>{{ $reservation->prix_total }} DH</strong></td>
                            <td>
                                @if($reservation->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($reservation->statut == 'confirme')
                                    <span class="badge bg-success">Confirmé</span>
                                @else
                                    <span class="badge bg-danger">Annulé</span>
                                @endif
                            </td>
                            <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Vous n'avez aucune réservation.
            <br>
            <a href="{{ route('cars.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-car"></i> Réserver une voiture
            </a>
        </div>
    @endif
</div>
@endsection