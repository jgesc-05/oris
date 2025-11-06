<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecretaryScheduleController extends Controller
{
    public function showBlockForm()
    {
        // TODO: pasar agenda/medicos si aplica
        return view('secretaria.horarios.bloquear');
    }

    public function storeBlock(Request $request)
    {
        $data = $request->validate([
            'medico_id' => ['required','integer'],
            'fecha'     => ['required','date'],
            'hora_desde'=> ['required','date_format:H:i'],
            'hora_hasta'=> ['required','date_format:H:i','after:hora_desde'],
            'motivo'    => ['nullable','string','max:255'],
        ]);

        // TODO: persistir el bloqueo en la BD

        return redirect()
            ->route('secretaria.inicio')
            ->with('status', 'Horario bloqueado correctamente.');
    }
}
