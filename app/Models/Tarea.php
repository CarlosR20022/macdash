<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'cliente',
        'nombre_pedido',
        'fecha_entrega',
        'estado',
        'detalles_adicionales',
        'telefono',
        'user_id',
        'trabajador_id',
        'prioridad'
    ];

    // Quién la autorizó (creó)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quién la está trabajando / actualizó el estado
    public function trabajador()
    {
        return $this->belongsTo(User::class, 'trabajador_id');
    }
}