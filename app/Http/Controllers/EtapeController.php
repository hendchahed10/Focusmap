<?php
namespace App\Http\Controllers;

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
            'objectif_id' => 'required|exists:objectifs,id',
        ]);

        $etape = Etape::create($validated);
        return redirect()->route('objectifs.show', $request->objectif_id);
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
    public function toggle($id)
{
    $etape = Etape::findOrFail($id);
    $etape->terminee = !$etape->terminee;
    $etape->save();

    return redirect()->back();
}

}
