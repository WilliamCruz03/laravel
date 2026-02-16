<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ArticuloController;

use App\Http\Controllers\CotizacionesController;
use App\Http\Controllers\ReportesController;


// Ruta principal Ventas
Route::get('/', function () {
    return redirect()->route('ventas');
});


Route::prefix('ventas')->name('ventas.')->group(function () {
    
    // Dashboard
    Route::get('/', function () {
        return view('ventas');
    })->name('dashboard');
    
    // Clientes
    Route::controller(ClientesController::class)->prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/crear', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/editar', 'edit')->name('edit');   // Muestra formulario de edición
        Route::put('/{id}', 'update')->name('update');      // Procesa la actualización
        Route::delete('/{id}', 'destroy')->name('destroy');  // Eliminar cliente
        });
    

        // Artículos y Pedidos
        Route::resource('articulos', ArticuloController::class)->names('articulos');
        Route::resource('pedidos', PedidoController::class)->names('pedidos');
        Route::post('/pedidos/{id}/estado', [PedidoController::class, 'updateEstado'])->name('pedidos.updateEstado');
        Route::get('/clientes/buscar', [App\Http\Controllers\ClientesController::class, 'buscar'])->name('clientes.buscar');
        
        // Cotizaciones
        Route::controller(CotizacionesController::class)->prefix('cotizaciones')->name('cotizaciones.')->group(function () {
            Route::get('/', 'index')->name('index');
        });
        
        // Reportes
        Route::controller(ReportesController::class)->prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', 'index')->name('index');
        });
});