@extends('layouts.sidebar-navbarC')

@section('containerC')
<div class="container mt-3" style="background-color: #E5E5E5; padding: 20px; border-radius: 8px;">
    <h2>Bienvenue, {{ $client->prenom }} !</h2>

    <!-- Section du solde -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Solde</div>
                <div class="card-body">
                    <span class="h5">Solde : <strong id="solde">{{ number_format($solde, 0, ',', ' ') }} FCFA</strong></span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">QR Code de Compte</div>
                <div class="card-body text-center">
                    <img src="data:image/png;base64, {!! base64_encode($qrCode) !!}" alt="QR Code" />
                    <p>Scannez ce code pour votre numéro de compte.</p>
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
                                        <th>Nom du Receveur</th>
                                        <th>Prénom du Receveur</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ optional($transaction->receveur)->nom ?? 'Inconnu' }}</td>
                                        <td>{{ optional($transaction->receveur)->prenom ?? 'Inconnu' }}</td>
                                        <td>{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</td>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' FCFA';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection