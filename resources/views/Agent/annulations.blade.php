<!-- resources/views/canceled_transactions.blade.php -->

<!DOCTYPE html>.

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions Annulées</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <h2 class="mb-4">Transactions Annulées</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Numéro de Compte</th>
                <th>Carte d'Identité</th>
                <th>Date d'Annulation</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->distributeur->nom }}</td>
                    <td>{{ $transaction->distributeur->prenom }}</td>
                    <td>{{ $transaction->distributeur->num_compte }}</td>
                    <td>{{ $transaction->distributeur->carte_identite }}</td>
                    <td>{{ $transaction->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Aucune transaction annulée trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('transactions') }}" class="btn btn-primary mt-3">Retour aux Transactions</a>
</body>
</html>
