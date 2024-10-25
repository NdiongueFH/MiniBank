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
<body>

    <!-- Sidebar -->
    <div class="d-flex flex-column flex-md-row" style="height: 100vh;">
        <nav id="sidebarMenu" class="col-12 col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="position: fixed; height: 100vh; width: 240px;">
            <div class="position-sticky d-flex flex-column" style="height: 100vh;">
                <!-- Logo centré -->
                <div class="p-3 text-center">
                    <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300">
                </div>
                
                <!-- Navigation Links -->
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('dashboard.distributeur') }}" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-house-door"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
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
        <div class="main-content flex-grow-1" style="margin-left: 240px;">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle sidebar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand text-white" href="#">MiniBank</a>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <form class="d-flex me-3">
                            <input class="form-control me-2" type="search" placeholder="Recherche..." aria-label="Search">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>

                        <ul class="navbar-nav">
                            <li class="nav-item dropdown position-relative me-3">
                                <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell" style="font-size: 1.5rem; color: white;"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="notificationDropdown" style="width: 300px;">
                                    <li class="dropdown-header text-center fw-bold">Notifications de transactions</li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="bi bi-arrow-down-circle-fill text-success"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold">Dépôt réussi</span>
                                                    <small class="d-block text-muted">Montant : 200 000 FCFA</small>
                                                    <small class="text-muted">Il y a 1 heure</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="bi bi-arrow-up-circle-fill text-danger"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold">Retrait effectué</span>
                                                    <small class="d-block text-muted">Montant : 150 000 FCFA</small>
                                                    <small class="text-muted">Il y a 30 minutes</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="text-center">
                                        <a href="#" class="text-primary">Voir toutes les transactions</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://via.placeholder.com/100' }}" class="rounded-circle" alt="User Profile" width="40" height="40">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="profileDropdown" style="width: 250px;">
                                    <li class="text-center mb-2">
                                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://via.placeholder.com/100' }}" class="rounded-circle mb-2" alt="User Photo" style="width: 80px; height: 80px; object-fit: cover;">
                                    </li>
                                    <li class="text-center"><strong>{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</strong></li>
                                    <li class="text-center text-muted mb-2">{{ auth()->user()->telephone }}</li>
                                    <li class="text-center text-muted">Compte: {{ auth()->user()->num_compte }}</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="container mt-4">
                <p>Bienvenue sur ton tableau de bord {{ auth()->user()->prenom }}.</p>
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted || 
                                   (typeof window.performance !== "undefined" && 
                                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>