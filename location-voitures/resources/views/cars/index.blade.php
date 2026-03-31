@extends('layouts.client')

@section('title', 'Nos vehicules')

@section('content')
<section class="catalog-page py-4 py-lg-5">
    <div class="container">
        <div
            id="cars-catalog-root"
            data-details-url="{{ url('/car') }}">
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
