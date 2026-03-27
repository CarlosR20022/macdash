<?php

use App\Http\Controllers\TareaController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rutas Públicas (El Login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Protegidas de MacDash (Requieren estar logueado para verlas)
Route::middleware('auth')->group(function () {
    // Principal
    Route::get('/', [TareaController::class, 'index']);
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');

    // -- NUEVAS RUTAS PARA AJAX --
    // 1. Ruta para que JavaScript pida los detalles de una tarea (GET)
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
    // 2. Ruta para que JavaScript actualice el estado de una tarea (POST)
    Route::post('/tareas/{tarea}/status', [TareaController::class, 'updateStatus'])->name('tareas.status.update');
});