<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Mail\PatientLoginLink;
use App\Models\User;

class PatientAuthController extends Controller
{
    /**
     * Mostrar el formulario de login de pacientes
     */
    public function showLoginForm()
    {
        return view('auth.paciente.login');
    }

    public function showRegister()
    {
        return view('auth.paciente.register');
    }

    public function register(Request $request)
    {
        Log::info(message: "Validating");
        $request->validate([
            'id_tipo_documento' => 'required|string|max:3',
            'numero_documento' => 'required|string|max:20|unique:users,numero_documento',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'correo_electronico' => 'required|email|unique:users,correo_electronico',
            'telefono' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string|max:255',
        ]);

        Log::info("Validated");

        // Crear paciente (tipo_usuario = 3)
        $paciente = \App\Models\User::create([
            'id_tipo_usuario'   => 3,
            'id_tipo_documento' => $request->id_tipo_documento,
            'numero_documento'  => $request->numero_documento,
            'nombres'           => $request->nombres,
            'apellidos'         => $request->apellidos,
            'fecha_nacimiento'  => $request->fecha_nacimiento,
            'correo_electronico'=> $request->correo_electronico,
            'telefono'          => $request->telefono,
            'observaciones'     => $request->observaciones,
        ]);

        return redirect()
            ->route('paciente.login')
            ->with('status', 'Registro exitoso. Ahora puedes iniciar sesión.');
    }

    /**
     * Procesar el login y enviar enlace al correo del paciente
     */
    public function sendLoginLink(Request $request)
    {
        $request->validate([
            'id_tipo_documento' => 'required',
            'numero_documento' => 'required',
            'fecha_nacimiento' => 'required|date',
        ]);
        Log::info($request);

        // Buscar al paciente (tipo de usuario = 3)
        $patient = User::where('id_tipo_usuario', 3)
            ->where('id_tipo_documento', $request->id_tipo_documento)
            ->where('numero_documento', $request->numero_documento)
            ->where('fecha_nacimiento', $request->fecha_nacimiento)
            ->first();

        if (!$patient) {
            return back()->withErrors(['error' => 'Datos no encontrados. Verifique e intente nuevamente.']);
        }

        // Crear token temporal
        $token = Str::random(60);
        Cache::put('paciente_login_'.$token, $patient->id_usuario, now()->addMinutes(15));

        // Enviar correo con el enlace de acceso
        Mail::to($patient->correo_electronico)->send(new PatientLoginLink($token));

        return back()->with('status', 'Se ha enviado un enlace de acceso a su correo electrónico.');
    }

    /**
     * Verificar enlace de login recibido en el correo
     */
    public function verifyLogin($token)
    {
        $patientId = Cache::get('paciente_login_'.$token);

        if (!$patientId) {
            return redirect()->route('paciente.login')
                ->withErrors(['error' => 'El enlace ha expirado o no es válido.']);
        }

        // Iniciar sesión en el guard de pacientes
        auth()->guard('paciente')->loginUsingId($patientId, false);

        return redirect()->route('paciente.dashboard');
    }
}
