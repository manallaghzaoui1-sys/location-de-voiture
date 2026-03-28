<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoLoc Admin - @yield('title', 'Back-office')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <i class="fas fa-shield-halved"></i>
            <div>
                <strong>AutoLoc Admin</strong>
                <small>Back-office</small>
            </div>
        </div>

        <nav class="admin-menu">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge"></i> Dashboard
            </a>
            <a href="{{ route('admin.cars.index') }}" class="{{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">
                <i class="fas fa-car-side"></i> Véhicules
            </a>
            <a href="{{ route('admin.reservations') }}" class="{{ request()->routeIs('admin.reservations') || request()->routeIs('admin.reservation.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Réservations
            </a>
            <a href="{{ route('admin.cities.index') }}" class="{{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                <i class="fas fa-city"></i> Villes
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i> Retour site</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <h1>@yield('title', 'Back-office')</h1>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf
                <button class="btn btn-sm btn-outline-danger">Déconnexion admin</button>
            </form>
        </header>

        <section class="admin-content">
            @if(session('success'))
                <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
            @endif

            @yield('content')
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
