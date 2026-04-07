<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('historial_tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->nullable()->constrained('tareas')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('accion');
            $table->string('detalles');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('historial_tareas');
    }
};