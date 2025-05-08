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
    {{-- Cartes des objectifs --}}
    <div class="row mt-4">
    @foreach($objectifs as $objectif)
        <div class="col-md-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title" style="font-family:cursive; font-weight:bold; color:rgb(126, 50, 50);">{{ $objectif->titre }}</h5>
                    <p class="card-text">{{ Str::limit($objectif->description, 100) }}</p>
                    <a href="{{ route('objectifs.show', $objectif->id) }}" class="btn btn-outline-danger">
                        Voir l’objectif
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{--Calendrier des objectifs--}}
<div class="container">
        <h2 class="text-center mt-4">Calendrier de mes objectifs</h2>
        <div id="calendar"></div>
    </div>
    <h3>Mes amis</h3>
    <ul>
        @foreach(auth()->user()->amis as $ami)
            <li>{{ $ami->nom }} ({{ $ami->login }})</li>
        @endforeach
    </ul>

    {{-- Formulaire de création d'objectifs --}}
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
            <label for="deadline"> :</label>
            <input type="date" name="deadline" id="deadline" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label for="visibilite">Visibilité :</label>
            <select name="visibilite" id="visibilite" class="form-control">
                <option value="prive">Privé</option>
                <option value="amis">Amis</option>
                <option value="public">Public</option>
            </select>
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
<br>
    {{-- Formulaire d'ajout d'amis --}}
    <form action="{{ route('amis.ajouter') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="ami_login">Ajouter un ami :</label>
        <input type="text" name="ami_login" id="ami_login" class="form-control" placeholder="Login de l'ami" required>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Ajouter</button>
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
    #calendar {
            max-width: 900px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
    }
</style>
@endsection

@section('scripts')
    {{-- Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- jsMind --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsmind/0.4.6/jsmind.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsmind/0.4.6/jsmind.css" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">



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
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        locale: 'fr',
        events: '/api/calendar/events',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        // Gestion de la sélection (création)
        select: function(info) {
    const title = prompt('Entrez le titre du nouvel événement:');
    if (title) {
        // Vérification de l'objectif
        axios.get('/objectifs/verifier', { 
            params: { titre: title }
        })
        .then(response => {
            if (response.data.existe) {
                return axios.post('/calendar/events', { 
                    title: title,
                    start: info.startStr,
                    end: info.endStr,
                    color: '#3788d8',
                    objectif_id: response.data.objectif_id,
                    _token: document.querySelector('meta[name="csrf-token"]').content
                });
            } else {
                throw new Error("Aucun objectif ne correspond à ce titre. Créez d'abord l'objectif.");
            }
        })
        .then(() => {
            calendar.refetchEvents();
            alert('Événement créé avec succès!');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert(error.response?.data?.message || error.message);
        });
    }
    calendar.unselect();
},
        // Gestion du clic sur événement
        eventClick: function(info) {
            if (confirm(`Supprimer "${info.event.title}" ?`)) {
                axios.delete(`/api/calendar/events/${info.event.id}`, {
                    data: { _token: document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(() => {
                    info.event.remove();
                    alert('Objectif supprimé');
                })
                .catch(error => {
                    console.error('Erreur:', error.response);
                    alert('Erreur lors de la suppression');
                });
            }
        },

        // Gestion du glisser-déposer
        eventDrop: function(info) {
            axios.put(`/api/calendar/events/${info.event.id}`, {
                start: info.event.startStr,
                end: info.event.endStr,
                _token: document.querySelector('meta[name="csrf-token"]').content
            })
            .catch(error => {
                console.error('Erreur:', error.response);
                info.revert();
                alert('Erreur de mise à jour');
            });
        }
    });

    calendar.render();
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
