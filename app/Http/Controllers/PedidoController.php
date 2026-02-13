<?php

namespace App\Http\Controllers;
use App\Models\Pedido;
use App\Models\Cliente;
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
        return view('pedidos.create', compact('clientes'));
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
    public function edit(Pedido $pedido)
    {
        $clientes = Cliente::all();
        return view('pedidos.edit', compact('pedido', 'clientes'));
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //
        $pedido->delete();
        return redirect()->route('ventas.pedidos.index')->with('success', 'Pedido eliminado');
    }
}