<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche la page de connexion.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Gère une tentative de connexion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valider les données de connexion
        $request->validate([
            'telephone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    
        // Tenter de connecter l'utilisateur avec le numéro de téléphone et le mot de passe
        if (!Auth::attempt(['telephone' => $request->telephone, 'password' => $request->password], $request->remember)) {
            // Rediriger en cas d'échec de connexion avec un message d'erreur
            return back()->withErrors([
                'telephone' => 'Les informations d\'identification sont incorrectes.',
            ])->onlyInput('telephone'); // Retourne uniquement l'entrée 'telephone'
        }
    
        // Si la connexion réussit, régénérer la session pour éviter les attaques CSRF
        $request->session()->regenerate();
    
        // Récupérer l'utilisateur actuellement connecté
        $user = Auth::user();
    
        // Redirection vers le tableau de bord en fonction du rôle de l'utilisateur
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
    

    /**
     * Déconnecte l'utilisateur et détruit la session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        // Déconnecter l'utilisateur
        Auth::guard('web')->logout();
    
        // Invalider la session en cours
        $request->session()->invalidate();
    
        // Régénérer le token CSRF pour des raisons de sécurité
        $request->session()->regenerateToken();
    
        // Définir les en-têtes pour empêcher la mise en cache
        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
    }
