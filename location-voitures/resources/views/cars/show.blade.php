@extends('layouts.client')

@section('title', $car->marque . ' ' . $car->modele)

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="panel-card p-3">
                <img src="{{ $car->image_url }}" class="img-fluid rounded" alt="{{ $car->marque }} {{ $car->modele }}">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel-card p-4 h-100">
                <h1 class="h2 mb-2">{{ $car->marque }} {{ $car->modele }}</h1>
                <p class="text-muted mb-4">{{ $car->description ?? 'Description non disponible pour ce vehicule.' }}</p>

                <div class="info-grid mb-4">
                    <div class="info-row"><span><i class="fas fa-calendar-alt"></i> Annee</span><strong>{{ $car->annee }}</strong></div>
                    <div class="info-row"><span><i class="fas fa-gas-pump"></i> Carburant</span><strong>{{ $car->carburant }}</strong></div>
                    <div class="info-row"><span><i class="fas fa-money-bill-wave"></i> Prix / jour</span><strong>{{ number_format($car->prix_par_jour, 2) }} DH</strong></div>
                </div>

                @auth('web')
                    @if($car->disponible)
                        <a href="{{ route('reservation.create', $carToken) }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-calendar-check"></i> Reserver maintenant
                        </a>
                    @else
                        <div class="alert alert-warning mb-0 text-center">Ce vehicule n'est pas disponible actuellement.</div>
                    @endif
                @else
                    <div class="border rounded p-3 bg-light">
                        <h5 class="mb-3">Creer un compte pour reserver ce vehicule</h5>
                        <form method="POST" action="{{ route('register.post') }}" class="row g-2">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ route('reservation.create', $carToken, false) }}">

                            <div class="col-12">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nom complet" value="{{ old('name') }}" required>
                            </div>

                            <div class="col-12">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required>
                            </div>

                            <div class="col-md-6">
                                <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror" placeholder="Telephone" value="{{ old('telephone') }}">
                            </div>

                            <div class="col-md-6">
                                <input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror" placeholder="CIN" value="{{ old('cin') }}" required>
                            </div>

                            <div class="col-12">
                                <input type="text" name="numero_permis" class="form-control @error('numero_permis') is-invalid @enderror" placeholder="Numero de permis" value="{{ old('numero_permis') }}" required>
                            </div>

                            <div class="col-md-6">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mot de passe" required>
                            </div>

                            <div class="col-md-6">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer mot de passe" required>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">Creer le compte et continuer</button>
                            </div>
                        </form>

                        <div class="text-center mt-2">
                            <small>Deja inscrit ? <a href="{{ route('login') }}">Connectez-vous</a></small>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
