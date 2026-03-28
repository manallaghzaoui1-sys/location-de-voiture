@extends('layouts.client')

@section('title', 'Confirmation réservation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="panel-card p-4 p-md-5">
                <h4 class="text-success mb-3"><i class="fas fa-circle-check"></i> Réservation enregistrée</h4>
                <p>Votre demande a été enregistrée avec la référence <strong>{{ $reservation->contract_reference }}</strong>.</p>

                <ul class="list-group mb-4">
                    <li class="list-group-item"><strong>Voiture:</strong> {{ $reservation->car->marque }} {{ $reservation->car->modele }}</li>
                    <li class="list-group-item"><strong>Ville:</strong> {{ optional($reservation->city)->name ?? '-' }}</li>
                    <li class="list-group-item"><strong>Période:</strong> {{ $reservation->date_debut->format('d/m/Y') }} au {{ $reservation->date_fin->format('d/m/Y') }}</li>
                    <li class="list-group-item"><strong>Prix location:</strong> {{ number_format($reservation->prix_location, 2) }} DH</li>
                    <li class="list-group-item"><strong>Frais déplacement:</strong> {{ number_format($reservation->frais_deplacement, 2) }} DH</li>
                    <li class="list-group-item"><strong>Total:</strong> {{ number_format($reservation->prix_total, 2) }} DH</li>
                </ul>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reservations.contract.download', $reservation) }}" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Télécharger le contrat PDF
                    </a>
                    <a href="{{ route('reservations.user') }}" class="btn btn-outline-secondary">Mes réservations</a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Accueil</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
