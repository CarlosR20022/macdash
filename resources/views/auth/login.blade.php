<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacDash - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#0F172A] text-slate-200 flex h-screen items-center justify-center">

    <div class="bg-[#1E293B] w-full max-w-md rounded-2xl border border-slate-700 shadow-2xl p-10">
        
        <div class="flex flex-col items-center mb-8">
            <div class="text-[#38BDF8] font-black text-3xl tracking-tighter italic mb-1">MacDash</div>
            <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Acceso al Sistema</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-3 rounded-lg mb-6 text-xs text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Usuario</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg p-3 text-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition-all text-sm"
                    placeholder="Ingresa tu usuario">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Contraseña</label>
                <input type="password" name="password" required 
                    class="w-full bg-slate-900 border border-slate-700 rounded-lg p-3 text-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition-all text-sm"
                    placeholder="Ingresa tu contraseña">
            </div>

            <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 text-white p-3 rounded-lg font-black text-xs uppercase tracking-widest shadow-lg shadow-sky-500/20 transition-all mt-4">
                Entrar al Sistema
            </button>
        </form>

    </div>

</body>
</html>