@extends('app')

@section('content')
<div class="container mt-5">
    <h2>👤 Mon profil</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profil.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', Auth::user()->nom) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="email">Adresse e-mail :</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
        </div>

        <hr>

        <h5>🔒 Modifier le mot de passe</h5>
        <div class="form-group mb-3">
            <label for="password">Nouveau mot de passe :</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label for="password_confirmation">Confirmation du mot de passe :</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
    <br><br>
    <form action="{{ route('compte.supprimer') }}" method="POST" onsubmit="return confirm('Etes-vous sûr(e) de vouloir supprimer votre compte ? Cette action est irréversible.')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">🗑 Supprimer mon compte</button>
</form>

</div>
@endsection
