<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MiniBank - Page d'Accueil</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #1e40af;
            height: 27vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        h2 {
            margin-top: 40px;
            font-size: 2rem;
        }

        footer {
            background-color: #1e40af;
            color: white;
            text-align: center;
            padding: 15px 0;
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 40px;
        }

        .lead {
            font-size: 1.25rem;
            margin-top: 20px;
        }

        .feature-icon {
            font-size: 2rem;
            color: #3b82f6;
        }

        .feature {
            margin: 20px 0;
        }

        .btn-custom {
            background-color: #ffb900;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="navbar">
    <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="120" style="margin-bottom: 20px;">
    <h1>Bienvenue sur MiniBank</h1>
        <nav class="nav">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link text-white">Connexion</a>
                    <a href="{{ route('register') }}" class="nav-link text-white">Inscription</a>
                @endauth
            @endif
        </nav>
    </div>

    <div class="content">
        <h2>Gérez vos transactions et paiements en toute sécurité</h2>
        <p class="lead">Fonctionnalités :</p>
        <div class="row">
            <div class="col-md-3 feature">
                <div class="feature-icon">💸</div>
                <h5>Transferts rapides</h5>
                <p>Effectuez vos transferts d'argent en quelques clics.</p>
            </div>
            <div class="col-md-3 feature">
                <div class="feature-icon">📊</div>
                <h5>Suivi en temps réel</h5>
                <p>Suivez vos transactions instantanément.</p>
            </div>
            <div class="col-md-3 feature">
                <div class="feature-icon">👥</div>
                <h5>Gestion simplifiée</h5>
                <p>Gérez facilement vos comptes et clients.</p>
            </div>
            <div class="col-md-3 feature">
                <div class="feature-icon">🕒</div>
                <h5>Accessibilité 24/7</h5>
                <p>Accédez à vos services à tout moment.</p>
            </div>
        </div>
        <p>Rejoignez-nous dès aujourd'hui pour profiter de tous nos services !</p>
        <a href="{{ route('register') }}" class="btn btn-custom">Commencer maintenant</a>
    </div>

    <footer>
        <p class="mb-0">© {{ date('Y') }} MiniBank. Tous droits réservés.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>