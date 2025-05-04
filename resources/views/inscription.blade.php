<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription FocusMap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            width: 350px;
        }
        .title {
            color: rgb(235, 112, 98);
            font-weight: bold;
            font-family: cursive;
        }
        .label {
            font-family: cursive;
            color: rgb(146, 19, 5);
        }
        .btn-custom {
            background-color: rgb(232, 109, 107);
            border: none;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-lg">
        <h3 class="text-center mb-4 title">Inscription</h3>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('inscription.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label label">Nom Complet</label>
                <input type="text" id="nom" name="nom" class="form-control" required value="{{ old('nom') }}">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="login" class="form-label label">Login</label>
                <input type="text" id="login" name="login" class="form-control" required value="{{ old('login') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label label">Mot de passe</label>
                <input type="password" id="mdp" name="password" class="form-control" required>
                <small class="text-muted">8 caractères minimum avec majuscules, minuscules, chiffres et symboles</small>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label label">Confirmation</label>
                <input type="password" id="mdp2" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-custom">S'inscrire</button>
        </form>
    </div>
</body>
</html>