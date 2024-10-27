<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
 

class UserController extends Controller{
    public function create()
    {
        return view('users.create'); // Retourne la vue de création d'utilisateur
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:users',
            'email' => 'nullable|email|unique:users',
            'adresse' => 'required|string|max:255',
            'carte_identite' => 'required|string|unique:users',
            'date_naissance' => 'required|date|before:today',
            'password' => 'required|string|min:8',
            'role' => 'required|in:agent,distributeur,client',
        ]);
    
        User::create($validatedData);
    
        return redirect()->route('welcome')->with('success', 'Utilisateur créé avec succès.');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string',
            'password' => 'required|string',
        ]);
    
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
    

}