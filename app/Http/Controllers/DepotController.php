<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Compte;
use Illuminate\Support\Facades\Auth;

class DepotController extends Controller
{
    // Afficher le formulaire de dépôt
    public function showForm()
    {
        return view('deposit');
    }

    // Traitement du dépôt
    public function processDeposit(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string',
            'montant' => 'required|numeric|min:0.01'
        ]);

        // Rechercher le distributeur par numéro de téléphone
        $distributeur = User::where('telephone', $request->telephone)->where('role', 'distributeur')->first();

        if (!$distributeur) {
            return redirect()->back()->withErrors(['telephone' => 'Distributeur inexistant.']);
        }

        // Ajouter le montant au solde du distributeur
        $compte = Compte::where('user_id', $distributeur->id)->first();
        $compte->solde += $request->montant;
        $compte->save();

        // Enregistrer la transaction
        Transaction::create([
            'emettteur_id' => Auth::id(),
            'distributeur_id' => $distributeur->id,
            'type' => 'depot',
            'mountant' => $request->montant,
            'agent_id' => Auth::id(),
            'statut' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Dépôt effectué avec succès.');
    }
}