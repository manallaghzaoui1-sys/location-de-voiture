@extends('layouts.client')

@section('title', 'Nos véhicules')

@section('content')
<div class="hero-section">
    <div class="container text-center">
        <h1><i class="fas fa-car"></i> Nos véhicules</h1>
        <p class="lead">Explorez notre flotte et réservez la voiture qui vous convient.</p>
    </div>
</div>

<div class="container">
    <div
        id="cars-catalog-root"
        data-details-url="{{ url('/car') }}"
        data-cars='@json($carsData)'>
    </div>

    <div class="d-flex justify-content-center mt-2">
        {{ $cars->links() }}
    </div>

    <noscript>
        <div class="alert alert-warning mt-3">Activez JavaScript pour les filtres dynamiques.</div>
    </noscript>
</div>
@endsection
