<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Auth;
class ProfileController extends Controller
{
    // Méthode d'édition du profil avec gestion des vues en fonction du rôle de l'utilisateur
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Vérifier le rôle et retourner la vue correspondante
        if ($user->role === 'agent') {
            return view('profile.editAgent', ['user' => $user]);
        } elseif ($user->role === 'client') {
            return view('profile.editClient', ['user' => $user]);
        } elseif ($user->role === 'distributeur') {
            return view('profile.editDistributeur', ['user' => $user]);
        }   

        // Si le rôle n'est pas autorisé, redirection vers la page d'accueil
        switch ($user->role) {
            case 'agent':
                return redirect()->route('dashboard.agent');
            case 'distributeur':
                return redirect()->route('dashboard.distributeur');
            case 'client':
                return redirect()->route('dashboard.client');

                default:
                // Si le rôle est inconnu, déconnecter l'utilisateur et retourner une erreur
                Auth::logout();
                throw ValidationException::withMessages([
                    'role' => 'Rôle utilisateur inconnu.',
                ]);
    }
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Valide les champs du formulaire
        $validatedData = $request->validated();

        // Mise à jour des champs utilisateur
        $user->nom = $validatedData['nom'];
        $user->prenom = $validatedData['prenom'];
        $user->date_naissance = $validatedData['date_naissance'];
        $user->adresse = $validatedData['adresse'];

        // Gestion de la photo de profil
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo); // Supprimer l'ancienne image
            }
            $user->photo = $request->file('photo')->store('photo', 'public'); // Sauvegarder la nouvelle image
        }

        // Modification du mot de passe si rempli
        if ($request->filled('current_password') && $request->filled('password')) {
            // Vérifie si l'ancien mot de passe est correct
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($validatedData['password']);
            } else {
                return Redirect::back()->withErrors(['current_password' => 'L\'ancien mot de passe est incorrect']);
            }
        }

        // Sauvegarder les modifications
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}