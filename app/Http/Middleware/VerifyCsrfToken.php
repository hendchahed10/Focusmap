<?php

namespace App\Http\Middleware; // Correction du namespace

use Closure; // Import manquant
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{

}