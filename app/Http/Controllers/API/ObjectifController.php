<?php
namespace App\Http\Controllers\API;

use App\Models\Objectif;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ObjectifController extends Controller
{
    public function index()
    {
        return Objectif::with('etapes')->get(); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'visibilité' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);
        $validated['utilisateur_login'] = auth()->user()->login;
        $objectif = Objectif::create($validated);
        return redirect()->back()->with('success', 'Objectif créé avec succès !');
    }

    public function show($id)
    {
        $objectif = Objectif::with('etapes')->findOrFail($id);
        return response()->json($objectif);
    }

    public function destroy($id)
    {
        $objectif = Objectif::findOrFail($id);
        $objectif->delete();
        return response()->json(['message' => 'Objectif supprimé avec succès.']);
    }

    public function update(Request $request, $id)
    {
        $objectif = Objectif::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'visibilité' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'utilisateur_login' => 'sometimes|string|exists:utilisateurs,login',
        ]);

        $objectif->update($validated);
        return response()->json($objectif);
    }
}
