<!-- resources/views/partials/userTable.blade.php -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Numéro de Compte</th>
            <th>Carte d'Identité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->nom }}</td>
                <td>{{ $user->prenom }}</td>
                <td>{{ $user->telephone }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->num_compte }}</td>
                <td>{{ $user->carte_identite }}</td>
                <td>
                    <form action="{{ route('user.' . $action, $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $action === 'block' ? 'danger' : 'success' }}">
                            {{ $action === 'block' ? 'Bloquer' : 'Débloquer' }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>