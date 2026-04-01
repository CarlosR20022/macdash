<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validamos que nos manden USUARIO y contraseña
        $credentials = $request->validate([
            'username' => ['required', 'string'], // <-- Cambiamos 'email' por 'username'
            'password' => ['required'],
        ]);

        // 2. Intentamos iniciar sesión con esos datos
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // Te manda al panel de MacDash
        }

        // 3. Si falla, regresamos el error al campo del usuario
        return back()->withErrors([
            'username' => 'El usuario o la contraseña son incorrectos.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}