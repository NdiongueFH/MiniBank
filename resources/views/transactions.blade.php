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
    <!-- @if(session('message'))
        <div class="alert alert-{{ session('message_type') == 'success' ? 'success' : 'danger' }}">
            {{ session('message') }}
        </div>
    @endif -->
    
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
                            <i class="bi bi-bar-chart"></i>
                            Paiments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size: 1.25rem; color: #505887;">
                            <i class="bi bi-credit-card"></i>
                            Crédit
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
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
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
                         Transactions Client
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <form class="d-flex me-3">
                            <input class="form-control me-2" type="search" placeholder="Recherche..." aria-label="Search">
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
        <div class="carte" style="width: 800px; height: 310px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-left: 50px; margin-top: 20px;">

            <!-- Conteneur principal avec Flexbox pour aligner les éléments -->
            <div style="display: flex; align-items: center; padding: 20px;">

                <!-- Informations du client à gauche -->
                <div class="p-3" style="margin-right: 250px;">
                    <p>Solde</p>
                    <h5>{{ $compte ? number_format($compte->solde, 0) : '0' }} FCFA</h5><br>
                    <p>Nom d'utilisateur</p>
                    <h5>{{ $client->prenom }} {{ $client->nom }}</h5>
                </div>

                <!-- QR Code à droite -->
                <div class="p-3">
                    <img src="{{ asset('storage/' . $qrCodePath) }}" alt="QR Code du Client" style="width: 200px; height: 150px;">
                </div>
            </div>

            <!-- Ligne de séparation -->
            <hr style="border: 1px solid #ced4da; margin: 0;">

            <!-- Numéro de téléphone en dessous -->
            <div class="p-3 d-flex justify-content-between align-items-center">
                <h5>{{ $client->telephone }}</h5>
                <img src="{{ asset('images/num.png') }}" alt="Icône Téléphone" style="width: 70px; height: 50px; margin-left: 10px;">
            </div>

            <!-- Message d'alerte -->
            @if(session('message'))
                <div style="margin-top: 10px; color: {{ session('message_type') == 'success' ? 'green' : 'red' }};">
                    {{ session('message') }}
                </div>

                <script>
                    setTimeout(function() {
                        var messageDiv = document.querySelector('div[style*="margin-top: 10px"]');
                        if (messageDiv) {
                            messageDiv.style.transition = 'opacity 0.5s ease';
                            messageDiv.style.opacity = '0';
                            setTimeout(function() {
                                messageDiv.style.display = 'none';
                            }, 500);
                        }
                    }, 5000);
                </script>
            @endif
        </div>



      <!-- Bloc de transfert -->
<div class="transfer-button" style="width: 300px; height: 120px; background-color: #2D60FF; color: white; border-radius: 40px; display: flex; align-items: center; justify-content: center; margin-left: 1000px; margin-top: -200px;" onclick="openModal()">
    <img src="{{ asset('images/transferer.png') }}" alt="Transférer" style="width: 40px; height: 40px; margin-right: 10px;">
    <h5 style="margin: 0;">Transférer</h5>
</div>

<!-- Modal de transfert-->
<div id="transferModal" class="modal" style="display:none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-content" style="padding: 40px; border-radius: 20px; background-color: white; width: 600px; margin: auto; position: relative; top: 50px;">
        <span class="close" onclick="closeModal()" style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 20px;">&times;</span>
        
        <img src="{{ asset('images/Minibank.png') }}" alt="Logo" style="display: block; width: 300px; margin: 0 auto 20px auto;">
        
        <h2 style="text-align: center;">Transférer de l'argent</h2>
        <form id="transferForm" action="{{ route('transferer') }}" method="POST">
        @csrf <!-- Ajoutez le jeton CSRF pour protéger contre les attaques CSRF -->
            <label for="numero_compte">Numéro de compte:</label>
            <input type="text" id="numero_compte" name="numero_compte" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;">
            
            <label for="montant_envoye">Montant envoyé:</label>
            <input type="number" id="montant_envoye" name="montant_envoye" required style="width: 100%; padding: 10px; margin: 20px 0 5px 0; border: 1px solid #ccc; border-radius: 5px;">
            <div id="errorMessage" class="text-danger" style="display: none; margin-top: 5px;">
                *Le montant envoyé doit être supérieur à 500.
            </div>
            <div id="balanceErrorMessage" class="text-danger" style="display: none; margin-top: 5px;">
                *Votre solde est insuffisant pour effectuer ce transfert.
            </div>

            <label for="montant_recu">Montant reçu:</label>
            <input type="number" id="montant_recu" name="montant_recu" required style="width: 100%; padding: 10px; margin: 20px 0 5px 0; border: 1px solid #ccc; border-radius: 5px;">
            <div id="receivedAmountError" class="text-danger" style="display: none; margin-top: 5px;">
                *Le montant reçu doit être supérieur à 500.
            </div>
            <div id="receivedBalanceErrorMessage" class="text-danger" style="display: none; margin-top: 5px;">
                *Le montant reçu doit être inférieur au solde disponible.
            </div>

            <button type="submit" style="width: 100%; background-color: #2D60FF; color: white; border: none; border-radius: 5px; padding: 10px;">Confirmer</button>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('transferModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('transferModal').style.display = 'none';
}

// Fermer le modal en cliquant en dehors de celui-ci
window.onclick = function(event) {
    var modal = document.getElementById('transferModal');
    if (event.target == modal) {
        closeModal();
    }
}

document.getElementById('transferForm').addEventListener('submit', function(event) {
    const amountSent = parseFloat(document.getElementById('montant_envoye').value);
    const amountReceived = parseFloat(document.getElementById('montant_recu').value);
    const errorMessage = document.getElementById('errorMessage');
    const balanceErrorMessage = document.getElementById('balanceErrorMessage');
    const receivedAmountError = document.getElementById('receivedAmountError');
    const receivedBalanceErrorMessage = document.getElementById('receivedBalanceErrorMessage');
    const currentBalance = {{ $compte ? $compte->solde : 0 }}; // Récupérez le solde actuel

    // Réinitialiser les messages d'erreur
    errorMessage.style.display = 'none';
    balanceErrorMessage.style.display = 'none';
    receivedAmountError.style.display = 'none';
    receivedBalanceErrorMessage.style.display = 'none';

    // Vérifier si le montant envoyé est inférieur ou égal à 500
    if (amountSent <= 500) {
        event.preventDefault(); // Empêche la soumission du formulaire
        errorMessage.style.display = 'block'; // Affiche le message d'erreur
    } 
    // Vérifier si le montant envoyé dépasse le solde actuel
    else if (amountSent > currentBalance) {
        event.preventDefault(); // Empêche la soumission du formulaire
        balanceErrorMessage.style.display = 'block'; // Affiche le message d'erreur
    }

    // Vérifier si le montant reçu est inférieur ou égal à 500
    if (amountReceived <= 500) {
        event.preventDefault(); // Empêche la soumission du formulaire
        receivedAmountError.style.display = 'block'; // Affiche le message d'erreur
    }

    // Vérifier si le montant reçu dépasse le solde actuel
    if (amountReceived >= currentBalance) {
        event.preventDefault(); // Empêche la soumission du formulaire
        receivedBalanceErrorMessage.style.display = 'block'; // Affiche le message d'erreur
    }

    // Si tout est valide, mettre à jour les soldes ici
    // Tu peux ajouter une logique pour soustraire le montant envoyé et ajouter le montant reçu.
});

// Écouteurs d'événements pour mettre à jour automatiquement les champs
document.getElementById('montant_envoye').addEventListener('input', function() {
    const amountSent = parseFloat(this.value);
    const amountReceivedField = document.getElementById('montant_recu');
    
    // Calculer le montant reçu
    if (!isNaN(amountSent)) {
            const amountReceived = Math.round(amountSent * 0.98);
        amountReceivedField.value = amountReceived.toFixed(2); // Mettre à jour le champ 'Montant reçu'
    } else {
        amountReceivedField.value = '';
    }

    // Vérifier et masquer les messages d'erreur en fonction du montant
    const errorMessage = document.getElementById('errorMessage');
    const balanceErrorMessage = document.getElementById('balanceErrorMessage');

    if (amountSent > 500 || isNaN(amountSent)) {
        errorMessage.style.display = 'none';
    } else {
        errorMessage.style.display = 'block'; // Montant invalide
    }

    const currentBalance = {{ $compte ? $compte->solde : 0 }};
    if (amountSent <= currentBalance || isNaN(amountSent)) {
        balanceErrorMessage.style.display = 'none';
    } else {
        balanceErrorMessage.style.display = 'block'; // Montant invalide
    }
});

// Écouteur d'événements pour mettre à jour le montant envoyé
document.getElementById('montant_recu').addEventListener('input', function() {
    const amountReceived = parseFloat(this.value);
    const amountSentField = document.getElementById('montant_envoye');

    // Calculer le montant envoyé
    if (!isNaN(amountReceived)) {
        const amountSent = Math.round(amountReceived / 0.98);
        amountSentField.value = amountSent.toFixed(2); // Mettre à jour le champ 'Montant envoyé'
    } else {
        amountSentField.value = '';
    }

    // Vérifier et masquer les messages d'erreur en fonction du montant
    const receivedAmountError = document.getElementById('receivedAmountError');
    const receivedBalanceErrorMessage = document.getElementById('receivedBalanceErrorMessage');

    if (amountReceived > 500 || isNaN(amountReceived)) {
        receivedAmountError.style.display = 'none';
    } else {
        receivedAmountError.style.display = 'block'; // Montant invalide
    }

    const currentBalance = {{ $compte ? $compte->solde : 0 }};
    if (amountReceived < currentBalance || isNaN(amountReceived)) {
        receivedBalanceErrorMessage.style.display = 'none';
    } else {
        receivedBalanceErrorMessage.style.display = 'block'; // Montant invalide
    }
});
</script>


        </div>
    </div>
    <!-- Titre des transactions -->
    <h3 style="margin-left: 300px; margin-top: -440px;">Liste des Transactions</h3>

    <div class="transactions" style="width: 1500px; height: 350px; background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); margin-left: 300px; margin-top: 5px; padding: 20px; overflow-y: auto;">
    <table class="table table-bordered" style="min-width: 100%;">
    <thead>
        <tr>
            <th>Nom Émetteur</th>
            <th>Prénom Émetteur</th>
            <th>Nom Receveur</th>
            <th>Prénom Receveur</th>
            <th>ID Transaction</th>
            <th>Téléphone Receveur</th>
            <th>Date</th>
            <th>Montant</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->emetteur->nom }}</td>
                <td>{{ $transaction->emetteur->prenom }}</td>
                <td>{{ $transaction->receveur->nom }}</td>
                <td>{{ $transaction->receveur->prenom }}</td>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->receveur->telephone }}</td>
                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
               <td style="color: {{ $transaction->type == 'depot' ? 'green' : ($transaction->type == 'transfert' ? 'orange' : 'red') }};">
                    {{ $transaction->type == 'depot' ? '+' : ($transaction->type == 'transfert' ? '' : '-') }}{{ number_format($transaction->mountant, 2) }} FCFA
                </td>                          
                <td>
                    <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#factureModalClient"
                            data-id="{{ $transaction->id }}"
                            data-nom-emetteur="{{ $transaction->emetteur->nom }}"
                            data-prenom-emetteur="{{ $transaction->emetteur->prenom }}"
                            data-nom-receveur="{{ $transaction->receveur->nom }}"
                            data-prenom-receveur="{{ $transaction->receveur->prenom }}"
                            data-telephone-receveur="{{ $transaction->receveur->telephone }}"
                            data-date="{{ $transaction->created_at->format('Y-m-d') }}"
                            data-montant="{{ number_format($transaction->mountant, 2) }} FCFA"
                            data-type="{{ $transaction->type }}">
                        Voir Facture
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</div>
       <!-- Modal Facture Client -->
<div class="modal fade" id="factureModalClient" tabindex="-1" aria-labelledby="factureModalClientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="factureModalClientLabel">Détails de la Facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <strong>ID Transaction :</strong> <span id="modalTransactionIdClient"></span><br>
                <strong>Nom Émetteur :</strong> <span id="modalNomEmetteur"></span><br>
                <strong>Prénom Émetteur :</strong> <span id="modalPrenomEmetteur"></span><br>
                <strong>Nom Receveur :</strong> <span id="modalNomReceveur"></span><br>
                <strong>Prénom Receveur :</strong> <span id="modalPrenomReceveur"></span><br>
                <strong>Téléphone Receveur :</strong> <span id="modalTelephoneReceveur"></span><br>
                <strong>Date :</strong> <span id="modalDateClient"></span><br>
                <strong>Montant :</strong> <span id="modalMontantClient"></span><br>
                <strong>Type :</strong> <span id="modalTypeClient"></span><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Événement au clic sur le bouton "Voir Facture" pour le client
    document.querySelectorAll('button[data-bs-target="#factureModalClient"]').forEach(button => {
        button.addEventListener('click', function() {
            // Récupérer les données des attributs data-*
            const transactionId = this.getAttribute('data-id');
            const nomEmetteur = this.getAttribute('data-nom-emetteur');
            const prenomEmetteur = this.getAttribute('data-prenom-emetteur');
            const nomReceveur = this.getAttribute('data-nom-receveur');
            const prenomReceveur = this.getAttribute('data-prenom-receveur');
            const telephoneReceveur = this.getAttribute('data-telephone-receveur');
            const date = this.getAttribute('data-date');
            const montant = this.getAttribute('data-montant');
            const type = this.getAttribute('data-type');

            // Remplir le modal avec les données
            document.getElementById('modalTransactionIdClient').textContent = transactionId;
            document.getElementById('modalNomEmetteur').textContent = nomEmetteur;
            document.getElementById('modalPrenomEmetteur').textContent = prenomEmetteur;
            document.getElementById('modalNomReceveur').textContent = nomReceveur;
            document.getElementById('modalPrenomReceveur').textContent = prenomReceveur;
            document.getElementById('modalTelephoneReceveur').textContent = telephoneReceveur;
            document.getElementById('modalDateClient').textContent = date;
            document.getElementById('modalMontantClient').textContent = montant;
            document.getElementById('modalTypeClient').textContent = type;
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


