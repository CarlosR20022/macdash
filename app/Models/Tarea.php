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
        'telefono'
    ];
}