<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/your/error.log'); // Assurez-vous que ce chemin est accessible en écriture
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MiniBank - Authentification Sécurisée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-color: #f0f9ff;
            --text-color: #1e293b;
        }

        body {
            background: var(--background-color);
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1rem 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white !important;
            font-weight: 600;
            font-size: 1.4rem;
        }

        .navbar-brand img {
            height: 40px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .auth-container {
            max-width: 800px;
            height: 600px;
            margin: auto;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            animation: slideUp 0.5s ease-out;
        }

        .auth-container:hover {
            transform: translateY(-5px);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
        }

        .input-group {
            border-radius: 8px;
            overflow: hidden;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 0.7rem 2rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .password-strength {
            height: 4px;
            background: #e2e8f0;
            margin-top: 8px;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .floating-security {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 10px 20px;
            border-radius: 50px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: #64748b;
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

    <div class="container my-5">
        <div class="auth-container">
            <div class="auth-header">
                <h2>Bienvenue sur MiniBank</h2>
                <p>Connectez-vous pour gérer vos transferts en toute sécurité</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" onsubmit="return validatePhoneNumber()">
                @csrf

                <div class="mb-4">
                    <label class="form-label" for="telephone">
                        <i class="fas fa-phone-alt me-1"></i>
                        {{ __('Numéro de téléphone') }}
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">+221</span>
                        <input id="telephone" 
                               class="form-control {{ $errors->has('telephone') ? 'is-invalid' : '' }}"
                               type="text" 
                               name="telephone" 
                               value="{{ old('telephone') }}" 
                               required 
                               autofocus 
                               placeholder="7X XXX XX XX"
                               maxlength="9" />
                    </div>
                    <div id="phoneFeedback" class="form-text"></div>
                    @if ($errors->has('telephone'))
                        <div class="invalid-feedback">{{ $errors->first('telephone') }}</div>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label" for="password">
                        <i class="fas fa-lock me-1"></i>
                        {{ __('Mot de passe') }}
                    </label>
                    <div class="input-group">
                        <input id="password" 
                               class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" />
                        <span class="input-group-text cursor-pointer" id="togglePassword">
                            <i class="fas fa-eye-slash"></i>
                        </span>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar"></div>
                    </div>
                    <div id="passwordFeedback" class="form-text mt-1"></div>
                    @if ($errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            {{ __('Se souvenir de moi') }}
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                        {{ __('Mot de passe oublié?') }}
                    </a>
                </div>

                <button class="btn btn-primary w-100" type="submit">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    {{ __('Se connecter') }}
                </button>
            </form>
        </div>
    </div>

    <div class="floating-security">
        <i class="fas fa-shield-alt text-primary"></i>
        Connexion sécurisée
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const passwordFeedback = document.querySelector('#passwordFeedback');
        const strengthBar = document.querySelector('.password-strength-bar');
        const telephoneInput = document.querySelector('#telephone');
        const phoneFeedback = document.querySelector('#phoneFeedback');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]+/)) strength += 25;
            if (password.match(/[A-Z]+/)) strength += 25;
            if (password.match(/[0-9]+/)) strength += 25;
            return strength;
        }

        password.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.style.backgroundColor = '#ef4444';
                passwordFeedback.innerHTML = 'Mot de passe faible';
                passwordFeedback.style.color = '#ef4444';
            } else if (strength <= 50) {
                strengthBar.style.backgroundColor = '#f59e0b';
                passwordFeedback.innerHTML = 'Mot de passe moyen';
                passwordFeedback.style.color = '#f59e0b';
            } else if (strength <= 75) {
                strengthBar.style.backgroundColor = '#10b981';
                passwordFeedback.innerHTML = 'Mot de passe fort';
                passwordFeedback.style.color = '#10b981';
            } else {
                strengthBar.style.backgroundColor = '#059669';
                passwordFeedback.innerHTML = 'Mot de passe très fort';
                passwordFeedback.style.color = '#059669';
            }
        });



        // Form validation
        function validateForm() {
            const phone = telephoneInput.value.replace(/\D/g, '');
            const pass = password.value;

            if (phone.length !== 9 || !phone.startsWith('7')) {
                alert('Le numéro de téléphone doit commencer par 7 et contenir 9 chiffres.');
                return false;
            }

            if (pass.length < 8) {
                alert('Le mot de passe doit contenir au moins 8 caractères.');
                return false;
            }

            return true;
        }

        // Real-time phone validation
        telephoneInput.addEventListener('input', function() {
            const phone = this.value.replace(/\D/g, '');
            if (phone.length === 0) {
                phoneFeedback.textContent = '';
                return;
            }
            
            if (!phone.startsWith('7')) {
                phoneFeedback.textContent = 'Le numéro doit commencer par 7';
                phoneFeedback.style.color = '#ef4444';
                return;
            }

            if (phone.length !== 9) {
                phoneFeedback.textContent = 'Le numéro doit contenir 9 chiffres';
                phoneFeedback.style.color = '#f59e0b';
                return;
            }

            phoneFeedback.textContent = 'Numéro de téléphone valide';
            phoneFeedback.style.color = '#10b981';
        });
    </script>
</body>
</html>