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
        try {
            // Validar los datos principales
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'fecha'      => 'required|date',
                'estado'     => 'required|string|in:pendiente,pagado,cancelado',
            ]);

            $detalles = $request->input('detalles', []);
            if (empty($detalles)) {
                return back()->withErrors(['detalles' => 'Debe agregar al menos un artículo.'])->withInput();
            }

            // Validar cada detalle
            foreach ($detalles as $index => $detalle) {
                $request->validate([
                    "detalles.{$index}.articulo_id"   => 'required|exists:articulos,id',
                    "detalles.{$index}.cantidad"       => 'required|integer|min:1',
                    "detalles.{$index}.precio_unitario" => 'required|numeric|min:0',
                ]);
            }

            DB::beginTransaction();

            // Crear pedido
            $pedido = Pedido::create([
                'cliente_id' => $validated['cliente_id'],
                'fecha'      => $validated['fecha'],
                'estado'     => $validated['estado'],
                'total'      => 0,
            ]);

            $total = 0;
            foreach ($detalles as $detalle) {
                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                $pedido->detalles()->create([
                    'articulo_id'     => $detalle['articulo_id'],
                    'cantidad'        => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal'        => $subtotal,
                ]);
                $total += $subtotal;
            }

            $pedido->update(['total' => $total]);

            DB::commit();

            return redirect()->route('ventas.pedidos.index')
                            ->with('success', 'Pedido creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            // 👇 Esto te mostrará el error exacto en pantalla
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
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

        $pedido = Pedido::findOrFail($id);
        //no se puede actualizar un pedido entregado o cancelado
        if (in_array($pedido->estado, ['entregado', 'cancelado'])) {
            return redirect()->route('ventas.pedidos.index')
                             ->with('error', 'No se puede editar un pedido entregado o cancelado.');
        }

        $pedido = Pedido::with('detalles.articulo')->findOrFail($id);
        
        // Si el estado es 'completado' no se puede editar
        if ($pedido->estado === 'completado') {
            return redirect()->route('ventas.pedidos.index')
                             ->with('error', 'No se puede editar un pedido completado.');
        }
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

        // No se puede actualizar si está entregado o cancelado
        if (in_array($pedido->estado, ['entregado', 'cancelado'])) {
            return redirect()->route('ventas.pedidos.index')
                            ->with('error', 'No se puede actualizar un pedido entregado o cancelado.');
        }

        // Validar datos principales
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha'      => 'required|date',
            'estado'     => 'required|in:pendiente,despachado,en_camino,entregado,retrasado,cancelado',
            'motivo_retraso' => 'nullable|string|max:255'
        ]);

        // Asignar campos al pedido
        $pedido->cliente_id = $validated['cliente_id'];
        $pedido->fecha = $validated['fecha'];
        $pedido->estado = $validated['estado'];
        if ($request->estado == 'retrasado') {
            $pedido->motivo_retraso = $validated['motivo_retraso'];
        } else {
            $pedido->motivo_retraso = null;
        }

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

        DB::beginTransaction();
        try {
            // Guardar el pedido (para tener el ID en los detalles)
            $pedido->save();

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

            // Actualizar total del pedido
            $pedido->total = $totalPedido;
            $pedido->save();

            DB::commit();

            return redirect()->route('ventas.pedidos.index')
                            ->with('success', 'Pedido actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateEstado(Request $request, $id)
    {
        try {
            $pedido = Pedido::findOrFail($id);

            // No permitir cambios si el pedido ya está entregado o cancelado
            if (in_array($pedido->estado, ['entregado', 'cancelado'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede modificar el estado de un pedido entregado o cancelado.'], 403);
            }
            $request->validate([
                'estado' => 'required|in:pendiente,despachado,en_camino,entregado,retrasado,cancelado',
                'motivo_retraso' => 'nullable|string|max:255'
            ]);

            // Actualizar estado
            $pedido->estado = $request->estado;

            // Solo actualizar el motivo si se proporciona (especialmente para estado retrasado)
            if ($request->has('motivo_retraso')) {
                $pedido->motivo_retraso = $request->motivo_retraso;
            }
            // Si no se envía motivo, mantener el existente

            $pedido->save();

            return response()->json(['success' => true, 'message' => 'Estado actualizado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);

        // Si el estado es 'pendiente' o 'entregado' no se puede eliminar (cancelado si)
        if (in_array($pedido->estado, ['pendiente', 'entregado'])) {
            return redirect()->route('ventas.pedidos.index')
                             ->with('error', 'No se puede eliminar un pedido pendiente.');
        }
                
        $pedido->delete();
        return redirect()->route('ventas.pedidos.index')
                            ->with('success','Pedido eliminado Correctamente.');
    }

    public function test()
{
    return response()->json(['ok' => true]);
}
}