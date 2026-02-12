<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientesController;
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
    });
    
    // Cotizaciones
    Route::controller(CotizacionesController::class)->prefix('cotizaciones')->name('cotizaciones.')->group(function () {
        Route::get('/', 'index')->name('index');
    });
    
    // Reportes
    Route::controller(ReportesController::class)->prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', 'index')->name('index');
    });
});