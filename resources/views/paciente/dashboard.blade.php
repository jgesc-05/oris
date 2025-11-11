@extends('layouts.paciente')

@section('title', 'Inicio — Paciente')

@section('patient-content')
@php
  $firstName   = $patient?->nombres ?? 'Javier';
  $currentDate = \Carbon\Carbon::now()->locale('es')->translatedFormat('l, j \\d\\e F');
  $proximaCita = $nextAppointment ?? null;
@endphp

<div class="space-y-8">

  {{-- Encabezado --}}
  <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Hola, {{ $firstName }}</h1>
      <p class="text-sm md:text-base text-neutral-600 mt-1">Hoy es {{ $currentDate }}</p>
    </div>
  </header>

  <x-ui.card class="space-y-6 p-6">
    {{-- Próxima cita destacada --}}
    @if($proximaCita)
      <div class="relative bg-rose-50 border border-rose-200 rounded-[var(--radius)] p-6 overflow-hidden">
        <div class="absolute right-4 top-4 text-5xl opacity-20 select-none">🩺</div>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
          <div>
            <h2 class="text-lg font-semibold text-rose-900">Tu próxima cita</h2>
            <p class="text-sm text-neutral-700 mt-1">
              {{ $proximaCita->fecha_hora_inicio->locale('es')->translatedFormat('l, j \\d\\e F') }}
              — {{ $proximaCita->fecha_hora_inicio->format('h:i A') }}<br>
              <span class="font-medium text-neutral-900">
                {{ $proximaCita->medico?->nombres }} {{ $proximaCita->medico?->apellidos }}
              </span><br>
              {{ $proximaCita->servicio?->nombre }}
            </p>
          </div>

          <div class="flex gap-2 mt-2 md:mt-0">
            <x-ui.button variant="secondary" size="sm" href="{{ route('paciente.citas.reprogramar.index') }}">Reprogramar</x-ui.button>
            <x-ui.button variant="warning" size="sm" href="{{ route('paciente.citas.cancelar.index') }}">Cancelar</x-ui.button>
          </div>
        </div>
      </div>
    @else
      <div class="bg-neutral-50 text-center py-6 border border-neutral-200 rounded-[var(--radius)]">
        <p class="text-neutral-700">No tienes citas programadas.</p>
        <div class="mt-3">
          <x-ui.button variant="primary" size="sm" href="{{ route('paciente.citas.create') }}">Agendar cita</x-ui.button>
        </div>
      </div>
    @endif
    <br>
    {{-- Acciones rápidas --}}
    <div>
      <h2 class="text-base font-semibold text-neutral-900 mb-3">Accesos rápidos</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
          $acciones = [
            ['icono' => '🗓️', 'texto' => 'Agendar cita', 'ruta' => route('paciente.citas.create')],
            ['icono' => '⛔', 'texto' => 'Cancelar cita', 'ruta' => route('paciente.citas.cancelar.index')],
            ['icono' => '📝', 'texto' => 'Modificar cita', 'ruta' => route('paciente.citas.reprogramar.index')],
            ['icono' => '📋', 'texto' => 'Mis citas', 'ruta' => route('paciente.citas.index')],
          ];
        @endphp

        @foreach ($acciones as $a)
          <a href="{{ $a['ruta'] }}"
             class="group block bg-white hover:bg-rose-50 border border-neutral-200 rounded-[var(--radius)] p-5 text-center transition shadow-sm hover:shadow-md">
            <div class="flex flex-col items-center justify-center gap-2">
              <div class="text-3xl group-hover:scale-110 transition">{{ $a['icono'] }}</div>
              <div class="text-sm font-medium text-neutral-900 group-hover:text-rose-700">{{ $a['texto'] }}</div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
    <br>
    {{-- Historial de citas --}}
    <div>
      <h2 class="text-base font-semibold text-neutral-900 mb-2">Historial de citas</h2>
      <div class="border border-neutral-200 rounded-[var(--radius)] overflow-hidden">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-100 text-neutral-700 border-b border-neutral-200">
            <tr>
              <th class="px-4 py-2 text-left font-medium">Fecha</th>
              <th class="px-4 py-2 text-left font-medium">Médico</th>
              <th class="px-4 py-2 text-left font-medium">Servicio</th>
              <th class="px-4 py-2 text-left font-medium">Estado</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-200">
            @forelse(($recentAppointments ?? []) as $appointment)
              <tr class="hover:bg-neutral-50 transition">
                <td class="px-4 py-2">
                  {{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F, h:i A') }}
                </td>
                <td class="px-4 py-2">
                  {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}
                </td>
                <td class="px-4 py-2">{{ $appointment->servicio?->nombre }}</td>
                <td class="px-4 py-2">
                  <x-appointment.status-badge :estado="$appointment->estado" />
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-4 text-center text-neutral-600">
                  Sin historial disponible aún.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </x-ui.card>
</div>
@endsection
