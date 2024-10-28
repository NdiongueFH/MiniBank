<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransactionAnnulation; // Importez le modèle
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    public function agentTransactions()
    {
        $transactions = Transaction::where('agent_id', Auth::id())
            ->with('distributeur')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('Agent.transactions', compact('transactions'));
    }

    public function showTransactionForm(Request $request)
    {
        $telephone = $request->input('telephone');
        $distributeur = User::where('telephone', $telephone)
            ->whereIn('role', ['distributeur', 'client'])
            ->first();

        if (!$distributeur) {
            return response()->json([
                'status' => 'error',
                'message' => 'Numéro distributeur ou client inexistant',
            ], 404);
        }

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

        $distributeur = User::findOrFail($validatedData['distributeur_id']);
        if ($distributeur->blocked) {
            return response()->json(['status' => 'error', 'message' => 'Ce compte est bloqué. Vous ne pouvez pas effectuer de transactions.']);
        }

        $compte = Compte::where('user_id', $distributeur->id)->first();
        if (!$compte) {
            $compte = new Compte();
            $compte->user_id = $distributeur->id;
            $compte->solde = 0; 
            $compte->save();
        }

        $compte->solde += $validatedData['montant'];
        $compte->save();

        Transaction::create([
            'mountant' => $validatedData['montant'],
            'agent_id' => Auth::id(),
            'distributeur_id' => $distributeur->id,
            'statut' => 'success',
        ]);

        return redirect()->route('transactions')->with('success', 'Transaction effectuée avec succès!');
    }

    public function canceledTransactions()
    {
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
    public function dashboard()
    {
        $userId = Auth::id();
        $transactions = Transaction::where('agent_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $totalDeposits = Transaction::where('agent_id', $userId)
            ->where('type', 'depot')
            ->sum('mountant');

        $totalWithdrawals = Transaction::where('agent_id', $userId)
            ->where('type', 'retrait')
            ->sum('mountant');

        return view('dashboards.dashboardAgent', compact('transactions', 'totalDeposits', 'totalWithdrawals'));
    }

    public function deposer(Request $request)
    {
        $request->validate([
            'account_number' => 'required|exists:users,num_compte',
            'amount' => 'required|numeric|min:1',
        ]);

        $client = User::where('num_compte', $request->account_number)->first();
        $distributeur = auth()->user();

        $distributeurCompte = Compte::where('user_id', $distributeur->id)->first();
        if ($distributeurCompte->solde < $request->amount) {
            return redirect()->back()->withErrors(['error' => 'Solde insuffisant pour effectuer ce dépôt.']);
        }

        Transaction::create([
            'emettteur_id' => $distributeur->id,
            'receveur_id' => $client->id,
            'distributeur_id' => $distributeur->id,
            'type' => 'depot',
            'mountant' => $request->amount,
            'frais' => $request->amount * 0.01,
            'statut' => 'completed',
        ]);

        $clientCompte = Compte::where('user_id', $client->id)->first();
        $clientCompte->updateSolde($request->amount);

        $bonus = $request->amount * 0.01;
        $distributeurCompte->updateSolde(-$request->amount + $bonus);

        return redirect()->back()->with('success', 'Dépôt effectué avec succès !');
    }

    public function retirerClient(Request $request)
    {
        $request->validate([
            'account_number' => 'required|exists:users,num_compte',
            'amount' => 'required|numeric|min:1',
        ]);

        $client = User::where('num_compte', $request->account_number)->first();
        $distributeur = auth()->user();

        $clientCompte = Compte::where('user_id', $client->id)->first();
        if ($clientCompte->solde < $request->amount) {
            return redirect()->back()->withErrors(['error' => 'Solde insuffisant pour effectuer ce retrait.']);
        }

        Transaction::create([
            'emettteur_id' => $client->id,
            'receveur_id' => $distributeur->id,
            'distributeur_id' => $distributeur->id,
            'type' => 'retrait',
            'mountant' => $request->amount,
            'frais' => $request->amount * 0.01,
            'statut' => 'completed',
        ]);

        $clientCompte->updateSolde(-$request->amount);

        $bonus = $request->amount * 0.01;
        $distributeurCompte = Compte::where('user_id', $distributeur->id)->first();
        $distributeurCompte->updateSolde($request->amount + $bonus);

        return redirect()->back()->with('success', 'Retrait effectué avec succès !');
    }
    public function retirerAgent(Request $request)
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

    public function annulerTransaction($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction || $transaction->annule || $transaction->expires_at < now()) {
            throw new Exception("La transaction ne peut pas être annulée.");
        }

        $transaction->update(['annule' => true, 'statut' => 'annulé']);

        $emetteur = $transaction->emetteur;
        $receveur = $transaction->receveur;
        $distributeur = $transaction->distributeur;

        $compteDistributeur = Compte::where('user_id', $distributeur->id)->first();

        if ($transaction->type === 'transfert') {
            $montant = $transaction->mountant;
            $compteEmetteur = Compte::where('user_id', $emetteur->id)->first();
            $compteReceveur = Compte::where('user_id', $receveur->id)->first();
            $compteEmetteur->increment('solde', $montant);
            $compteReceveur->decrement('solde', $montant);
        } elseif ($transaction->type === 'depot') {
            $montant = $transaction->mountant;
            $bonus = $montant * 0.01;
            $compteDistributeur->decrement('solde', $montant + $bonus);
        } elseif ($transaction->type === 'retrait') {
            $montant = $transaction->mountant;
            $bonus = $montant * 0.01;
            $compteReceveur = Compte::where('user_id', $receveur->id)->first();
            $compteReceveur->decrement('solde', $montant);
            $compteDistributeur->decrement('solde', $bonus);
        }

        return response()->json(['message' => 'Transaction annulée avec succès.'], 200);
    }

    public function index()
    {
        $distributeur = auth()->user();
        if (!$distributeur) {
            return redirect()->route('login')->withErrors(['error' => 'Vous devez être connecté pour accéder à cette page.']);
        }

        $compte = Compte::where('user_id', $distributeur->id)->first();
        $transactions = Transaction::with('receveur')
            ->where('distributeur_id', $distributeur->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('distributeur_transactions', [
            'transactions' => $transactions,
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
