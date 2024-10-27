<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Compte;
use App\Models\Transaction;
use App\Models\Client;
use Exception;



class TransactionController extends Controller
{
    public function deposer(Request $request)
    {
        // Validation des données
        $request->validate([
            'account_number' => 'required|exists:users,num_compte',
            'amount' => 'required|numeric|min:1',
        ]);
    
        // Récupérer l'utilisateur (client) par numéro de compte
        $client = User::where('num_compte', $request->account_number)->first();
        $distributeur = auth()->user(); // L'utilisateur connecté (distributeur)
    
        // Vérifiez le solde du distributeur
        $distributeurCompte = Compte::where('user_id', $distributeur->id)->first();
        if ($distributeurCompte->solde < $request->amount) {
            return redirect()->back()->withErrors(['error' => 'Solde insuffisant pour effectuer ce dépôt.']);
        }
    
        // Créer la transaction
        $transaction = Transaction::create([
            'emettteur_id' => $distributeur->id, // Distributeur comme émetteur
            'receveur_id' => $client->id, // Client comme receveur
            'distributeur_id' => $distributeur->id,
            'type' => 'depot',
            'mountant' => $request->amount,
            'frais' => $request->amount * 0.01, // 1% de bonus
            'statut' => 'completed',
        ]);
    
        // Mettre à jour le solde du client
        $clientCompte = Compte::where('user_id', $client->id)->first();
        $clientCompte->updateSolde($request->amount); // Ajout du montant au solde du client
    
        // Mettre à jour le solde du distributeur
        $bonus = $request->amount * 0.01; // Calcul du bonus de 1%
        $distributeurCompte->updateSolde(-$request->amount + $bonus); // Soustraction du montant et ajout du bonus
    
        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Dépôt effectué avec succès !');
    }

   
    public function retirer(Request $request)
    {
        // Validation des données
        $request->validate([
            'account_number' => 'required|exists:users,num_compte',
            'amount' => 'required|numeric|min:1',
        ]);
    
        // Récupérer l'utilisateur (client) par numéro de compte
        $client = User::where('num_compte', $request->account_number)->first();
        $distributeur = auth()->user(); // L'utilisateur connecté (distributeur)
    
        // Vérifiez le solde du client
        $clientCompte = Compte::where('user_id', $client->id)->first();
        if ($clientCompte->solde < $request->amount) {
            return redirect()->back()->withErrors(['error' => 'Solde insuffisant pour effectuer ce retrait.']);
        }
    
        // Créer la transaction
        $transaction = Transaction::create([
            'emettteur_id' => $client->id, // Client comme émetteur
            'receveur_id' => $distributeur->id, // Distributeur comme receveur
            'distributeur_id' => $distributeur->id,
            'type' => 'retrait',
            'mountant' => $request->amount,
            'frais' => $request->amount * 0.01, // 1% de bonus
            'statut' => 'completed',
        ]);
    
        // Mettre à jour le solde du client
        $clientCompte->updateSolde(-$request->amount); // Soustraction du montant du solde du client
    
        // Mettre à jour le solde du distributeur
        $bonus = $request->amount * 0.01; // Calcul du bonus de 1%
        $distributeurCompte = Compte::where('user_id', $distributeur->id)->first();
        $distributeurCompte->updateSolde($request->amount + $bonus); // Ajout du montant et du bonus au solde du distributeur
    
        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Retrait effectué avec succès !');
    }

    public function annulerTransaction($id)
    {
        // Trouver la transaction
        $transaction = Transaction::find($id);
    
        // Vérifiez si la transaction existe et si elle peut être annulée
        if (!$transaction || $transaction->annule || $transaction->expires_at < now()) {
            throw new Exception("La transaction ne peut pas être annulée.");
        }
    
        // Mettre à jour le statut et l'annulation de la transaction
        $transaction->update(['annule' => true, 'statut' => 'annulé']);
    
        // Mettre à jour les comptes en fonction du type de transaction
        $emetteur = $transaction->emetteur; // L'émetteur de la transaction
        $receveur = $transaction->receveur; // Le receveur de la transaction
        $distributeur = $transaction->distributeur; // Le distributeur
    
        // Récupérer le compte du distributeur
        $compteDistributeur = Compte::where('user_id', $distributeur->id)->first();
        
        if ($transaction->type === 'transfert') {
            // Remboursement à l'émetteur
            $montant = $transaction->mountant;
    
            // Récupérer le compte de l'émetteur et du receveur
            $compteEmetteur = Compte::where('user_id', $emetteur->id)->first();
            $compteReceveur = Compte::where('user_id', $receveur->id)->first();
    
            // Mettre à jour le solde de l'émetteur et du receveur
            $compteEmetteur->increment('solde', $montant);
            $compteReceveur->decrement('solde', $montant);
    
        } elseif ($transaction->type === 'depot') {
            // Retirer le montant et le bonus de 1% du distributeur
            $montant = $transaction->mountant;
            $bonus = $montant * 0.01;
    
            // Mettre à jour le solde du distributeur
            $compteDistributeur->decrement('solde', $montant + $bonus);
    
        } elseif ($transaction->type === 'retrait') {
            // Retirer le montant et le bonus de 1% du distributeur
            $montant = $transaction->mountant;
            $bonus = $montant * 0.01;
    
            // Récupérer le compte du client (receveur)
            $compteReceveur = Compte::where('user_id', $receveur->id)->first();
    
            // Mettre à jour le solde du client et du distributeur
            $compteReceveur->decrement('solde', $montant);
            $compteDistributeur->decrement('solde', $bonus);
        }
    
        return response()->json(['message' => 'Transaction annulée avec succès.'], 200);
    }

    public function index()
    {

        
        // Récupérer l'utilisateur connecté
        $distributeur = auth()->user();

        // Vérifier si l'utilisateur est authentifié
        if (!$distributeur) {
            return redirect()->route('login')->withErrors(['error' => 'Vous devez être connecté pour accéder à cette page.']);
        }

        // Récupérer le compte associé
        $compte = Compte::where('user_id', $distributeur->id)->first();

         // Récupérer uniquement les transactions pour ce distributeur
        $transactions = Transaction::with('receveur')
        ->where('distributeur_id', $distributeur->id) // Filtrer par distributeur connecté
        ->orderBy('created_at', 'desc') // Tri par date décroissante
        ->paginate(5); // Nombre de transactions par page
       
        // Passer les transactions, l'utilisateur et le compte à la vue
        return view('distributeur_transactions', [
            'transactions' => $transactions,
            'user' => $distributeur,
            'compte' => $compte,
        ]);

        
    }

    public function dashboard()
    {
        // Récupérer l'utilisateur connecté
        $distributeur = auth()->user();
        // Récupérer le compte associé
        $compte = Compte::where('user_id', $distributeur->id)->first();

        // Vérifiez les valeurs
        dd($distributeur, $compte);

        return view('distributeur_transactions', [
            'user' => $distributeur,
            'compte' => $compte,
        ]);
    }
    public function show($id)
{
    $transaction = Transaction::with('receveur')->find($id);

    if ($transaction) {
        return response()->json($transaction);
    } else {
        return response()->json(['error' => 'Transaction non trouvée'], 404);
    }
}




}