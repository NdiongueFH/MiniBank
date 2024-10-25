<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
class AgentController extends Controller
{
    public function showDistributeurs() {
        // Filtrer les utilisateurs où le rôle est distributeur
        $distributeurs = User::where('role', 'distributeur')->get();
        return view('agents.listes', ['users' => $distributeurs]);
    }

    public function showClients() {
        // Filtrer les utilisateurs où le rôle est client
        $clients = User::where('role', 'client')->get();
        return view('agents.listes', ['users' => $clients]);
    }

    public function showDistributeursBloques() {
        // Filtrer les distributeurs bloqués
        $distributeurs = User::where('role', 'distributeur')->where('status', 'bloque')->get();
        return view('agents.listes', ['users' => $distributeurs]);
    }

    public function showClientsBloques() {
        // Filtrer les clients bloqués
        $clients = User::where('role', 'client')->where('status', 'bloque')->get();
        return view('agents.listes', ['users' => $clients]);
    }

    public function ajouterUtilisateur(Request $request) {
    // Valider les données du formulaire
$validatedData = $request->validate([
    'prenom' => 'required|string|max:255',
    'nom' => 'required|string|max:255',
    'telephone' => 'required|string|max:20|unique:users,telephone',
    'email' => 'nullable|email|unique:users,email',
    'num_compte' => 'nullable|string|max:20|unique:users,num_compte',
    'adresse' => 'required|string|max:255',
    'carte_identite' => 'required|string|max:14|unique:users,carte_identite',
    'photo' => 'nullable|string|max:255', // si c'est une URL ou un chemin d'image
    'date_naissance' => 'required|date',
    'password' => 'required|string|min:8|confirmed',
    'role' => 'in:distributeur,client,agent', // Retrait de required
]);

// Assigner le rôle par défaut si non fourni
$validatedData['role'] = $request->input('role', 'client');

    
        // Créer un nouvel utilisateur
        User::create([
            'prenom' => $validatedData['prenom'],
            'nom' => $validatedData['nom'],
            'telephone' => $validatedData['telephone'],
            'email' => $validatedData['email'],
            'num_compte' => $validatedData['num_compte'],
            'adresse' => $validatedData['adresse'],
            'carte_identite' => $validatedData['carte_identite'],
            'photo' => $validatedData['photo'],
            'date_naissance' => $validatedData['date_naissance'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'blocked' => false, // Par défaut, l'utilisateur n'est pas bloqué
        ]);
    
        // Rediriger avec un message de succès
        return redirect()->back()->with('message', 'Utilisateur ajouté avec succès.');
    }
    





}
