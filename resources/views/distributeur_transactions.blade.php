@extends('layouts.sidebar-navbarD')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <!-- Carte du solde principal -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body position-relative bg-gradient-primary text-white rounded">
                    <div class="row">
                        <div class="col-md-7">
                            <h6 class="text-white-50 mb-3">
                                <i class="fas fa-wallet me-2"></i>Solde disponible
                            </h6>
                            <div class="d-flex align-items-center mb-3">
                                <h3 class="mb-4">{{ $compte ? number_format($compte->solde, 2) : '0.00' }} FCFA</h3>
                                <button class="btn btn-light btn-sm rounded-circle" id="toggleSolde">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag me-2"></i>
                                <span>{{ $user->telephone }}</span>
                            </div>
                        </div>
                        <div class="col-md-5 text-center">
                          
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte des actions rapides -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body d-flex justify-content-around">
                    <button class="btn btn-primary d-flex flex-column align-items-center p-3 rounded-3" 
                            data-bs-toggle="modal" data-bs-target="#depositModal">
                        <i class="fas fa-arrow-down mb-2 fa-2x"></i>
                        <span>Dépôt</span>
                    </button>
                    <button class="btn btn-success d-flex flex-column align-items-center p-3 rounded-3" 
                            data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                        <i class="fas fa-arrow-up mb-2 fa-2x"></i>
                        <span>Retrait</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions récentes -->
    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="fas fa-exchange-alt me-2"></i>Transactions Récentes
                </h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary active">Aujourd'hui</button>
                    <button class="btn btn-sm btn-outline-primary">Cette semaine</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Bénéficiaire</th>
                            <th class="border-0">Montant</th>
                            <th class="border-0">Date & Heure</th>
                            <th class="border-0">Type</th>
                            <th class="border-0">Statut</th>
                            <th class="border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach ($transactions as $transaction)
<tr>
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar-initial rounded-circle bg-primary-subtle text-primary me-2">
                @if ($transaction->receveur)
                    {{ substr($transaction->receveur->prenom, 0, 1) }}
                @else
                    ?
                @endif
            </div>
            <div>
                @if ($transaction->receveur)
                    <h6 class="mb-0">{{ $transaction->receveur->nom }}</h6>
                    <small class="text-muted">{{ $transaction->receveur->prenom }}</small>
                @else
                    <h6 class="mb-0">Receveur non trouvé</h6>
                    <small class="text-muted">N/A</small>
                @endif
            </div>
        </div>
    </td>
    <td>
        <span class="{{ $transaction->type == 'depot' ? 'text-danger' : 'text-success' }}">
            {{ $transaction->type == 'depot' ? '-' : '+' }}{{ number_format(abs($transaction->montant), 2) }} FCFA
        </span>
    </td>
    <td>
        <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
        <small class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
    </td>
    <td>
        <span class="badge {{ $transaction->type == 'depot' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
            {{ ucfirst($transaction->type) }}
        </span>
    </td>
    <td>
        <span class="badge bg-success">Complété</span>
    </td>
    <td>
        <button class="btn btn-link text-primary" data-bs-toggle="modal" 
                data-bs-target="#factureModal" 
                data-id="{{ $transaction->id }}"
                data-nom="{{ $transaction->receveur ? $transaction->receveur->nom : 'N/A' }}"
                data-prenom="{{ $transaction->receveur ? $transaction->receveur->prenom : 'N/A' }}"
                data-telephone="{{ $transaction->receveur ? $transaction->receveur->telephone : 'N/A' }}"
                data-date="{{ $transaction->created_at->format('Y-m-d') }}"
                data-montant="{{ number_format($transaction->montant, 2) }} FCFA"
                data-type="{{ $transaction->type }}">
            <i class="fas fa-file-invoice me-1"></i>
            Facture
        </button>
       
        
    </td>
</tr>
@endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    
    .avatar-initial {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }
    
    .progress {
        background-color: #edf2f9;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .btn-group .btn {
        border-radius: 20px;
    }

    .badge {
        padding: 0.5em 1em;
        border-radius: 20px;
    }

    .btn-primary, .btn-success {
        border-radius: 10px;
        transition: transform 0.2s;
    }

    .btn-primary:hover, .btn-success:hover {
        transform: translateY(-2px);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la visibilité du solde
    const soldeElement = document.getElementById('solde');
    const toggleButton = document.getElementById('toggleSolde');
    
    toggleButton.addEventListener('click', function() {
        soldeVisible = !soldeVisible;
        soldeElement.style.visibility = soldeVisible ? 'visible' : 'hidden';
        toggleButton.querySelector('i').className = soldeVisible ? 'fas fa-eye' : 'fas fa-eye-slash';
    });
});
</script>
    
    <!-- Les modals restent les mêmes mais avec des styles Bootstrap améliorés -->
              
        <!-- Modal pour le dépôt -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Taille du modal -->
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="depositModalLabel">Menu de dépôt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center"> <!-- Alignement centré -->
                <!-- Image ajoutée ici -->
                <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300" style="margin-bottom: 20px;">
                
                <!-- Formulaire de dépôt -->
                <form id="depositForm" class="flex-grow-1 w-100 text-center" method="POST" action="{{ route('deposer') }}">
                    @csrf
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <label for="depositAccount" class="form-label">Numéro de compte</label>
                        <input type="text" class="form-control mx-auto" id="depositAccount" name="account_number" placeholder="Saisir un numéro de compte" style="width: 70%;" required>
                    </div>
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <label for="depositAmount" class="form-label">Montant à déposer</label>
                        <input type="number" class="form-control mx-auto" id="depositAmount" name="amount" placeholder="Montant à déposer" style="width: 70%;" required>
                        <div id="errorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                            *Le montant à déposer doit être supérieur ou égal à 1000.
                        </div>

                        <div id="balanceErrorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                            *Le montant à déposer ne peut pas dépasser votre solde actuel de {{ number_format($compte->solde, 2) }} FCFA.
                        </div>
                    </div>
                    <div class="text-center" style="color: blue; margin-bottom: 20px;">
                        Bonus = 1%
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Déposer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


      <!-- Modal pour le retrait -->
      <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Taille du modal -->
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="withdrawalModalLabel">Menu de retrait</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center"> <!-- Alignement centré -->
                    <!-- Image ajoutée ici -->
                    <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300" style="margin-bottom: 20px;">
    
                    <!-- Formulaire de retrait -->
                    <form id="withdrawalForm" class="flex-grow-1 w-100 text-center" method="POST" action="{{ route('retirer') }}">
                        @csrf
                        <div class="mb-3 d-flex flex-column align-items-center">
                            <label for="withdrawalAccount" class="form-label">Numéro de compte</label>
                            <input type="text" class="form-control mx-auto" id="withdrawalAccount" name="account_number" placeholder="Saisir un numéro de compte" style="width: 70%;" required>
                        </div>
                        <div class="mb-3 d-flex flex-column align-items-center">
                            <label for="withdrawalAmount" class="form-label">Montant à retirer</label>
                            <input type="number" class="form-control mx-auto" id="withdrawalAmount" name="amount" placeholder="Montant à retirer" style="width: 70%;" required>
                            <div id="withdrawalErrorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                                *Le montant à retirer doit être supérieur ou égal à 1000.
                            </div>
                            <div id="withdrawalBalanceErrorMessage" class="text-danger" style="display: none; margin-top: 10px;">
                                *Le montant à retirer ne peut pas dépasser votre solde actuel.
                            </div>
                        </div>
    
                        <div class="text-center" style="color: blue; margin-bottom: 20px;">
                            Bonus = 1%
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Retirer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    
  <!-- Modal Facture -->
<div class="modal fade" id="factureModal" tabindex="-1" aria-labelledby="factureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="factureModalLabel">
                    <i class="fas fa-file-invoice"></i> Reçu de Transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="company-header mb-4">
                    <h4 class="company-name">MiniBank</h4>
                    
                </div>
                
                <div class="receipt-details">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <h6 class="info-title">Informations Client</h6>
                                <div class="info-content">
                                    <p><span id="modalNomClient"></span> <span id="modalPrenomClient"></span></p>
                                    <p><i class="fas fa-phone"></i> <span id="modalTelephoneClient"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <h6 class="info-title">Détails Transaction</h6>
                                <div class="info-content">
                                    <p><i class="fas fa-hashtag"></i> ID: <span id="modalTransactionId"></span></p>
                                    <p><i class="far fa-calendar-alt"></i> Date: <span id="modalDate"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="transaction-details mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type de Transaction</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span id="modalType"></span></td>
                                        <td class="text-end"><strong><span id="modalMontant"></span></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="footer-note mt-4">
                        <p class="text-muted"><small>Ce reçu fait office de preuve de transaction. Veuillez le conserver pour vos archives.</small></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>





          <!-- Modal d'Annulation -->
        


 <!-- Modal pour afficher les informations du client -->
 <div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientModalLabel">Informations du Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="clientDetails">
                <p>Chargement des détails...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>






    <script>






document.getElementById('depositForm').addEventListener('submit', function(event) {
                const accountNumber = document.getElementById('depositAccount').value.trim();
                const amount = parseFloat(document.getElementById('depositAmount').value);
                const errorMessage = document.getElementById('errorMessage');
                const balanceErrorMessage = document.getElementById('balanceErrorMessage');
                const currentBalance = {{ $compte ? $compte->solde : 0 }}; // Récupérez le solde actuel

                // Réinitialiser les messages d'erreur
                errorMessage.style.display = 'none';
                balanceErrorMessage.style.display = 'none';

                // Vérifier si le montant est inférieur à 1000
                if (amount < 1000) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    errorMessage.style.display = 'block'; // Affiche le message d'erreur
                } 
                // Vérifier si le montant dépasse le solde actuel
                else if (amount > currentBalance) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    balanceErrorMessage.style.display = 'block'; // Affiche le message d'erreur
                }
            });

            // Masquer le message d'erreur lorsque l'utilisateur saisit un nouvel input
            document.getElementById('depositAccount').addEventListener('input', function() {
                document.getElementById('errorMessage').style.display = 'none';
                document.getElementById('balanceErrorMessage').style.display = 'none';
            });

            document.getElementById('depositAmount').addEventListener('input', function() {
                const amount = parseFloat(this.value);
                const errorMessage = document.getElementById('errorMessage');
                const balanceErrorMessage = document.getElementById('balanceErrorMessage');

                // Masquer le message si le montant est valide
                if (amount >= 1000) {
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.style.display = 'block'; // Montant invalide
                }

                // Vérifier si le montant dépasse le solde actuel
                const currentBalance = {{ $compte ? $compte->solde : 0 }};
                if (amount <= currentBalance) {
                    balanceErrorMessage.style.display = 'none';
                } else {
                    balanceErrorMessage.style.display = 'block'; // Montant invalide
                }
            });




</script>



<script>
// Vérification du numéro de compte lors de la saisie
document.getElementById('withdrawalAccount').addEventListener('input', function() {
    const accountNumber = this.value;
    const accountErrorMessage = document.getElementById('accountErrorMessage');

    // Réinitialiser le message d'erreur
    accountErrorMessage.style.display = 'none';

    if (accountNumber.length >= 5) { // Vérifiez si le numéro de compte est valide
        fetch(`/api/get-account-info/${accountNumber}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    accountErrorMessage.style.display = 'block'; // Affiche le message d'erreur
                    accountErrorMessage.textContent = data.message; // Affichez le message d'erreur
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des informations :', error);
            });
    }
});

// Gestion de la soumission du formulaire de retrait
document.getElementById('withdrawalForm').addEventListener('submit', function(event) {
    const amount = parseFloat(document.getElementById('withdrawalAmount').value);
    const withdrawalErrorMessage = document.getElementById('withdrawalErrorMessage');
    const withdrawalBalanceErrorMessage = document.getElementById('withdrawalBalanceErrorMessage');
    const currentBalance = {{ $compte ? $compte->solde : 0 }}; // Récupérez le solde actuel

    // Réinitialiser les messages d'erreur
    withdrawalErrorMessage.style.display = 'none';
    withdrawalBalanceErrorMessage.style.display = 'none';

    // Debugging: afficher les valeurs
    console.log('Montant à retirer:', amount);

    // Vérifier si le montant est inférieur à 1000
    if (amount < 1000) {
        event.preventDefault(); // Empêche la soumission du formulaire
        withdrawalErrorMessage.style.display = 'block'; // Affiche le message d'erreur
        withdrawalErrorMessage.textContent = "*Le montant à retirer doit être supérieur ou égal à 1000.";
    } 
    // Vérifier si le montant dépasse le solde du client
    else if (amount > currentBalance) {
        event.preventDefault(); // Empêche la soumission du formulaire
        withdrawalBalanceErrorMessage.style.display = 'block'; // Affiche le message d'erreur
        withdrawalBalanceErrorMessage.textContent = "*Le montant dépasse votre solde disponible.";
    }
});

// Masquer les messages d'erreur lors de la saisie dans le champ de montant
document.getElementById('withdrawalAmount').addEventListener('input', function() {
    const amount = parseFloat(this.value);
    const withdrawalErrorMessage = document.getElementById('withdrawalErrorMessage');

    // Vérifier si le montant est valide
    withdrawalErrorMessage.style.display = (amount >= 1000) ? 'none' : 'block';
});

</script>

<script>
        document.querySelectorAll('button[data-bs-target="#factureModal"]').forEach(button => {
    button.addEventListener('click', function() {
        // Récupérer les données des attributs data-*
        const transactionId = this.getAttribute('data-id');
        const nomClient = this.getAttribute('data-nom');
        const prenomClient = this.getAttribute('data-prenom');
        const telephoneClient = this.getAttribute('data-telephone');
        const date = this.getAttribute('data-date');
        const montant = this.getAttribute('data-montant');
        const type = this.getAttribute('data-type');

        // Afficher les données dans la console
        console.log('Transaction ID:', transactionId);
        console.log('Nom Client:', nomClient);
        console.log('Prénom Client:', prenomClient);
        console.log('Téléphone Client:', telephoneClient);
        console.log('Date:', date);
        console.log('Montant:', montant);
        console.log('Type:', type);

        // Remplir le modal avec les données
        document.getElementById('modalTransactionId').textContent = transactionId;
        document.getElementById('modalNomClient').textContent = nomClient;
        document.getElementById('modalPrenomClient').textContent = prenomClient;
        document.getElementById('modalTelephoneClient').textContent = telephoneClient;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalMontant').textContent = montant;
        document.getElementById('modalType').textContent = type;
    });
});
;




</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Les scripts JavaScript existants restent les mêmes -->
</body>
</html>
@endsection