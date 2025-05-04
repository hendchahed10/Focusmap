@extends('app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">☀ C'est un plaisir de vous voir, {{ Auth::user()->nom }} !</h2>

    {{-- Objectifs actifs --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">🎯 Objectifs actifs</h5>

            @if($objectifs->isEmpty())
                <p class="text-muted">Vous n'avez pas encore d'objectifs.</p>
            @else
                <!-- jsMind Container -->
                <div id="jsmind_container" style="width: 100%; height: 600px; background-color: #f5f5f5; border: 1px solid #ccc;"></div>

                <!-- Intégration de jsMind depuis CDN -->
                <script src="https://cdn.jsdelivr.net/npm/jsmind@0.4.1/dist/jsmind.js"></script>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // Vérification des données jsMind dans la console
                        const mindData = @json($jsMindData); // Passer les données à JavaScript

                        console.log("🧠 Données jsMind : ", mindData);

                        // Configuration de jsMind
                        const options = {
                            container: 'jsmind_container', // Id du conteneur
                            theme: 'primary',              // Thème de la mindmap
                            editable: false                // Empêcher la modification
                        };

                        const jm = new jsMind(options);   // Initialisation de jsMind
                        jm.show(mindData);                // Affichage des données dans le conteneur
                    });
                </script>
            @endif
        </div>
    </div>

    {{-- Formulaire de création --}}
    <h2>Créer un objectif</h2>
    <form action="{{ route('objectifs.store') }}" method="POST">
        @csrf

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="form-group mb-2">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" class="form-control" required>
        </div>

        <div class="form-group mb-2">
            <label for="description">Description :</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group mb-2">
            <label for="latitude">Latitude :</label>
            <input type="text" name="latitude" id="latitude" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label for="longitude">Longitude :</label>
            <input type="text" name="longitude" id="longitude" class="form-control">
        </div>

        <div id="map" style="height: 300px;" class="my-3"></div>

        <button type="submit" class="btn btn-success">Créer</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, marker;

    function initMap(lat, lng) {
        map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat;
            document.getElementById('longitude').value = pos.lng;
        });

        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    window.onload = function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                initMap(pos.coords.latitude, pos.coords.longitude);
            }, function () {
                initMap(48.8566, 2.3522);
            });
        } else {
            initMap(48.8566, 2.3522);
        }
    }
</script>
@endsection
