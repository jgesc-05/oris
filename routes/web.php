<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PatientAuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Doctor\DoctorPortalController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SpecialtyController;
use App\Models\Specialty;
use App\Http\Controllers\Paciente\PatientPortalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Secretary\SecretaryAppointmentController;
use App\Http\Controllers\Secretary\SecretaryPatientController;
use App\Http\Controllers\Secretary\SecretaryPortalController;
use App\Http\Controllers\Secretary\SecretaryScheduleController;


Route::get('/', function () {
    return redirect()->route('acceder');
});

// Ruta de prueba (mantener)
Route::view('/test', 'test'); // Esto es una prueba

// Página de acceso general
Route::view('/acceder', 'access.index')->name('acceder');

// Staff: login (mock si aún no lo tienes)
//Route::view('/login', 'auth.login')->name('login'); //Quitarlo luego


//Rutas de login empresarial
Route::get('/login', [UserController::class, 'viewStaffLogin'])->name('login');
Route::post('/login', [UserController::class, 'staffLogin'])->name('staff.login');

//Logout empresarial
Route::post('/logout', [UserController::class, 'staffLogout'])->name('logout');

// ============================================
// Pacientes
// ============================================
Route::prefix('paciente')->name('paciente.')->group(function () {
    // Mostrar formulario de login
    Route::get('login', [PatientAuthController::class, 'showLoginForm'])->name('login');

    // Procesar login: envía el enlace al correo
    Route::post('login', [PatientAuthController::class, 'sendLoginLink'])->name('login.submit');

    // Registro (si lo mantendrás en el futuro)
    Route::get('registro', [PatientAuthController::class, 'showRegister'])->name('register');
    Route::post('registro', [PatientAuthController::class, 'register']);

    // Verificación del enlace
    Route::get('verificar-login/{token}', [PatientAuthController::class, 'verifyLogin'])->name('login.verify');
    Route::post('logout', [PatientAuthController::class, 'logout'])->middleware('auth:paciente')->name('logout');

    // Sección privada del paciente
    Route::middleware('auth:paciente')->group(function () {
        Route::get('inicio', [PatientPortalController::class, 'inicio'])->name('inicio');
        Route::get('servicios', [SpecialtyController::class, 'patientIndex'])->name('servicios');
        //Route::get('servicios/{especialidad}', [PatientPortalController::class, 'serviciosEspecialidad'])->name('servicios.especialidad');
        Route::get('servicios/{slug}', [ServiceController::class, 'showBySpecialty'])
        ->name('servicios.especialidad');
        Route::get('servicios/{especialidad}/{servicio}', [ServiceController::class, 'showService'])
        ->name('servicios.detalle');

        //Route::get('servicios/{especialidad}/{servicio}', [PatientPortalController::class, 'servicioDetalle'])->name('servicios.detalle');
        Route::get('medicos', [DoctorController::class, 'indexDoctors'])->name('medicos');
        Route::get('medicos/{especialidad}', [DoctorController::class, 'doctorsBySpecialty'])->name('medicos.especialidad');    
        Route::get('medicos/{especialidad}/{medico}', [DoctorController::class, 'doctorDetail'])->name('medicos.detalle');
        Route::prefix('citas')->name('citas.')->group(function () {
            Route::get('disponibilidad', [PatientPortalController::class, 'citasDisponibilidad'])->name('disponibilidad');
            Route::get('crear', [PatientPortalController::class, 'citasCreate'])->name('create');
            Route::post('/', [PatientPortalController::class, 'citasStore'])->name('store');
            Route::get('reprogramar', [PatientPortalController::class, 'reprogramarIndex'])->name('reprogramar.index');
            Route::post('reprogramar/seleccionar', [PatientPortalController::class, 'reprogramarSelect'])->name('reprogramar.submit');
            Route::get('reprogramar/{id}/editar', [PatientPortalController::class, 'reprogramarEdit'])->whereNumber('id')->name('reprogramar.edit');
            Route::put('reprogramar/{id}', [PatientPortalController::class, 'reprogramarUpdate'])->whereNumber('id')->name('reprogramar.update');
            Route::get('confirmada/{id}', [PatientPortalController::class, 'citaConfirmada'])->whereNumber('id')->name('confirmada');
            Route::get('reprogramar/{id}/confirmada', [PatientPortalController::class, 'reprogramarConfirmada'])->whereNumber('id')->name('reprogramar.confirmada');

            Route::get('cancelar', [PatientPortalController::class, 'citasCancelarIndex'])->name('cancelar.index');
            Route::post('cancelar/submit', [PatientPortalController::class, 'citasCancelarSubmit'])->name('cancelar.submit');
            Route::get('mis-citas', [PatientPortalController::class, 'citasIndex'])->name('index');
        });
    });
});

Route::prefix('secretaria')->name('secretaria.')->middleware(['web', 'auth', 'role:secretaria'])->group(function () {
    Route::get('inicio', [SecretaryPortalController::class, 'inicio'])->name('inicio');
    Route::get('agenda', [SecretaryPortalController::class, 'agenda'])->name('agenda');
    Route::patch('agenda/citas/{appointment}/atendida', [SecretaryPortalController::class, 'markAsAttended'])
        ->name('agenda.mark-attended')
        ->whereNumber('appointment');

    Route::get('servicios', [SecretaryPortalController::class, 'servicios'])->name('servicios.index');
    Route::get('servicios/{especialidad}', [SecretaryPortalController::class, 'serviciosEspecialidad'])->name('servicios.especialidad');
    Route::get('servicios/{especialidad}/{servicio}', [SecretaryPortalController::class, 'serviciosDetalle'])->name('servicios.detalle');

    Route::get('medicos', [SecretaryPortalController::class, 'medicos'])->name('medicos.index');
    Route::get('medicos/{especialidad}', [SecretaryPortalController::class, 'medicosEspecialidad'])->name('medicos.especialidad');
    Route::get('medicos/{especialidad}/{medico}', [SecretaryPortalController::class, 'medicosDetalle'])->name('medicos.detalle');

    Route::prefix('pacientes')->name('pacientes.')->group(function () {
        Route::get('/', [SecretaryPatientController::class, 'index'])->name('index');
        Route::get('crear', [SecretaryPatientController::class, 'create'])->name('create');
        Route::post('/', [SecretaryPatientController::class, 'store'])->name('store');
        Route::get('{patient}', [SecretaryPatientController::class, 'show'])->whereNumber('patient')->name('show');
    });

    Route::prefix('citas')->name('citas.')->group(function () {
        Route::get('disponibilidad', [SecretaryAppointmentController::class, 'disponibilidad'])->name('disponibilidad');

        Route::get('agendar', [SecretaryAppointmentController::class, 'showAgendarLookup'])->name('agendar.lookup');
        Route::post('agendar', [SecretaryAppointmentController::class, 'submitAgendarLookup'])->name('agendar.lookup.submit');
        Route::get('agendar/{patient}', [SecretaryAppointmentController::class, 'showCreateForm'])
            ->name('create.form')
            ->whereNumber('patient');
        Route::post('agendar/{patient}', [SecretaryAppointmentController::class, 'storeAppointment'])
            ->name('create.store')
            ->whereNumber('patient');
        Route::get('confirmada/{appointment}', [SecretaryAppointmentController::class, 'showAppointmentConfirmation'])
            ->name('confirmada')
            ->whereNumber('appointment');

        Route::get('reprogramar', [SecretaryAppointmentController::class, 'showReprogramarLookup'])->name('reprogramar.lookup');
        Route::post('reprogramar', [SecretaryAppointmentController::class, 'submitReprogramarLookup'])->name('reprogramar.lookup.submit');
        Route::get('reprogramar/{patient}/seleccion', [SecretaryAppointmentController::class, 'showReprogramSelection'])
            ->name('reprogramar.seleccion')
            ->whereNumber('patient');
        Route::post('reprogramar/{patient}/seleccion', [SecretaryAppointmentController::class, 'submitReprogramSelection'])
            ->name('reprogramar.seleccion.submit')
            ->whereNumber('patient');
        Route::get('reprogramar/{patient}/{appointment}/editar', [SecretaryAppointmentController::class, 'editReprogram'])
            ->name('reprogramar.edit')
            ->whereNumber('patient')
            ->whereNumber('appointment');
        Route::put('reprogramar/{patient}/{appointment}', [SecretaryAppointmentController::class, 'updateReprogram'])
            ->name('reprogramar.update')
            ->whereNumber('patient')
            ->whereNumber('appointment');
        Route::get('reprogramar/{appointment}/confirmada', [SecretaryAppointmentController::class, 'showReprogramConfirmation'])
            ->name('reprogramar.confirmada')
            ->whereNumber('appointment');

        Route::get('cancelar', [SecretaryAppointmentController::class, 'showCancelarLookup'])->name('cancelar.lookup');
        Route::post('cancelar', [SecretaryAppointmentController::class, 'submitCancelarLookup'])->name('cancelar.lookup.submit');
        Route::get('cancelar/{patient}', [SecretaryAppointmentController::class, 'showCancelList'])
            ->name('cancelar.list')
            ->whereNumber('patient');
        Route::post('cancelar/{patient}', [SecretaryAppointmentController::class, 'cancelAppointment'])
            ->name('cancelar.confirm')
            ->whereNumber('patient');
    });

    Route::prefix('horarios')->name('horarios.')->group(function () {
        Route::get('bloquear', [SecretaryScheduleController::class, 'showBlockForm'])
            ->name('bloquear');
        Route::post('bloquear', [SecretaryScheduleController::class, 'storeBlock'])
            ->name('bloquear.store');
        Route::delete('bloquear/{block}', [SecretaryScheduleController::class, 'destroyBlock'])
            ->name('bloquear.destroy')
            ->whereNumber('block');
    });


});

Route::prefix('medico')->name('medico.')->middleware(['web', 'auth', 'role:medico'])->group(function () {
    Route::get('inicio', [DoctorPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('dashboard', fn () => redirect()->route('medico.dashboard'));
    Route::get('agenda', [DoctorPortalController::class, 'agenda'])->name('agenda');

    Route::prefix('pacientes')->name('pacientes.')->group(function () {
        Route::get('/', [DoctorPortalController::class, 'patientsIndex'])->name('index');
        Route::get('{patient}', [DoctorPortalController::class, 'patientsShow'])
            ->whereNumber('patient')
            ->name('show');
    });
});

// routes/web.php

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    //Route::view('/usuarios', 'admin.usuarios.index')->name('usuarios.index');
    //Route::view('/usuarios/crear', 'admin.usuarios.create')->name('usuarios.create');
    //Crear usuarios por admin
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    // Detalle de usuario (mock)
    /*Route::get('/usuarios/{usuario}', function ($usuario) {
        return view('admin.usuarios.show', ['id' => $usuario]);
    })->whereNumber('usuario')->name('usuarios.show');*/
    // Route::view('/usuarios/{usuario}/editar', 'admin.usuarios.edit')->name('usuarios.edit');

    //Mostrar info de usuario
    Route::get('/usuarios/{usuario}', [UserController::class, 'show'])->name('usuarios.show');

    //Edición de datos de usuario
    Route::get('/usuarios/{id}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');

    //Suspender usuario
    Route::patch('/usuarios/{id}/estado', [UserController::class, 'toggleState'])->name('usuarios.toggle-state');

    //Eliminar usuario
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');

    // Visualización de reportes empresariales
    Route::get('/reportes', [ReportController::class, 'index'])->name('reportes.index');

    // ===== Configuración =====
    // Apunta a resources/views/admin/config/index.blade.php
    Route::view('/config', 'admin.config.index')->name('config');

    /** Configuración - Especialidades */
    Route::get('/config/especialidades', [SpecialtyController::class, 'index'])->name('config.especialidad.index');
    //Route::view('/config/especialidades', 'admin.config.especialidad.index')->name('config.especialidad.index');
    //Route::view('/config/especialidades/crear', 'admin.config.especialidad.create')->name('config.especialidad.create');

    //Crear especialidad (con alertas)
    Route::get('/config/especialidades/crear', [SpecialtyController::class, 'showCreate'])->name('config.especialidad.create');

    //Desactivar o activar la especialidad (inactiva o activa)
    Route::post('/config/especialidades/{id}/toggle', [SpecialtyController::class, 'toggleState'])->name('config.especialidad.toggle');

    //Eliminar especialidad
    Route::delete('/config/especialidades/{id}', [SpecialtyController::class, 'destroy'])->name('config.especialidad.destroy');


    Route::post('/config/especialidades/crear', [SpecialtyController::class, 'storeSpecialty'])->name('config.especialidad.createSp');

    //Vista de edición (para pasar parámetro de id real)
    Route::get('/config/especialidades/{id}/editar', [SpecialtyController::class, 'edit'])
        ->name('config.especialidad.edit');

    //Actualización de la especialidad
    Route::put('/config/especialidades/{id}/actualizar', [SpecialtyController::class, 'update'])->name('config.especialidad.update');

    /** Configuración - Servicios */

    //Crear servicio
    Route::get('/config/servicios/crear', [ServiceController::class, 'create'])->name('config.servicio.create');
    Route::post('/config/servicios/crear', [ServiceController::class, 'store'])->name('config.servicio.store');

    //Listar los servicios disponibles (tabla principal)
    Route::get('/config/servicios', [ServiceController::class, 'index'])->name('config.servicio.index');

    //Route::view('/config/servicios', 'admin.config.servicio.index')->name('config.servicio.index');
    //Route::view('/config/servicios/crear', 'admin.config.servicio.create')->name('config.servicio.create');

    //Editar los servicios
    Route::get('/config/servicios/{id}/editar', [ServiceController::class, 'edit'])->name('config.servicio.edit');
    //Actualizar servicio
    Route::put('/config/servicios/{id}/editar', [ServiceController::class, 'update'])->name('config.servicio.update');

    //Cambiar estado del servicio
    Route::post('/config/servicios/{id}/toggle', [ServiceController::class, 'toggleState'])->name('config.servicio.toggle');

    //Eliminar los servicios
    Route::delete('/config/servicios/{id}/eliminar', [ServiceController::class, 'destroy'])->name('config.servicio.destroy');

    /*Route::get('/config/servicios/{id}/editar', function ($id) {
        return view('admin.config.servicio.edit', ['id' => $id]);
    })->whereNumber('id')->name('config.servicio.edit');*/

});
