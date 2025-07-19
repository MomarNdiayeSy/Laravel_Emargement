<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours - @yield('title', 'Accueil')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #2d3748;
        }
        .navbar {
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            color: #fff !important;
            transition: color 0.3s;
        }
        .navbar-brand:hover {
            color: #dbeafe !important;
        }
        .nav-link {
            color: #fff !important;
            font-weight: 400;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #dbeafe !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 0.8)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
        .container {
            max-width: 1300px;
            margin-top: 2rem;
        }
        main {
            min-height: calc(100vh - 200px); /* Pour pousser le footer en bas */
        }
        footer {
            background: #1e3a8a;
            color: #fff;
            padding: 1.5rem 0;
            font-size: 0.9rem;
        }
        .btn-link {
            color: #fff !important;
            text-decoration: none;
        }
        .btn-link:hover {
            color: #dbeafe !important;
            text-decoration: underline;
        }
        .welcome-text {
            color: #dbeafe;
            font-size: 1rem;
            margin-right: 1rem;
        }
        .blink {
            animation: blinker 1s linear infinite;
        }
        @keyframes blinker {
            50% { opacity: 0; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}"><i class="bi bi-house-door me-2"></i>Gestion des Cours</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Utilisateurs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.salles.index') }}"><i class="bi bi-building me-2"></i>Salles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.notifications.index') }}"><i class="bi bi-bell me-2"></i>Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.cours.index') }}"><i class="bi bi-book me-2"></i>Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.emargements.index') }}">
                                <i class="bi bi-check-square me-2"></i>Émargements
                                @if (isset($pendingEmargementsCount) && $pendingEmargementsCount > 0)
                                    <span class="badge bg-warning text-dark ms-2 blink">
                                        <i class="bi bi-bell"></i> {{ $pendingEmargementsCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.rapports.index') }}"><i class="bi bi-bar-chart me-2"></i>Rapports</a>
                        </li>
                    @elseif (auth()->user()->role === 'professeur')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('professeur.cours.index') }}">
                                <i class="bi bi-book me-2"></i>Cours
                                @if (isset($newCoursCount) && $newCoursCount > 0)
                                    <span class="badge bg-warning text-dark ms-2 blink">
                                        <i class="bi bi-bell"></i> {{ $newCoursCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('professeur.emargements.index') }}"><i class="bi bi-check-square me-2"></i>Émargements</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('professeur.rapports.index') }}"><i class="bi bi-bar-chart me-2"></i>Rapports</a>
                        </li>
                    @elseif (auth()->user()->role === 'gestionnaire')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('gestionnaire.cours.index') }}"><i class="bi bi-book me-2"></i>Cours</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('gestionnaire.rapports.index') }}"><i class="bi bi-bar-chart me-2"></i>Rapports</a>
                        </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item">
                        <span class="welcome-text"><i class="bi bi-person-circle me-2"></i>Bienvenue, {{ auth()->user()->prenom }} ({{ auth()->user()->role }})</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-2"></i>Inscription</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @yield('content')
    </div>
</main>

<footer class="text-center">
    <p>© {{ date('Y') }} Institut Supérieur Informatique - Gestion des Cours</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
