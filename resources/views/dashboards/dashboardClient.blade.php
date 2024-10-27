@extends('layouts.sidebar-navbarC')

@section('containerC')
<div class="container mt-3" style="background-color: #E5E5E5; padding: 20px; border-radius: 8px;">
    <!-- Section du solde -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Solde et QR Code de Compte</div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <span class="h5 me-3">Solde : 
                            <span id="solde">{{ number_format($solde, 0, ',', ' ') }} FCFA</span>
                        </span>
                        <button class="btn btn-outline-secondary btn-sm" id="toggleSolde">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="mt-3">Numéro de compte : <strong>{{ $numCompte }}</strong></p>

                    <div class="text-center">
                        <img id="qrCode" src="data:image/png;base64,{!! $qrCode !!}" alt="QR Code" style="max-width: 100px; transition: filter 0.3s;" />
                        <p>Scannez ce code pour votre numéro de compte.</p>
                    </div>
                </div>
            </div>
        </div>

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

    <!-- Section des transactions récentes -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Transactions Récentes</div>
                <div class="card-body">
                    @if ($recentTransactions->isEmpty())
                        <p class="text-muted">Aucune transaction récente.</p>
                    @else
                        <div class="table-responsive">
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
                                        <td>{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</td>
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

    <!-- Section des activités mensuelles -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Activités Mensuelles</div>
                <div class="card-body">
                    <canvas id="monthlyActivitiesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var soldeElement = document.getElementById('solde');
        var toggleButton = document.getElementById('toggleSolde');
        var qrCodeElement = document.getElementById('qrCode');

        var soldeVisible = true;
        toggleButton.addEventListener('click', function() {
            soldeElement.style.visibility = soldeVisible ? 'hidden' : 'visible';
            toggleButton.querySelector('i').classList.toggle('fa-eye-slash', soldeVisible);
            toggleButton.querySelector('i').classList.toggle('fa-eye', !soldeVisible);
            soldeVisible = !soldeVisible;
        });

        // Génération dynamique du QR code toutes les 30 secondes
        setInterval(function() {
            qrCodeElement.style.filter = 'blur(4px)'; // Ajouter un flou

            setTimeout(function() {
                $.get('/generate-qr-code', function(data) {
                    qrCodeElement.src = 'data:image/png;base64,' + data.qrCode;
                });

                // Retirer le flou après un court moment
                setTimeout(function() {
                    qrCodeElement.style.filter = 'blur(0)'; // Enlever le flou
                }, 300); // Temps pour enlever le flou
            }, 300); // Temps de flou avant la mise à jour
        }, 30000); // Intervalle de 30 secondes

        // Chart.js initialization for monthly activities
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