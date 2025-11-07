@extends('layouts.paciente')

@section('title', 'Mis citas — Paciente')

@section('patient-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Mis citas</h1>

  @if (session('status'))
    <x-ui.alert variant="success" class="mb-4">
      {{ session('status') }}
    </x-ui.alert>
  @endif

  <x-ui.card class="max-w-6xl p-0 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-neutral-100 text-neutral-700">
        <tr>
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
            <td class="px-3 py-2">
              {{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F Y') }}
            </td>
            <td class="px-3 py-2">{{ $appointment->fecha_hora_inicio->format('h:i A') }}</td>
            <td class="px-3 py-2">
              {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}
            </td>
            <td class="px-3 py-2">{{ $appointment->servicio?->nombre }}</td>
            <td class="px-3 py-2">
              <x-ui.badge variant="{{ $appointment->estado === 'Cancelada' ? 'warning' : 'success' }}">
                {{ $appointment->estado }}
              </x-ui.badge>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-3 py-4 text-center text-neutral-600">
              Aún no has registrado citas.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </x-ui.card>
@endsection
