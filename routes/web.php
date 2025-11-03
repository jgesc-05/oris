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

    /** Configuración - Especialidades */
    Route::view('/config/especialidades', 'admin.config.especialidad.index')->name('config.especialidad.index');
    Route::view('/config/especialidades/crear', 'admin.config.especialidad.create')->name('config.especialidad.create');
    Route::get('/config/especialidades/{id}/editar', function ($id) {
        return view('admin.config.especialidad.edit', ['id' => $id]);
    })->whereNumber('id')->name('config.especialidad.edit');

    /** Configuración - Servicios */
    Route::view('/config/servicios', 'admin.config.servicio.index')->name('config.servicio.index');
    Route::view('/config/servicios/crear', 'admin.config.servicio.create')->name('config.servicio.create');
    Route::get('/config/servicios/{id}/editar', function ($id) {
        return view('admin.config.servicio.edit', ['id' => $id]);
    })->whereNumber('id')->name('config.servicio.edit');

    /** Configuración - Médicos */
    Route::view('/config/medicos', 'admin.config.medico.index')->name('config.medico.index');
    Route::view('/config/medicos/crear', 'admin.config.medico.create')->name('config.medico.create');
    Route::get('/config/medicos/{id}/editar', function ($id) {
        return view('admin.config.medico.edit', ['id' => $id]);
    })->whereNumber('id')->name('config.medico.edit');
    });
