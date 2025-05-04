<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom', 'email', 'login', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Relation avec les objectifs
     */
    public function objectifs()
    {
        return $this->hasMany(Objectif::class, 'utilisateur_login', 'login');
    }
    public function getAuthIdentifierName()
{
    return 'login'; // Instead of default 'email'
}
}
