<?php
namespace App\Http\Controllers\API;

use App\Models\Etape;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EtapeController extends Controller
{
    public function index()
    {
        return Etape::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required',
            'description' => 'nullable',
            'completed' => 'required|boolean',
            'objectif_id' => 'required|exists:objectifs,id',
        ]);

        $etape = Etape::create($validated);

        return response()->json($etape, 201);
    }

    public function show($id)
    {
        return Etape::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $etape = Etape::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'sometimes|required',
            'description' => 'nullable',
            'completed' => 'sometimes|required|boolean',
            'objectif_id' => 'sometimes|required|exists:objectifs,id',
        ]);

        $etape->update($validated);

        return response()->json($etape);
    }

    public function destroy($id)
    {
        $etape = Etape::findOrFail($id);
        $etape->delete();

        return response()->noContent();
    }

    public function etapesParObjectif($objectif_id)
    {
        return Etape::where('objectif_id', $objectif_id)->get();
    }
}
