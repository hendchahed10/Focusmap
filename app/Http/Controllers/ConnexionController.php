<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;

class ConnexionController extends Controller
{
    public function showForm()
    {
        return view('connexion'); 
    }

    public function handleConnexion(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('login', 'password');

        // Vérifier si l'utilisateur existe
        $userExists = Utilisateur::where('login', $credentials['login'])->exists();

        if (!$userExists) {
            return back()->withErrors([
                'login' => 'Ce login n\'existe pas !',
            ])->withInput($request->only('login'));
        }

        // Tentative de connexion (removed duplicate attempt)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security measure
            return redirect()->intended('dashboard');
        }

        // Cas où le mdp est erroné
        return back()->withErrors([
            'password' => 'Mot de passe incorrect !',
        ])->withInput($request->only('login'));
    }
}