<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;

    protected $table = 'etapes';

    protected $fillable = ['titre', 'description', 'completed', 'objectif_id'];

    public function objectif()
    {
        return $this->belongsTo(Objectif::class, 'objectif_id');
    }
}
