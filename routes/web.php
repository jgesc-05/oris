<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PacienteAuthController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta de prueba (mantener)
Route::view('/test', 'test'); // Esto es una prueba

// Página de acceso general
Route::view('/acceder', 'access.index')->name('acceder');

// Staff: login (mock si aún no lo tienes)
Route::view('/login', 'auth.login')->name('login');

// ============================================
// AUTH - Pacientes
// ============================================
Route::prefix('paciente')->name('paciente.')->group(function () {
    // Mostrar formulario de login
    Route::get('login', [PacienteAuthController::class, 'showLogin'])->name('login');

    // Procesar login (cuando se agregue backend)
    Route::post('login', [PacienteAuthController::class, 'login']);

    // Registro
    Route::get('registro', [PacienteAuthController::class, 'showRegister'])->name('register');
    Route::post('registro', [PacienteAuthController::class, 'register']);
});
