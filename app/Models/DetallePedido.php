<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = "detalle_pedido";
    protected $fillable = [
        'pedido_id',
        'articulo_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    // Relación inversa
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}