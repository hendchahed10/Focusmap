<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\API\ObjectifController;
use App\Http\Controllers\API\EtapeController;
use App\Http\Controllers\DeconnexionController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});




//-------------routeurs spécifiques au back end----------------------

// Route pour traiter la soumission (POST)
Route::post('/connexion', [ConnexionController::class, 'handleConnexion'])->name('connexion.submit');

// Route pour traiter la soumission (POST)
Route::post('/inscription', [InscriptionController::class, 'register'])->name('inscription.submit');

//Route pour le contrôleur Objectif
Route::apiResource('objectifs',ObjectifController::class);

//Route pour le contrôleur Etape
Route::apiResource('etapes',EtapeController::class);

//Routeur de déconnexion
Route::post('/logout', [DeconnexionController::class, 'logout']);

//Routeur de suppression de compte
Route::delete('/compte', [CompteController::class, 'delete']);

//Routeur vers le dashboardcontroller
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

//Routeur pour la création d'objectif
Route::post('/objectifs', [ObjectifController::class, 'store'])->name('objectifs.store');




//-------------routeurs spécifiques au front end---------------------

// Route pour l'accueil
Route::get('/accueil', function () {
    return view('Accueil');
})->name('accueil'); 

//Route pour app.blade.php
Route::get('/app', function () {
    return view('app');
})->name('app'); 

// Page : création d'un objectif
Route::get('/objectifs/creer', function () {
    return view('objectifs.creer');
})->name('objectifs.creer');

// Page : liste des objectifs de l'utilisateur
Route::get('/mes-objectifs', function () {
    return view('objectifs.liste');
})->name('objectifs.liste');

// Page : détail d'un objectif avec étapes, carte, timeline
Route::get('/objectifs/{id}', function ($id) {
    return view('objectifs.detail', ['id' => $id]);
})->name('objectifs.detail');

// Page : profil utilisateur
Route::get('/profil', function () {
    return view('utilisateur.profil');
})->name('profil');

// Route pour afficher le formulaire (GET)
Route::get('/connexion', [ConnexionController::class, 'showForm'])->name('connexion.form');

// Route pour afficher le formulaire (GET)
Route::get('/inscription', [InscriptionController::class, 'showForm'])->name('inscription.form');


Auth::routes();

//pour la déconnexion
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
     ->name('logout')
     ->middleware('auth');

