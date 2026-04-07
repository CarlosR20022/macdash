<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialTarea extends Model
{
    protected $table = 'historial_tareas';
    protected $fillable = ['tarea_id', 'user_id', 'accion', 'detalles'];

    public function usuario() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tarea() {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }
}