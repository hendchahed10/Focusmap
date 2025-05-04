<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion FocusMap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-title {
            color: rgb(235, 112, 98);
            font-weight: bold;
            font-family: cursive;
        }
        .form-label-custom {
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
    <div class="card p-4 shadow-lg" style="width: 350px;">
        <h3 class="text-center mb-4 login-title">Connexion</h3>
        
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('connexion.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="login" class="form-label form-label-custom">Login</label>
                <input type="text" id="login" name="login" class="form-control" required value="{{ old('login') }}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label form-label-custom">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-custom">Se connecter</button>
        </form>
        <p class="text-center mt-3" style="font-size: 15px; color: rgb(179, 52, 52);">
            Vous n'avez pas de compte ?<br>
            <a href="{{ route('inscription.form') }}" style="color:rgb(234, 61, 67);">Créer un compte</a>
        </p>
    </div>
</body>
</html>