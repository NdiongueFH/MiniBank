
<div class="container">
    <h2>Mes Transactions</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Émetteur</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->emetteur->prenom }} {{ $transaction->emetteur->nom }}</td>
                <td>{{ $transaction->mountant }}</td>
                <td>{{ $transaction->created_at }}</td>
                <td>{{ $transaction->type }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
