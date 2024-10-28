@extends('layouts.sideAndNav')

@section('containerAgent')
<div class="row">
    <!-- Carte pour afficher les informations de l'agent -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informations de l'Agent</h5>
                <p><strong>Nom :</strong> {{ auth()->user()->nom }}</p>
                <p><strong>Prénom :</strong> {{ auth()->user()->prenom }}</p>
                <p><strong>Numéro de Compte :</strong> {{ auth()->user()->num_compte }}</p>
                <p><strong>Carte d'Identité :</strong> {{ auth()->user()->carte_identite }}</p>
                <p><strong>Téléphone :</strong> {{ auth()->user()->telephone }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dernières Transactions</h5>
                <ul class="list-group">
                    @foreach($transactions as $transaction)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <!-- Affiche le nom et prénom du distributeur/client -->
                                <strong>{{ $transaction->distributeur->nom }} {{ $transaction->distributeur->prenom }}</strong><br>
                                <span class="{{ $transaction->type == 'depot' ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->type == 'depot' ? '+' : '-' }}{{ abs($transaction->mountant) }}
                                </span>
                            </div>
                            <span class="float-end">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Carte pour le diagramme en barres -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Diagramme des Transactions</h5>
                <canvas id="transactionsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Carte pour le diagramme par adresse du distributeur -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transactions par Adresse</h5>
                <canvas id="transactionsByAddressChart"></canvas>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

<!-- Ajoutez ce script pour inclure Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script pour les diagrammes -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Exemple de données pour le diagramme des transactions
    const transactionsData = {
        labels: ['Dépôts', 'Retraits'],
        datasets: [{
            label: 'Montant',
            data: [{{ $totalDeposits }}, {{ $totalWithdrawals }}], // Remplacer par les valeurs réelles
            backgroundColor: ['#28a745', '#dc3545'], // Vert pour les dépôts, Rouge pour les retraits
        }]
    };

    const ctx = document.getElementById('transactionsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: transactionsData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Exemple de données pour le diagramme par adresse du distributeur
    const addressesData = {
        labels: ['Adresse 1', 'Adresse 2', 'Adresse 3'], // Remplacer par les adresses réelles
        datasets: [{
            label: 'Transactions',
            data: [12, 19, 3], // Remplacer par les valeurs réelles
            backgroundColor: '#007bff',
        }]
    };

    const ctx2 = document.getElementById('transactionsByAddressChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: addressesData,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>