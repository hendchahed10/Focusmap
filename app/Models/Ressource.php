<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = ['objectif_id', 'titre', 'url', 'fichier'];

    public function objectif()
    {
        return $this->belongsTo(Objectif::class);
    }
}
