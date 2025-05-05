<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;


class CompteController extends Controller
{
    // Afficher le formulaire de modification du profil
public function editProfil()
{
    return view('profil');
}

// Mettre à jour les informations du profil
public function updateProfil(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'nom' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => [
            'nullable',
            'confirmed',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
        ],
    ]);

    $user->nom = $request->nom;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('profil')->with('success', 'Profil mis à jour avec succès.');
}
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Supprimer les objectifs liés (si la relation existe)
        $user->objectifs()->delete();

        // Supprimer l'utilisateur
        $user->delete();

        // Redirige vers la page d'accueil 
    return redirect('accueil')->with('success', 'Votre compte a bien été supprimé.');
    }
}
