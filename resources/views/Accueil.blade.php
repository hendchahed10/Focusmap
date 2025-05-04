<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
@extends('app')

@section('title', 'Accueil')

@section('content')
<div class="container text-center mt-5">

    <h1 class="mb-4 display-4 fw-bold" style="color: rgb(234, 39, 98); font-family:'Comic Sans MS', cursive; font-weight: bold;">
        Bienvenue sur FocusMap 
    </h1>

    <p class="lead mb-5">
        Visualisez, planifiez et réalisez vos objectifs personnels comme jamais auparavant !
    </p>

    <a href="{{ route('connexion.form') }}" class="btn btn-lg btn-danger mb-5">
        Commencer un objectif
    </a>

    <hr class="my-5">

    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4 class="card-title">🧠 Carte Mentale</h4>
                    <p class="card-text">
                        Organisez vos objectifs, ordonnez vos idées, donnez vie à vos rêves ⛅
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4 class="card-title">📍 Carte Interactive</h4>
                    <p class="card-text">
                        Placez vos objectifs sur la carte du monde ; visez haut, voyez loin 🌍
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4 class="card-title">🔥 Suivi de Motivation</h4>
                    <p class="card-text">
                        Visualisez votre progression, observez vos rêves se peindre doucement en réalité 🌱
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

</body>
</html>