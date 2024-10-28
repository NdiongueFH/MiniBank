<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    //
    public function index()
    {
        // Afficher le tableau de bord des agents
        return view('dashboard.agent');
    }

    public function createDistributeur(Request $request)
    {
        $validatedData = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:users',
            'adresse' => 'required|string|max:255',
            'carte_identite'=>'required|string|max:15',
            'date_naissance' => 'required|date|before:today',
        ]);

        $num_compte = $this->generateAccountNumber('distributeur');

        $distributeur = User::create([
            'prenom' => $validatedData['prenom'],
            'nom' => $validatedData['nom'],
            'telephone' => $validatedData['telephone'],
            'adresse' => $validatedData['adresse'],
            'date_naissance' => $validatedData['date_naissance'],
            'mot_de_passe' => Hash::make('default_password'), // Vous pouvez changer ça
            'role' => 'distributeur',
            'carte_identite' => $validatedData['carte_identite'],
            'num_compte' => $num_compte,
        ]);


        // Optionnel : notifier le distributeur
        // Notification::send($distributor, new AccountCreatedNotification());

        return response()->json($distributeur, 201);
    }

    public function createClient(Request $request)
    {
        $validatedData = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:users',
            'adresse' => 'required|string|max:255',
            'carte_identite'=>'required|string|max:15',
            'date_naissance' => 'required|date|before:today',
        ]);

        $num_compte = $this->generateAccountNumber('client');

        $client = User::create([
            'prenom' => $validatedData['prenom'],
            'nom' => $validatedData['nom'],
            'telephone' => $validatedData['telephone'],
            'adresse' => $validatedData['adresse'],
            'date_naissance' => $validatedData['date_naissance'],
            'mot_de_passe' => Hash::make('default_password'), // Vous pouvez changer ça
            'role' => 'client',
            'carte_identite' => $validatedData['carte_identite'],
            'num_compte' => $num_compte,
        ]);

        // Optionnel : notifier le client
        // Notification::send($client, new AccountCreatedNotification());

        return response()->json($client, 201);
    }

    private function generateAccountNumber($role)
    {
        return substr($role, 0, 3) . date('Y') . rand(1000, 9999);
    }
}