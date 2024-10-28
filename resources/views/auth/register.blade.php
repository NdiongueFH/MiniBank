<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - MoneyTransfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --success-color: #10b981;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .header-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            height: 40vh;
            position: relative;
            overflow: hidden;
        }

        .header-banner::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('/api/placeholder/800/400');
            opacity: 0.1;
            background-size: cover;
            background-position: center;
        }

        .header-content {
            position: relative;
            z-index: 1;
            color: white;
            text-align: center;
            padding-top: 5vh;
        }

        .header-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .form-container {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-top: -15vh;
            position: relative;
            padding: 2rem;
            margin-bottom: 3rem;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 1rem;
        }

        .step-number {
            width: 35px;
            height: 35px;
            background-color: #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            color: #6b7280;
            font-weight: 600;
        }

        .progress-step.active .step-number {
            background-color: var(--accent-color);
            color: white;
        }

        .step-title {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .progress-step.active .step-title {
            color: var(--accent-color);
            font-weight: 600;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }

        .profile-upload {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 2rem;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent-color);
        }

        .upload-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: var(--accent-color);
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-overlay:hover {
            transform: scale(1.1);
        }

        .form-floating {
            margin-bottom: 1rem;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .btn-primary {
            background-color: var(--accent-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .security-features {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            color: #64748b;
        }

        .feature-item i {
            color: var(--success-color);
            margin-right: 0.5rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .password-strength {
            height: 5px;
            margin-top: 0.5rem;
            border-radius: 5px;
            background-color: #e5e7eb;
        }

        .password-strength-meter {
            height: 100%;
            width: 0;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .terms-section {
            max-height: 200px;
            overflow-y: auto;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

    </style>
</head>
<body>
    <div class="header-banner">
        <div class="header-content">
            <h1>Bienvenue sur MiniBank</h1>
            <p class="lead">Transférez votre argent en toute sécurité partout au Sénégal</p>
            <a href="/" class="nav-link text-white opacity-75 hover:opacity-100">
                <i class="fas fa-home me-1"></i> Accueil
            </a>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <div class="progress-steps">
                <div class="progress-step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-title">Informations personnelles</div>
                </div>
                <div class="progress-step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-title">Vérification d'identité</div>
                </div>
                <div class="progress-step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-title">Sécurité</div>
                </div>
            </div>

            <form id="registrationForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Section 1: Informations personnelles -->
                <div class="form-section active" id="section1">
                    <div class="profile-upload">
                        <img src="/api/placeholder/150/150" alt="Profile" class="profile-image" id="profileDisplay">
                        <label for="profile_image" class="upload-overlay">
                            <i class="fas fa-camera text-white"></i>
                            <input type="file" id="profile_image" name="photo" accept="image/*" class="d-none">
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                                <label for="nom">Nom</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Votre prénom" required>
                                <label for="prenom">Prénom</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                                <label for="date_naissance">Date de naissance</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="role" name="role" value="client" readonly>
                                <label for="role">Statut</label>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary float-end" onclick="nextSection(1)">Suivant</button>
                </div>

                <!-- Section 2: Vérification d'identité -->
                <div class="form-section" id="section2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="carte_identite" name="carte_identite" placeholder="Numéro CNI" required pattern="^[0-9]+$">
                                <label for="carte_identite">Numéro de Carte d'Identité</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">+221</span>
                                    <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Numéro de téléphone" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Votre adresse" required>
                        <label for="adresse">Adresse complète</label>
                    </div>

                    

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="previousSection(2)">Précédent</button>
                        <button type="button" class="btn btn-primary" onclick="nextSection(2)">Suivant</button>
                    </div>
                </div>

                <!-- Section 3: Sécurité -->
                <div class="form-section" id="section3">
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                        <label for="password">Mot de passe</label>
                        <div class="password-strength">
                            <div class="password-strength-meter"></div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                    </div>



                    <div class="terms-section mb-3">
                        <h5>Conditions d'utilisation</h5>
                        <p>En créant un compte, vous acceptez nos conditions d'utilisation et notre politique de confidentialité.</p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les conditions d'utilisation et la politique de confidentialité
                            </label>
                        </div>
                    </div>

                    <div class="security-features mb-4">
                        <h6 class="mb-3">Fonctionnalités de sécurité incluses :</h6>
                        <div class="feature-item">
                            <i class="fas fa-lock"></i>
                            <span>Cryptage de bout en bout</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>Surveillance des transactions 24/7</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="previousSection(3)">Précédent</button>
                        <button type="submit" class="btn btn-primary">Créer mon compte</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Gestion de l'upload d'image
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileDisplay').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Gestion des étapes du formulaire
        function nextSection(currentSection) {
            if (validateSection(currentSection)) {
                document.querySelector(`#section${currentSection}`).classList.remove('active');
                document.querySelector(`#section${currentSection + 1}`).classList.add('active');
                
                // Mise à jour des indicateurs d'étape
                document.querySelector(`[data-step="${currentSection}"]`).classList.remove('active');
                document.querySelector(`[data-step="${currentSection + 1}"]`).classList.add('active');
            }
        }

        function previousSection(currentSection) {
            document.querySelector(`#section${currentSection}`).classList.remove('active');
            document.querySelector(`#section${currentSection - 1}`).classList.add('active');
            
            // Mise à jour des indicateurs d'étape
            document.querySelector(`[data-step="${currentSection}"]`).classList.remove('active');
            document.querySelector(`[data-step="${currentSection - 1}"]`).classList.add('active');
        }

        // Validation des sections
        function validateSection(sectionNumber) {
            let isValid = true;
            const section = document.querySelector(`#section${sectionNumber}`);
            const inputs = section.querySelectorAll('input[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Validations spécifiques par section
            switch(sectionNumber) {
                case 1:
                    // Validation de l'âge
                    const dateNaissance = new Date(document.getElementById('date_naissance').value);
                    const age = calculateAge(dateNaissance);
                    if (age < 18) {
                        alert('Vous devez avoir au moins 18 ans pour créer un compte.');
                        isValid = false;
                    }
                    break;
                    
                case 2:
                    // Validation du numéro de téléphone
                    const phone = document.getElementById('telephone').value;
                    if (!validatePhoneNumber(phone)) {
                        alert('Veuillez entrer un numéro de téléphone valide.');
                        isValid = false;
                    }
                    break;
            }

            return isValid;
        }

        // Calcul de l'âge
        function calculateAge(birthday) {
            const today = new Date();
            let age = today.getFullYear() - birthday.getFullYear();
            const m = today.getMonth() - birthday.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
                age--;
            }
            return age;
        }

        // Validation du numéro de téléphone
        function validatePhoneNumber(phone) {
            const phoneNumber = phone.replace(/[^0-9]/g, '');
            return phoneNumber.length === 9 && 
                   phoneNumber.startsWith('7') && 
                   (phoneNumber.startsWith('70') || 
                    phoneNumber.startsWith('75') || 
                    phoneNumber.startsWith('76') || 
                    phoneNumber.startsWith('77') || 
                    phoneNumber.startsWith('78'));
        }

        // Gestion de la force du mot de passe
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.querySelector('.password-strength-meter');
        
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updatePasswordStrengthUI(strength);
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            // Longueur minimale
            if (password.length >= 8) strength += 25;
            
            // Contient des chiffres
            if (/\d/.test(password)) strength += 25;
            
            // Contient des lettres minuscules et majuscules
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            
            // Contient des caractères spéciaux
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            return strength;
        }

        function updatePasswordStrengthUI(strength) {
            strengthMeter.style.width = `${strength}%`;
            
            // Changement de couleur en fonction de la force
            if (strength < 25) {
                strengthMeter.style.backgroundColor = '#dc3545'; // Rouge
            } else if (strength < 50) {
                strengthMeter.style.backgroundColor = '#ffc107'; // Jaune
            } else if (strength < 75) {
                strengthMeter.style.backgroundColor = '#fd7e14'; // Orange
            } else {
                strengthMeter.style.backgroundColor = '#28a745'; // Vert
            }
        }

        // Confirmation du mot de passe
        const confirmPassword = document.getElementById('password_confirmation');
        confirmPassword.addEventListener('input', function() {
            if (this.value === passwordInput.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });

// Formatage automatique du numéro de téléphone
const phoneInput = document.getElementById('telephone');
phoneInput.addEventListener('input', function(e) {
    let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,3})(\d{0,2})(\d{0,2})/);
    e.target.value = x[1] + 
                     (x[2] ? x[2] : '') + 
                     (x[3] ? x[3] : '') + 
                     (x[4] ? x[4] : '');
});


        // Prévention de la soumission du formulaire si les mots de passe ne correspondent pas
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }
            
            if (!document.getElementById('terms').checked) {
                e.preventDefault();
                alert('Veuillez accepter les conditions d\'utilisation.');
                return false;
            }
        });
    </script>
</body>
</html>