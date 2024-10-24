@extends('layouts.sidebar-navbarD')

@section('content')
<div class="container mt-3" style="background-color: #E5E5E5; padding: 20px; border-radius: 8px;">
    <div class="row">
        <!-- Section du solde -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Solde</div>
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
                </div>
            </div>
        </div>

        <!-- Section des transactions récentes -->
        <div class="col-md-6">
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

    {{-- <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Transfert Rapide</div>
                <div class="card-body">
                    <p class="text-muted">Contenu du Transfert Rapide</p>
                </div>
            </div>
        </div> --}}

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
        var soldeElement = document.getElementById('solde');
        var toggleButton = document.getElementById('toggleSolde');

        var soldeVisible = true; // Le solde est visible par défaut

        toggleButton.addEventListener('click', function() {
            if (soldeVisible) {
                soldeElement.style.visibility = 'hidden'; // Masquer le solde
                toggleButton.querySelector('i').classList.remove('fa-eye');
                toggleButton.querySelector('i').classList.add('fa-eye-slash');
            } else {
                soldeElement.style.visibility = 'visible'; // Afficher le solde
                toggleButton.querySelector('i').classList.remove('fa-eye-slash');
                toggleButton.querySelector('i').classList.add('fa-eye');
            }
            soldeVisible = !soldeVisible; // Inverser l'état de visibilité
        });

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