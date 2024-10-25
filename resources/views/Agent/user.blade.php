@extends('layouts.sideAndNav')

@section('containerAgent')
    <div class="container">
        <h1>Liste des Utilisateurs</h1>

        <!-- Message de succès -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Barre de recherche -->
        <div class="input-group mb-3">
            <input type="text" id="searchAccountNumber" class="form-control" placeholder="Rechercher par numéro de compte">
            <button id="searchButton" class="btn btn-primary">Rechercher</button>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="userTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#clientsActifs">Clients Actifs</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#distributeursActifs">Distributeurs Actifs</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#clientsBloques">Clients Bloqués</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#distributeursBloques">Distributeurs Bloqués</a></li>
        </ul>

        <div class="tab-content">
            <!-- Clients Actifs -->
            <div class="tab-pane fade show active" id="clientsActifs">
                @include('partials.userTable', ['users' => $clientsActifs, 'action' => 'block'])
                {{ $clientsActifs->links('pagination::bootstrap-4') }}
            </div>
            <!-- Distributeurs Actifs -->
            <div class="tab-pane fade" id="distributeursActifs">
                @include('partials.userTable', ['users' => $distributeursActifs, 'action' => 'block'])
                {{ $distributeursActifs->links('pagination::bootstrap-4') }}
            </div>
            <!-- Clients Bloqués -->
            <div class="tab-pane fade" id="clientsBloques">
                @include('partials.userTable', ['users' => $clientsBloques, 'action' => 'unblock'])
                {{ $clientsBloques->links('pagination::bootstrap-4') }}
            </div>
            <!-- Distributeurs Bloqués -->
            <div class="tab-pane fade" id="distributeursBloques">
                @include('partials.userTable', ['users' => $distributeursBloques, 'action' => 'unblock'])
                {{ $distributeursBloques->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <!-- Modal pour afficher les informations de l'utilisateur -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Informations de l'Utilisateur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nom :</strong> <span id="userName"></span></p>
                        <p><strong>Prénom :</strong> <span id="userFirstName"></span></p>
                        <p><strong>Téléphone :</strong> <span id="userPhone"></span></p>
                        <p><strong>Email :</strong> <span id="userEmail"></span></p>
                        <p><strong>Numéro de Compte :</strong> <span id="userAccountNumber"></span></p>
                        <p><strong>Carte d'Identité :</strong> <span id="userIdentityCard"></span></p>
                        <form id="blockForm" method="POST">
                            @csrf
                            <button type="submit" id="blockUnblockButton" class="btn"></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ajout des bibliothèques JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script pour la recherche et affichage du modal -->
    <script>
        $(document).ready(function () {
            $('#searchButton').click(function () {
                var accountNumber = $('#searchAccountNumber').val();

                $.ajax({
                    url: '{{ route("agent.searchUser") }}',
                    method: 'GET',
                    data: { num_compte: accountNumber },
                    success: function (response) {
                        if (response.status === 'found') {
                            $('#userName').text(response.user.nom);
                            $('#userFirstName').text(response.user.prenom);
                            $('#userPhone').text(response.user.telephone);
                            $('#userEmail').text(response.user.email);
                            $('#userAccountNumber').text(response.user.num_compte);
                            $('#userIdentityCard').text(response.user.carte_identite);

                            // Configuration du bouton de blocage/déblocage
                            if (response.user.blocked) {
                                $('#blockUnblockButton').text('Débloquer').removeClass('btn-danger').addClass('btn-success');
                                $('#blockForm').attr('action', '{{ url("agent/user") }}/' + response.user.id + '/unblock');
                            } else {
                                $('#blockUnblockButton').text('Bloquer').removeClass('btn-success').addClass('btn-danger');
                                $('#blockForm').attr('action', '{{ url("agent/user") }}/' + response.user.id + '/block');
                            }

                            // Affiche le modal
                            var userModal = new bootstrap.Modal(document.getElementById('userModal'));
                            userModal.show();
                        } else {
                            alert("Utilisateur introuvable avec ce numéro de compte.");
                        }
                    },
                    error: function () {
                        alert("Erreur lors de la recherche de l'utilisateur.");
                    }
                });
            });
        });
    </script>
@endsection
