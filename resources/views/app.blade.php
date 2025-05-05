
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FocusMap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="{{ asset('js/all.js') }}" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsmind@0.8.5/es6/jsmind.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsmind@0.8.5/style/jsmind.css">
    
    @yield('head')
</head>

<body style="background: linear-gradient(to right, #f5f5f5, #ffeaea);">
    <header class="p-3 text-white" style="font-family:cursive; background: linear-gradient(90deg, rgb(238, 94, 69), rgb(251, 156, 186));">
        <div class="container d-flex justify-content-between">
            <h1>🌟 FocusMap</h1>
            <nav>
                @auth 
                <a href="{{ route('dashboard') }}" class="text-white me-3">Mes Objectifs</a>
                <a href="{{ route('profil') }}" class="text-white">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link" style="color:white;">Déconnexion</button>
                </form>
            @else
            <a href="{{ route('inscription.form') }}" class="text-white me-3"  style="font-size:20px; font-weight:bold;">S'inscrire</a>
            <a href="{{ route('connexion.form') }}" class="text-white me-3" style="font-size:20px; font-weight:bold;">Se connecter</a>
            @endauth
            </nav>
        </div>
    </header>

    <main class="container mt-4">
        @yield('content')
    </main>

    <footer class=" text-center py-3 mt-5" style="background-color:rgb(250, 204, 205);">
        <p>&copy; {{ date('Y') }} FocusMap. Tous droits réservés.</p>
    </footer>
    @yield('scripts')
</body>
</html>
