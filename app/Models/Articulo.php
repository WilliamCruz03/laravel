<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock'
    ];

    // Relación con DetallePedido (un artículo puede estar en muchos detalles)
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}