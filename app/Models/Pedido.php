<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_id',
        'fecha',
        'total',
        'estado'
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con DetallePedido
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}