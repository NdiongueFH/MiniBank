<?php

// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionAnnulation; // Importez le modèle
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function agentTransactions()
{
    // Récupérer les transactions paginées de l'agent connecté, 5 par page
    $transactions = Transaction::where('agent_id', Auth::id())
        ->with('distributeur')
        ->orderBy('created_at', 'desc')
        ->paginate(5);



    return view('Agent.transactions', compact('transactions'));
}

    public function showTransactionForm(Request $request)
    {
        $telephone = $request->input('telephone');

        // Recherche l'utilisateur dans la base de données par numéro de téléphone
        $distributeur = User::where('telephone', $telephone)
            ->whereIn('role', ['distributeur', 'client']) // On s'assure que c'est un distributeur ou un client
            ->first();

        if (!$distributeur) {
            // Si aucun utilisateur n'est trouvé, retourner un message d'erreur
            return response()->json([
                'status' => 'error',
                'message' => 'Numéro distributeur ou client inexistant',
            ], 404);
        }

        // Si l'utilisateur existe, rediriger vers le formulaire de transaction
        return response()->json([
            'status' => 'success',
            'url' => route('transaction.create', ['distributeur' => $distributeur->id])
        ]);
    }

    public function create(Request $request)
    {
        $distributeur = User::findOrFail($request->input('distributeur'));
        return view('Agent.create_transaction', compact('distributeur'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'montant' => 'required|numeric|min:1',
            'distributeur_id' => 'required|exists:users,id',
        ]);

        // Ajouter le montant au solde du distributeur
        $distributeur = User::findOrFail($validatedData['distributeur_id']);
        if ($distributeur->blocked) {
            return response()->json(['status' => 'error', 'message' => 'Ce compte est bloqué. Vous ne pouvez pas effectuer de transactions.']);
        }
        $compte = Compte::where('user_id', $distributeur->id)->first();

        // Si le compte n'existe pas, le créer
        if (!$compte) {
            $compte = new Compte();
            $compte->user_id = $distributeur->id;
            $compte->solde = 0; // Solde initial à 0
            $compte->save();
        }



        // Ajouter le montant au solde du distributeur
        $compte->solde += $validatedData['montant'];
        $compte->save();

        // Créer la transaction
        Transaction::create([
            'mountant' => $validatedData['montant'],
            'agent_id' => Auth::id(),
            'distributeur_id' => $distributeur->id,
            'statut' => 'success', // Vous pouvez modifier le statut selon la logique
        ]);

        return redirect()->route('transactions')->with('success', 'Transaction effectuée avec succès!');
    }

    public function canceledTransactions()
    {
        // Récupérer les transactions annulées par l'agent connecté
        $transactions = Transaction::where('agent_id', Auth::id())
            ->where('annule', true)
            ->with(['distributeur' => function($query) {
                $query->select('id', 'nom', 'prenom', 'num_compte', 'carte_identite');
            }])->get();

        return view('Agent.annulations', compact('transactions'));
    }



    public function cancel($id)
    {
        $transaction = Transaction::findOrFail($id);

        // Déduire le montant du solde
        $compte = Compte::where('user_id', $transaction->distributeur_id)->first();
        if ($compte) {
            $compte->solde -= $transaction->mountant;
            $compte->save();
        }

            $transaction->annule = true;
            $transaction->statut = 'annulé';
            $transaction->save();

            return redirect()->route('transactions')->with('status', 'Transaction annulée et solde mis à jour.');
    }

public function showCancelledTransactions()
{
    $annulations = TransactionAnnulation::with('transaction', 'client', 'agent')
        ->where('agent_id', Auth::id())
        ->get();

    return view('Agent.annulations', compact('annulations'));
}
public function retirer(Request $request)
{
    $request->validate([
        'telephone' => 'required|string',
        'montant' => 'required|numeric|min:1',
    ]);

    $distributeur = User::where('telephone', $request->input('telephone'))
        ->whereIn('role', ['distributeur', 'client'])
        ->first();

    if (!$distributeur) {
        return response()->json(['status' => 'error', 'message' => 'Client ou distributeur inexistant.']);
    }

    // Vérifier le solde du compte
    $compte = Compte::where('user_id', $distributeur->id)->first();

    if (!$compte || $compte->solde < $request->input('montant')) {
        return response()->json(['status' => 'error', 'message' => 'Solde insuffisant.']);
    }

    if ($distributeur->blocked) {
        return response()->json(['status' => 'error', 'message' => 'Ce compte est bloqué. Vous ne pouvez pas effectuer de transactions.']);
    }

    // Déduire le montant du solde
    $compte->solde -= $request->input('montant');
    $compte->save();

    // Créer une nouvelle transaction de retrait
    Transaction::create([
        'mountant' => -$request->input('montant'), // Montant négatif pour un retrait
        'agent_id' => Auth::id(),
        'distributeur_id' => $distributeur->id,
        'type' => 'retrait', // Définir le type sur 'retrait'
        'statut' => 'success',
    ]);

    return response()->json(['status' => 'success']);
}
public function dashboard()
{
    $userId = Auth::id(); // ID de l'agent connecté

    // Récupérer les trois dernières transactions
    $transactions = Transaction::where('agent_id', $userId)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    // Calculer les montants des dépôts et retraits
    $totalDeposits = Transaction::where('agent_id', $userId)
        ->where('type', 'depot')
        ->sum('mountant');

    $totalWithdrawals = Transaction::where('agent_id', $userId)
        ->where('type', 'retrait')
        ->sum('mountant');

    // Assurez-vous que la vue est correcte
    return view('dashboards.dashboardAgent', compact('transactions', 'totalDeposits', 'totalWithdrawals'));
}




}


