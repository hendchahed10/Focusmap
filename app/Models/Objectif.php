<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;

    protected $table = 'objectifs';

    protected $fillable = [
        'titre', 'description', 'deadline',
        'visibilité', 'latitude', 'longitude', 'utilisateur_login'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_login', 'login');
    }

    public function étapes()
    {
        return $this->hasMany(Etape::class, 'objectif_id');
    }
}
