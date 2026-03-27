<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>MacDash - Panel Principal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .tabla-macdash th, .tabla-macdash td { border: 1px solid #e2e8f0; }
    </style>
</head>
<body class="bg-[#111827] text-white flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#1F2937] border-r-2 border-white flex flex-col p-6 shrink-0 z-10">
            <div class="mb-2 flex flex-col items-center mt-0">
             <img src="{{ asset('img/MacDash.png') }}" alt="MacDash Logo" class="w-45 h-45 object-contain mb-1">
        </div>

        <nav class="space-y-6">
            <button onclick="toggleModal('modalNuevaTarea')" class="w-full bg-[#5A8BB5] hover:bg-[#4A7A9E] border-[3px] border-slate-900 rounded-xl p-4 flex flex-col items-center justify-center transition-all shadow-[0_4px_0_rgb(15,23,42)] active:translate-y-1 active:shadow-none">
                <img src="{{ asset('img/tarea.png') }}" alt="Nueva Tarea" class="w-14 h-14 mb-2">
                <span class="text-white font-bold text-lg">Nueva tarea</span>
            </button>

            <button class="w-full bg-[#5A8BB5] hover:bg-[#4A7A9E] border-[3px] border-slate-900 rounded-xl p-4 flex flex-col items-center justify-center transition-all shadow-[0_4px_0_rgb(15,23,42)] active:translate-y-1 active:shadow-none">
                <img src="{{ asset('img/calendario.png') }}" alt="Calendario" class="w-14 h-14 mb-2">
                <span class="text-white font-bold text-lg">Calendario</span>
            </button>

            <button class="w-full bg-[#5A8BB5] hover:bg-[#4A7A9E] border-[3px] border-slate-900 rounded-xl p-4 flex flex-col items-center justify-center transition-all shadow-[0_4px_0_rgb(15,23,42)] active:translate-y-1 active:shadow-none">
                <img src="{{ asset('img/registro.png') }}" alt="Registros" class="w-14 h-14 mb-2">
                <span class="text-white font-bold text-lg">Registros</span>
            </button>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 bg-[#0F172A]">
    <header class="h-24 border-b-2 border-white flex items-center justify-between px-10 bg-[#0F172A] z-10 relative">
                <input type="text" placeholder="BUSCAR TAREA O CLIENTE" class="bg-slate-200 text-slate-800 placeholder-slate-500 rounded-lg px-4 py-3 w-[400px] focus:outline-none focus:ring-2 focus:ring-sky-500 font-semibold text-base">
            
            <div class="flex items-center gap-8">
                <span class="text-2xl font-medium">{{ now()->format('h:i a') }}</span>
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/perfil.png') }}" alt="Avatar" class="w-12 h-12 rounded-full border-2 border-slate-400">
                    <span class="font-bold text-xl">{{ Auth::user()->name }}</span>                    
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="text-sm text-red-400 hover:text-red-300 font-bold underline">Salir</button>
                    </form>
                </div>
            </div>
        </header>

        <section class="p-10 overflow-auto">
            @if(session('success'))
                <div class="bg-emerald-500 text-white p-4 rounded-lg mb-6 text-center font-bold text-base shadow-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-transparent overflow-hidden">
                <table class="w-full text-center border-collapse tabla-macdash bg-transparent">
                    <thead class="bg-slate-200 text-slate-800 text-lg">
                        <tr>
                            <th class="p-4 font-bold w-24">
                                <a href="?sort_by=id&order={{ $sort_by == 'id' && $order == 'asc' ? 'desc' : 'asc' }}" class="hover:text-sky-600 flex justify-center items-center gap-1">
                                    ID 
                                    @if($sort_by == 'id')
                                        <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 font-bold">Tarea</th>
                            <th class="p-4 font-bold">
                                 <a href="?sort_by=fecha_entrega&order={{ $sort_by == 'fecha_entrega' && $order == 'asc' ? 'desc' : 'asc' }}" class="hover:text-sky-600 flex justify-center items-center gap-1">
                                    Fecha entrega
                                    @if($sort_by == 'fecha_entrega')
                                        <span>{!! $order == 'asc' ? '&#9650;' : '&#9660;' !!}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="p-4 font-bold w-40">Detalles</th>
                            <th class="p-4 font-bold w-48">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-100 text-lg">
                        @forelse($tareas as $tarea)
                        <tr>
                            <td class="p-4 border-slate-400 border">{{ $tarea->id }}</td>
                            <td class="p-4 border-slate-400 border">{{ $tarea->nombre_pedido }}</td>
                            <td class="p-4 border-slate-400 border">{{ $tarea->fecha_entrega }}</td>
                            <td class="p-4 border-slate-400 border text-center">
                                <button onclick="abrirDetalles({{ $tarea->id }})" class="underline decoration-white hover:text-sky-300 transition-colors">Detalles</button>
                            </td>
                            <td class="p-4 border-slate-400 border text-center">
                                <button onclick="cambiarEstado({{ $tarea->id }})" id="statusButton_{{ $tarea->id }}" class="px-4 py-1.5 text-sm font-bold text-black border-2 border-black rounded shadow-[3px_3px_0_rgba(0,0,0,1)] hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-[1px_1px_0_rgba(0,0,0,1)]
                                    {{ $tarea->estado == 'Terminado' ? 'bg-[#4ade80]' : '' }}
                                    {{ $tarea->estado == 'Pendiente' ? 'bg-[#fb923c]' : '' }}
                                    {{ $tarea->estado == 'En proceso' ? 'bg-[#38bdf8]' : '' }}
                                    {{ $tarea->estado == 'Cancelado' ? 'bg-slate-400' : '' }}
                                ">
                                    {{ $tarea->estado }}
                                </button>
                            </td>
                        </tr>
                        @empty
                        @for($i=0; $i<6; $i++)
                        <tr>
                            <td class="p-6 border-slate-400 border text-transparent">-</td>
                            <td class="p-6 border-slate-400 border"></td>
                            <td class="p-6 border-slate-400 border"></td>
                            <td class="p-6 border-slate-400 border"></td>
                            <td class="p-6 border-slate-400 border"></td>
                        </tr>
                        @endfor
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="modalNuevaTarea" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-[#1F2937] w-full max-w-lg rounded-xl border-2 border-slate-600 shadow-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <img src="{{ asset('img/tarea.png') }}" class="w-6 h-6"> Nueva Orden
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
                        <input type="date" name="fecha_entrega" required class="w-full bg-slate-800 border border-slate-600 rounded p-2 text-white color-scheme-dark">
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

    <div id="modalDetallesTarea" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="bg-[#1E293B] w-full max-w-xl rounded-2xl border border-slate-700 shadow-2xl p-10 transform transition-all scale-95">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    <img src="{{ asset('img/MacDash.png') }}" class="w-8 h-8"> Detalles de la Tarea <span id="detId"></span>
                </h3>
                <button onclick="toggleModal('modalDetallesTarea')" class="text-slate-400 hover:text-white font-bold text-2xl">✕</button>
            </div>

            <div class="grid grid-cols-2 gap-x-8 gap-y-6 text-sm">
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-1.5">Cliente</label>
                    <p id="detCliente" class="text-white bg-slate-800 p-3 rounded-lg font-semibold"></p>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-1.5">Teléfono</label>
                    <p id="detTel" class="text-white bg-slate-800 p-3 rounded-lg font-semibold"></p>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-1.5">Nombre del Pedido</label>
                    <p id="detPedido" class="text-white bg-slate-800 p-3 rounded-lg font-semibold"></p>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-1.5">Fecha de toma (Automática)</label>
                    <p id="detFechaToma" class="text-white bg-slate-800 p-3 rounded-lg"></p>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-1.5">Fecha Entrega</label>
                    <p id="detFechaEntrega" class="text-white bg-slate-800 p-3 rounded-lg"></p>
                </div>

                <div class="col-span-2">
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-1.5">Descripción y Medidas</label>
                    <p id="detDesc" class="text-white bg-slate-800 p-4 rounded-lg min-h-24 whitespace-pre-line"></p>
                </div>
            </div>

            <div class="flex justify-end mt-8 border-t border-slate-700 pt-6 gap-3">
                <button type="button" onclick="toggleModal('modalDetallesTarea')" class="px-6 py-2.5 text-slate-400 text-xs font-bold uppercase tracking-wider">CERRAR</button>
            </div>
        </div>
    </div>

    <div id="statusPopup" class="absolute bg-[#1F2937] border-2 border-black rounded-lg shadow-[4px_4px_0_rgba(0,0,0,1)] hidden z-50 p-2 space-y-2 w-48 text-center" data-task-id="">
        <button onclick="actualizarEstado('Pendiente')" class="w-full bg-[#fb923c] text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">PENDIENTE</button>
        <button onclick="actualizarEstado('En proceso')" class="w-full bg-[#38bdf8] text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">EN PROCESO</button>
        <button onclick="actualizarEstado('Terminado')" class="w-full bg-[#4ade80] text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">TERMINADO</button>
        <button onclick="actualizarEstado('Cancelado')" class="w-full bg-slate-400 text-black border border-black rounded px-3 py-1 font-bold text-xs hover:-translate-y-0.5 transition-transform active:translate-y-0 active:shadow-none">CANCELADO</button>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            modal.classList.toggle('hidden');
        }

        function abrirDetalles(id) {
            fetch(`/tareas/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error al cargar');
                    return response.json();
                })
                .then(task => {
                    document.getElementById('detId').innerText = "#" + String(task.id).padStart(3, '0');
                    document.getElementById('detCliente').innerText = task.cliente;
                    document.getElementById('detTel').innerText = task.telefono || 'N/A';
                    document.getElementById('detPedido').innerText = task.nombre_pedido;
                    document.getElementById('detFechaToma').innerText = task.fecha_toma;
                    document.getElementById('detFechaEntrega').innerText = task.fecha_entrega;
                    document.getElementById('detDesc').innerText = task.detalles_adicionales || 'N/A';
                    toggleModal('modalDetallesTarea');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No se pudieron cargar los detalles.');
                });
        }

        function cambiarEstado(id) {
            const popup = document.getElementById('statusPopup');
            const button = document.getElementById(`statusButton_${id}`);
            const rect = button.getBoundingClientRect();

            popup.dataset.taskId = id;
            popup.style.top = `${rect.bottom + window.scrollY + 5}px`;
            popup.style.left = `${rect.left + window.scrollX}px`;
            popup.classList.toggle('hidden');
        }

        function actualizarEstado(nuevoEstado) {
            const popup = document.getElementById('statusPopup');
            const id = popup.dataset.taskId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/tareas/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ estado: nuevoEstado })
            })
            .then(response => {
                if (!response.ok) throw new Error('Error al actualizar');
                return response.json();
            })
            .then(data => {
                const button = document.getElementById(`statusButton_${id}`);
                button.innerText = nuevoEstado; // Actualiza el texto

                // Actualiza el color
                button.classList.remove('bg-[#4ade80]', 'bg-[#fb923c]', 'bg-[#38bdf8]', 'bg-slate-400');
                if (nuevoEstado === 'Terminado') button.classList.add('bg-[#4ade80]');
                else if (nuevoEstado === 'Pendiente') button.classList.add('bg-[#fb923c]');
                else if (nuevoEstado === 'En proceso') button.classList.add('bg-[#38bdf8]');
                else if (nuevoEstado === 'Cancelado') button.classList.add('bg-slate-400');

                popup.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo actualizar el estado.');
            });
        }

        // Cierra el menú de estado si haces clic afuera
        document.addEventListener('click', function(event) {
            const popup = document.getElementById('statusPopup');
            const isClickInside = popup.contains(event.target) || event.target.tagName === 'BUTTON';
            if (!isClickInside && !popup.classList.contains('hidden')) {
                popup.classList.add('hidden');
            }
        });
    </script>
</body>
</html>