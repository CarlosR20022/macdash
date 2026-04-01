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
        Schema::table('users', function (Blueprint $table) {
            // Agregamos los apellidos
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            
            // Nombre de usuario para el login
            $table->string('username')->unique()->nullable();
            
            // El ID de rol: 1 = Superadmin, 2 = Trabajador (Por defecto)
            $table->integer('role_id')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Esto es por si algún día queremos revertir los cambios
            $table->dropColumn(['apellido_paterno', 'apellido_materno', 'username', 'role_id']);
        });
    }
};