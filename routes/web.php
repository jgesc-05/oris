<?php

use App\Http\Controllers\UserController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PacienteAuthController;

//Provisional
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
    // Dashboard
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // ===== Usuarios =====
    Route::view('/usuarios', 'admin.usuarios.index')->name('usuarios.index');           // listado
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');

    // OJO: primero rutas estáticas, luego dinámicas
    Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])
        ->whereNumber('usuario')->name('usuarios.edit');
    Route::get('/usuarios/{usuario}', [UserController::class, 'show'])
        ->whereNumber('usuario')->name('usuarios.show');
    Route::put('/usuarios/{usuario}', [UserController::class, 'update'])
        ->whereNumber('usuario')->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])
        ->whereNumber('usuario')->name('usuarios.destroy');

    // ===== Pacientes =====
    Route::view('/pacientes', 'admin.pacientes.index')->name('pacientes.index');
    Route::get('/pacientes/{paciente}', function ($paciente) {
        return view('admin.pacientes.show', ['id' => $paciente]);
    })->whereNumber('paciente')->name('pacientes.show');

    // ===== Reportes =====
    Route::view('/reportes', 'admin.reportes.index')->name('reportes.index');

    // ===== Configuración =====
    // Apunta a resources/views/admin/config/index.blade.php
    Route::view('/config', 'admin.config.index')->name('config');

    // Subsecciones de configuración
    Route::view('/config/especialidad/crear', 'admin.config.especialidad.create')
        ->name('config.especialidad.create');
    Route::view('/config/servicio/crear', 'admin.config.servicio.create')
        ->name('config.servicio.create');
    Route::view('/config/publicar-odontologo', 'admin.config.publicar-odontologo')
        ->name('config.publicar-odontologo');
});
