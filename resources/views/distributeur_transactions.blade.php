<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MiniBank - Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <div class="d-flex" style="height: 100vh;">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="position: fixed; height: 100vh; width: 240px;">
            <div class="position-sticky d-flex flex-column" style="height: 100vh;">
                <!-- Logo centré -->
                <div class="p-3 text-center">
                    <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300">
                </div>
                
                <!-- Navigation Links -->
                <ul class="nav flex-column flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-house-door"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-card-list"></i>
                            Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-person-lines-fill"></i>
                            Contactez l'agent
                        </a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-gear"></i>
                            Paramètres
                        </a>
                    </li>
                </ul>

               <!-- Déconnexion button (aligned at the bottom) -->
                <div class="mt-auto mb-3 px-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary w-100" style="background-color: #505887; color: white;">
                            <i class="bi bi-box-arrow-left"></i> Déconnexion
                        </button>
                    </form>
                </div>

            </div>
        </nav>

        <!-- Main content -->
        <div class="main-content flex-grow-1" style="margin-left: 240px; width: calc(100% - 240px);">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand text-white" href="#">
                         Transactions Distributeur
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <form class="d-flex me-3" method="GET" action="{{ route('client.search') }}">
                        <input class="form-control me-2" type="search" name="account_number" placeholder="Numéro de compte" aria-label="Search" required>
                        <button class="btn btn-outline-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>

                        <ul class="navbar-nav">
                            <!-- Icon de notification -->
                            <li class="nav-item position-relative me-3">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-bell" style="font-size: 1.5rem; color: white;"></i>
                                 </a>
                            </li>
                            <!-- Profil utilisateur -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <img src="{{ asset('.jpg') }}" class="rounded-circle" alt="User Profile" width="40" height="40">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <h2 class="titre" style="margin-left: 50px; margin-top: 20px;">Ma carte</h2>

           <!-- Bloc de la taille d'une carte bancaire -->
            <div class="carte" style="width: 800px; height: 280px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-left: 50px; margin-top: 20px;">
                <!-- Contenu du bloc (comme un numéro de carte, nom, etc.) -->
                <div class="p-3">
                    <p>Solde</p>
                    <h5>{{ $compte ? number_format($compte->solde, 2) : '0.00' }} FCFA</h5><br>
                    <p>Nom d'utilisateur</p>
                    <h5>{{ $user->prenom }} {{ $user->nom }}</h5>
                </div>

                <!-- Trait fin -->
                <hr style="border: 1px solid #ced4da; margin: 0;">

                <!-- Numéro de téléphone -->
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <h5>{{ $user->telephone }}</h5>
                    <img src="{{ asset('images/num.png') }}" alt="Icône Téléphone" style="width: 70px; height: 50px; margin-left: 10px;">
                </div>
                 
            </div>

             <!-- Conteneur pour les blocs -->
             <div style="margin-top: -280px; text-align: center; margin-left: 550px;">

                <!-- Bloc de Depot -->
                <div class="transfer-button" style="width: 300px; height: 120px; background-color: #2D60FF; color: white; border-radius: 60px; display: flex; align-items: center; justify-content: center; margin: 10px auto;" data-bs-toggle="modal" data-bs-target="#depositModal">
                    <img src="{{ asset('images/transferer.png') }}" alt="Transférer" style="width: 40px; height: 40px; margin-right: 10px;">
                    <h5 style="margin: 0;">Depot</h5>
                </div>

                <!-- Bloc de Retrait -->
                <div class="transfer-button" style="width: 300px; height: 120px; background-color: #41D4A8; color: white; border-radius: 60px; display: flex; align-items: center; justify-content: center; margin: 10px auto;" data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                    <img src="{{ asset('images/transferer.png') }}" alt="Transférer" style="width: 40px; height: 40px; margin-right: 10px;">
                    <h5 style="margin: 0;">Retrait</h5>
                </div>

        </div>


            

           
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

        <script>
            document.getElementById('withdrawalAccount').addEventListener('input', function() {
                const accountNumber = this.value;

                if (accountNumber.length >= 5) { // Vérifiez si le numéro de compte est valide
                    fetch(`/api/get-account-info/${accountNumber}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert(data.message); // Affichez le message d'erreur
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de la récupération des informations :', error);
                        });
                }
            });

            document.getElementById('withdrawalForm').addEventListener('submit', function(event) {
                const amount = parseFloat(document.getElementById('withdrawalAmount').value);
                const withdrawalErrorMessage = document.getElementById('withdrawalErrorMessage');
                const withdrawalBalanceErrorMessage = document.getElementById('withdrawalBalanceErrorMessage');

                // Réinitialiser les messages d'erreur
                withdrawalErrorMessage.style.display = 'none';
                withdrawalBalanceErrorMessage.style.display = 'none';

                // Debugging: afficher les valeurs
                console.log('Montant à retirer:', amount);

                // Vérifier si le montant est inférieur à 1000
                if (amount < 1000) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    withdrawalErrorMessage.style.display = 'block'; // Affiche le message d'erreur
                } 
                // Vérifier si le montant dépasse le solde du client (si nécessaire, vous pouvez gérer cela ici)
                // Vous aurez besoin d'ajouter une logique pour obtenir le solde ici si vous le souhaitez
            });

            // Masquer les messages d'erreur lors de la saisie
            document.getElementById('withdrawalAmount').addEventListener('input', function() {
                const amount = parseFloat(this.value);
                const withdrawalErrorMessage = document.getElementById('withdrawalErrorMessage');
                const withdrawalBalanceErrorMessage = document.getElementById('withdrawalBalanceErrorMessage');

                // Vérifier si le montant est valide
                withdrawalErrorMessage.style.display = (amount >= 1000) ? 'none' : 'block';
                // Vous pouvez également gérer la vérification du solde ici si nécessaire
            });
        </script>
<!-- Modal d'Annulation -->
<div class="modal fade" id="annulationModal" tabindex="-1" aria-labelledby="annulationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- Taille du modal -->
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="annulationModalLabel">Annulation de la Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center"> <!-- Alignement centré -->
                <!-- Image ajoutée ici -->
                <img src="{{ asset('images/Minibank.png') }}" alt="Logo" class="img-fluid mx-auto d-block" width="300" style="margin-bottom: 20px;">

                <form id="annulationForm" method="POST" action="{{ route('annuler.transaction') }}" class="w-100 text-center">
                    @csrf
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <label for="numeroCompte" class="form-label">Numéro de Compte</label>
                        <input type="text" class="form-control mx-auto" name="numero_compte" id="numeroCompte" style="width: 70%;" required>
                    </div>
                    <div class="mb-3 d-flex flex-column align-items-center">
                        <label for="transactionId" class="form-label">ID de Transaction</label>
                        <input type="text" class="form-control mx-auto" name="transaction_id" id="transactionId" style="width: 70%;" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">Annuler la Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


        <script>
            // Gérer l'affichage des données dans le modal d'annulation
            $('#annulationModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Bouton qui a déclenché le modal
                var transactionId = button.data('transaction-id'); // Récupérer l'ID de la transaction

                // Mettre à jour le champ de l'ID de la transaction
                var modal = $(this);
                modal.find('#transactionId').val(transactionId);
            });
        </script>
</div>
</div>

    <!-- Titre des transactions -->
    <h3 style="margin-left: 300px; margin-top: -440px;">Liste des Transactions</h3>

    <!-- Bloc des transactions -->
    
    <div class="transactions" style="width: 1500px; height: 350px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-left: 300px; margin-top: 5px; padding: 20px; overflow-y: auto;">
    <table class="table table-bordered" style="min-width: 100%;">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>ID Transaction</th>
                <th>Téléphone</th>
                <th>Date</th>
                <th>Montant</th>
                <th>Facture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->receveur->nom }}</td>
                    <td>{{ $transaction->receveur->prenom }}</td>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->receveur->telephone }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    <!-- <td style="color: {{ $transaction->type == 'depot' ? 'green' : ($transaction->type == 'retrait' ? 'red' : 'black') }};">
                        {{ $transaction->type == 'depot' ? '+' : ($transaction->type == 'retrait' ? '-' : '') }}{{ number_format($transaction->mountant, 2) }} FCFA
                    </td> -->
                    <td style="color: {{ $transaction->type == 'depot' ? 'red' : 'green' }};">
                        {{ $transaction->type == 'depot' ? '-' : '+' }}{{ number_format(abs($transaction->mountant), 2) }} FCFA
                    </td>         

                   <td>
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#factureModal" 
                                data-id="{{ $transaction->id }}"
                                data-nom="{{ $transaction->receveur->nom }}"
                                data-prenom="{{ $transaction->receveur->prenom }}"
                                data-telephone="{{ $transaction->receveur->telephone }}"
                                data-date="{{ $transaction->created_at->format('Y-m-d') }}"
                                data-montant="{{ number_format($transaction->mountant, 2) }} FCFA"
                                data-type="{{ $transaction->type }}">
                            Voir Facture
                        </button>  
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#annulationModal" 
                            data-transaction-id="{{ $transaction->id }}">
                            Annulation
                        </button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

   <!-- Modal Facture -->
        <div class="modal fade" id="factureModal" tabindex="-1" aria-labelledby="factureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="factureModalLabel">Détails de la Facture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <strong>ID Transaction :</strong> <span id="modalTransactionId"></span><br>
                        <strong>Nom :</strong> <span id="modalNomClient"></span><br>
                        <strong>Prénom :</strong> <span id="modalPrenomClient"></span><br>
                        <strong>Téléphone :</strong> <span id="modalTelephoneClient"></span><br>
                        <strong>Date :</strong> <span id="modalDate"></span><br>
                        <strong>Montant :</strong> <span id="modalMontant"></span><br>
                        <strong>Type :</strong> <span id="modalType"></span><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

    <script>
        // Événement au clic sur le bouton "Voir Facture"
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
    </script>

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
                document.querySelector('form.d-flex').addEventListener('submit', function(event) {
                    event.preventDefault(); // Empêche la soumission du formulaire

                    const accountNumber = event.target.account_number.value;

                    fetch(`/client/search?account_number=${accountNumber}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Client non trouvé');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Afficher les détails du client dans le modal
                            const clientDetails = document.getElementById('clientDetails');
                            clientDetails.innerHTML = `
                                <strong>Nom :</strong> ${data.nom}<br>
                                <strong>Prénom :</strong> ${data.prenom}<br>
                                <strong>Téléphone :</strong> ${data.telephone}<br>
                                <strong>Statut :</strong> ${data.blocked ? 'Bloqué' : 'Actif'}<br>

                            `;
                            // Ouvrir le modal
                            const clientModal = new bootstrap.Modal(document.getElementById('clientModal'));
                            clientModal.show();
                        })
                        .catch(error => {
                            alert(error.message);
                        });
                });
        </script>



        <!-- Pagination -->
        <div class="pagination" style="margin-top: 20px; display: flex; justify-content: flex-end; margin-right: 50px;">
        <a href="#" style="color: #2D60FF; text-decoration: none; margin-right: 10px;">
            <i class="bi bi-arrow-left"></i> Précédent
        </a>
        <div style="margin: 0 10px;">
            <a href="#" style="cursor: pointer; color: #2D60FF; text-decoration: none;">1</a>
            <a href="#" style="cursor: pointer; margin: 0 5px; color: #2D60FF; text-decoration: none;">2</a>
            <a href="#" style="cursor: pointer; margin: 0 5px; color: #2D60FF; text-decoration: none;">3</a>
            <a href="#" style="cursor: pointer; margin: 0 5px; color: #2D60FF; text-decoration: none;">4</a>
        </div>
        <a href="#" style="color: #2D60FF; text-decoration: none; margin-left: 10px;">
            Suivant <i class="bi bi-arrow-right"></i>
        </a>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>