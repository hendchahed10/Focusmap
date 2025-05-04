<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeconnexionController extends Controller
{
    public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('accueil');
}
}
