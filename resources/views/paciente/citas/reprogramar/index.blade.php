{{-- resources/views/paciente/citas/reprogramar/index.blade.php --}}
@extends('layouts.paciente')

@section('title', 'Reprogramar cita — Paciente')

@section('patient-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Reprogramar cita</h1>

  <x-ui.card class="max-w-5xl p-0">
    <form method="POST" action="{{ route('paciente.citas.reprogramar.submit') }}" class="flex flex-col gap-0">
      @csrf

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-100 text-neutral-700">
            <tr>
              <th class="px-3 py-2 w-10"></th>
              <th class="px-3 py-2 text-left">Fecha</th>
              <th class="px-3 py-2 text-left">Hora</th>
              <th class="px-3 py-2 text-left">Médico</th>
              <th class="px-3 py-2 text-left">Servicio</th>
              <th class="px-3 py-2 text-left">Estado</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-200">
            @forelse($appointments as $appointment)
              <tr>
                <td class="px-3 py-2 text-center">
                  <input type="radio" name="cita_id" value="{{ $appointment->id_cita }}" class="form-radio" required>
                </td>
                <td class="px-3 py-2">
                  {{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F') }}
                </td>
                <td class="px-3 py-2">{{ $appointment->fecha_hora_inicio->format('h:i A') }}</td>
                <td class="px-3 py-2">
                  {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}
                </td>
                <td class="px-3 py-2">{{ $appointment->servicio?->nombre }}</td>
                <td class="px-3 py-2">
                  <x-appointment.status-badge :estado="$appointment->estado" />
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-3 py-4 text-center text-neutral-600">
                  No tienes citas próximas para reprogramar.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="border-t border-neutral-200 p-4">
        <x-ui.button
          variant="primary"
          size="lg"
          block
          class="rounded-full"
          :disabled="($appointments ?? collect())->isEmpty()"
        >
          Reprogramar cita
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
