
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FocusMap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <script src="{{ asset('js/all.js') }}" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/jsmind@0.4.1/dist/jsmind.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jsmind@0.4.1/dist/jsmind.js"></script>
    @yield('head')
</head>
<body style="background: linear-gradient(to right, #f5f5f5, #ffeaea);">
    <header class="p-3 text-white" style="font-family:cursive; background: linear-gradient(90deg, rgb(238, 94, 69), rgb(251, 156, 186));">
        <div class="container d-flex justify-content-between">
            <h1>🌟 FocusMap</h1>
            <nav>
                @auth 
                <a href="{{ route('objectifs.liste') }}" class="text-white me-3">Mes Objectifs</a>
                <a href="{{ route('objectifs.creer') }}" class="text-white me-3">Créer un nouvel objectif</a>
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
    <script type="text/javascript">
    var mindData = {
        "meta": {
            "name": "example",
            "author": "your_name",
            "version": "0.1"
        },
        "format": "node_tree",
        "data": {
            "id": "root",
            "topic": "Mon Objectif",
            "children": [
                {
                    "id": "child1",
                    "topic": "Objectif 1",
                    "children": [
                        {
                            "id": "child1.1",
                            "topic": "Sous-objectifs"
                        }
                    ]
                },
                {
                    "id": "child2",
                    "topic": "Objectif 2"
                }
            ]
        }
    };

    window.onload = function() {
        var options = {
            container: 'jsmind_container',
            theme: 'primary',
            editable: true,
        };

        var jm = new jsMind(options);
        jm.show(mindData);
    };
    @yield('scripts')
</script>
</body>
</html>
