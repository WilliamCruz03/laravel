<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizaciones extends Model
{
    
protected $fillable = [
    'cliente_id',
    'fecha',
    'total'
    ];
}
