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

    {{--Liste des amis}}
    <h3>Mes amis</h3>
    <ul>
        @foreach(auth()->user()->amis as $ami)
            <li>{{ $ami->nom }} ({{ $ami->login }})</li>
        @endforeach
    </ul>

<div class="row mt-4">
    <!-- Liste des objectifs publics -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-globe me-2"></i>Objectifs Publics
                </h5>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                @forelse($publicObjectives as $objectif)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>{{ $objectif->titre }}</h6>
                            <p class="text-muted small">
                                Par {{ $objectif->utilisateur->nom }} • 
                                {{ $objectif->created_at->diffForHumans() }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('objectifs.show', $objectif->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                                @if($objectif->latitude && $objectif->longitude)
                                    <button class="btn btn-sm btn-outline-secondary"
                                            onclick="focusOnMap({{ $objectif->latitude }}, {{ $objectif->longitude }})">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        Aucun objectif public pour le moment
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Liste des objectifs partagés -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-friends me-2"></i>Partagés avec moi
                </h5>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                @forelse($sharedObjectives as $objectif)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>{{ $objectif->titre }}</h6>
                            <p class="text-muted small">
                                Partagé par {{ $objectif->utilisateur->nom }} • 
                                {{ $objectif->pivot->created_at->diffForHumans() }}
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('objectifs.show', $objectif->id) }}" 
                                   class="btn btn-sm btn-outline-success">
                                    Voir
                                </a>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="unshareObjective({{ $objectif->id }})">
                                    <i class="fas fa-user-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        Aucun objectif partagé avec vous
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
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
            <label for="deadline">Deadline :</label>
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


<!--Mindmap-->
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

<!--Calendrier-->
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


<!--Carte-->
<script>
    const objectifs = @json($objectifs);
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialisation de la carte
    const map = L.map('map').setView([48.8566, 2.3522], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // 2. Configuration des icônes personnalisées
    const objectiveIcon = L.icon({
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34]
    });

    // 3. Groupe de marqueurs pour meilleures performances
    const markersGroup = L.layerGroup().addTo(map);

    // 4. Ajout des objectifs existants
    @foreach($objectifs as $objectif)
        @if($objectif->latitude && $objectif->longitude)
            const marker{{ $objectif->id }} = L.marker(
                [{{ $objectif->latitude }}, {{ $objectif->longitude }}],
                { 
                    icon: objectiveIcon,
                    objectiveId: {{ $objectif->id }} // Stocke l'ID dans le marqueur
                }
            ).addTo(markersGroup);
            
            // Popup avec détails
            marker{{ $objectif->id }}.bindPopup(`
                <div class="leaflet-popup-content">
                    <h5>{{ $objectif->titre }}</h5>
                    <p>{{ Str::limit($objectif->description, 100) }}</p>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('objectifs.show', $objectif->id) }}" 
                           class="btn btn-sm btn-primary">
                            Voir
                        </a>
                        <button onclick="editObjective({{ $objectif->id }})" 
                                class="btn btn-sm btn-outline-secondary">
                            ✏️
                        </button>
                    </div>
                </div>
            `);
        @endif
    @endforeach

    // 5. Gestion du clic sur la carte (création)
    let marker;
    map.on('click', function(e) {
        // Supprime l'ancien marqueur temporaire
        if (marker) map.removeLayer(marker);
        
        // Crée un nouveau marqueur temporaire
        marker = L.marker(e.latlng, {
            draggable: true,
            icon: objectiveIcon
        }).addTo(map);

        // Met à jour les champs du formulaire
        document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(6);

        // Gestion du drag
        marker.on('dragend', function(event) {
            const pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat.toFixed(6);
            document.getElementById('longitude').value = pos.lng.toFixed(6);
        });
    });

    // 6. Fonctions utilitaires
    window.editObjective = function(id) {
        // Implémentez votre logique d'édition ici
        console.log('Édition de l\'objectif', id);
        // Exemple: window.location.href = `/objectifs/${id}/edit`;
    };

    // 7. Fit bounds pour voir tous les marqueurs
    if (markersGroup.getLayers().length > 0) {
        map.fitBounds(markersGroup.getBounds(), { padding: [50, 50] });
    }
});
// Fonction pour centrer la carte sur un objectif
window.focusOnMap = function(lat, lng) {
    map.flyTo([lat, lng], 15, {
        duration: 1,
        easeLinearity: 0.25
    });
    
    // Animation du marqueur
    const marker = Object.values(markersGroup._layers)
        .find(m => m.options.objectiveId === id);
    
    if (marker) {
        marker.openPopup();
        marker.setIcon(L.icon({
            iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-red.png',
            iconSize: [25, 41]
        }));
        
        setTimeout(() => {
            marker.setIcon(objectiveIcon);
        }, 2000);
    }
};

// Fonction pour supprimer un partage
window.unshareObjective = function(objectifId) {
    if (confirm('Arrêter de suivre cet objectif ?')) {
        axios.delete(`/api/objectifs/${objectifId}/unshare`)
            .then(() => window.location.reload())
            .catch(error => alert('Erreur: ' + error.response.data.message));
    }
};

    </script>
@endsection
