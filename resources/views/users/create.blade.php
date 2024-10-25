<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Utilisateur</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Créer un Utilisateur</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div>
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div>
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div>
                <label for="telephone">Numéro de téléphone</label>
                <input type="text" id="telephone" name="telephone" required>
            </div>

            <div>
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" required>
            </div>

            <div>
                <label for="carte_identite">Numéro de carte d'identité</label>
                <input type="text" id="carte_identite" name="carte_identite" required>
            </div>

            <div>
                <label for="date_naissance">Date de naissance</label>
                <input type="date" id="date_naissance" name="date_naissance" required>
            </div>

            <div>
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="role">Rôle</label>
                <select id="role" name="role" required>
                    <option value="agent">Agent</option>
                    <option value="distributeur">Distributeur</option>
                    <option value="client">Client</option>
                </select>
            </div>

            <div id="emailField" style="display: none;">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>

            <button type="submit">Créer Utilisateur</button>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#role').change(function () {
                if ($(this).val() === 'agent') {
                    $('#emailField').show();
                } else {
                    $('#emailField').hide();
                }
            });
        });
    </script>
</body>
</html>
