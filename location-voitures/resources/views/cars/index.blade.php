@extends('layouts.client')

@section('title', 'Nos vehicules')

@section('content')
<section class="catalog-page py-4 py-lg-5">
    <div class="container">
        <div
            id="cars-catalog-root"
            data-details-url="{{ url('/car') }}">
            <div class="row g-3">
                @forelse($carsData as $car)
                    <div class="col-sm-6 col-lg-4">
                        <article class="card h-100 border-0 shadow-sm">
                            <img src="{{ $car['image_url'] }}" class="card-img-top" alt="{{ $car['marque'] }} {{ $car['modele'] }}" style="height: 220px; object-fit: cover;">
                            <div class="card-body bg-dark text-light">
                                <h5 class="card-title mb-1">{{ $car['marque'] }} {{ $car['modele'] }}</h5>
                                <p class="mb-2 text-warning fw-bold">{{ number_format($car['prix_par_jour'], 2) }} DH / jour</p>
                                <p class="small mb-3 text-white-50">Annee: {{ $car['annee'] }} | Carburant: {{ $car['carburant'] }}</p>
                                <a href="{{ $car['details_url'] }}" class="btn btn-sm btn-outline-warning w-100">Voir details</a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">Aucun vehicule disponible pour le moment.</div>
                    </div>
                @endforelse
            </div>
        </div>
        <script id="cars-catalog-data" type="application/json">@json($carsData)</script>

        <div class="catalog-pagination d-flex justify-content-center mt-4">
            {{ $cars->links() }}
        </div>

        <noscript>
            <div class="alert alert-warning mt-3">Activez JavaScript pour les filtres dynamiques.</div>
        </noscript>
    </div>
</section>
@endsection
