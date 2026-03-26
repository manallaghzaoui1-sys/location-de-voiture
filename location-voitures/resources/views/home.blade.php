@extends('layouts.app')

@section('content')
<div class="hero-section">
    <div class="container">
        <h1><i class="fas fa-car"></i> AutoLoc</h1>
        <p class="lead">La meilleure solution pour la location de voitures au Maroc</p>
        <a href="{{ route('cars.index') }}" class="btn btn-light btn-lg">
            <i class="fas fa-search"></i> Voir nos véhicules
        </a>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2>Pourquoi choisir AutoLoc ?</h2>
            <p>Des services de qualité pour votre mobilité</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-car-side fa-3x text-primary mb-3"></i>
            <h4>Large choix</h4>
            <p>Plusieurs modèles disponibles pour tous les besoins</p>
        </div>
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-tachometer-alt fa-3x text-primary mb-3"></i>
            <h4>Prix compétitifs</h4>
            <p>Les meilleurs prix du marché avec transparence</p>
        </div>
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-headset fa-3x text-primary mb-3"></i>
            <h4>Support 24/7</h4>
            <p>Assistance client dédiée 7 jours sur 7</p>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
            <h4>Assurance incluse</h4>
            <p>Tous nos véhicules sont assurés</p>
        </div>
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
            <h4>Réservation facile</h4>
            <p>Réservez en quelques clics</p>
        </div>
        <div class="col-md-4 text-center mb-4">
            <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
            <h4>Livraison gratuite</h4>
            <p>Livraison à votre adresse</p>
        </div>
    </div>
</div>
@endsection