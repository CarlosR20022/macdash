<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; // <-- Importamos la herramienta de encriptación

class UserController extends Controller
{
    public function store(Request $request)
    {
        // 1. Candado de seguridad
        if (auth()->user()->role_id !== 1) {
            abort(403, 'No tienes permiso para hacer esto.');
        }

        // 2. Validamos los datos (Quité la restricción de min:6 en la contraseña)
        $request->validate([
            'name' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string', 
        ]);

        // 3. Generamos el correo dinámico
        $nombreLimpio = Str::slug($request->name, '');
        $apellidoLimpio = Str::slug($request->apellido_paterno, '');
        $correoDinamico = $nombreLimpio . '@' . $apellidoLimpio . '.local';

        // 4. Creamos al trabajador
        User::create([
            'name' => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'username' => $request->username,
            'email' => $correoDinamico,
            'password' => Hash::make($request->password), // <-- Forzamos la encriptación
            'role_id' => 2,
        ]);

        return redirect()->back()->with('success', '¡Trabajador registrado con éxito!');
    }
    public function update(Request $request, User $usuario)
    {
        // 1. Candado de seguridad estricto
        if (auth()->user()->role_id !== 1) {
            abort(403, 'No tienes permiso para hacer esto.');
        }

        // 2. Validamos los datos (Fíjate que la contraseña ya no es obligatoria aquí)
        $request->validate([
            'name' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            // El unique ignora al usuario actual para que no marque error si no cambia su usuario
            'username' => 'required|string|max:255|unique:users,username,' . $usuario->id,
            'password' => 'nullable|string', 
        ]);

        // 3. Re-generamos el correo por si cambiaste su nombre
        $nombreLimpio = Str::slug($request->name, '');
        $apellidoLimpio = Str::slug($request->apellido_paterno, '');
        $correoDinamico = $nombreLimpio . '@' . $apellidoLimpio . '.local';

        // 4. Actualizamos los datos básicos
        $usuario->update([
            'name' => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'username' => $request->username,
            'email' => $correoDinamico,
        ]);

        // 5. Si escribiste una contraseña nueva, la encriptamos y la guardamos
        if ($request->filled('password')) {
            $usuario->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->back()->with('success', '¡Usuario actualizado correctamente!');
    }
}