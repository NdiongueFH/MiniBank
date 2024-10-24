<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /*
     

    Affiche la page de connexion.*/
      public function create(){
          return view('auth.login');}


    /*
     

    Traite la demande de connexion.*/
      public function store(Request $request){// Validation des données$request->validate(['telephone' => ['required', 'string'],'password' => ['required', 'string'],]);


        // Tentative de connexion avec le numéro de téléphone
        if (Auth::attempt(['telephone' => $request->telephone, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Redirection vers le tableau de bord en fonction du rôle
            $user = Auth::user();
            switch ($user->role) {
                case 'agent':
                    return redirect()->route('dashboard.agent');
                case 'distributeur':
                    return redirect()->route('dashboard.distributeur');
                case 'client':
                    return redirect()->route('dashboard.client');
            }
        }

        // Si l'authentification échoue
        throw ValidationException::withMessages([
            'telephone' => __('Ces informations de connexion ne correspondent pas à nos enregistrements.'),
        ]);
    }

    /*
     

    Déconnexion de l'utilisateur.*/
      public function destroy(Request $request){Auth::guard('web')->logout();


        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
