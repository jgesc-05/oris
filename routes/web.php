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

// routes/web.php

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // Usuarios
    Route::view('/usuarios', 'admin.usuarios.index')->name('usuarios.index');
    Route::view('/usuarios/crear', 'admin.usuarios.create')->name('usuarios.create');
    // Detalle de usuario (mock)
    Route::get('/usuarios/{usuario}', function ($usuario) {
        return view('admin.usuarios.show', ['id' => $usuario]);
    })->whereNumber('usuario')->name('usuarios.show');


    // Pacientes
    Route::view('/pacientes', 'admin.pacientes.index')->name('pacientes.index');

    // 👇 NUEVO: detalle de paciente (mock)
    Route::get('/pacientes/{paciente}', function ($paciente) {
        return view('admin.pacientes.show', ['id' => $paciente]);
    })->whereNumber('paciente')->name('pacientes.show');

    // Reportes / Config
    Route::view('/reportes', 'admin.reportes.index')->name('reportes.index');
    Route::view('/config', 'admin.config')->name('config');
});
