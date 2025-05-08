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
        'visibilite', 'latitude', 'longitude', 'utilisateur_login'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_login', 'login');
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class, 'objectif_id');
    }
    public function ressources()
{
    return $this->hasMany(Ressource::class);
}
public function motivations()
{
    return $this->hasMany(Motivation::class);
}
public function events()
{
    return $this->hasMany(Event::class, 'objectif_id', 'id');
}
public function estVisiblePar($user)
{
    if ($this->visibilite === 'public') return true;

    if ($this->visibilite === 'amis' && $user) {
        return $this->user->amis->contains($user->id);
    }

    return auth()->check() && $user && $user->id === $this->user_id;
}


}
