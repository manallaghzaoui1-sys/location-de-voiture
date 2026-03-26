<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoLoc - @yield('title', 'Location de Voitures')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #3498db;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--secondary-color) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .car-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .car-card img {
            height: 200px;
            object-fit: cover;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
        
        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .price-badge {
            background-color: var(--secondary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-car"></i> AutoLoc
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cars.index') }}">
                            <i class="fas fa-car"></i> Véhicules
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reservations.user') }}">
                                <i class="fas fa-calendar-check"></i> Mes réservations
                            </a>
                        </li>
                        @if(Auth::user()->email == 'admin@example.com')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn nav-link" style="background:none; border:none;">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Inscription
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-car"></i> AutoLoc</h5>
                    <p>Location de voitures simple et rapide</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p><i class="fas fa-phone"></i> +212 612345678</p>
                    <p><i class="fas fa-envelope"></i> contact@autoloc.ma</p>
                </div>
                <div class="col-md-4">
                    <h5>Horaires</h5>
                    <p>Lun-Ven: 8h-20h</p>
                    <p>Sam: 9h-18h</p>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">&copy; 2026 AutoLoc - Tous droits réservés</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>