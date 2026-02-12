<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClientesController extends Controller
{
    //
public function index()
{
    //obtener clientes desde el modelo
    $clientes = Cliente::getclientes();

    return view('clientes.index', compact('clientes'));
}


public function create()
    {
        return view('clientes.create');
    }

}
