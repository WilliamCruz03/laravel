<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            // Cambia esta línea según el tipo de clientes.id
            $table->unsignedInteger('cliente_id'); // si clientes.id es INT UNSIGNED
            // Si es INT sin signo, usa integer('cliente_id')->unsigned();
            $table->date('fecha');
            $table->decimal('total', 10, 2)->default(0);
            $table->string('estado')->default('pendiente');
            $table->timestamps();

            // Agrega la llave foránea manualmente
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }
};