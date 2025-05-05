<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    // Spécifie que la clé primaire est 'login'
    protected $primaryKey = 'login';

    // Précise que ce n'est pas un champ auto-incrémenté
    public $incrementing = false;

    // Précise le type de la clé primaire
    protected $keyType = 'string';

    protected $fillable = [
        'nom', 'email', 'login', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function objectifs()
    {
        return $this->hasMany(Objectif::class, 'utilisateur_login', 'login');
    }

    // Redéfinit l'identifiant d'authentification
    public function getAuthIdentifierName()
    {
        return 'login';
    }
}
