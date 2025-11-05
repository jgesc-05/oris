{{-- resources/views/paciente/home.blade.php (o el que uses) --}}
@extends('layouts.paciente')

@section('title', 'Inicio — Paciente')

@section('patient-content')
@php
  $firstName   = $patient?->nombres ?? 'Javier';
  $currentDate = \Carbon\Carbon::now()->locale('es')->translatedFormat('l, j \d\e F');

  // Si tu controlador ya pasa la próxima cita, usa esa; si no, este mock no rompe nada.
  $cita = $nextAppointment ?? [
    'dia'      => 'Lunes, 30 de septiembre',
    'hora'     => '9:00 AM',
    'doctor'   => 'Dra. Sandra Rodríguez',
    'detalle'  => 'Control Ortodoncia',
    'existe'   => true, // si no hay cita, pon false desde el controlador
  ];
@endphp

<div class="space-y-6">

  {{-- Encabezado --}}
  <header>
    <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Hola, {{ $firstName }}</h1>
    <p class="text-sm md:text-base text-neutral-600 mt-1">Tu próxima cita está programada</p>
  </header>

  {{-- Próxima cita (banner) --}}
  <x-ui.card class="bg-white border-neutral-300">
    @if(!empty($cita['existe']))
      <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
          <div class="text-sm text-neutral-600">{{ $cita['dia'] }}</div>
          <div class="text-sm text-neutral-600">{{ $cita['hora'] }}</div>
        </div>

        <div class="text-right">
          <div class="text-sm font-medium text-neutral-900">{{ $cita['doctor'] }}</div>
          <div class="text-xs text-neutral-600">{{ $cita['detalle'] }}</div>
        </div>
      </div>

      <x-slot name="footer">
        <div class="flex flex-wrap gap-2">
            <x-ui.button variant="secondary" size="sm" href="{{ route('paciente.citas.reprogramar.index') }}">
            Reprogramar
            </x-ui.button>
          <x-ui.button variant="warning" size="sm" href="#">Cancelar</x-ui.button>
        </div>
      </x-slot>
    @else
      <div class="text-neutral-700">Sin citas programadas.</div>
      <x-slot name="footer">
        <x-ui.button variant="primary" size="sm" href="{{ route('paciente.citas.create') }}">Agendar cita</x-ui.button>
      </x-slot>
    @endif
  </x-ui.card>

  {{-- Acciones rápidas (4 tarjetas) --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

    <a href="{{ route('paciente.citas.create') }}"
    class="block focus:outline-none focus:ring-2 focus:ring-primary-400 rounded-[var(--radius)]">
    <x-ui.card class="bg-white hover:bg-neutral-200 transition cursor-pointer text-center">
        <div class="py-4">
        <div class="text-2xl">🗓️</div>
        <div class="mt-2 text-sm font-medium">Agendar cita</div>
        </div>
    </x-ui.card>
    </a>

    <a href="{{ route('paciente.citas.cancelar.index') }}"
    class="block focus:outline-none focus:ring-2 focus:ring-primary-400 rounded-[var(--radius)]">
    <x-ui.card class="bg-white hover:bg-neutral-200 transition cursor-pointer text-center">
        <div class="py-4">
        <div class="text-2xl">⛔</div>
        <div class="mt-2 text-sm font-medium">Cancelar cita</div>
        </div>
    </x-ui.card>
    </a>


    <a href="{{ route('paciente.citas.reprogramar.index') }}"
    class="block focus:outline-none focus:ring-2 focus:ring-primary-400 rounded-[var(--radius)]">
    <x-ui.card class="bg-white hover:bg-neutral-200 transition cursor-pointer text-center">
        <div class="py-4">
        <div class="text-2xl">📝</div>
        <div class="mt-2 text-sm font-medium">Modificar cita</div>
        </div>
    </x-ui.card>
    </a>


    <a href="{{ route('paciente.citas.index') }}"
    class="block focus:outline-none focus:ring-2 focus:ring-primary-400 rounded-[var(--radius)]">
    <x-ui.card class="bg-white hover:bg-neutral-200 transition cursor-pointer text-center">
        <div class="py-4">
        <div class="text-2xl">👤</div>
        <div class="mt-2 text-sm font-medium">Mis citas</div>
        </div>
    </x-ui.card>
    </a>

  </div>

  {{-- Historial de citas --}}
  <div>
    <h2 class="text-base font-semibold text-neutral-900 mb-2">Historial de citas</h2>
    <x-ui.card class="p-0 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-neutral-100 text-neutral-700">
          <tr>
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Odontólogo</th>
            <th class="px-3 py-2 text-left">Servicio</th>
            <th class="px-3 py-2 text-left">Estado</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-neutral-200">
          <tr>
            <td class="px-3 py-2">20 de Junio</td>
            <td class="px-3 py-2">Antonio Londoño</td>
            <td class="px-3 py-2">Limpieza dental</td>
            <td class="px-3 py-2">Completada</td>
          </tr>
          <tr>
            <td class="px-3 py-2">30 de agosto</td>
            <td class="px-3 py-2">Sandra Rodríguez</td>
            <td class="px-3 py-2">Control de ortodoncia</td>
            <td class="px-3 py-2">Cancelada</td>
          </tr>
        </tbody>
      </table>
    </x-ui.card>
  </div>

</div>
@endsection
