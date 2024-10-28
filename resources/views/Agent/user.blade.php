@extends('layouts.sidebar-navbarA')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<div class="container-fluid px-4">
    <!-- En-tête avec titre et bouton d'ajout -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Utilisateurs</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus"></i> Nouvel Utilisateur
        </button>
    </div>

    <!-- Messages d'alerte -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Barre de recherche améliorée -->
    
            <div class="input-group">
                
                <input type="text" id="searchAccountNumber" class="form-control" placeholder="Rechercher par numéro de compte">
                <button id="searchButton" class="btn btn-primary">Rechercher</button>
            </div>
        

    <!-- Tabs dans une carte -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" id="userTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#clientsActifs">
                        <i class="fas fa-users"></i> Clients Actifs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#distributeursActifs">
                        <i class="fas fa-store"></i> Distributeurs Actifs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#clientsBloques">
                        <i class="fas fa-user-lock"></i> Clients Bloqués
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#distributeursBloques">
                        <i class="fas fa-store-slash"></i> Distributeurs Bloqués
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="clientsActifs">
                    @include('partials.userTable', ['users' => $clientsActifs, 'action' => 'block'])
                    {{ $clientsActifs->links('pagination::bootstrap-4') }}
                </div>
                <div class="tab-pane fade" id="distributeursActifs">
                    @include('partials.userTable', ['users' => $distributeursActifs, 'action' => 'block'])
                    {{ $distributeursActifs->links('pagination::bootstrap-4') }}
                </div>
                <div class="tab-pane fade" id="clientsBloques">
                    @include('partials.userTable', ['users' => $clientsBloques, 'action' => 'unblock'])
                    {{ $clientsBloques->links('pagination::bootstrap-4') }}
                </div>
                <div class="tab-pane fade" id="distributeursBloques">
                    @include('partials.userTable', ['users' => $distributeursBloques, 'action' => 'unblock'])
                    {{ $distributeursBloques->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

<!-- Modal pour ajouter un utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i> Ajouter un Nouvel Utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST" id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telephone" class="form-label">Numéro de téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="carte_identite" class="form-label">Numéro de carte d'identité</label>
                                <input type="text" class="form-control" id="carte_identite" name="carte_identite" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-label">Rôle</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Sélectionnez un rôle</option>
                                    <option value="agent">Agent</option>
                                    <option value="distributeur">Distributeur</option>
                                    <option value="client">Client</option>
                                </select>
                            </div>
                        </div>

                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer Utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Modal pour afficher les informations de l'utilisateur -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informations de l'Utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="user-info">
                        <p><i class="fas fa-user"></i> <strong>Nom :</strong> <span id="userName"></span></p>
                        <p><i class="fas fa-user"></i> <strong>Prénom :</strong> <span id="userFirstName"></span></p>
                        <p><i class="fas fa-phone"></i> <strong>Téléphone :</strong> <span id="userPhone"></span></p>
                        <p><i class="fas fa-envelope"></i> <strong>Email :</strong> <span id="userEmail"></span></p>
                        <p><i class="fas fa-hashtag"></i> <strong>Numéro de Compte :</strong> <span id="userAccountNumber"></span></p>
                        <p><i class="fas fa-id-card"></i> <strong>Carte d'Identité :</strong> <span id="userIdentityCard"></span></p>
                    </div>
                    <form id="blockForm" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" id="blockUnblockButton" class="btn w-100"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Ajout des bibliothèques JavaScript -->

<script>
$(document).ready(function () {
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

                    if (response.user.blocked) {
                        $('#blockUnblockButton')
                            .text('Débloquer')
                            .removeClass('btn-danger')
                            .addClass('btn-success');
                        $('#blockForm').attr('action', '{{ url("agent/user") }}/' + response.user.id + '/unblock');
                    } else {
                        $('#blockUnblockButton')
                            .text('Bloquer')
                            .removeClass('btn-success')
                            .addClass('btn-danger');
                        $('#blockForm').attr('action', '{{ url("agent/user") }}/' + response.user.id + '/block');
                    }

                    var userModal = new bootstrap.Modal(document.getElementById('userModal'));
                    userModal.show();
                } else {
                    // Swal.fire('Erreur', 'Utilisateur introuvable avec ce numéro de compte', 'error');
                    alert('ussss')
                }
            },
            error: function () {
                // Swal.fire('Erreur', 'Une erreur est survenue lors de la recherche', 'error');
            }
        });
    });

</script>
<script>

    // Validation du formulaire d'ajout d'utilisateur
    $('#addUserModal form').submit(function(e) {
        var requiredFields = $(this).find('[required]');
        var valid = true;

        requiredFields.each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            Swal.fire('Attention', 'Veuillez remplir tous les champs obligatoires', 'warning');
        }
    });
});
</script>


@endsection