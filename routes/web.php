<?php

use App\Http\Controllers\TareaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController; // <-- Importamos el controlador de usuarios
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;

// Rutas Públicas (El Login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Protegidas de MacDash (Requieren estar logueado Y bloquean la caché)
Route::middleware(['auth', PreventBackHistory::class])->group(function () {
    
    // Principal
    Route::get('/', [TareaController::class, 'index']);
    
    // Tareas
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
    Route::post('/tareas/{tarea}/status', [TareaController::class, 'updateStatus'])->name('tareas.status.update');

    // Trabajadores (ESTA ES LA RUTA QUE FALTABA)
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');

});