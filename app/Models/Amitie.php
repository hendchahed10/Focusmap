<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amitie extends Model
{
    use HasFactory;

    protected $table = 'amities';

    protected $fillable = ['login1', 'login2'];

    public function utilisateur1()
    {
        return $this->belongsTo(Utilisateur::class, 'login1', 'login');
    }

    public function utilisateur2()
    {
        return $this->belongsTo(Utilisateur::class, 'login2', 'login');
    }
}
