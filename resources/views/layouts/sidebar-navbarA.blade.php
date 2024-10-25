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
    <div class="d-flex" style="height: 100vh;">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="position: fixed; height: 100vh; width: 240px;">
            <div class="position-sticky d-flex flex-column" style="height: 100vh;">
                <!-- Logo centré -->
                <div class="p-3 text-center">
                    <img src="{{ asset('storage.images.Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300">
                </div>
                
                <!-- Navigation Links -->
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('dashboard.agent') }}" style="font-size: 1.25rem; color: #505887;">
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
                            <i class="bi bi-person"></i>
                            Gestion de Compte
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

                        <ul class="navbar-nav">
                            <!-- Icon de notification -->
                            <li class="nav-item position-relative me-3">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-bell" style="font-size: 1.5rem; color: white;"></i>
                                </a>
                            </li>
                            <!-- Profil utilisateur -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://via.placeholder.com/100' }}" class="rounded-circle" alt="User Profile" width="40" height="40">

                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="container mt-4">
                
                <p>Bienvenue sur ton tableau de bord {{ auth()->user()->prenom }} .</p>
                <!-- Ajouter d'autres contenus ici -->
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
