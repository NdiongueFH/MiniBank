<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Réinitialiser le Mot de Passe</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand img {
            height: 50px;
        }
        .auth-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .auth-container h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img alt="MiniBank logo" src="{{ asset('images/Minibank.png') }}" />
                MiniBank
            </a>
            <a href="/" class="nav-link text-white">Accueil</a>
        </div>
    </nav>

    <div class="auth-container">
        <h2 class="text-center">Réinitialiser le Mot de Passe</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
        
            <!-- Champ caché pour le token -->
            <input type="hidden" name="token" value="fixed_token">
            
            <!-- Numéro de téléphone -->
            <div class="mb-3">
                <label for="telephone" class="form-label">{{ __('Numéro de téléphone') }}</label>
                <div class="input-group">
                    <span class="input-group-text">+221</span>
                    <input id="telephone" class="form-control {{ $errors->has('telephone') ? 'is-invalid' : '' }}" 
                           type="text" name="telephone" value="{{ old('telephone') }}" required autofocus 
                           placeholder="700000000" maxlength="9" />
                </div>
                @if ($errors->has('telephone'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('telephone') }}</strong>
                    </span>
                @endif
            </div>
        
            <!-- Mot de passe -->
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                <input id="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                       type="password" name="password" required autocomplete="new-password" />
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        
            <!-- Confirmation du mot de passe -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
                <input id="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                       type="password" name="password_confirmation" required autocomplete="new-password" />
                @if ($errors->has('password_confirmation'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
        
            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary">
                    {{ __('Réinitialiser le mot de passe') }}
                </button>
            </div>
        </form>
        
    </div>
</body>
</html>