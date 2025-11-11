<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorPortalController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user();
        $today = now()->toDateString();

        $todayAppointments = Appointment::with(['paciente', 'servicio'])
            ->where('id_usuario_medico', $doctor->id_usuario)
            ->whereDate('fecha_hora_inicio', $today)
            ->orderBy('fecha_hora_inicio')
            ->get();

        $indicators = [
            'programadas' => $todayAppointments->where('estado', Appointment::STATUS_PROGRAMADA)->count(),
            'atendidas' => $todayAppointments->where('estado', Appointment::STATUS_ATENDIDA)->count(),
            'canceladas' => $todayAppointments->where('estado', Appointment::STATUS_CANCELADA)->count(),
            'pacientes'   => $todayAppointments->pluck('id_usuario_paciente')->filter()->unique()->count(),
        ];

        $monthAppointments = Appointment::where('id_usuario_medico', $doctor->id_usuario)
            ->whereBetween('fecha_hora_inicio', [now()->startOfMonth(), now()->endOfMonth()])
            ->get();

        $monthStats = [
            'total'        => $monthAppointments->count(),
            'programadas'  => $monthAppointments->where('estado', Appointment::STATUS_PROGRAMADA)->count(),
            'atendidas'    => $monthAppointments->where('estado', Appointment::STATUS_ATENDIDA)->count(),
            'canceladas'   => $monthAppointments->where('estado', Appointment::STATUS_CANCELADA)->count(),
        ];

        $productivity = $monthStats['total'] > 0
            ? round(($monthStats['atendidas'] / $monthStats['total']) * 100)
            : 0;

        $upcomingAppointments = Appointment::with(['paciente', 'servicio'])
            ->where('id_usuario_medico', $doctor->id_usuario)
            ->where('fecha_hora_inicio', '>=', now())
            ->orderBy('fecha_hora_inicio')
            ->limit(5)
            ->get();

        return view('medico.dashboard', compact(
            'doctor',
            'todayAppointments',
            'indicators',
            'monthStats',
            'productivity',
            'upcomingAppointments'
        ));
    }

    public function patientsIndex(Request $request)
    {
        $doctor = Auth::user();
        $search = trim($request->input('q', ''));

        $patientIds = Appointment::where('id_usuario_medico', $doctor->id_usuario)
            ->pluck('id_usuario_paciente')
            ->filter()
            ->unique()
            ->values();

        $patients = $patientIds->isNotEmpty()
            ? User::whereIn('id_usuario', $patientIds)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($inner) use ($search) {
                        $inner->where('nombres', 'like', "%{$search}%")
                            ->orWhere('apellidos', 'like', "%{$search}%")
                            ->orWhere('numero_documento', 'like', "%{$search}%")
                            ->orWhere('correo_electronico', 'like', "%{$search}%");
                    });
                })
                ->orderBy('nombres')
                ->orderBy('apellidos')
                ->get()
            : collect();

        $latestAppointments = Appointment::where('id_usuario_medico', $doctor->id_usuario)
            ->whereIn('id_usuario_paciente', $patientIds)
            ->select('id_usuario_paciente')
            ->selectRaw('MAX(fecha_hora_inicio) as ultima_cita')
            ->groupBy('id_usuario_paciente')
            ->pluck('ultima_cita', 'id_usuario_paciente');

        $stats = [
            'total'   => $patients->count(),
            'activos' => $patients->where('estado', 'activo')->count(),
        ];
        $stats['inactivos'] = max($stats['total'] - $stats['activos'], 0);

        return view('medico.pacientes.index', compact(
            'patients',
            'stats',
            'search',
            'latestAppointments'
        ));
    }

    public function patientsShow(int $patientId)
    {
        $doctor = Auth::user();

        $hasRelationship = Appointment::where('id_usuario_medico', $doctor->id_usuario)
            ->where('id_usuario_paciente', $patientId)
            ->exists();

        abort_unless($hasRelationship, 404, 'Paciente no asociado a tu agenda.');

        $patient = User::findOrFail($patientId);

        $appointments = Appointment::with('servicio')
            ->where('id_usuario_medico', $doctor->id_usuario)
            ->where('id_usuario_paciente', $patientId)
            ->orderByDesc('fecha_hora_inicio')
            ->get();

        $stats = [
            'total'       => $appointments->count(),
            'atendidas' => $appointments->where('estado', Appointment::STATUS_ATENDIDA)->count(),
            'canceladas'  => $appointments->where('estado', Appointment::STATUS_CANCELADA)->count(),
            'proximas'    => $appointments->filter(fn ($appointment) => $appointment->fecha_hora_inicio && $appointment->fecha_hora_inicio->isFuture())->count(),
        ];

        $lastAppointment = $appointments
            ->filter(fn ($appointment) => $appointment->fecha_hora_inicio && $appointment->fecha_hora_inicio->isPast())
            ->sortByDesc('fecha_hora_inicio')
            ->first();

        $nextAppointment = $appointments
            ->filter(fn ($appointment) => $appointment->fecha_hora_inicio && $appointment->fecha_hora_inicio->isFuture())
            ->sortBy('fecha_hora_inicio')
            ->first();

        $timeline = $appointments->take(6);

        return view('medico.pacientes.show', compact(
            'patient',
            'stats',
            'lastAppointment',
            'nextAppointment',
            'timeline'
        ));
    }

    public function agenda(Request $request)
    {
        $doctor = Auth::user();

        $estado = $request->input('estado');
        if (!in_array($estado, Appointment::allowedStatuses(), true)) {
            $estado = null;
        }

        $filters = [
            'fecha' => $request->input('fecha', now()->toDateString()),
            'estado' => $estado,
            'paciente' => $request->input('paciente'),
        ];

        $query = Appointment::with(['paciente', 'servicio'])
            ->where('id_usuario_medico', $doctor->id_usuario)
            ->orderBy('fecha_hora_inicio');

        if (!empty($filters['fecha'])) {
            $query->whereDate('fecha_hora_inicio', $filters['fecha']);
        }

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['paciente'])) {
            $query->whereHas('paciente', function ($q) use ($filters) {
                $q->where('nombres', 'like', '%'.$filters['paciente'].'%')
                    ->orWhere('apellidos', 'like', '%'.$filters['paciente'].'%')
                    ->orWhere('numero_documento', 'like', '%'.$filters['paciente'].'%');
            });
        }

        $appointments = $query->get();

        return view('medico.agenda.index', compact('appointments', 'filters'));
    }
}
