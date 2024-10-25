@extends('layouts.sideAndNav')

@section('containerAgent')

<div class="container mt-5">
    <h1 class="mb-3">Transactions de l'Agent</h1>

    <div class="d-flex flex-wrap gap-2 mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#distributeurModal">
            Nouvelle Transaction
        </button>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#retraitModal">
            Effectuer un Retrait
        </button>
        <a href="{{ route('transactions.canceled') }}" class="btn btn-danger">Transactions Annulées</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date Transaction</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Nom du Distributeur</th>
                    <th>Prénom du Distributeur</th>
                    <th>Adresse du Distributeur</th>
                    <th>Numéro de Compte</th>
                    <th>Carte d'Identité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->mountant }}</td>
                        <td>{{ $transaction->statut }}</td>
                        @php $distributeur = $transaction->distributeur; @endphp
                        <td>{{ $distributeur->nom }}</td>
                        <td>{{ $distributeur->prenom }}</td>
                        <td>{{ $distributeur->adresse }}</td>
                        <td>{{ $distributeur->num_compte }}</td>
                        <td>{{ $distributeur->carte_identite }}</td>
                        <td>
                            @if(!$transaction->annule)
                                <form action="{{ route('transaction.cancel', $transaction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-danger btn-sm" type="submit">Annuler</button>
                                </form>
                            @else
                                <span>Annulée</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $transactions->links('pagination::bootstrap-4') }}
    </div>
</div>



<!-- Modale pour le numéro du distributeur -->
<div class="modal fade" id="distributeurModal" tabindex="-1" aria-labelledby="distributeurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="distributeurModalLabel">Saisir le numéro du distributeur ou client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="distributeurForm">
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" required>
                    </div>
                    <button type="button" id="validateBtn" class="btn btn-primary w-100">Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modale pour effectuer un retrait -->
<div class="modal fade" id="retraitModal" tabindex="-1" aria-labelledby="retraitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="retraitModalLabel">Effectuer un Retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="retraitForm">
                    <div class="mb-3">
                        <label for="clientTelephone" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="clientTelephone" name="clientTelephone" required>
                    </div>
                    <div id="clientInfo" class="mb-3" style="display: none;">
                        <h6>Informations du Client/Distributeur :</h6>
                        <p id="clientName"></p>
                        <p id="clientBalance"></p>
                    </div>
                    <div class="mb-3">
                        <label for="montantRetrait" class="form-label">Montant à Retirer</label>
                        <input type="number" class="form-control" id="montantRetrait" name="montantRetrait" required min="1">
                    </div>
                    <button type="button" id="confirmRetraitBtn" class="btn btn-success w-100">Confirmer le Retrait</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modale d'erreur -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Erreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Vérifier le numéro de téléphone pour la nouvelle transaction
        $('#validateBtn').click(function() {
            var telephone = $('#telephone').val();

            $.ajax({
                url: '{{ route("transaction.checkDistributor") }}',
                method: 'GET',
                data: { telephone: telephone },
                success: function(response) {
                    if (response.status === 'error') {
                        $('#errorMessage').text(response.message);
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                    } else {
                        window.location.href = response.url;
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        $('#errorMessage').text('Numéro distributeur ou client inexistant');
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                    }
                }
            });
        });

        // Vérifier le numéro de téléphone pour le retrait
        $('#clientTelephone').on('input', function() {
            var telephone = $(this).val();

            $.ajax({
                url: '{{ route("transaction.checkDistributor") }}',
                method: 'GET',
                data: { telephone: telephone },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#clientName').text(`Nom: ${response.data.nom}, Prénom: ${response.data.prenom}`);
                        $('#clientBalance').text(`Solde: ${response.data.solde} €`);
                        $('#clientInfo').show();
                    }
                },
                error: function(xhr) {
                    $('#clientInfo').hide();
                }
            });
        });

        // Confirmer le retrait
        $('#confirmRetraitBtn').click(function() {
            var telephone = $('#clientTelephone').val();
            var montant = $('#montantRetrait').val();

            $.ajax({
                url: '{{ route("transaction.retrait") }}',
                method: 'POST',
                data: {
                    telephone: telephone,
                    montant: montant,
                    _token: '{{ csrf_token() }}' // Inclure le token CSRF
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Retrait effectué avec succès!');
                        location.reload(); // Recharger la page pour mettre à jour la liste des transactions
                    } else {
                        $('#errorMessage').text(response.message);
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                    }
                },
                error: function(xhr) {
                    $('#errorMessage').text('Erreur lors de l\'effectuer le retrait');
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                }
            });
        });
    });
</script>
@endsection
