<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoLoc - @yield('title', 'Espace client')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark client-navbar">
    <div class="container">
        <a class="navbar-brand brand-logo" href="{{ route('home') }}">
            <i class="fas fa-car-side"></i> AutoLoc
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navClient" aria-controls="navClient" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navClient">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('cars.index') }}">Véhicules</a></li>
                @auth('web')
                    <li class="nav-item"><a class="nav-link" href="{{ route('reservations.user') }}">Mes réservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}">Mon profil</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button type="submit" class="btn btn-link nav-link p-0">Déconnexion</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-primary ms-lg-2" href="{{ route('register') }}">Inscription</a></li>
                @endauth
            </ul>
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
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="footer-title"><i class="fas fa-car-side"></i> AutoLoc</h5>
                <p class="footer-text mb-0">
                    Service de location de voitures au Maroc avec réservation simple,
                    contrat clair et assistance réactive.
                </p>
            </div>

            <div class="col-sm-6 col-lg-4">
                <h6 class="footer-subtitle">Liens utiles</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('cars.index') }}">Véhicules</a></li>
                    @auth('web')
                        <li><a href="{{ route('reservations.user') }}">Mes réservations</a></li>
                        <li><a href="{{ route('profile.show') }}">Mon profil</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Connexion</a></li>
                        <li><a href="{{ route('register') }}">Inscription</a></li>
                    @endauth
                </ul>
            </div>

            <div class="col-sm-6 col-lg-4">
                <h6 class="footer-subtitle">Contact</h6>
                <ul class="footer-contact">
                    <li><i class="fas fa-phone"></i> +212 6 12 34 56 78</li>
                    <li><i class="fas fa-envelope"></i> contact@autoloc.ma</li>
                    <li><i class="fas fa-location-dot"></i> Casablanca, Maroc</li>
                    <li><i class="fas fa-clock"></i> Lun - Sam: 08h00 - 20h00</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 py-3">
            <small>© {{ now()->year }} AutoLoc. Tous droits réservés.</small>
            <small>Conçu pour une expérience de réservation fiable.</small>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
