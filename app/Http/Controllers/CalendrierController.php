<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;


class CalendrierController extends Controller
{
    public function index(Request $request)
    {
        return Event::where('utilisateur_login', auth()->user()->login)
              ->get(['id', 'title', 'start', 'end', 'color', 'objectif_id']);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'color' => 'nullable|string|size:7|starts_with:#',
            'objectif_id' => 'required|exists:objectifs,id'
        ]);
    
        $event = Event::create([
            'utilisateur_login' => auth()->user()->login,
            'objectif_id' => $validated['objectif_id'],
            'title' => $validated['title'],
            'start' => $validated['start'],
            'end' => $validated['end'] ?? null,
            'color' => $validated['color'] ?? '#3788d8',
        ]);
    
        return response()->json($event, 201);
    }
public function update(Request $request, $id)
{
    $event = Event::findOrFail($id);
    
    $validated = $request->validate([
        'start' => 'sometimes|date',
        'end' => 'nullable|date|after_or_equal:start',
        'color' => 'sometimes|string|size:7|starts_with:#',
    ]);

    $event->update($validated);
    
    return response()->json($event);
}

public function destroy($id)
{
    Event::where('id', $id)->where('utilisateur_login', auth()->user()->login)->delete();
    return response()->noContent();
}

}
