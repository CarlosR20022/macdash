<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Carbon\Carbon; // IMPORTANTE: Librería para manejar fechas

class TareaController extends Controller
{
    // Mostrar la tabla con todas las tareas
    public function index(Request $request)
    {
        // Obtenemos la columna y el orden de la URL, por defecto: 'created_at' y 'desc'
        $sort_by = $request->get('sort_by', 'id'); 
        $order = $request->get('order', 'desc');

        // Validamos para evitar errores si alguien escribe algo raro en la URL
        $valid_columns = ['id', 'fecha_entrega', 'created_at'];
        $valid_orders = ['asc', 'desc'];

        if (!in_array($sort_by, $valid_columns)) {
            $sort_by = 'id';
        }
        if (!in_array($order, $valid_orders)) {
            $order = 'desc';
        }

        // Hacemos la consulta a la base de datos
        $tareas = Tarea::orderBy($sort_by, $order)->get();

        // Enviamos las variables a la vista para saber cómo están ordenados actualmente
        return view('welcome', compact('tareas', 'sort_by', 'order'));
    }

        // 1. Este método responde los detalles de una tarea en formato JSON
    public function show(Tarea $tarea)
    {
        // 1. FECHA DE TOMA FORMATEADA (Con texto y hora)
        $tarea->fecha_toma = \Carbon\Carbon::parse($tarea->created_at)->format('j') . ' de ' . ucfirst(\Carbon\Carbon::parse($tarea->created_at)->locale('es')->monthName) . ' ' . \Carbon\Carbon::parse($tarea->created_at)->format('Y, h:i a');
        
        // 2. FECHA DE ENTREGA FORMATEADA (Solo texto)
        $tarea->fecha_entrega_formateada = \Carbon\Carbon::parse($tarea->fecha_entrega)->format('j') . ' de ' . ucfirst(\Carbon\Carbon::parse($tarea->fecha_entrega)->locale('es')->monthName) . ' ' . \Carbon\Carbon::parse($tarea->fecha_entrega)->format('Y');
        
        // Devolvemos la tarea completa
        return response()->json($tarea);
    }

    // 2. Este método actualiza el estado de una tarea
    public function updateStatus(Request $request, Tarea $tarea)
    {
        $newStatus = $request->input('estado');
        $valid_statuses = ['Pendiente', 'En proceso', 'Terminado', 'Cancelado'];

        if (!in_array($newStatus, $valid_statuses)) {
            return response()->json(['error' => 'Estado no válido.'], 400);
        }

        $tarea->estado = $newStatus;
        $tarea->save();

        return response()->json(['success' => 'Estado actualizado con éxito.']);
    }

    // Guardar una nueva tarea (desde el botón "Nueva Tarea")
    public function store(Request $request)
    {
        $request->validate([
            'cliente' => 'required',
            'nombre_pedido' => 'required',
            'fecha_entrega' => 'required',
            'detalles_adicionales' => 'required',
        ]);

        Tarea::create($request->all());

        return redirect()->back()->with('success', 'Tarea creada con éxito');
    }
}