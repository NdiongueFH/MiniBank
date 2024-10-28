<?php

namespace App\Http\Controllers;
use App\Models\Transaction; // Importation du modèle Transaction pour gérer les transactions
use App\Models\Compte; // Importation du modèle Compte pour gérer les comptes utilisateurs
use Illuminate\Support\Facades\Auth; // Auth pour gérer l'authentification des utilisateurs
use Carbon\Carbon; // Carbon pour manipuler les dates
use SimpleSoftwareIO\QrCode\Facades\QrCode; // QrCode pour générer des codes QR

class DashboardController extends Controller
{
    // Méthode pour le tableau de bord client
    public function clientDashboard()
    {
        // Récupération des informations de l'utilisateur connecté
        $client = Auth::user();
        
        // Recherche du compte associé à l'utilisateur connecté
        $compte = Compte::where('user_id', $client->id)->first();

        // Vérification de l'existence du compte. Si le compte n'existe pas, retour à la page précédente avec un message d'erreur.
        if (!$compte) {
            return redirect()->back()->with('error', 'Compte introuvable.');
        }

        // Récupération des trois transactions récentes pour le client
        $recentTransactions = Transaction::where('emettteur_id', $client->id)
            ->orWhere('receveur_id', $client->id)
            ->with(['receveur' => function ($query) {
                $query->select('id', 'prenom', 'nom'); // Récupération du prénom et du nom du receveur
            }])
            ->orderBy('created_at', 'desc') // Tri par date décroissante
            ->take(3) // Limite de 3 transactions
            ->get();

        // Définition de plafonds pour le compte du client
        $cumulMensuelMaximum = 5000000; // Plafond mensuel exemple
        $soldeMaximum = 1000000; // Plafond de solde maximum

        // Calcul du montant total des transactions reçues ce mois-ci
        $cumulMontantsRecus = Transaction::where('receveur_id', $client->id)
            ->whereMonth('created_at', now()->month) // Transactions de ce mois
            ->sum('mountant');
        $cumulMaximumRestant = $cumulMensuelMaximum - $cumulMontantsRecus;

        // Récupération du solde actuel du compte du client
        $solde = $compte->solde;

        // Génération d'un code QR pour le numéro de compte du client
        $qrCodeImage = QrCode::format('png')->size(200)->generate($client->num_compte);

        // Récupération des transactions pour chaque mois afin d'afficher un graphique
        $transactions = Transaction::where('emettteur_id', $client->id)
            ->orWhere('receveur_id', $client->id)
            ->get()
            ->groupBy(function ($transaction) {
                return \Carbon\Carbon::parse($transaction->created_at)->format('M'); // Groupement par mois
            });

        // Structure de données pour le graphique
        $dashboardData = [
            'deposit' => [], // Dépôts mensuels
            'withdraw' => [], // Retraits mensuels
            'transfer' => [], // Transferts mensuels
        ];

        // Remplissage des données pour chaque type de transaction par mois
        foreach ($transactions as $month => $monthTransactions) {
            $dashboardData['deposit'][$month] = $monthTransactions->where('type', 'depot')->sum('mountant');
            $dashboardData['withdraw'][$month] = $monthTransactions->where('type', 'retrait')->sum('mountant');
            $dashboardData['transfer'][$month] = $monthTransactions->where('type', 'transfert')->sum('mountant');
        }

        // Rendu de la vue du tableau de bord avec toutes les données nécessaires
        return view('dashboards.dashboardClient', [
            'client' => $client,
            'recentTransactions' => $recentTransactions,
            'solde' => $solde,
            'qrCode' => base64_encode($qrCodeImage), // Encodage du QR code en base64 pour affichage
            'plafondsCompte' => [
                'solde_maximum' => $soldeMaximum,
                'cumul_mensuel_maximum' => $cumulMensuelMaximum,
                'cumul_maximum_restant' => max(0, $cumulMaximumRestant), // Valeur positive uniquement
            ],
            'numCompte' => $client->num_compte,
            'dashboardData' => $dashboardData, // Données pour le graphique
        ]);
    }

    // Méthode pour générer un QR code unique pour le client
    public function generateQrCode()
    {
        $client = Auth::user();
        $qrCodeImage = QrCode::format('png')->size(200)->generate($client->num_compte . '?' . time()); // Ajout d'un timestamp pour l'unicité
        return response()->json(['qrCode' => base64_encode($qrCodeImage)]); // Retourne le QR code en JSON
    }

    // Méthode pour le tableau de bord d'un agent
    public function agentDashboard()
    {
        return view('dashboards.dashboardAgent'); // Vue du tableau de bord de l'agent
    }

    // Méthode pour le tableau de bord d'un distributeur
    public function distributeurDashboard()
    {
        $distributeur = Auth::user();

        // Récupération du compte du distributeur
        $compte = Compte::where('user_id', $distributeur->id)->first();

        // Vérification de l'existence du compte
        if (!$compte) {
            return redirect()->back()->with('error', 'Compte introuvable.');
        }

        // Récupération des trois transactions récentes avec les informations de type
        $recentTransactions = Transaction::where('emettteur_id', $distributeur->id)
            ->with('receveur') // Récupération des détails du receveur
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Définition des plafonds pour le distributeur
        $cumulMensuelMaximum = 10000000; // Plafond mensuel fixe

        // Calcul du montant des transactions reçues pour le mois en cours
        $cumulMontantsRecus = Transaction::where('receveur_id', $distributeur->id)
            ->whereMonth('created_at', now()->month)
            ->sum('mountant');
        $cumulMaximumRestant = $cumulMensuelMaximum - $cumulMontantsRecus;

        // Récupération du solde actuel du distributeur
        $solde = $compte->solde;
        $soldeMaximum = 2000000; // Plafond fixe de solde

        // Données pour les transactions mensuelles pour le graphique
        $transactions = Transaction::where('emettteur_id', $distributeur->id)
            ->orWhere('receveur_id', $distributeur->id)
            ->get()
            ->groupBy(function ($transaction) {
                return \Carbon\Carbon::parse($transaction->created_at)->format('M'); // Groupement par mois
            });

        // Structure de données pour le graphique
        $dashboardData = [
            'deposit' => [], // Dépôts mensuels
            'withdraw' => [], // Retraits mensuels
        ];

        // Remplissage des données pour chaque mois pour les dépôts et retraits
        foreach ($transactions as $month => $monthTransactions) {
            $dashboardData['deposit'][$month] = $monthTransactions->where('type', 'depot')->sum('mountant');
            $dashboardData['withdraw'][$month] = $monthTransactions->where('type', 'retrait')->sum('mountant');
        }

        // Rendu de la vue du tableau de bord du distributeur avec les données
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
            'dashboardData' => $dashboardData // Données pour le graphique
        ]);
    }
}
