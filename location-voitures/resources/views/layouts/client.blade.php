<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bouhila Car - @yield('title', 'Espace client')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body>
@php($brandLogo = asset('images/logo/bouhila-car-logo.svg'))
<nav class="navbar navbar-expand-xl navbar-dark client-navbar">
    <div class="container client-navbar-inner">
        <a class="navbar-brand brand-logo" href="{{ route('home') }}">
            <img src="{{ $brandLogo }}" alt="Bouhila Car" class="brand-logo-image" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navClient" aria-controls="navClient" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navClient">
            <ul class="navbar-nav ms-xl-4 me-xl-auto align-items-xl-center gap-xl-1">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cars.index') }}">Nos vehicules</a></li>
                @auth('web')
                    <li class="nav-item"><a class="nav-link" href="{{ route('reservations.user') }}">Mes reservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}">Mon profil</a></li>
                @endauth
            </ul>

            <div class="client-nav-tools">
                <div class="nav-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher" aria-label="Recherche">
                </div>

                @auth('web')
                    <form method="POST" action="{{ route('logout') }}" class="d-inline-block">@csrf
                        <button type="submit" class="btn btn-outline-gold">Deconnexion</button>
                    </form>
                @else
                    <a class="btn btn-outline-gold" href="{{ route('login') }}">Connexion</a>
                    <a class="btn btn-gold" href="{{ route('register') }}">Inscription</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
    </div>
@endif

@yield('content')

<footer class="footer-pro">
    <div class="container py-5">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <h5 class="footer-title">
                    <img src="{{ $brandLogo }}" alt="Bouhila Car" class="footer-logo-image" />
                </h5>
                <p class="footer-text mb-0">
                    Service de location de voitures au Maroc avec reservation simple,
                    contrat clair et assistance reactive.
                </p>
            </div>

            <div class="col-sm-6 col-lg-3">
                <h6 class="footer-subtitle">Nos vehicules</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('cars.index') }}">SUV</a></li>
                    <li><a href="{{ route('cars.index') }}">Citadine</a></li>
                    <li><a href="{{ route('cars.index') }}">Luxe</a></li>
                    <li><a href="{{ route('cars.index') }}">Economique</a></li>
                </ul>
            </div>

            <div class="col-sm-6 col-lg-3">
                <h6 class="footer-subtitle">Reservation</h6>
                <ul class="footer-contact">
                    <li><i class="fas fa-phone"></i> +212 5 22 12 34 56</li>
                    <li><i class="fas fa-envelope"></i> contact@bouhilacar.ma</li>
                    <li><i class="fas fa-location-dot"></i> Casablanca, Maroc</li>
                </ul>
            </div>

            <div class="col-lg-2">
                <h6 class="footer-subtitle">Support</h6>
                <div class="footer-socials">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 py-3">
            <small>© {{ now()->year }} Bouhila Car. Tous droits reserves.</small>
            <small>Concu pour une experience de reservation fiable.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
