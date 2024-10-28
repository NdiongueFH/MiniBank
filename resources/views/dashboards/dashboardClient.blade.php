@extends('layouts.sidebar-navbarC') 
@section('content') 

<!-- Conteneur principal avec des styles pour le padding et les bordures arrondies -->
<div class="container mt-3" style="background-color: #E5E5E5; padding: 20px; border-radius: 8px;">
    
    <!-- Section Solde et QR Code -->
    <div class="row">
        <div class="col-md-6">
            <!-- Carte contenant le solde du compte et le QR code -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Solde et QR Code de Compte</div>
                <div class="card-body">
                    
                    <!-- Affichage du solde avec un bouton pour masquer/afficher le montant -->
                    <div class="d-flex align-items-center">
                        <span class="h5 me-3">Solde : 
                            <span id="solde">{{ number_format($solde, 0, ',', ' ') }} FCFA</span> 
                            <!-- Affiche le solde formaté -->
                        </span>
                        <button class="btn btn-outline-secondary btn-sm" id="toggleSolde">
                            <i class="fas fa-eye"></i> <!-- Icône pour le bouton -->
                        </button>
                    </div>
                    
                    <!-- Affichage du numéro de compte -->
                    <p class="mt-3">Numéro de compte : <strong>{{ $numCompte }}</strong></p>
                    
                    <!-- QR Code du compte -->
                    <div class="text-center">
                        <img id="qrCode" src="data:image/png;base64,{!! $qrCode !!}" alt="QR Code" style="max-width: 100px; transition: filter 0.3s;" />
                        <p>Scannez ce code pour votre numéro de compte.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte pour les plafonds de compte -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Plafonds du Compte</div>
                <div class="card-body">
                    <p>Solde Maximum pour votre Compte : <strong>{{ number_format($plafondsCompte['solde_maximum'], 0, ',', ' ') }} FCFA</strong></p>
                    <p>Cumul Mensuel Maximum : <strong>{{ number_format($plafondsCompte['cumul_mensuel_maximum'], 0, ',', ' ') }} FCFA</strong></p>
                    <p>Cumul Maximum Restant : <strong>{{ number_format($plafondsCompte['cumul_maximum_restant'], 0, ',', ' ') }} FCFA</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Transactions Récentes -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Transactions Récentes</div>
                <div class="card-body">
                    @if ($recentTransactions->isEmpty())
                        <p class="text-muted">Aucune transaction récente.</p>
                    @else
                        <div class="table-responsive">
                            <!-- Table des transactions récentes -->
                            <table class="table table-striped table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Type</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ optional($transaction->receveur)->nom ?? 'Inconnu' }}</td>
                                        <td>{{ optional($transaction->receveur)->prenom ?? 'Inconnu' }}</td>
                                        <td>{{ number_format($transaction->mountant, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>{{ ucfirst($transaction->type) }}</td>  
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section des activités mensuelles avec un graphique -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Activités Mensuelles</div>
                <div class="card-body">
                    <canvas id="monthlyActivitiesChart"></canvas> <!-- Graphique pour les activités mensuelles -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclusion des scripts Chart.js et jQuery -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var soldeElement = document.getElementById('solde');
        var toggleButton = document.getElementById('toggleSolde');
        var qrCodeElement = document.getElementById('qrCode');
        
        // Fonction pour masquer/afficher le solde
        var soldeVisible = true;
        toggleButton.addEventListener('click', function() {
            soldeElement.style.visibility = soldeVisible ? 'hidden' : 'visible';
            toggleButton.querySelector('i').classList.toggle('fa-eye-slash', soldeVisible);
            toggleButton.querySelector('i').classList.toggle('fa-eye', !soldeVisible);
            soldeVisible = !soldeVisible;
        });
        
        // Mise à jour du QR code toutes les 30 secondes
        setInterval(function() {
            qrCodeElement.style.filter = 'blur(4px)';

            setTimeout(function() {
                $.get('/generate-qr-code', function(data) {
                    qrCodeElement.src = 'data:image/png;base64,' + data.qrCode;
                });

                setTimeout(function() {
                    qrCodeElement.style.filter = 'blur(0)';
                }, 300);
            }, 300);
        }, 30000);
        
        // Initialisation de Chart.js pour le graphique des activités mensuelles
        var ctx = document.getElementById('monthlyActivitiesChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(@json($dashboardData['deposit'])),
                datasets: [
                    {
                        label: 'Dépôt',
                        data: Object.values(@json($dashboardData['deposit'])),
                        backgroundColor: '#36a2eb'
                    },
                    {
                        label: 'Retrait',
                        data: Object.values(@json($dashboardData['withdraw'])),
                        backgroundColor: '#ff6384'
                    },
                    {
                        label: 'Transfert',
                        data: Object.values(@json($dashboardData['transfer'])),
                        backgroundColor: '#ffce56'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Montant en FCFA'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mois'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
