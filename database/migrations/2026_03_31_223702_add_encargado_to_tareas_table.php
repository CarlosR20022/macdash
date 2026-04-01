<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            // Guardará el ID del último trabajador que movió el estatus
            $table->foreignId('trabajador_id')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['trabajador_id']);
            $table->dropColumn('trabajador_id');
        });
    }
};