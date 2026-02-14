<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
    // Validar los datos principales
    $validated = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'fecha'      => 'required|date',
        'estado'     => 'required|string|in:pendiente,pagado,cancelado',
    ]);

    // Validar que existan detalles y que cada uno tenga los campos necesarios
    $detalles = $request->input('detalles', []);
    if (empty($detalles)) {
        return back()->withErrors(['detalles' => 'Debe agregar al menos un artículo al pedido.'])->withInput();
    }

    // Validar cada detalle
    foreach ($detalles as $index => $detalle) {
        $request->validate([
            "detalles.{$index}.articulo_id"   => 'required|exists:articulos,id',
            "detalles.{$index}.cantidad"       => 'required|integer|min:1',
            "detalles.{$index}.precio_unitario" => 'required|numeric|min:0',
        ]);
    }

    // Usar transacción para asegurar integridad
    DB::beginTransaction();
    try {
        // Crear el pedido
        $pedido = Pedido::create([
            'cliente_id' => $validated['cliente_id'],
            'fecha'      => $validated['fecha'],
            'estado'     => $validated['estado'],
            'total'      => 0, // temporal, luego lo actualizamos
        ]);

        $totalPedido = 0;

        // Crear cada detalle
        foreach ($detalles as $detalle) {
            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
            $pedido->detalles()->create([
                'articulo_id'     => $detalle['articulo_id'],
                'cantidad'        => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal'        => $subtotal,
            ]);
            $totalPedido += $subtotal;
        }

        // Actualizar el total del pedido
        $pedido->update(['total' => $totalPedido]);

        DB::commit();

        return redirect()->route('ventas.pedidos.index')
                         ->with('success', 'Pedido creado exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Ocurrió un error al guardar el pedido: ' . $e->getMessage()])->withInput();
    }
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
    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        // Validar datos principales
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha'      => 'required|date',
            'estado'     => 'required|string|in:pendiente,pagado,cancelado',
        ]);

        $detalles = $request->input('detalles', []);
        if (empty($detalles)) {
            return back()->withErrors(['detalles' => 'Debe agregar al menos un artículo al pedido.'])->withInput();
        }

        foreach ($detalles as $index => $detalle) {
            $request->validate([
                "detalles.{$index}.articulo_id"   => 'required|exists:articulos,id',
                "detalles.{$index}.cantidad"       => 'required|integer|min:1',
                "detalles.{$index}.precio_unitario" => 'required|numeric|min:0',
            ]);
        }

        DB::beginTransaction();
        try {
            // Actualizar datos del pedido
            $pedido->update([
                'cliente_id' => $validated['cliente_id'],
                'fecha'      => $validated['fecha'],
                'estado'     => $validated['estado'],
            ]);

            // Eliminar detalles antiguos
            $pedido->detalles()->delete();

            $totalPedido = 0;
            // Crear los nuevos detalles
            foreach ($detalles as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                $pedido->detalles()->create([
                    'articulo_id'     => $detalle['articulo_id'],
                    'cantidad'        => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal'        => $subtotal,
                ]);
                $totalPedido += $subtotal;
            }

            $pedido->update(['total' => $totalPedido]);

            DB::commit();

            return redirect()->route('ventas.pedidos.index')
                            ->with('success', 'Pedido actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()])->withInput();
        }
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