<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClientesController extends Controller
{


    public function index(Request $request)
    {
        $search = $request->get('search');

        $clientes = Cliente::when($search, function ($query, $search) {
            return $query->where('nombre', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
            'email' => 'required|email|unique:clientes,email',
            'telefono' => 'nullable|string|max:20|regex:/^[0-9\s\-\(\)]+$/',
            'direccion' => 'nullable|string|max:255'
        ]);

        Cliente::create($validated);

        return redirect()->route('ventas.clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id, //ignora este email
            'telefono' => 'nullable|string|max:20|regex:/^[0-9\s\-\(\)]+$/',
            'direccion' => 'nullable|string|max:255'
        ]);

        $cliente->update($validated);

        return redirect()->route('ventas.clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('ventas.clientes.index')
                        ->with('success', 'Cliente eliminado correctamente.');
    }
}
