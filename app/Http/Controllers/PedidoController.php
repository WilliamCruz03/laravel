<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Articulo;
use Illuminate\Http\Request;


class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pedidos = Pedido::with('cliente')->get();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        $clientes = Cliente::all();
        $articulos = Articulo::all();
        return view('pedidos.create', compact('clientes', 'articulos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'total' => 'required|numeric',
            'estado' => 'required|string',
        ]);

        Pedido::create($validated);

        return redirect()->route('ventas.pedidos.index')->with('success', 'Pedido creado');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pedido = Pedido::with('detalles.articulo')->findOrFail($id);
        $clientes = Cliente::all();
        $articulos = Articulo::all();

        return view('pedidos.edit', compact('pedido','clientes','articulos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'total' => 'required|numeric',
            'estado' => 'required|string',
        ]);

        return redirect()->route('ventas.pedidos.index')->with('success', 'Pedido actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();
        return redirect()->route('ventas.pedidos.index')->with('success', 'Pedido eliminado');
    }
}