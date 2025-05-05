<?php

namespace App\Http\Controllers;
use App\Models\Motivation;
use Illuminate\Http\Request;

class MotivationController extends Controller
{


public function store(Request $request)
{
    $validated = $request->validate([
        'contenu' => 'required|string',
        'moment' => 'nullable|date',
        'objectif_id' => 'required|exists:objectifs,id',
    ]);

    Motivation::create($validated);

    return redirect()->route('objectifs.show', $validated['objectif_id'])->with('success', 'Note de motivation ajoutée !');
}

}
