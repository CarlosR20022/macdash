<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->string('nombre_pedido'); // Lona, Pamfletos, etc.
            $table->string('fecha_entrega'); 
            $table->enum('estado', ['Pendiente', 'En proceso', 'Terminado', 'Cancelado'])->default('Pendiente');
            $table->text('detalles_adicionales')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps(); // Esto nos da la fecha de toma automática
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
