<?php

namespace App\Http\Controllers;

use App\Models\Amitie;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class AmitieController extends Controller
{
public function ajouter(Request $request)
{
    $request->validate([
        'ami_login' => 'required|exists:utilisateurs,login',
    ]);

    $login1 = auth()->user()->login;
    $login2 = $request->input('ami_login');

    // Ne pas s'ajouter soi-même
    if ($login1 === $login2) {
        return back()->with('error', 'Vous ne pouvez pas vous ajouter vous-même.');
    }

    // Vérifier si l'amitié existe déjà
    $deja_amis = Amitie::where(function ($q) use ($login1, $login2) { //pour vérifier la relation d'amitié dans les 2 sens 
        $q->where('login1', $login1)->where('login2', $login2);
    })->orWhere(function ($q) use ($login1, $login2) {
        $q->where('login1', $login2)->where('login2', $login1);
    })->exists();

    if ($deja_amis) {
        return back()->with('error', 'Vous êtes déjà amis.');
    }

    // Créer l'amitié dans les 2 sens
    Amitie::create(['login1' => $login1, 'login2' => $login2]);
    Amitie::create(['login1' => $login2, 'login2' => $login1]);

    return back()->with('success', 'Ami ajouté avec succès !');
}

}
