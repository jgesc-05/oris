@extends('layouts.secretaria')

@section('title', 'Seleccionar cita — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-4xl">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">citas — reprogramar</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Selecciona la cita para {{ $patient->nombres }} {{ $patient->apellidos }}
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Escoge la cita que deseas reprogramar y continúa con el proceso.
      </p>
    </header>

    @if ($errors->any())
      <x-ui.alert variant="warning">
        {{ $errors->first() }}
      </x-ui.alert>
    @endif

    <x-ui.card class="p-0 overflow-hidden">
      @if ($appointments->isEmpty())
        <div class="p-6 text-sm text-neutral-600">
          El paciente no tiene citas programadas para reprogramar.
        </div>
      @else
        <form method="POST" action="{{ route('secretaria.citas.reprogramar.seleccion.submit', $patient->id_usuario) }}">
          @csrf
          <table class="min-w-full text-sm">
            <thead class="bg-neutral-100 text-neutral-700">
              <tr>
                <th class="px-4 py-2 text-left font-medium uppercase text-xs"></th>
                <th class="px-4 py-2 text-left font-medium uppercase text-xs">Fecha</th>
                <th class="px-4 py-2 text-left font-medium uppercase text-xs">Hora</th>
                <th class="px-4 py-2 text-left font-medium uppercase text-xs">Servicio</th>
                <th class="px-4 py-2 text-left font-medium uppercase text-xs">Médico</th>
                <th class="px-4 py-2 text-left font-medium uppercase text-xs">Estado</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white">
              @foreach ($appointments as $appointment)
                <tr class="hover:bg-neutral-50 transition">
                  <td class="px-4 py-3">
                    <input
                      type="radio"
                      name="cita_id"
                      value="{{ $appointment->id_cita }}"
                      class="form-radio"
                      required
                    >
                  </td>
                  <td class="px-4 py-3">{{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F') }}</td>
                  <td class="px-4 py-3">{{ $appointment->fecha_hora_inicio->format('h:i A') }}</td>
                  <td class="px-4 py-3">{{ $appointment->servicio?->nombre }}</td>
                  <td class="px-4 py-3">{{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}</td>
                  <td class="px-4 py-3">
                    <x-ui.badge variant="{{ $appointment->estado === 'Programada' ? 'success' : 'info' }}">
                      {{ $appointment->estado }}
                    </x-ui.badge>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="border-t border-neutral-200 bg-neutral-50 p-4 flex justify-end gap-3">
            <x-ui.button :href="route('secretaria.citas.reprogramar.lookup')" variant="secondary" size="sm" class="rounded-full">Volver</x-ui.button>
            <x-ui.button variant="primary" size="sm" class="rounded-full px-6">
              Continuar
            </x-ui.button>
          </div>
        </form>
      @endif
    </x-ui.card>
  </div>
@endsection
