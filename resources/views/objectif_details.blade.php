@extends('app')

@section('content')
<div class="container py-4">
  <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-4">← Retour</a>
  <h2>{{ $objectif->titre }}</h2>
  <p class="lead">{{ $objectif->description }}</p>
  <h4 class="mt-4">📈 Progression</h4>
<div class="progress mb-3" style="height: 25px;">
  <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progression }}%;" aria-valuenow="{{ $progression }}" aria-valuemin="0" aria-valuemax="100">
    {{ $progression }}%
  </div>
</div>
<p>{{ $terminees }} étape(s) sur {{ $total }} complétée(s)</p>

  <h4>📌 Étapes</h4>
<ol class="list-group list-group-numbered mb-4">
  @foreach ($objectif->etapes as $etape)
    <li class="list-group-item d-flex align-items-start">
      <form method="POST" action="{{ route('etapes.toggle', $etape->id) }}" class="me-2 mt-1">
        @csrf
        @method('PATCH')
        <input type="checkbox" onchange="this.form.submit()" {{ $etape->terminee ? 'checked' : '' }}>
      </form>
      <div>
        <div class="{{ $etape->terminee ? 'text-success text-decoration-line-through' : '' }}">
          <strong>{{ $etape->titre }}</strong>
        </div>
        <div class="text-muted">
          {{ $etape->description }}
        </div>
      </div>
    </li>
  @endforeach
</ol>
<h4 class="mt-5">🕒 Timeline de motivation</h4>
<ul class="timeline list-unstyled">
    @forelse ($objectif->motivations as $motivation)
        <li class="mb-3 border-start ps-3">
            <div><strong>{{ $motivation->moment->format('d M Y') }}</strong></div>
            <div>{{ $motivation->contenu }}</div>
        </li>
    @empty
        <p class="text-muted">Aucune entrée de motivation pour le moment.</p>
    @endforelse
</ul>



  <h4>➕ Ajouter une nouvelle étape</h4>
  <form method="POST" action="{{ route('etapes.store') }}">
    @csrf
    <input type="hidden" name="objectif_id" value="{{ $objectif->id }}">

    <div class="mb-3">
        <label for="titre" class="form-label">Titre de l'étape</label>
        <input type="text" id="titre" name="titre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description de l'étape</label>
        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Ajouter l'étape</button>
</form>
<h5 class="mt-4">➕ Ajouter une note de motivation</h5>
<form action="{{ route('motivations.store') }}" method="POST" class="mb-4">
    @csrf
    <input type="hidden" name="objectif_id" value="{{ $objectif->id }}">
    
    <div class="mb-3">
        <label for="contenu" class="form-label">Contenu</label>
        <textarea name="contenu" class="form-control" rows="3" required></textarea>
    </div>

    <div class="mb-3">
        <label for="moment" class="form-label">Date (optionnelle)</label>
        <input type="date" name="moment" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Ajouter</button>
</form>


  <hr class="my-5">

  <h4 class="mt-5">🔗 Liens ou ressources</h4>

<ul class="list-group mb-3">
  @foreach($objectif->ressources as $ressource)
    <li class="list-group-item">
      <a href="{{ $ressource->url }}" target="_blank">{{ $ressource->titre }}</a>
    </li>
  @endforeach
</ul>

<form method="POST" action="{{ route('ressources.store') }}">
  @csrf
  <input type="hidden" name="objectif_id" value="{{ $objectif->id }}">
  <div class="mb-3">
    <label class="form-label">Titre</label>
    <input type="text" name="titre" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Fichier</label>
    <input type="file" name="file" class="form-control">
  </div>
  <button type="submit" class="btn btn-outline-primary">Ajouter une ressource</button>
</form>

</div>
<style>
.timeline {
  position: relative;
}
.timeline li {
  position: relative;
  padding-left: 20px;
  border-left: 3px solid #0d6efd;
}
.timeline li::before {
  content: '';
  position: absolute;
  left: -6px;
  top: 0;
  width: 12px;
  height: 12px;
  background-color: #0d6efd;
  border-radius: 50%;
}
</style>
@endsection
