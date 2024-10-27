<?php

// app/Http/Controllers/ClientController.php

// app/Http/Controllers/ClientController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Compte;
use App\Models\User;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $client = auth()->user();

        // Récupérer le compte associé à l'utilisateur
        $compte = Compte::where('user_id', $client->id)->first();

        // Récupérer les transactions où le client est le receveur ou l'émetteur
        $transactions = Transaction::where(function($query) use ($client) {
            $query->where('receveur_id', $client->id)
                  ->orWhere('emettteur_id', $client->id);
        })
        ->with('distributeur') // Charge les détails du distributeur
        ->orderBy('created_at', 'desc') // Tri par date décroissante
        ->get();

        // Passer les informations à la vue
        return view('transactions', [
            'client' => $client,
            'transactions' => $transactions,
            'compte' => $compte,
        ]);
    }

    public function search(Request $request)
    {
        $accountNumber = $request->input('account_number');
    
        // Rechercher le client dans la table users
        $client = User::where('num_compte', $accountNumber)->first();
    
        if ($client) {
            return response()->json($client);
        } else {
            return response()->json(['error' => 'Client non trouvé'], 404);
        }
    }

    public function transfer(Request $request)
    {
        // Validation des données
        $request->validate([
            'numero_compte' => 'required|string',
            'montant_envoye' => 'required|numeric|min:500', // Vérification que le montant est supérieur à 500
        ]);

        // Trouver le compte émetteur
        $emetteur = Compte::where('user_id', auth()->user()->id)->first();

        // Vérifier si le compte existe
        if (!$emetteur) {
            return response()->json(['error' => 'Votre compte n\'existe pas.'], 404);
        }

        // Vérifier le solde du compte émetteur
        if ($emetteur->solde < $request->montant_envoye) {
            return response()->json(['error' => 'Votre solde est insuffisant pour effectuer ce transfert.'], 403);
        }

        // Trouver le compte récepteur
        $receveur = User::where('num_compte', $request->numero_compte)->first();

        // Vérifier si le compte récepteur existe
        if (!$receveur) {
            return response()->json(['error' => 'Le compte récepteur n\'existe pas.'], 404);
        }

        // Effectuer le transfert
        // Réduire le solde de l'émetteur
        $emetteur->solde -= $request->montant_envoye;
        $emetteur->save();

        // Créer la transaction pour le transfert
        Transaction::create([
            'emettteur_id' => auth()->user()->id,
            'receveur_id' => $receveur->id,
            'distributeur_id' => null, // Mettre à jour si nécessaire
            'agent_id' => null, // Mettre à jour si nécessaire
            'type' => 'transfert',
            'montant' => $request->montant_envoye,
            'frais' => 0, // Ajustez si vous avez des frais
            'statut' => 'completed',
        ]);

        // (Ajouter la logique pour le compte récepteur ici si nécessaire)
        return response()->json(['success' => 'Transfert effectué avec succès.'], 200);
    }
}
