@extends('layouts.app')

@section('title', 'Nos Véhicules')

@section('content')
<div class="hero-section">
    <div class="container">
        <h1><i class="fas fa-car"></i> Nos Véhicules</h1>
        <p class="lead">Choisissez la voiture qui vous convient</p>
    </div>
</div>

<div class="container">
    <div class="row">
        @forelse($cars as $car)
            <div class="col-md-4 mb-4">
                <div class="card car-card h-100">
                    @if($car->image)
                        <img src="{{ asset('storage/' . $car->image) }}" class="card-img-top" alt="{{ $car->marque }} {{ $car->modele }}">
                    @else
                        <img src="https://via.placeholder.com/300x200?text={{ $car->marque }}+{{ $car->modele }}" class="card-img-top" alt="{{ $car->marque }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $car->marque }} {{ $car->modele }}</h5>
                        <p class="card-text">
                            <i class="fas fa-calendar-alt"></i> Année: {{ $car->annee }}<br>
                            <i class="fas fa-gas-pump"></i> Carburant: {{ $car->carburant }}<br>
                            <span class="price-badge">{{ $car->prix_par_jour }} DH / jour</span>
                        </p>
                        <a href="{{ route('cars.show', $car->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-info-circle"></i> Voir détails
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Aucune voiture disponible pour le moment.
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $cars->links() }}
    </div>
</div>
@endsection