<!-- resources/views/create_transaction.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Créer une Transaction</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informations du Distributeur</h5>
            <p>Nom: {{ $distributeur->nom }}</p>
            <p>Prénom: {{ $distributeur->prenom }}</p>
            <p>Adresse: {{ $distributeur->adresse }}</p>
            <p>Numéro de Compte: {{ $distributeur->num_compte }}</p>
            <p>Carte d'Identité: {{ $distributeur->carte_identite }}</p>

            <form action="{{ route('transaction.store') }}" method="POST">
                @csrf
                <input type="hidden" name="distributeur_id" value="{{ $distributeur->id }}">
                <div class="mb-3">
                    <label for="montant" class="form-label">Montant</label>
                    <input type="number" class="form-control" id="montant" name="montant" required>
                </div>
                <button type="submit" class="btn btn-primary">Valider</button>
                <a href="{{ route('dashboard.agent1') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>