<?php
namespace App\Http\Controllers;

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
        'visibilite' => 'nullable|in:prive,amis,public',
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
            $etapes = $objectif->etapes;
            $total = $etapes->count();
            $terminees = $etapes->where('terminee', true)->count();
            $progression = $total > 0 ? round(($terminees / $total) * 100) : 0;
        
            return view('objectif_details', compact('objectif', 'etapes', 'progression', 'terminees', 'total'));
        
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
            'visibilite' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'utilisateur_login' => 'sometimes|string|exists:utilisateurs,login',
        ]);

        $objectif->update($validated);
        return response()->json($objectif);
    }
    public function verifierTitre(Request $request)
    {
        $request->validate(['titre' => 'required|string']);
        $objectif = Objectif::where('titre', 'LIKE', '%'.$request->titre.'%')
                          ->where('utilisateur_login', auth()->user()->login)
                          ->first();
    
        if (!$objectif) {
            return response()->json([
                'existe' => false,
                'message' => 'Aucun objectif trouvé. Créez d\'abord l\'objectif.'
            ], 404);
        }
    
        return response()->json([
            'existe' => true,
            'objectif_id' => $objectif->id,
            'titre' => $objectif->titre // Retourne le titre exact de la base
        ]);
    }
// Dans votre contrôleur (ex: ObjectifController.php)
public function create()
{
    $objectifs = auth()->user()->objectifs()
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    return view('dashboard', compact('objectifs'));
}
// Dans ObjectifController.php
public function dashboard()
{
    // Objectifs publics de tous les utilisateurs
    $publicObjectives = Objectif::where('visibilite', 'public')
        ->with('utilisateur')
        ->latest()
        ->get();

    // Objectifs partagés avec l'utilisateur connecté
    $sharedObjectives = auth()->user()->objectifsPartages()
        ->with('utilisateur')
        ->latest()
        ->get();

        return view('dashboard', [
            'publicObjectives' => $publicObjectives,
            'sharedObjectives' => $sharedObjectives,
            'amis' => auth()->user()->amis
        ]);
}
// ObjectifController.php
public function unshare(Objectif $objectif)
{
    auth()->user()->objectifsPartages()->detach($objectif->id);
    
    return response()->json([
        'message' => 'Vous ne suivez plus cet objectif'
    ]);
}

}
