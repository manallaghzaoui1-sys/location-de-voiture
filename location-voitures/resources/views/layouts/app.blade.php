<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoLoc - @yield('title', 'Espace Client')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary:#1f3a5f; --accent:#d35400; --light:#f8f9fa; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--light); }
        .navbar { background: var(--primary); }
        .navbar-brand,.nav-link { color:#fff !important; }
        .hero-section { background: linear-gradient(135deg, #1f3a5f, #2d6fb0); color:#fff; padding: 72px 0; margin-bottom: 36px; }
        .btn-primary { background: var(--accent); border-color: var(--accent); }
        .btn-primary:hover { background:#b04a00; border-color:#b04a00; }
        .footer { background: var(--primary); color:#fff; padding: 24px 0; margin-top: 48px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="fas fa-car"></i> AutoLoc</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navClient">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navClient">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('cars.index') }}">Véhicules</a></li>
                @auth('web')
                    <li class="nav-item"><a class="nav-link" href="{{ route('reservations.user') }}">Mes réservations</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}">Mon profil</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button type="submit" class="btn nav-link" style="background:none;border:none;">Déconnexion</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Inscription</a></li>
                @endauth
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.login') }}">Espace admin</a></li>
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
<div class="container mt-3"><div class="alert alert-success">{{ session('success') }}</div></div>
@endif
@if(session('error'))
<div class="container mt-3"><div class="alert alert-danger">{{ session('error') }}</div></div>
@endif

@yield('content')

<footer class="footer"><div class="container text-center">&copy; 2026 AutoLoc</div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
