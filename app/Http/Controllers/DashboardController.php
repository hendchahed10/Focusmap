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
    $objectifs = Objectif::where('utilisateur_login', auth()->user()->login)->get();

    // Construire le format jsMind (node_tree)
    $nodes = [
        'id' => 'root',
        'topic' => 'Mes Objectifs',
        'children' => []
    ];

    foreach ($objectifs as $objectif) {
        $nodes['children'][] = [
            'id' => 'obj-' . $objectif->id,
            'topic' => $objectif->titre,
            'children' => [
                [
                    'id' => 'desc-' . $objectif->id,
                    'topic' => $objectif->description
                ]
            ]
        ];
    }

    $jsMindData = [
        'meta' => [
            'name' => 'focusmap',
            'author' => 'toi',
            'version' => '1.0'
        ],
        'format' => 'node_tree',
        'data' => $nodes
    ];

    return view('dashboard', [
        'objectifs' => $objectifs,
        'jsMindData' => $jsMindData // pas encodé ici
    ]);
}
}