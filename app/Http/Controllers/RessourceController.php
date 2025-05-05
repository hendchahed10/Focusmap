<?php

namespace App\Http\Controllers;
use App\Models\Ressource;
use Illuminate\Http\Request;

class RessourceController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'titre' => 'required',
        'url' => 'nullable|url',
        'objectif_id' => 'required|exists:objectifs,id',
    ]);

    Ressource::create($request->only('titre', 'url', 'objectif_id'));

    return redirect()->route('objectifs.show', $request->objectif_id);
}
}
