<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\HistorialTarea;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TareaController extends Controller
{
    public function index()
    {
        $tareas = Tarea::with(['usuario', 'trabajador'])->orderBy('id', 'desc')->get();
        $urgentes = collect(); // Aquí guardaremos las tareas críticas

        foreach ($tareas as $tarea) {
            if ($tarea->estado == 'Terminado') {
                $prioridad = 'Terminada';
            } elseif ($tarea->estado == 'Cancelado') {
                $prioridad = 'Cancelada';
            } else {
                $diasRestantes = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($tarea->fecha_entrega)->startOfDay(), false);
                $prioridad = $tarea->prioridad; 
                if (!$prioridad) { 
                    $prioridad = $diasRestantes <= 2 ? 'Alta' : ($diasRestantes <= 10 ? 'Media' : 'Baja');
                }
                
                // Si la prioridad es Alta, la agregamos a la lista de Urgencias
                if ($prioridad == 'Alta') {
                    $urgentes->push($tarea);
                }
            }
            $tarea->prioridad_calculada = $prioridad; 
        }

        // Obtenemos los últimos 50 movimientos del historial
        $historial = HistorialTarea::with(['usuario'])->orderBy('id', 'desc')->take(50)->get();

        return view('welcome', compact('tareas', 'urgentes', 'historial'));
    }

    public function show(Tarea $tarea)
    {
        $tarea->load(['usuario', 'trabajador']);
        $tarea->autorizo = $tarea->usuario ? $tarea->usuario->nombre_completo : 'Desconocido';
        $tarea->encargado = ($tarea->estado !== 'Pendiente' && $tarea->trabajador) ? $tarea->trabajador->nombre_completo : 'Aún sin asignar';
        $tarea->fecha_toma = Carbon::parse($tarea->created_at)->format('j') . ' de ' . ucfirst(Carbon::parse($tarea->created_at)->locale('es')->monthName) . ' ' . Carbon::parse($tarea->created_at)->format('Y, h:i a');
        $tarea->fecha_entrega_formateada = Carbon::parse($tarea->fecha_entrega)->format('j') . ' de ' . ucfirst(Carbon::parse($tarea->fecha_entrega)->locale('es')->monthName) . ' ' . Carbon::parse($tarea->fecha_entrega)->format('Y');

        if ($tarea->estado == 'Terminado') {
            $prioridad = 'Terminada';
        } elseif ($tarea->estado == 'Cancelado') {
            $prioridad = 'Cancelada';
        } else {
            $diasRestantes = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($tarea->fecha_entrega)->startOfDay(), false);
            $prioridad = $tarea->prioridad;
            if (!$prioridad) {
                $prioridad = $diasRestantes <= 2 ? 'Alta' : ($diasRestantes <= 10 ? 'Media' : 'Baja');
            }
        }
        $tarea->prioridad_calculada = $prioridad;

        return response()->json($tarea);
    }

    public function updateStatus(Request $request, Tarea $tarea)
    {
        $newStatus = $request->input('estado');
        $valid_statuses = ['Pendiente', 'En proceso', 'Terminado', 'Cancelado'];

        if (!in_array($newStatus, $valid_statuses)) return response()->json(['error' => 'Estado no válido.'], 400);

        $estadoAnterior = $tarea->estado;
        $tarea->estado = $newStatus;

        if ($newStatus == 'Pendiente') {
            $tarea->trabajador_id = null;
        } else {
            $tarea->trabajador_id = auth()->id(); 
        }
        $tarea->save();

        // MAGIA: Guardamos el cambio en el historial automáticamente
        HistorialTarea::create([
            'tarea_id' => $tarea->id,
            'user_id' => auth()->id(),
            'accion' => 'Cambio de Estado',
            'detalles' => "cambió la tarea a '{$newStatus}'"
        ]);

        return response()->json(['success' => 'Estado actualizado.']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente' => 'required',
            'nombre_pedido' => 'required',
            'fecha_entrega' => 'required',
        ]);

        $datos = $request->all();
        $datos['estado'] = 'Pendiente';
        $datos['user_id'] = auth()->id();
        
        if (isset($datos['prioridad']) && $datos['prioridad'] == 'Automatica') {
            $datos['prioridad'] = null;
        }

        $tareaNueva = Tarea::create($datos);

        // MAGIA: Guardamos la creación de la tarea en el historial
        HistorialTarea::create([
            'tarea_id' => $tareaNueva->id,
            'user_id' => auth()->id(),
            'accion' => 'Creación',
            'detalles' => "creó la nueva tarea"
        ]);

        return redirect()->back()->with('success', 'Tarea creada con éxito');
    }
}