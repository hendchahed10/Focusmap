<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'utilisateur_login',  // Clé étrangère vers utilisateurs 
        'objectif_id',        // Clé étrangère Rvers objectifs
        'title',
        'start',
        'end',
        'color'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_login', 'login');
    }

    /**
     * Relation avec l'objectif (requise)
     */
    public function objectif()
    {
        return $this->belongsTo(Objectif::class, 'objectif_id');
    }
}