<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Compte;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DashboardController extends Controller
{
    public function clientDashboard()
{
    $client = Auth::user();
    $compte = Compte::where('user_id', $client->id)->first();

    if (!$compte) {
        return redirect()->back()->with('error', 'Compte introuvable.');
    }

    // Transactions récentes
    $recentTransactions = Transaction::where('emetteur_id', $client->id)
        ->orWhere('receveur_id', $client->id)
        ->with(['receveur' => function ($query) {
            $query->select('id', 'prenom', 'nom');
        }])
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    // Plafonds fixes pour le compte client
    $cumulMensuelMaximum = 5000000; // Exemple de plafond mensuel
    $soldeMaximum = 1000000; // Plafond fixe

    // Calculer le cumul mensuel des transactions
    $cumulMontantsRecus = Transaction::where('receveur_id', $client->id)
        ->whereMonth('created_at', now()->month)
        ->sum('montant');
    $cumulMaximumRestant = $cumulMensuelMaximum - $cumulMontantsRecus;

    // Obtenir le solde du client
    $solde = $compte->solde;

    // Générer le QR code pour le numéro de compte
    $qrCodeImage = QrCode::format('png')->size(200)->generate($client->num_compte);

    // Transactions pour le graphique
    $transactions = Transaction::where('emetteur_id', $client->id)
        ->orWhere('receveur_id', $client->id)
        ->get()
        ->groupBy(function ($transaction) {
            return \Carbon\Carbon::parse($transaction->created_at)->format('M');
        });

    $dashboardData = [
        'deposit' => [],
        'withdraw' => [],
        'transfer' => [],
    ];

    foreach ($transactions as $month => $monthTransactions) {
        $dashboardData['deposit'][$month] = $monthTransactions->where('type', 'depot')->sum('montant');
        $dashboardData['withdraw'][$month] = $monthTransactions->where('type', 'retrait')->sum('montant');
        $dashboardData['transfer'][$month] = $monthTransactions->where('type', 'transfert')->sum('montant');
    }

    return view('dashboards.dashboardClient', [
        'client' => $client,
        'recentTransactions' => $recentTransactions,
        'solde' => $solde,
        'qrCode' => base64_encode($qrCodeImage),
        'plafondsCompte' => [
            'solde_maximum' => $soldeMaximum,
            'cumul_mensuel_maximum' => $cumulMensuelMaximum,
            'cumul_maximum_restant' => max(0, $cumulMaximumRestant),
        ],
        'numCompte' => $client->num_compte,
        'dashboardData' => $dashboardData, // Assurez-vous que cela est inclus
    ]);
}

    public function generateQrCode()
{
    $client = Auth::user();
    $qrCodeImage = QrCode::format('png')->size(200)->generate($client->num_compte . '?' . time());
    return response()->json(['qrCode' => base64_encode($qrCodeImage)]);
}



    

    public function agentDashboard()
    {
        return view('dashboards.dashboardAgent');
    }

    public function distributeurDashboard()
    {
        $distributeur = Auth::user();
    
        // Récupérer le compte du distributeur
        $compte = Compte::where('user_id', $distributeur->id)->first();
    
        // Vérifier si le compte existe
        if (!$compte) {
            return redirect()->back()->with('error', 'Compte introuvable.');
        }
    
        // Récupérer les transactions récentes avec le type inclus
        $recentTransactions = Transaction::where('emetteur_id', $distributeur->id)
            ->with('receveur')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    
        // Définir le Cumul Mensuel Maximum
        $cumulMensuelMaximum = 10000000; // Plafond fixe pour le mois
    
        // Calculer le cumul des montants reçus ce mois-ci
        $cumulMontantsRecus = Transaction::where('receveur_id', $distributeur->id)
            ->whereMonth('created_at', now()->month)
            ->sum('montant');
    
        // Calculer le cumul maximum restant
        $cumulMaximumRestant = $cumulMensuelMaximum - $cumulMontantsRecus;
    
        // Récupérer le solde
        $solde = $compte->solde;
        $soldeMaximum = 2000000; // Plafond fixe
    
        // Récupérer les données pour le graphique
        $transactions = Transaction::where('emetteur_id', $distributeur->id)
            ->orWhere('receveur_id', $distributeur->id)
            ->get()
            ->groupBy(function ($transaction) {
                return \Carbon\Carbon::parse($transaction->created_at)->format('M');
            });
    
        $dashboardData = [
            'deposit' => [],
            'withdraw' => [],
        ];
    
        foreach ($transactions as $month => $monthTransactions) {
            $dashboardData['deposit'][$month] = $monthTransactions->where('type', 'depot')->sum('montant');
            $dashboardData['withdraw'][$month] = $monthTransactions->where('type', 'retrait')->sum('montant');
        }
    
        return view('dashboards.dashboardDistributeur', [
            'distributeur' => $distributeur,
            'recentTransactions' => $recentTransactions,
            'plafondsCompte' => [
                'solde_maximum' => $soldeMaximum,
                'cumul_mensuel_maximum' => $cumulMensuelMaximum,
                'cumul_maximum_restant' => max(0, $cumulMaximumRestant),
            ],
            'numCompte' => $distributeur->num_compte,
            'solde' => $solde,
            'dashboardData' => $dashboardData // Passer les données pour le graphique
        ]);
    }
    










}




