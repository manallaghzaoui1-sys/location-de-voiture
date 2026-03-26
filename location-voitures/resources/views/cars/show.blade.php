@extends('layouts.app')

@section('title', $car->marque . ' ' . $car->modele)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            @if($car->image)
                <img src="{{ asset('storage/' . $car->image) }}" class="img-fluid rounded shadow" alt="{{ $car->marque }}">
            @else
                <img src="https://via.placeholder.com/600x400?text={{ $car->marque }}+{{ $car->modele }}" class="img-fluid rounded shadow" alt="Voiture">
            @endif
        </div>
        <div class="col-md-6">
            <h1>{{ $car->marque }} {{ $car->modele }}</h1>
            <p class="lead">{{ $car->description ?: 'Description non disponible' }}</p>
            
            <table class="table table-bordered mt-4">
                <tr>
                    <th><i class="fas fa-calendar-alt"></i> Année</th>
                    <td>{{ $car->annee }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-gas-pump"></i> Carburant</th>
                    <td>{{ $car->carburant }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-cogs"></i> Transmission</th>
                    <td>Manuelle</td>
                </tr>
                <tr>
                    <th><i class="fas fa-users"></i> Places</th>
                    <td>5</td>
                </tr>
            </table>
            
            <div class="alert alert-info text-center">
                <h3>{{ $car->prix_par_jour }} DH <small>/ jour</small></h3>
            </div>
            
            @auth
                @if($car->disponible)
                    <a href="{{ route('reservation.create', $car->id) }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-calendar-check"></i> Réserver maintenant
                    </a>
                @else
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-clock"></i> Cette voiture n'est pas disponible actuellement
                    </div>
                @endif
            @else
                <div class="alert alert-warning text-center">
                    <i class="fas fa-sign-in-alt"></i> 
                    <a href="{{ route('login') }}">Connectez-vous</a> pour réserver cette voiture
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection