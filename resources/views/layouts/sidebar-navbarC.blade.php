<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MiniBank - Dashboard</title>


    <script>
        // Javascript pour empêcher la mise en cache
        window.onload = function() {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }
    </script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #E5E5E5; >

    <!-- Sidebar -->
    <div class="d-flex" style="height: 100vh;">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="position: fixed; height: 100vh; width: 240px;">
            <div class="position-sticky d-flex flex-column" style="height: 100vh;">
                <!-- Logo centré -->
                <div class="p-3 text-center">
                    <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300">
                </div>
                
                <!-- Navigation Links -->
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('dashboard.client') }}" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-house-door"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link" href="{{ route('client.dashboard') }}" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-card-list"></i>
                            Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-person-lines-fill"></i>
                            Contactez l'agent
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-bar-chart"></i>
                            Paiments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-credit-card"></i>
                            Crédit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-gear"></i>
                            Paramètres
                        </a>
                    </li>
                </ul>

                <!-- Déconnexion button (aligned at the bottom) -->
<div class="mt-auto mb-3 px-3">
    <form method="POST" action="{{ route('logout') }}">
        @csrf <!-- Protection contre les attaques CSRF -->
        <button type="submit" class="btn btn-secondary w-100" style="background-color: #505887; color: white;">
            <i class="bi bi-box-arrow-left"></i> Déconnexion
        </button>
    </form>
</div>

            </div>
        </nav>

        <!-- Main content -->
        <div class="main-content flex-grow-1" style="margin-left: 240px; width: calc(100% - 240px);">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand text-white" href="#">
                         MiniBank
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <form class="d-flex me-3">
                            <input class="form-control me-2" type="search" placeholder="Recherche..." aria-label="Search">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        <!-- Menu de droite -->
                        <ul class="navbar-nav align-items-center">
                            <!-- Centre d'aide -->
                            <li class="nav-item me-3">
                                <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Centre d'aide">
                                    <i class="bi bi-question-circle fs-5"></i>
                                </a>
                            </li>
            
                            <!-- Notifications -->
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link position-relative" href="#" id="notificationsDropdown" data-bs-toggle="dropdown">
                                    <i class="bi bi-bell fs-5"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                        3
                                        <span class="visually-hidden">notifications non lues</span>
                                    </span>
                                </a>
                                
                                <!-- Dropdown notifications -->
                                <div class="dropdown-menu dropdown-menu-end shadow-lg p-0" style="width: 380px;">
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Notifications</h6>
                                            <a href="#" class="text-decoration-none small">Tout marquer comme lu</a>
                                        </div>
                                    </div>
                                    <div class="notifications-scroll" style="max-height: 400px; overflow-y: auto;">
                                        <!-- Notification non lue -->
                                        <div class="p-3 border-bottom bg-light">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="rounded-circle p-2 bg-success bg-opacity-10">
                                                        <i class="bi bi-check-circle text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Virement reçu</h6>
                                                    <p class="mb-1 small">Vous avez reçu 50 000 FCFA de Jean Dupont</p>
                                                    <span class="text-muted xsmall">Il y a 5 minutes</span>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <span class="badge bg-primary rounded-pill">Nouveau</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Autres notifications -->
                                        <div class="p-3 border-bottom">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="rounded-circle p-2 bg-warning bg-opacity-10">
                                                        <i class="bi bi-exclamation-circle text-warning"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Alerte de sécurité</h6>
                                                    <p class="mb-1 small">Une nouvelle connexion a été détectée</p>
                                                    <span class="text-muted xsmall">Il y a 1 heure</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 text-center border-top">
                                        <a href="#" class="text-decoration-none">Voir toutes les notifications</a>
                                    </div>
                                </div>
                            </li>
            
                            <!-- Menu utilisateur -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://via.placeholder.com/100' }}" 
                                             class="rounded-circle profile-photo" 
                                             width="40" height="40"
                                             alt="Photo de profil">
                                        <div class="d-none d-lg-block ms-2">
                                            <div class="fw-bold">{{ auth()->user()->prenom }}</div>
                                            <div class="small text-muted">{{ auth()->user()->role }}</div>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown shadow-lg">
                                    <div class="p-3 text-center border-bottom">
                                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://via.placeholder.com/100' }}" 
                                             class="rounded-circle mb-3 profile-photo" 
                                             width="80" height="80"
                                             alt="Photo de profil">
                                        <h6 class="mb-1">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h6>
                                        <p class="small text-muted mb-0">{{ auth()->user()->telephone }}</p>
                                        <div class="badge bg-success bg-opacity-10 text-success mt-2">
                                            Compte vérifié <i class="bi bi-check-circle-fill ms-1"></i>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>N° Compte</span>
                                            <span class="fw-bold">{{ auth()->user()->num_compte }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Statut</span>
                                            <span class="text-success">Actif</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
            
                                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear me-3 text-muted"></i>
                                        Paramètres
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="p-2">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="container mt-4">
                <p>Bienvenue sur ton tableau de bord {{ auth()->user()->prenom }} .</p>
                <!-- Ajouter d'autres contenus ici -->
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted || 
                                   (typeof window.performance != "undefined" && 
                                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                // Rechargement de la page à partir du serveur
                window.location.reload();
            }
        });
        </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>