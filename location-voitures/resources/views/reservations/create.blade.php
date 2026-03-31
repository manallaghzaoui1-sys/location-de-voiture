@extends('layouts.client')

@section('title', 'Réserver ' . $car->marque . ' ' . $car->modele)

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="panel-card p-4">
                <h4 class="mb-3"><i class="fas fa-calendar-check"></i> Réservation</h4>
                <p class="text-muted">Renseignez vos dates et votre ville de livraison.</p>

                <form method="POST" action="{{ route('reservation.store') }}">
                    @csrf
                    <input type="hidden" name="car_token" value="{{ $carToken }}">

                    <div class="mb-3">
                        <label class="form-label">Voiture</label>
                        <input type="text" class="form-control" value="{{ $car->marque }} {{ $car->modele }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="city_id" class="form-label">Ville de livraison</label>
                        <select id="city_id" name="city_id" class="form-select @error('city_id') is-invalid @enderror" required>
                            <option value="">Choisir une ville</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" data-fee="{{ $city->travel_fee }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }} ({{ number_format($city->travel_fee, 2) }} DH)
                                </option>
                            @endforeach
                        </select>
                        @error('city_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut" min="{{ date('Y-m-d') }}" value="{{ old('date_debut') }}" required>
                            @error('date_debut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin" value="{{ old('date_fin') }}" required>
                            @error('date_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div id="reservation-pricing-root" class="my-3" data-price-per-day="{{ (float) $car->prix_par_jour }}" data-date-start-input-id="date_debut" data-date-end-input-id="date_fin" data-city-select-id="city_id"></div>

                    <button type="submit" class="btn btn-primary w-100">Confirmer la réservation</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel-card p-3 h-100">
                <img src="{{ $car->image_url }}" class="img-fluid rounded mb-3" alt="{{ $car->marque }} {{ $car->modele }}">
                <h5>{{ $car->marque }} {{ $car->modele }}</h5>
                <div class="info-grid mt-3">
                    <div class="info-row"><span>Année</span><strong>{{ $car->annee }}</strong></div>
                    <div class="info-row"><span>Carburant</span><strong>{{ $car->carburant }}</strong></div>
                    <div class="info-row"><span>Prix / jour</span><strong>{{ number_format($car->prix_par_jour, 2) }} DH</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
