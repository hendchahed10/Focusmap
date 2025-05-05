@extends('app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">☀ C'est un plaisir de vous voir, {{ Auth::user()->nom }} !</h2>

    {{-- Objectifs actifs --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">🍃 Objectifs actifs</h5>

            @if($objectifs->isEmpty())
                <p class="text-muted">Vous n'avez pas encore d'objectifs.</p>
            @else
            <div id="jsmind_container" style="width: 100%; height: 600px; background: #f9f9f9; border: 1px solid #ccc; overflow: hidden;"></div>
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
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group mb-2">
            <label for="deadline">Latitude :</label>
            <input type="date" name="deadline" id="deadline" class="form-control">
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
<style>
    #jsmind_container {
        min-height: 400px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
    jmnode {
        padding: 10px !important;
        border-radius: 5px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    #jsmind_container a {
        color: white !important;
        text-decoration: none;
    }

    /* Bonus : enlever soulignement au survol si tu veux */
    #jsmind_container a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('scripts')
    {{-- Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- jsMind --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsmind/0.4.6/jsmind.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsmind/0.4.6/jsmind.css" />

    <script>
document.addEventListener("DOMContentLoaded", function() {
    @if(!$objectifs->isEmpty())
        try {
            const options = {
                container: 'jsmind_container',
                theme: 'info',
                editable: false,
                mode: 'full'
            };

            const jm = new jsMind(options);
            jm.show(@json($jsMindData));

            setTimeout(() => {
                jm.resize();
                document.querySelector('#jsmind_container').style.visibility = 'visible';
            }, 100);

            // Clic sur un nœud
            jm.add_event_listener(function(type, data) {
                if (type === jsMind.event_type.select) {
                    console.log("Nœud cliqué :", data?.node?.id);
                    const node = data.node;
                    if (node?.id?.startsWith('obj-')) {
                        const objectifId = node.id.replace('obj-', '');
                        window.open('/objectifs/' + objectifId, '_blank');
                    }
                }
            });

        } catch (e) {
            console.error("Erreur jsMind:", e);
            document.getElementById('jsmind_container').innerHTML = 
                '<div class="alert alert-danger">Erreur d\'affichage de la mindmap</div>';
        }
    @endif
});

</script>



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
                    initMap(48.8566, 2.3522); // Fallback: Paris
                });
            } else {
                initMap(48.8566, 2.3522);
            }
        }
    </script>
@endsection
