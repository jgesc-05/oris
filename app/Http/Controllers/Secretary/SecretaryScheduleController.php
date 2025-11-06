<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\ScheduleBlock;
use App\Models\User;
use Illuminate\Http\Request;

class SecretaryScheduleController extends Controller
{
    public function showBlockForm(Request $request)
    {
        $medicos = User::whereHas('userType', function ($query) {
                $query->where('nombre', 'Médico');
            })
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get(['id_usuario', 'nombres', 'apellidos']);

        $blocks = ScheduleBlock::with('medico')
            ->orderByDesc('fecha')
            ->orderByDesc('hora_desde')
            ->limit(15)
            ->get();

        return view('secretaria.horarios.bloquear', compact('medicos', 'blocks'));
    }

    public function storeBlock(Request $request)
    {
        $data = $request->validate([
            'medico_id'  => ['required', 'integer'],
            'fecha'      => ['required', 'date', 'after_or_equal:today'],
            'hora_desde' => ['required', 'date_format:H:i'],
            'hora_hasta' => ['required', 'date_format:H:i', 'after:hora_desde'],
            'motivo'     => ['nullable', 'string', 'max:255'],
        ]);

        $medico = User::where('id_usuario', $data['medico_id'])
            ->whereHas('userType', fn ($query) => $query->where('nombre', 'Médico'))
            ->firstOrFail();

        $overlap = ScheduleBlock::where('medico_id', $medico->id_usuario)
            ->where('fecha', $data['fecha'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('hora_desde', [$data['hora_desde'], $data['hora_hasta']])
                    ->orWhereBetween('hora_hasta', [$data['hora_desde'], $data['hora_hasta']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('hora_desde', '<=', $data['hora_desde'])
                          ->where('hora_hasta', '>=', $data['hora_hasta']);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors(['hora_desde' => 'El médico ya tiene un bloqueo que se superpone con el horario elegido.'])
                ->withInput();
        }

        ScheduleBlock::create([
            'medico_id'  => $medico->id_usuario,
            'fecha'      => $data['fecha'],
            'hora_desde' => $data['hora_desde'],
            'hora_hasta' => $data['hora_hasta'],
            'motivo'     => $data['motivo'] ?? null,
            'created_by' => $request->user()?->id_usuario,
        ]);

        return redirect()
            ->route('secretaria.horarios.bloquear')
            ->with('status', 'Horario bloqueado correctamente.');
    }
}
