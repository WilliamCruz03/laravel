<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    public static function getClientes(): array
        {
            return[
                [
                    'id' => 1,
                    'nombre' => 'Cliente 1',
                    'email' => 'cliente1@example.com',
                    'telefono' => '123-456-7890',
                    'direccion' => '123 Calle Principal, Ciudad, País'
                ],
                [
                    'id' => 2,
                    'nombre' => 'Cliente 2',
                    'email' => 'cliente2@example.com',
                    'telefono' => '098-765-4321',
                    'direccion' => '456 Calle Secundaria, Ciudad, País'
                ],

                [
                    'id' => 3,
                    'nombre' => 'Cliente 3',
                    'email' => 'cliente3@example.com',
                    'telefono' => '555-123-4567',
                    'direccion' => '789 Calle Terciaria, Ciudad, País'
                ],

                [
                    'id'=> 4,
                    'nombre'=> 'Cliente 4',
                    'email'=> 'cliente4@example.com',
                    'telefono'=> '555-987-6543',
                    'direccion'=> '101 Calle Cuarta, Ciudad, País'
                ]
            ];
        }
    
}
