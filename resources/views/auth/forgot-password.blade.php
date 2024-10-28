<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mot de Passe Oublié</title>
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
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img alt="MiniBank logo" src="{{ asset('images/Minibank.png') }}" />
                MiniBank
            </a>
            <div class="d-flex">
                <a href="/" class="nav-link text-white opacity-75 hover:opacity-100">
                    <i class="fas fa-home me-1"></i> Accueil
                </a>
            </div>
        </div>
    </nav>

    <div class="auth-container">
        <h2 class="text-center">Mot de Passe Oublié</h2>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous votre numéro de téléphone et nous vous enverrons un lien de réinitialisation.') }}
        </div>

        <!-- Session Status -->
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
            @if (session('token'))
                <br>
                <a href="{{ route('password.reset.form', ['token' => session('token'), 'telephone' => session('telephone')]) }}">
                    Cliquez ici pour réinitialiser votre mot de passe.
                </a>
                              
            @endif
        </div>
    @endif
    

        <form method="POST" action="{{ route('password.request') }}" onsubmit="return validatePhoneNumber()">
            @csrf

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

            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary">
                    {{ __('Envoyer le lien de réinitialisation du mot de passe') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        // Limitation de saisie pour le téléphone
        const telephoneInput = document.querySelector('#telephone');
        telephoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9); // Restrict to numbers and max 9 digits
        });

        // Validation du numéro de téléphone avant l'envoi du formulaire
        function validatePhoneNumber() {
            const phoneNumber = parseInt(telephoneInput.value, 10);
            if (phoneNumber < 700000000 || phoneNumber > 789999999) {
                alert('Le numéro de téléphone doit être compris entre 700000000 et 789999999.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>