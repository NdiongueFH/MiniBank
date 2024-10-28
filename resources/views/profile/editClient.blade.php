<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTransfer - Profil</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
@extends('layouts.sidebar-navbarC')

@section('content')

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête du profil -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img id="currentPhoto" src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : '/api/placeholder/100/100' }}" 
                             alt="Photo de profil" 
                             class="w-24 h-24 rounded-full border-4 border-blue-500 object-cover">
                        <label for="photo" class="absolute bottom-0 right-0 bg-blue-500 rounded-full p-2 cursor-pointer">
                            <i class="fas fa-camera text-white"></i>
                            <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </label>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $user->prenom }} {{ $user->nom }}</h1>
                        <p class="text-gray-600">Membre depuis {{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation par onglets -->
        <div class="bg-white rounded-lg shadow-lg mb-8">
            <nav class="flex border-b">
                <button class="px-6 py-4 text-blue-600 border-b-2 border-blue-600 font-medium" onclick="switchTab('profile')">
                    <i class="fas fa-user mr-2"></i>Profil
                </button>
                <button class="px-6 py-4 text-gray-600 hover:text-blue-600" onclick="switchTab('security')">
                    <i class="fas fa-shield-alt mr-2"></i>Sécurité
                </button>

            </nav>

            <!-- Contenu des onglets -->
            <div class="p-6">
                <!-- Onglet Profil -->
                <div id="profile" class="tab-content active">
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                                <input type="date" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance) }}" 
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-500">+221</span>
                                    <input type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}" 
                                           class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Adresse</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rue</label>
                                    <input type="text" name="adresse" value="{{ old('adresse', $user->adresse) }}" 
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Onglet Sécurité -->
                <div id="security" class="tab-content hidden">
                    <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                                <div class="relative">
                                    <input type="password" name="current_password" 
                                           class="w-full pl-4 pr-10 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" class="absolute right-3 top-3 text-gray-500" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                                <div class="relative">
                                    <input type="password" name="password" id="password"
                                           class="w-full pl-4 pr-10 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" class="absolute right-3 top-3 text-gray-500" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-2 grid grid-cols-2 gap-2">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-green-500"></i>
                                        <span class="text-sm text-gray-600">8 caractères minimum</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-check text-green-500"></i>
                                        <span class="text-sm text-gray-600">1 caractère spécial</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="w-full pl-4 pr-10 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" class="absolute right-3 top-3 text-gray-500" onclick="togglePassword(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-key mr-2"></i>Mettre à jour le mot de passe
                            </button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Cacher tous les contenus
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Afficher le contenu sélectionné
            document.getElementById(tabId).classList.remove('hidden');
            
            // Mettre à jour les styles des boutons
            document.querySelectorAll('nav button').forEach(button => {
                button.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                button.classList.add('text-gray-600');
            });
            
            // Mettre en évidence le bouton actif
            event.target.classList.remove('text-gray-600');
            event.target.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
        }

        function togglePassword(button) {
            const input = button.parentElement.querySelector('input');
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function previewImage(event) {
            const img = document.querySelector('img[alt="Profile"]'); // Image actuelle
            const preview = document.getElementById('preview'); // Image de prévisualisation
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Met à jour la source de l'image actuelle et de prévisualisation
                    img.src = e.target.result; // Met à jour la source de l'image actuelle
                    preview.src = e.target.result; // Met à jour l'aperçu
                    preview.style.display = 'block'; // Affiche l'image de prévisualisation
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
        @endsection