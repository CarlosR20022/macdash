<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>MacDash - Panel Principal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .tabla-macdash th, .tabla-macdash td { border: 1px solid #e2e8f0; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#111827] text-white flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#1F2937] border-r-2 border-white flex flex-col pt-2 px-6 pb-6 shrink-0 z-10">
        <div class="mb-1 flex flex-col items-center mt-[-10px]">
            <img src="{{ asset('img/MacDash.png') }}" alt="MacDash Logo" class="w-45 h-45 object-contain mb-0">
        </div>
        
        <nav class="space-y-6 mt-4">
            <button onclick="toggleModal('modalNuevaTarea')" class="w-full bg-[#5A8BB5] hover:bg-[#4A7A9E] border-[3px] border-slate-900 rounded-xl p-4 flex flex-col items-center justify-center transition-all shadow-[0_4px_0_rgb(15,23,42)] active:translate-y-1 active:shadow-none">
                <img src="{{ asset('img/tarea.png') }}" alt="Nueva Tarea" class="w-14 h-14 mb-2">
                <span class="text-white font-bold text-lg">Nueva tarea</span>
            </button>
            
            <button onclick="cambiarVista('calendario')" class="w-full bg-[#5A8BB5] hover:bg-[#4A7A9E] border-[3px] border-slate-900 rounded-xl p-4 flex flex-col items-center justify-center transition-all shadow-[0_4px_0_rgb(15,23,42)] active:translate-y-1 active:shadow-none">
                <img src="{{ asset('img/calendario.png') }}" alt="Calendario" class="w-14 h-14 mb-2">
                <span class="text-white font-bold text-lg">Calendario</span>
            </button>
            
            <button onclick="cambiarVista('tabla')" class="w-full bg-[#5A8BB5] hover:bg-[#4A7A9E] border-[3px] border-slate-900 rounded-xl p-4 flex flex-col items-center justify-center transition-all shadow-[0_4px_0_rgb(15,23,42)] active:translate-y-1 active:shadow-none">
                <img src="{{ asset('img/registro.png') }}" alt="Registros" class="w-14 h-14 mb-2">
                <span class="text-white font-bold text-lg">Registros</span>
            </button>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 bg-[#0F172A] relative h-screen">
        
        <header class="h-24 border-b-2 border-white flex items-center justify-between px-8 bg-[#0F172A] z-10 shrink-0">
            
            <div class="flex-none">
                <input type="text" id="buscadorTareas" placeholder="BUSCAR ID O CLIENTE" class="bg-slate-200 text-slate-800 placeholder-slate-500 rounded-lg px-4 py-2.5 w-[280px] focus:outline-none focus:ring-2 focus:ring-sky-500 font-bold text-sm transition-all">
            </div>
            
            <div class="flex items-center gap-6">
                
                @if(Auth::user()->role_id == 1)
                <button onclick="toggleModal('modalNuevoUsuario')" class="bg-[#10B981] hover:bg-[#059669] text-black border-2 border-black rounded-md px-4 py-2.5 text-sm font-bold flex items-center gap-2 shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none transition-all whitespace-nowrap">
                    👤 Trabajadores
                </button>
                @endif
                
                <button onclick="toggleModal('modalHistorial')" class="bg-slate-800 hover:bg-slate-700 text-white border border-slate-600 rounded-md px-4 py-2.5 text-sm font-bold flex items-center gap-2 shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none transition-all whitespace-nowrap">
                    📜 Historial
                </button>

                <span id="reloj-macdash" class="text-base font-bold whitespace-nowrap min-w-[70px] text-center text-slate-200">
                    Cargando...
                </span>
                
                <div class="h-8 w-px bg-slate-600"></div>
                
                <div class="flex items-center gap-3 cursor-pointer hover:bg-slate-800 p-1.5 rounded-lg transition-colors" onclick="toggleModal('modalUrgencias')" title="Ver Tareas Urgentes">
                    
                    <div class="relative shrink-0 flex items-center justify-center">
                        <img src="{{ asset('img/perfil.png') }}" alt="Avatar" class="w-10 h-10 rounded-full border-2 border-slate-400 object-cover shrink-0">
                        
                        @if(count($urgentes) > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4 z-10">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-[#0F172A]"></span>
                            </span>
                        @endif
                    </div>
                    
                    <span class="font-bold text-sm whitespace-nowrap max-w-[150px] truncate">
                        {{ Auth::user()->nombre_completo ?? Auth::user()->name }}
                    </span>                    
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0 flex items-center">
                    @csrf
                    <button type="submit" class="text-sm text-red-400 hover:text-red-300 font-bold underline whitespace-nowrap">
                        Salir
                    </button>
                </form>

            </div>
        </header>

        <section class="p-6 flex-1 overflow-hidden relative flex flex-col">
            @if(session('success'))
                <div id="mensaje-exito" class="absolute top-4 left-1/2 transform -translate-x-1/2 z-50 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-xl font-bold text-base transition-opacity duration-500">
                    {{ session('success') }}
                </div>
            @endif

            <div id="vista-tabla" class="bg-transparent overflow-auto h-full transition-all duration-300 flex flex-col">
                <table class="w-full text-center border-collapse tabla-macdash bg-transparent">
                    <thead class="bg-slate-200 text-slate-800 text-base sticky top-0 z-10">
                        <tr>
                            <th class="py-3 px-4 font-bold w-24">ID</th>
                            <th class="py-3 px-4 font-bold">Tarea / Cliente</th>
                            <th class="py-3 px-5 font-bold w-20">Prioridad</th>
                            <th class="py-3 px-4 font-bold">Fecha entrega</th>
                            <th class="py-3 px-4 font-bold w-32">Detalles</th>
                            <th class="py-3 px-4 font-bold w-40">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-100 text-base">
                        @forelse($tareas as $tarea)
                        <tr class="fila-tarea hover:bg-slate-800/50 transition-colors" data-id="{{ $tarea->id }}" data-cliente="{{ strtolower($tarea->cliente) }}" data-prioridad="{{ $tarea->prioridad_calculada }}">
                            
                            <td class="py-2.5 px-4 border-slate-400 border font-bold text-slate-300">
                                #{{ $tarea->id }}
                            </td>
                            
                            <td class="py-2.5 px-4 border-slate-400 border text-left px-6">
                                <span class="font-bold text-white block truncate">{{ $tarea->nombre_pedido }}</span>
                                <span class="text-xs text-sky-400 font-medium truncate">Cliente: {{ $tarea->cliente }}</span>
                            </td>
                            
                            <td class="py-2.5 px-1 border-slate-400 border text-center">
                                @php
                                    $pColor = 'bg-emerald-500/20 text-emerald-400 border-emerald-500/50'; 
                                    if($tarea->prioridad_calculada == 'Alta') $pColor = 'bg-red-500/20 text-red-400 border-red-500/50';
                                    if($tarea->prioridad_calculada == 'Media') $pColor = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50';
                                    if($tarea->prioridad_calculada == 'Terminada') $pColor = 'bg-[#4ade80]/20 text-[#4ade80] border-[#4ade80]/50'; 
                                    if($tarea->prioridad_calculada == 'Cancelada') $pColor = 'bg-slate-500/20 text-slate-400 border-slate-500/50';
                                @endphp
                                <span class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest border {{ $pColor }}">
                                    {{ $tarea->prioridad_calculada }}
                                </span>
                            </td>
                            
                            <td class="py-2.5 px-4 border-slate-400 border text-sm font-medium">
                                {{ \Carbon\Carbon::parse($tarea->fecha_entrega)->format('j') }} de {{ ucfirst(\Carbon\Carbon::parse($tarea->fecha_entrega)->locale('es')->monthName) }}
                            </td>
                            
                            <td class="py-2.5 px-4 border-slate-400 border text-center">
                                <button onclick="abrirDetalles({{ $tarea->id }})" class="underline decoration-white hover:text-sky-300 transition-colors text-sm">
                                    Detalles
                                </button>
                            </td>
                            
                            <td class="py-2.5 px-4 border-slate-400 border text-center relative">
                                <button onclick="cambiarEstado({{ $tarea->id }}, this)" id="statusButton_tabla_{{ $tarea->id }}" class="px-3 py-1 text-xs font-bold text-black border-2 border-black rounded shadow-[2px_2px_0_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-[1px_1px_0_rgba(0,0,0,1)] {{ $tarea->estado == 'Terminado' ? 'bg-[#4ade80]' : '' }} {{ $tarea->estado == 'Pendiente' ? 'bg-[#fb923c]' : '' }} {{ $tarea->estado == 'En proceso' ? 'bg-[#38bdf8]' : '' }} {{ $tarea->estado == 'Cancelado' ? 'bg-slate-400' : '' }}">
                                    {{ $tarea->estado }}
                                </button>
                            </td>
                        </tr>
                        @empty
                        @for($i=0; $i<6; $i++)
                        <tr class="fila-vacia">
                            <td class="py-4 border-slate-400 border text-transparent">-</td>
                            <td class="border-slate-400 border"></td>
                            <td class="border-slate-400 border"></td>
                            <td class="border-slate-400 border"></td>
                            <td class="border-slate-400 border"></td>
                            <td class="border-slate-400 border"></td>
                        </tr>
                        @endfor
                        @endforelse
                    </tbody>
                </table>
                <div id="controles-paginacion" class="mt-auto pt-4 pb-2"></div>
            </div>

            <div id="vista-calendario" class="hidden h-full flex flex-col bg-[#1E293B] rounded-xl border border-slate-600 shadow-2xl p-4 transition-all duration-300">
                <div class="flex justify-between items-center mb-3 bg-slate-800 p-3 rounded-lg border border-slate-700 shrink-0">
                    <button onclick="cambiarMes(-1)" class="bg-[#5A8BB5] hover:bg-[#4A7A9E] text-white px-4 py-1.5 rounded font-bold shadow-md transition-all active:scale-95 text-sm">
                        ◀ Anterior
                    </button>
                    <h2 id="mes-anio-texto" class="text-2xl font-black uppercase tracking-widest text-white drop-shadow-md">
                        MES AÑO
                    </h2>
                    <button onclick="cambiarMes(1)" class="bg-[#5A8BB5] hover:bg-[#4A7A9E] text-white px-4 py-1.5 rounded font-bold shadow-md transition-all active:scale-95 text-sm">
                        Siguiente ▶
                    </button>
                </div>
                <div class="grid grid-cols-7 gap-2 text-center font-bold text-slate-400 uppercase text-xs mb-2 shrink-0">
                    <div class="text-red-400">Dom</div>
                    <div>Lun</div>
                    <div>Mar</div>
                    <div>Mié</div>
                    <div>Jue</div>
                    <div>Vie</div>
                    <div class="text-sky-400">Sáb</div>
                </div>
                <div id="cuadricula-calendario" class="grid grid-cols-7 gap-1.5 flex-1 auto-rows-fr"></div>
            </div>
        </section>
    </main>

    <div id="modalNuevaTarea" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-[#1F2937] w-full max-w-lg rounded-xl border-2 border-slate-600 shadow-2xl p-6">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <img src="{{ asset('img/tarea.png') }}" class="w-6 h-6"> Nueva Tarea
                </h3>
                <button onclick="toggleModal('modalNuevaTarea')" class="text-slate-400 hover:text-white font-bold text-xl">✕</button>
            </div>
            
            <form action="{{ route('tareas.store') }}" method="POST" class="space-y-4">
                @csrf 
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-300 mb-1">Nombre del Cliente</label>
                        <input type="text" name="cliente" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Pedido (Ej: Lona)</label>
                        <input type="text" name="nombre_pedido" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Fecha Entrega</label>
                        <input type="date" id="inputFechaEntrega" name="fecha_entrega" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white color-scheme-dark">
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-300 mb-1 text-sky-400">Prioridad (Opcional)</label>
                        <select name="prioridad" class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-sky-500 font-bold">
                            <option value="Automatica" selected>🤖 Automática (Calculada por fecha)</option>
                            <option value="Baja">🟢 Baja</option>
                            <option value="Media">🟡 Media</option>
                            <option value="Alta">🔴 Alta</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Detalles Adicionales</label>
                    <textarea name="detalles_adicionales" rows="2" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white"></textarea>
                </div>
                
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-[#4ade80] hover:bg-[#22c55e] text-black border-2 border-black shadow-[2px_2px_0_rgba(0,0,0,1)] px-6 py-2 rounded font-bold transition-transform active:translate-y-1 active:shadow-none">
                        GUARDAR TAREA
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalListaTareasDia" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-[55] overflow-y-auto p-4">
        <div class="bg-[#1E293B] w-full max-w-5xl rounded-2xl border border-slate-700 shadow-2xl p-8 my-auto transform transition-all">
            <div class="flex justify-between items-center mb-6 border-b border-slate-700 pb-4">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    📅 Tareas del <span id="tituloModalDia" class="text-sky-400 ml-1"></span>
                </h3>
                <button onclick="toggleModal('modalListaTareasDia')" class="text-slate-400 hover:text-white font-bold text-2xl">✕</button>
            </div>
            
            <div id="contenedorTareasDia" class="flex flex-wrap justify-center gap-6 max-h-[60vh] overflow-y-auto p-4 no-scrollbar"></div>
            
            <div class="flex justify-end mt-6 pt-4 border-t border-slate-700">
                <button type="button" onclick="toggleModal('modalListaTareasDia')" class="px-6 py-2.5 bg-slate-700 hover:bg-slate-600 rounded text-white text-xs font-bold uppercase tracking-wider transition-colors">
                    CERRAR
                </button>
            </div>
        </div>
    </div>

    <div id="modalDetallesTarea" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-[60] p-4">
        <div class="bg-[#1E293B] w-full max-w-3xl rounded-2xl border border-slate-600 shadow-2xl flex flex-col overflow-hidden transform transition-all scale-95">
            
            <div class="bg-slate-800 border-b border-slate-700 p-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="bg-sky-500/20 p-2.5 rounded-xl border border-sky-500/50">
                        <img src="{{ asset('img/MacDash.png') }}" class="w-8 h-8 object-contain drop-shadow-lg">
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white leading-none tracking-tight">Ficha Técnica <span id="detId" class="text-sky-400 ml-1"></span></h3>
                        <div class="flex gap-2 mt-1.5">
                            <p id="detStatusBadge" class="text-[10px] font-bold uppercase tracking-widest inline-block px-2.5 py-0.5 rounded border"></p>
                            <p id="detPriBadge" class="text-[10px] font-bold uppercase tracking-widest inline-block px-2.5 py-0.5 rounded border"></p>
                        </div>
                    </div>
                </div>
                <button onclick="toggleModal('modalDetallesTarea')" class="text-slate-500 hover:text-white font-bold text-2xl transition-colors bg-slate-900/50 hover:bg-slate-700 w-10 h-10 rounded-full flex items-center justify-center border border-slate-700">✕</button>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 bg-[#0F172A]">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 flex items-center gap-1.5"><span class="text-lg">🏢</span> Cliente</label>
                        <p id="detCliente" class="text-white text-lg font-semibold bg-slate-800/80 px-4 py-3 rounded-lg border border-slate-700/50 shadow-inner"></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-800/80 p-3 rounded-lg border border-slate-700/50 shadow-inner">
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-1">Fecha de Toma</label>
                            <p id="detFechaToma" class="text-slate-300 text-xs font-medium"></p>
                        </div>
                        <div class="bg-sky-900/20 p-3 rounded-lg border border-sky-700/50 shadow-inner">
                            <label class="block text-[10px] font-black uppercase text-sky-500 mb-1">Fecha Entrega</label>
                            <p id="detFechaEntrega" class="text-sky-300 text-sm font-bold"></p>
                        </div>
                    </div>
                    <div class="space-y-3 pt-4 border-t border-slate-700/50">
                        <div class="flex items-center justify-between bg-slate-800/80 p-3.5 rounded-lg border border-slate-700/50">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-yellow-500/20 flex items-center justify-center border border-yellow-500/50"><span class="text-[10px]">👤</span></div>
                                <span class="text-[10px] font-black uppercase text-yellow-500 tracking-wider">Autorizó</span>
                            </div>
                            <p id="detAutorizo" class="text-white font-bold text-xs"></p>
                        </div>
                        <div class="flex items-center justify-between bg-slate-800/80 p-3.5 rounded-lg border border-slate-700/50">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-sky-500/20 flex items-center justify-center border border-sky-500/50"><span class="text-[10px]">⚙️</span></div>
                                <span class="text-[10px] font-black uppercase text-sky-400 tracking-wider">Encargado</span>
                            </div>
                            <p id="detEncargado" class="text-white font-bold text-xs"></p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col h-full bg-slate-800/30 rounded-xl p-5 border border-slate-700/50 shadow-inner">
                    <div class="mb-5 pb-5 border-b border-slate-700/50">
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-1.5 flex items-center gap-1.5"><span class="text-lg">🏷️</span> Nombre del Pedido</label>
                        <p id="detPedido" class="text-white text-xl font-black leading-tight drop-shadow-sm"></p>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Descripción y Detalles Adicionales</label>
                        <div class="flex-1 bg-slate-900/80 p-4 rounded-xl border border-slate-700 overflow-y-auto custom-scroll min-h-[120px]">
                            <p id="detDesc" class="text-slate-300 text-sm whitespace-pre-line leading-relaxed font-medium"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-800 border-t border-slate-700 p-5 flex justify-end gap-3">
                <button type="button" onclick="toggleModal('modalDetallesTarea')" class="px-8 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold uppercase tracking-widest rounded transition-colors border border-black shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none">
                    CERRAR FICHA
                </button>
            </div>
        </div>
    </div>

    <div id="modalUrgencias" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-[70] p-4">
        <div class="bg-[#1E293B] w-full max-w-5xl rounded-2xl border border-red-600 shadow-[0_0_50px_rgba(220,38,38,0.3)] flex flex-col overflow-hidden transform transition-all">
            
            <div class="bg-red-900/40 border-b border-red-500/50 p-6 flex justify-between items-center">
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <span class="text-3xl animate-pulse">🚨</span> Tareas Pendientes y de Urgencia
                </h3>
                <button onclick="toggleModal('modalUrgencias')" class="text-red-300 hover:text-white font-bold text-2xl w-10 h-10 bg-red-900/50 hover:bg-red-800 rounded-full flex items-center justify-center transition-colors border border-red-500/50">✕</button>
            </div>
            
            <div class="p-8 max-h-[70vh] overflow-y-auto no-scrollbar bg-[#0F172A] flex flex-wrap justify-center gap-6">
                @forelse($urgentes as $tarea)
                    <div class="w-full sm:w-80 shrink-0 rounded-xl border-2 border-red-500/50 bg-red-500/5 p-6 flex flex-col gap-3 relative shadow-lg">
                        <div class="absolute top-4 left-4 text-[10px] font-black uppercase px-2.5 py-1 rounded border border-red-500 text-red-400 bg-red-900/30 shadow-[0_0_10px_rgba(239,68,68,0.5)]">
                            ALTA PRIORIDAD
                        </div>
                        <div class="absolute top-4 right-4 text-[10px] font-black uppercase px-2.5 py-1 rounded border {{ $tarea->estado == 'Pendiente' ? 'border-[#fb923c] text-[#fb923c]' : 'border-[#38bdf8] text-[#38bdf8]' }}">
                            {{ $tarea->estado }}
                        </div>
                        
                        <div class="pt-8 pr-2">
                            <p class="text-[10px] uppercase text-slate-400 font-bold mb-0.5">Cliente</p>
                            <p class="font-black text-white text-xl leading-tight truncate">{{ $tarea->cliente }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-slate-400 font-bold mb-0.5">Pedido</p>
                            <p class="text-slate-300 font-bold truncate">{{ $tarea->nombre_pedido }}</p>
                        </div>
                        
                        <div class="mt-auto pt-4 flex gap-2">
                            <button onclick="toggleModal('modalUrgencias'); abrirDetalles({{ $tarea->id }})" class="flex-1 bg-red-600 hover:bg-red-500 text-white text-xs font-black py-2.5 rounded transition-colors border border-black shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none tracking-widest">
                                VER FICHA URGENTE
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 w-full">
                        <span class="text-6xl mb-6">🙌</span>
                        <p class="text-emerald-400 text-2xl font-black tracking-wide">¡Todo bajo control!</p>
                        <p class="text-slate-400 font-medium mt-2">No tienes tareas con prioridad alta pendientes.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="modalHistorial" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-[70] p-4">
        <div class="bg-[#1E293B] w-full max-w-2xl rounded-2xl border border-slate-600 shadow-2xl flex flex-col overflow-hidden transform transition-all">
            <div class="bg-slate-800 border-b border-slate-700 p-6 flex justify-between items-center">
                <h3 class="text-xl font-black text-white flex items-center gap-2">📜 Historial de Cambios</h3>
                <button onclick="toggleModal('modalHistorial')" class="text-slate-500 hover:text-white font-bold text-2xl bg-slate-900/50 hover:bg-slate-700 w-10 h-10 rounded-full flex items-center justify-center border border-slate-700 transition-colors">✕</button>
            </div>
            
            <div class="p-6 max-h-[60vh] overflow-y-auto no-scrollbar bg-[#0F172A] space-y-3">
                @forelse($historial as $registro)
                    <div class="bg-slate-800/80 p-4 rounded-xl border border-slate-700/50 flex gap-4 items-center shadow-sm hover:border-slate-500 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-slate-700/80 border border-slate-600 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                            {{ $registro->accion == 'Creación' ? '📝' : '🔄' }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-200 font-medium">
                                <span class="font-black text-sky-400">{{ $registro->usuario->name ?? 'Usuario Eliminado' }}</span>
                                {{ $registro->detalles }} 
                                @if($registro->tarea_id)
                                    <span class="font-bold text-white bg-slate-900 px-1.5 py-0.5 rounded border border-slate-600 ml-1">#{{ $registro->tarea_id }}</span>
                                @endif
                            </p>
                            <p class="text-[10px] font-bold uppercase text-slate-500 mt-1.5 flex items-center gap-1.5">
                                <span class="bg-slate-900/50 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($registro->created_at)->diffForHumans() }}</span>
                                <span>•</span>
                                <span>{{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y h:i A') }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 text-center py-10 font-bold text-lg">Aún no hay registros en el historial.</p>
                @endforelse
            </div>
            
            <div class="bg-slate-800 border-t border-slate-700 p-4 flex justify-end">
                <button type="button" onclick="toggleModal('modalHistorial')" class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold uppercase tracking-widest rounded border border-black shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none transition-all">
                    CERRAR HISTORIAL
                </button>
            </div>
        </div>
    </div>

    <div id="modalNuevoUsuario" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-[#1F2937] w-full max-w-lg rounded-xl border-2 border-slate-600 shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">👤 Registrar Trabajador</h3>
                <button onclick="toggleModal('modalNuevoUsuario')" class="text-slate-400 hover:text-white font-bold text-xl">✕</button>
            </div>
            <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
                @csrf 
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-300 mb-1">Nombre(s)</label>
                        <input type="text" name="name" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Usuario (Login)</label>
                        <input type="text" name="username" required placeholder="Ej. juanperez" class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Contraseña</label>
                        <input type="password" name="password" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-sky-500">
                    </div>
                </div>
                <div class="flex justify-between mt-4">
                    <button type="button" onclick="toggleModal('modalNuevoUsuario'); toggleModal('modalListaUsuarios')" class="bg-[#38bdf8] hover:bg-[#0284c7] text-black border-2 border-black shadow-[2px_2px_0_rgba(0,0,0,1)] px-4 py-2 rounded font-bold transition-transform active:translate-y-1 active:shadow-none">
                        👁️ VER TRABAJADORES
                    </button>
                    <button type="submit" class="bg-[#4ade80] hover:bg-[#22c55e] text-black border-2 border-black shadow-[2px_2px_0_rgba(0,0,0,1)] px-6 py-2 rounded font-bold transition-transform active:translate-y-1 active:shadow-none">
                        GUARDAR TRABAJADOR
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalListaUsuarios" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-[#1F2937] w-full max-w-4xl rounded-xl border-2 border-slate-600 shadow-2xl p-6 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">👥 Lista de Trabajadores</h3>
                <button onclick="toggleModal('modalListaUsuarios')" class="text-slate-400 hover:text-white font-bold text-xl">✕</button>
            </div>
            <div class="overflow-x-auto bg-slate-800 rounded-lg border border-slate-600">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-700 text-slate-300 text-sm">
                        <tr>
                            <th class="p-3 border-b border-slate-600 text-center">Acciones</th>
                            <th class="p-3 border-b border-slate-600">ID</th>
                            <th class="p-3 border-b border-slate-600">Rol</th>
                            <th class="p-3 border-b border-slate-600">Nombre Completo</th>
                            <th class="p-3 border-b border-slate-600">Usuario (Login)</th>
                            <th class="p-3 border-b border-slate-600">Contraseña</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-200 text-sm">
                        @php $listaUsuarios = \App\Models\User::all(); @endphp 
                        @foreach($listaUsuarios as $user) 
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="p-3 border-b border-slate-600/50 text-center">
                                <button onclick="editarUsuario({{ $user->id }}, '{{ $user->name }}', '{{ $user->apellido_paterno }}', '{{ $user->apellido_materno }}', '{{ $user->username }}')" class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 hover:bg-yellow-500 hover:text-black px-3 py-1 rounded text-xs font-bold transition-colors shadow-sm">
                                    ✏️ Editar
                                </button>
                            </td>
                            <td class="p-3 border-b border-slate-600/50">#{{ $user->id }}</td>
                            <td class="p-3 border-b border-slate-600/50">
                                @if($user->role_id == 1) 
                                    <span class="bg-purple-500/20 text-purple-400 border border-purple-500/50 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">Superadmin</span> 
                                @else 
                                    <span class="bg-sky-500/20 text-sky-400 border border-sky-500/50 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider">Trabajador</span> 
                                @endif
                            </td>
                            <td class="p-3 border-b border-slate-600/50 font-medium">{{ $user->name }} {{ $user->apellido_paterno }} {{ $user->apellido_materno }}</td>
                            <td class="p-3 border-b border-slate-600/50 font-mono text-emerald-400 font-bold">{{ $user->username }}</td>
                            <td class="p-3 border-b border-slate-600/50 text-slate-500 tracking-widest">********</td>
                        </tr> 
                        @endforeach 
                    </tbody>
                </table>
            </div>
            <div class="flex justify-start mt-6">
                <button type="button" onclick="toggleModal('modalListaUsuarios'); toggleModal('modalNuevoUsuario')" class="text-slate-400 hover:text-white text-sm font-bold uppercase underline">
                    ← Regresar a Registro
                </button>
            </div>
        </div>
    </div>

    <div id="modalEditarUsuario" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-[60]">
        <div class="bg-[#1F2937] w-full max-w-lg rounded-xl border-2 border-yellow-600 shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">✏️ Editar Trabajador</h3>
                <button onclick="toggleModal('modalEditarUsuario')" class="text-slate-400 hover:text-white font-bold text-xl">✕</button>
            </div>
            <form id="formEditarUsuario" method="POST" class="space-y-4">
                @csrf 
                @method('PUT') 
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-300 mb-1">Nombre(s)</label>
                        <input type="text" id="edit_name" name="name" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Apellido Paterno</label>
                        <input type="text" id="edit_paterno" name="apellido_paterno" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Apellido Materno</label>
                        <input type="text" id="edit_materno" name="apellido_materno" class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Usuario (Para Login)</label>
                        <input type="text" id="edit_username" name="username" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white outline-none focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1 text-yellow-400">Nueva Contraseña</label>
                        <input type="password" name="password" placeholder="Dejar en blanco para no cambiar" class="w-full bg-slate-800 border border-yellow-600/50 rounded p-2 text-white outline-none focus:border-yellow-500 placeholder-slate-500 text-xs">
                    </div>
                </div>
                <div class="flex justify-between mt-6">
                    <button type="button" onclick="toggleModal('modalEditarUsuario'); toggleModal('modalListaUsuarios')" class="text-slate-400 hover:text-white text-sm font-bold uppercase underline">
                        ← Cancelar
                    </button>
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-black border-2 border-black shadow-[2px_2px_0_rgba(0,0,0,1)] px-6 py-2 rounded font-bold transition-transform active:translate-y-1 active:shadow-none">
                        ACTUALIZAR DATOS
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="statusPopup" class="absolute bg-[#1F2937] border-2 border-black rounded-lg shadow-[4px_4px_0_rgba(0,0,0,1)] hidden z-[70] p-2 space-y-2 w-48 text-center" data-task-id="">
        <button onclick="actualizarEstado('Pendiente')" class="w-full bg-[#fb923c] text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">PENDIENTE</button>
        <button onclick="actualizarEstado('En proceso')" class="w-full bg-[#38bdf8] text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">EN PROCESO</button>
        <button onclick="actualizarEstado('Terminado')" class="w-full bg-[#4ade80] text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">TERMINADO</button>
        <button onclick="actualizarEstado('Cancelado')" class="w-full bg-slate-400 text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">CANCELADO</button>
    </div>

    <script>
    const tareasData = @json($tareas);
    let tareasFiltradas = tareasData; 
    let fechaCalendario = new Date();
    let currentPage = 1;
    const itemsPerPage = 5;
    let filasTotalesTabla = [];

    document.addEventListener("DOMContentLoaded", function() {
        filasTotalesTabla = Array.from(document.querySelectorAll('.fila-tarea'));
        aplicarFiltroYPaginacion();
        
        const mensajeExito = document.getElementById('mensaje-exito');
        if (mensajeExito) {
            setTimeout(() => { 
                mensajeExito.style.opacity = '0'; 
                setTimeout(() => { mensajeExito.remove(); }, 500); 
            }, 3000); 
        }
        
        const inputFecha = document.getElementById('inputFechaEntrega');
        if (inputFecha) { 
            const hoyLocal = new Date(); 
            const localTodayStr = `${hoyLocal.getFullYear()}-${String(hoyLocal.getMonth() + 1).padStart(2, '0')}-${String(hoyLocal.getDate()).padStart(2, '0')}`; 
            inputFecha.min = localTodayStr; 
        }
    });

    function aplicarFiltroYPaginacion() {
        const term = document.getElementById('buscadorTareas').value.toLowerCase().trim();
        document.querySelectorAll('.fila-vacia').forEach(f => f.style.display = 'none');
        
        let filasCoincidentes = filasTotalesTabla.filter(fila => { 
            const id = fila.dataset.id; 
            const cliente = fila.dataset.cliente; 
            return id.includes(term) || cliente.includes(term); 
        });
        
        filasTotalesTabla.forEach(fila => fila.style.display = 'none');
        
        const startIndex = (currentPage - 1) * itemsPerPage; 
        const endIndex = startIndex + itemsPerPage; 
        const filasPaginaActual = filasCoincidentes.slice(startIndex, endIndex);
        
        filasPaginaActual.forEach(fila => fila.style.display = '');
        renderizarControlesPaginacion(filasCoincidentes.length, filasCoincidentes, startIndex, endIndex);
    }

    function renderizarControlesPaginacion(totalItems, filasCoincidentes, startIndex, endIndex) {
        const contenedor = document.getElementById('controles-paginacion');
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        if (totalPages <= 1) { 
            contenedor.innerHTML = ''; 
            return; 
        }
        
        let alertaAnterior = false; 
        for (let i = 0; i < startIndex; i++) { 
            if (filasCoincidentes[i].dataset.prioridad === 'Alta') alertaAnterior = true; 
        }
        
        let alertaSiguiente = false; 
        for (let i = endIndex; i < filasCoincidentes.length; i++) { 
            if (filasCoincidentes[i].dataset.prioridad === 'Alta') alertaSiguiente = true; 
        }
        
        const htmlPuntoRojo = `<span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 z-10"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border border-white"></span></span>`;
        
        contenedor.innerHTML = `
            <div class="flex items-center gap-6 justify-center">
                <button onclick="cambiarPagina(-1)" class="relative px-5 py-2 bg-[#5A8BB5] hover:bg-[#4A7A9E] text-white rounded font-bold transition-all shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed border border-black" ${currentPage === 1 ? 'disabled' : ''}>
                    ◀ Anterior ${alertaAnterior && currentPage > 1 ? htmlPuntoRojo : ''}
                </button>
                <span class="text-slate-400 font-bold text-sm bg-slate-800 px-4 py-2 rounded border border-slate-700">
                    Página <span class="text-white">${currentPage}</span> de ${totalPages}
                </span>
                <button onclick="cambiarPagina(1)" class="relative px-5 py-2 bg-[#5A8BB5] hover:bg-[#4A7A9E] text-white rounded font-bold transition-all shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed border border-black" ${currentPage === totalPages ? 'disabled' : ''}>
                    Siguiente ▶ ${alertaSiguiente && currentPage < totalPages ? htmlPuntoRojo : ''}
                </button>
            </div>
        `;
    }

    function cambiarPagina(direccion) { 
        currentPage += direccion; 
        aplicarFiltroYPaginacion(); 
    }

    document.getElementById('buscadorTareas').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim(); 
        currentPage = 1; 
        aplicarFiltroYPaginacion();
        
        if(term === '') { 
            tareasFiltradas = tareasData; 
        } else { 
            tareasFiltradas = tareasData.filter(t => String(t.id).includes(term) || t.cliente.toLowerCase().includes(term)); 
        }
        
        if(!document.getElementById('vista-calendario').classList.contains('hidden')){ 
            generarCalendario(); 
        }
    });
    
    function cambiarVista(vista) {
        const divTabla = document.getElementById('vista-tabla'); 
        const divCalendario = document.getElementById('vista-calendario');
        
        if (vista === 'calendario') { 
            divTabla.classList.add('hidden'); 
            divCalendario.classList.remove('hidden'); 
            generarCalendario(); 
        } else { 
            divCalendario.classList.add('hidden'); 
            divTabla.classList.remove('hidden'); 
            divTabla.classList.add('flex'); 
        }
    }

    function generarCalendario() {
        const mesAnioTexto = document.getElementById('mes-anio-texto'); 
        const cuadricula = document.getElementById('cuadricula-calendario');
        const anio = fechaCalendario.getFullYear(); 
        const mes = fechaCalendario.getMonth(); 
        
        mesAnioTexto.innerText = new Intl.DateTimeFormat('es-MX', { month: 'long', year: 'numeric' }).format(fechaCalendario); 
        cuadricula.innerHTML = '';
        
        const primerDia = new Date(anio, mes, 1).getDay(); 
        const diasEnMes = new Date(anio, mes + 1, 0).getDate();
        const hoy = new Date(); 
        const hoyStr = `${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, '0')}-${String(hoy.getDate()).padStart(2, '0')}`;
        
        for (let i = 0; i < primerDia; i++) {
            cuadricula.innerHTML += `<div class="bg-[#0F172A]/50 rounded-lg border border-slate-800/50"></div>`;
        }
        
        for (let i = 1; i <= diasEnMes; i++) {
            const fechaStr = `${anio}-${String(mes + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            const tareasDelDia = tareasFiltradas.filter(t => t.fecha_entrega === fechaStr); 
            let htmlBotonTareas = '';
            
            if (tareasDelDia.length > 0) { 
                let textoTarea = tareasDelDia.length === 1 ? 'Tarea' : 'Tareas'; 
                htmlBotonTareas = `<div onclick="abrirListaDia('${fechaStr}')" class="mt-2 bg-[#5A8BB5]/20 border border-[#5A8BB5]/50 text-sky-200 rounded p-1.5 text-center cursor-pointer text-xs font-bold shadow-sm hover:bg-[#5A8BB5] hover:text-white hover:scale-105 transition-all">📋 ${tareasDelDia.length} ${textoTarea}</div>`; 
            }
            
            const esHoy = (hoyStr === fechaStr); 
            const claseHoy = esHoy ? 'border-sky-500 shadow-[0_0_10px_rgba(56,189,248,0.3)] bg-slate-800/80' : 'border-slate-700 bg-slate-800/40';
            
            cuadricula.innerHTML += `<div class="p-1.5 rounded-lg border flex flex-col ${claseHoy} transition-colors min-h-0"><span class="font-black text-slate-400 text-right text-xs mb-0.5 ${esHoy ? 'text-sky-400' : ''}">${i}</span><div class="flex-1 overflow-y-auto no-scrollbar flex flex-col justify-center min-h-0">${htmlBotonTareas}</div></div>`;
        }
    }

    function cambiarMes(direccion) { 
        fechaCalendario.setMonth(fechaCalendario.getMonth() + direccion); 
        generarCalendario(); 
    }

    function abrirListaDia(fechaStr) {
        const tareasDelDia = tareasFiltradas.filter(t => t.fecha_entrega === fechaStr); 
        const [anio, mes, dia] = fechaStr.split('-'); 
        const fechaObj = new Date(anio, mes - 1, dia);
        const fechaFormateada = new Intl.DateTimeFormat('es-MX', { day: 'numeric', month: 'long', year: 'numeric' }).format(fechaObj);
        
        document.getElementById('tituloModalDia').innerText = fechaFormateada; 
        const contenedor = document.getElementById('contenedorTareasDia'); 
        contenedor.innerHTML = '';
        
        tareasDelDia.forEach(task => {
            let colorBorder = 'border-slate-500'; let colorBg = 'bg-slate-800';
            if(task.estado === 'Terminado') { colorBorder = 'border-[#4ade80]'; colorBg = 'bg-[#4ade80]/10'; }
            if(task.estado === 'Pendiente') { colorBorder = 'border-[#fb923c]'; colorBg = 'bg-[#fb923c]/10'; }
            if(task.estado === 'En proceso') { colorBorder = 'border-[#38bdf8]'; colorBg = 'bg-[#38bdf8]/10'; }
            
            let priBorder = 'border-emerald-400'; let priText = 'text-emerald-400';
            if(task.prioridad_calculada === 'Alta') { priBorder = 'border-red-400'; priText = 'text-red-400'; }
            if(task.prioridad_calculada === 'Media') { priBorder = 'border-yellow-400'; priText = 'text-yellow-400'; }
            if(task.prioridad_calculada === 'Terminada') { priBorder = 'border-[#4ade80]'; priText = 'text-[#4ade80]'; } 
            if(task.prioridad_calculada === 'Cancelada') { priBorder = 'border-slate-400'; priText = 'text-slate-400'; }
            
            let autorizo = task.usuario ? task.usuario.name : 'Desconocido'; 
            let encargado = (task.estado !== 'Pendiente' && task.trabajador) ? task.trabajador.name : 'Aún sin asignar';
            
            contenedor.innerHTML += `
                <div class="w-full sm:w-80 shrink-0 rounded-xl border-2 ${colorBorder} ${colorBg} p-5 flex flex-col gap-3 relative shadow-lg">
                    <div class="absolute top-3 left-3 text-[10px] font-black uppercase px-2 py-1 rounded border ${priBorder} ${priText}">${task.prioridad_calculada}</div>
                    <div class="absolute top-3 right-3 text-[10px] font-black uppercase px-2 py-1 rounded border ${colorBorder}">${task.estado}</div>
                    <div class="pt-6 pr-2">
                        <p class="text-[10px] uppercase text-slate-400 font-bold mb-0.5">Cliente</p>
                        <p class="font-bold text-white text-lg leading-tight truncate">${task.cliente}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase text-slate-400 font-bold mb-0.5">Pedido</p>
                        <p class="text-slate-200 font-medium truncate">${task.nombre_pedido}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <div class="bg-slate-900/60 p-2 rounded border border-slate-700">
                            <p class="text-[9px] uppercase text-yellow-500 font-bold mb-0.5">Alta</p>
                            <p class="text-xs text-slate-300 truncate font-semibold">${autorizo}</p>
                        </div>
                        <div class="bg-slate-900/60 p-2 rounded border border-slate-700">
                            <p class="text-[9px] uppercase text-sky-400 font-bold mb-0.5">Encargado</p>
                            <p class="text-xs text-slate-300 truncate font-semibold">${encargado}</p>
                        </div>
                    </div>
                    <div class="mt-auto pt-4 flex gap-2">
                        <button onclick="abrirDetalles(${task.id})" class="flex-1 bg-[#5A8BB5] hover:bg-[#4A7A9E] text-white text-xs font-bold py-2 rounded transition-colors border border-black shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none">VER FICHA</button>
                        <button onclick="cambiarEstado(${task.id}, this)" id="statusButton_modal_${task.id}" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold py-2 rounded transition-colors border border-black shadow-[2px_2px_0_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none">ESTADO</button>
                    </div>
                </div>
            `;
        });
        
        toggleModal('modalListaTareasDia');
    }

    function toggleModal(modalID) { 
        document.getElementById(modalID).classList.toggle('hidden'); 
    }

    function abrirDetalles(id) {
        fetch(`/tareas/${id}`)
            .then(response => { if (!response.ok) throw new Error('Error al cargar'); return response.json(); })
            .then(task => {
                document.getElementById('detId').innerText = "#" + String(task.id).padStart(3, '0'); 
                document.getElementById('detCliente').innerText = task.cliente; 
                document.getElementById('detAutorizo').innerText = task.autorizo || 'Desconocido'; 
                document.getElementById('detEncargado').innerText = (task.estado !== 'Pendiente' && task.encargado) ? task.encargado : 'Aún sin asignar'; 
                document.getElementById('detPedido').innerText = task.nombre_pedido; 
                document.getElementById('detFechaToma').innerText = task.fecha_toma; 
                document.getElementById('detFechaEntrega').innerText = task.fecha_entrega_formateada || task.fecha_entrega; 
                document.getElementById('detDesc').innerText = task.detalles_adicionales || 'N/A';
                
                const badge = document.getElementById('detStatusBadge'); 
                badge.innerText = task.estado; 
                badge.className = "text-[10px] font-bold uppercase tracking-widest inline-block px-2.5 py-0.5 rounded border ";
                if(task.estado === 'Terminado') badge.className += "bg-[#4ade80]/20 text-[#4ade80] border-[#4ade80]/50"; 
                if(task.estado === 'Pendiente') badge.className += "bg-[#fb923c]/20 text-[#fb923c] border-[#fb923c]/50"; 
                if(task.estado === 'En proceso') badge.className += "bg-[#38bdf8]/20 text-[#38bdf8] border-[#38bdf8]/50"; 
                if(task.estado === 'Cancelado') badge.className += "bg-slate-400/20 text-slate-300 border-slate-400/50";
                
                const pBadge = document.getElementById('detPriBadge'); 
                pBadge.innerText = task.prioridad_calculada; 
                pBadge.className = "text-[10px] font-bold uppercase tracking-widest inline-block px-2.5 py-0.5 rounded border ";
                if(task.prioridad_calculada === 'Alta') pBadge.className += "bg-red-500/20 text-red-400 border-red-500/50"; 
                if(task.prioridad_calculada === 'Media') pBadge.className += "bg-yellow-500/20 text-yellow-400 border-yellow-500/50"; 
                if(task.prioridad_calculada === 'Baja') pBadge.className += "bg-emerald-500/20 text-emerald-400 border-emerald-500/50"; 
                if(task.prioridad_calculada === 'Terminada') pBadge.className += "bg-[#4ade80]/20 text-[#4ade80] border-[#4ade80]/50"; 
                if(task.prioridad_calculada === 'Cancelada') pBadge.className += "bg-slate-500/20 text-slate-400 border-slate-500/50";
                
                toggleModal('modalDetallesTarea');
            })
            .catch(error => { 
                console.error('Error:', error); 
                alert('No se pudieron cargar los detalles.'); 
            });
    }

    function cambiarEstado(id, btnElement) {
        const popup = document.getElementById('statusPopup'); 
        if (popup.dataset.taskId == id && !popup.classList.contains('hidden')) { 
            popup.classList.add('hidden'); 
            return; 
        }
        
        const rect = btnElement.getBoundingClientRect(); 
        popup.dataset.taskId = id; 
        popup.classList.remove('hidden'); 
        
        const popupHeight = popup.offsetHeight; 
        const espacioAbajo = window.innerHeight - rect.bottom;
        
        if (espacioAbajo < popupHeight + 10) { 
            popup.style.top = `${rect.top + window.scrollY - popupHeight - 5}px`; 
        } else { 
            popup.style.top = `${rect.bottom + window.scrollY + 5}px`; 
        }
        
        popup.style.left = `${rect.left + window.scrollX}px`;
    }

    function actualizarEstado(nuevoEstado) {
        const popup = document.getElementById('statusPopup'); 
        const id = popup.dataset.taskId; 
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/tareas/${id}/status`, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, 
            body: JSON.stringify({ estado: nuevoEstado }) 
        })
        .then(response => { 
            if (!response.ok) throw new Error('Error al actualizar'); 
            return response.json(); 
        })
        .then(data => { 
            popup.classList.add('hidden'); 
            setTimeout(() => { window.location.reload(); }, 300); 
        })
        .catch(error => { 
            console.error('Error:', error); 
            alert('No se pudo actualizar el estado.'); 
        });
    }

    document.addEventListener('click', function(event) {
        const popup = document.getElementById('statusPopup'); 
        const isClickInside = popup.contains(event.target) || event.target.tagName === 'BUTTON';
        
        if (!isClickInside && !popup.classList.contains('hidden')) {
            popup.classList.add('hidden');
        }
    });

    function actualizarReloj() {
        const reloj = document.getElementById('reloj-macdash'); 
        if(!reloj) return;
        
        const ahora = new Date(); 
        let horas = ahora.getHours(); 
        let minutos = ahora.getMinutes(); 
        let ampm = horas >= 12 ? 'pm' : 'am';
        
        horas = horas % 12; 
        horas = horas ? horas : 12; 
        minutos = minutos < 10 ? '0' + minutos : minutos; 
        
        reloj.innerText = horas + ':' + minutos + ' ' + ampm;
    }
    
    setInterval(actualizarReloj, 1000); 
    actualizarReloj();

    function editarUsuario(id, name, paterno, materno, username) {
        document.getElementById('edit_name').value = name; 
        document.getElementById('edit_paterno').value = paterno; 
        document.getElementById('edit_materno').value = materno; 
        document.getElementById('edit_username').value = username; 
        document.getElementById('formEditarUsuario').action = '/usuarios/' + id;
        
        toggleModal('modalListaUsuarios'); 
        toggleModal('modalEditarUsuario');
    }
    </script>
</body>
</html>