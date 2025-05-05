<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\EtapeController;
use App\Http\Controllers\DeconnexionController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\MotivationController;

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

//Routeur de modification de profil
Route::put('/profil', [CompteController::class, 'updateProfil'])->name('profil.update');

//Routeur de suppression de compte
Route::delete('/compte', [CompteController::class, 'deleteAccount'])->name('compte.supprimer');

//Routeur vers le dashboardcontroller
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth') ->name('dashboard');

//Routeur pour la création d'objectif
Route::post('/objectifs', [ObjectifController::class, 'store'])->name('objectifs.store');

//Routeur pour la création d'étape
Route::post('/etapes', [EtapeController::class, 'store'])->name('etapes.store');

//Routeur pour ajouter une ressource à un objectif
Route::post('/ressources', [RessourceController::class, 'store'])->name('ressources.store');

//Routeur pour ajouter une timeline 
Route::post('/motivations', [App\Http\Controllers\MotivationController::class, 'store'])->name('motivations.store');



//-------------routeurs spécifiques au front end---------------------

// Route pour l'accueil
Route::get('/accueil', function () {
    return view('Accueil');
})->name('accueil'); 

// Route pour afficher le formulaire de connexion(GET)
Route::get('/connexion', [ConnexionController::class, 'showForm'])->name('connexion.form');

// Route pour afficher le formulaire d'inscription (GET)
Route::get('/inscription', [InscriptionController::class, 'showForm'])->name('inscription.form');

//Route pour app.blade.php
Route::get('/app', function () {
    return view('app');
})->name('app'); 

// Page : profil utilisateur
Route::get('/profil', [CompteController::class, 'editProfil'])->name('profil');

//Affichage des détails d'un objectif
Route::get('/objectifs/{id}', [ObjectifController::class, 'show'])->name('objectifs.show');

//Pour la checkbox de la liste des étapes d'un objectif
Route::patch('/etapes/{id}/toggle', [EtapeController::class, 'toggle'])->name('etapes.toggle');


Auth::routes();

//pour la déconnexion
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
     ->name('logout')
     ->middleware('auth');

