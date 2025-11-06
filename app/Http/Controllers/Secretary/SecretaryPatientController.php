<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SecretaryPatientController extends Controller
{
    public function index()
    {
        $patients = User::whereHas('userType', function ($query) {
            $query->where('nombre', 'Paciente');
        })->latest('created_at')->take(10)->get();

        return view('secretaria.pacientes.index', compact('patients'));
    }

    public function show(User $patient)
    {
        if ($patient->userType?->nombre !== 'Paciente') {
            abort(404);
        }

        return view('secretaria.pacientes.show', compact('patient'));
    }

    public function create()
    {
        return view('secretaria.pacientes.create');
    }

    public function store(Request $request)
    {
        $patientTypeId = UserType::where('nombre', 'Paciente')->value('id_tipo_usuario');

        if (!$patientTypeId) {
            return back()->withErrors(['error' => 'No se encontró el tipo de usuario paciente.'])->withInput();
        }

        $data = $request->validate([
            'id_tipo_documento' => ['required', 'string', 'max:3'],
            'numero_documento'  => ['required', 'string', 'max:20', 'unique:users,numero_documento'],
            'nombres'           => ['required', 'string', 'max:100'],
            'apellidos'         => ['required', 'string', 'max:100'],
            'fecha_nacimiento'  => ['required', 'date'],
            'correo_electronico'=> ['required', 'email', 'unique:users,correo_electronico'],
            'telefono'          => ['nullable', 'string', 'max:20'],
            'observaciones'     => ['nullable', 'string', 'max:255'],
        ]);

        $patient = User::create([
            'id_tipo_usuario'   => $patientTypeId,
            'id_tipo_documento' => $data['id_tipo_documento'],
            'numero_documento'  => $data['numero_documento'],
            'nombres'           => $data['nombres'],
            'apellidos'         => $data['apellidos'],
            'fecha_nacimiento'  => $data['fecha_nacimiento'],
            'correo_electronico'=> $data['correo_electronico'],
            'telefono'          => $data['telefono'] ?? null,
            'observaciones'     => $data['observaciones'] ?? null,
            'estado'            => 'activo',
            'password'          => Str::random(12),
        ]);

        return redirect()
            ->route('secretaria.pacientes.show', $patient->id_usuario)
            ->with('status', 'Paciente registrado correctamente.');
    }
}
