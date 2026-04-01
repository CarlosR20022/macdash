<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Agregamos la columna user_id.
            // Le ponemos nullable() para que las tareas viejas que ya tienes no causen error.
            // Y constrained('users') crea la relación directa con tu tabla de trabajadores.
            $table->foreignId('user_id')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};