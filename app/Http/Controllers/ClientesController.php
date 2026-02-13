<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClientesController extends Controller
{
    public function index()
    {
        $clientes = [
            [1, 'Cliente 1', 'cliente1@example.com', '123-456-7890', 'Calle Principal, Ciudad, País'],
            [2, 'Cliente 2', 'cliente2@example.com', '098-765-4321', 'Calle Secundaria, Ciudad, País'],
            [3, 'Cliente 3', 'cliente3@example.com', '555-123-4567', 'Calle Terciaria, Ciudad, País'],
            [4, 'Cliente 4', 'cliente4@example.com', '555-987-6543', 'Calle Cuarta, Ciudad, País']
        ];

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }
}
