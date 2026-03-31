@extends('layouts.client')

@section('title', 'Accueil')

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <img src="{{ asset('images/logo/bouhila-car-logo.svg') }}" alt="Bouhila Car" class="hero-logo-image mb-3" />
        <h1 class="display-5 fw-bold">Bouhila Car</h1>
        <p class="lead mb-4">Location de voitures fiable, rapide et transparente partout au Maroc.</p>
        <a href="{{ route('cars.index') }}" class="btn btn-light btn-lg px-4">
            <i class="fas fa-search"></i> Voir nos vehicules
        </a>
    </div>
</div>

<div class="container pb-3">
    <div class="text-center mb-4">
        <h2 class="section-title">Pourquoi choisir Bouhila Car</h2>
        <p class="section-subtitle">Une experience de location claire, professionnelle et securisee.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon"><i class="fas fa-car-side"></i></div>
                <h5>Large choix de vehicules</h5>
                <p class="mb-0 text-muted">Citadines, SUV et berlines disponibles selon vos besoins.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h5>Tarifs transparents</h5>
                <p class="mb-0 text-muted">Prix/jour clair, frais de ville visibles et total calcule en direct.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon"><i class="fas fa-file-signature"></i></div>
                <h5>Contrat PDF instantane</h5>
                <p class="mb-0 text-muted">Confirmation par email et telechargement du contrat des validation.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h5>Securite et confiance</h5>
                <p class="mb-0 text-muted">Informations clients protegees et acces administrateur isole.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                <h5>Reservation simple</h5>
                <p class="mb-0 text-muted">Choisissez vos dates et votre ville en quelques clics.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <h5>Support reactif</h5>
                <p class="mb-0 text-muted">Notre equipe reste disponible pour vos demandes importantes.</p>
            </div>
        </div>
    </div>
</div>
@endsection
