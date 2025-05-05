<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Objectif;

class DashboardController extends Controller
{
    /**
     * Affiche la page de tableau de bord avec les objectifs de l'utilisateur connecté
     */
    public function index()
{
    $user = auth()->user();
    $objectifs = Objectif::where('utilisateur_login', $user->login)->get();

    if($objectifs->isEmpty()) {
        return view('dashboard', ['objectifs' => $objectifs]);
    }

    // Format node_array obligatoire pour jsMind
    $nodes = [
        [
            'id' => 'root',
            'topic' => 'Mes Objectifs (' . $objectifs->count() . ')',
            'isroot' => true,
            'expanded' => true
        ]
    ];

    foreach ($objectifs as $obj) {
        $nodes[] = [
            'id' => 'obj-' . $obj->id,
            'topic' => '<a href="' . route('objectifs.show', ['id' => $obj->id]) . '">' . e($obj->titre) . '</a>',
            'parentid' => 'root'

        ];
        
    }

    $jsMindData = [
        'meta' => [
            'name' => 'Objectifs de '.$user->nom,
            'author' => 'FocusMap',
            'version' => '1.0',
        ],
        'format' => 'node_array', 
        'data' => $nodes
    ];

    return view('dashboard', [
        'objectifs' => $objectifs,
        'jsMindData' => $jsMindData 
    ]);
}
}