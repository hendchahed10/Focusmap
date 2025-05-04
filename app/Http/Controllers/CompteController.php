<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompteController extends Controller
{
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Supprimer les objectifs liés (si la relation existe)
        $user->objectifs()->delete();

        // Supprimer l'utilisateur
        $user->delete();

        return response()->json(['message' => 'Compte supprimé avec succès.']);
    }
}
