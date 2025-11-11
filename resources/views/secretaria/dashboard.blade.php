@extends('layouts.secretaria')

@section('title', 'Inicio — Secretaría')

@section('secretary-content')

<div class="space-y-8">

  {{-- Encabezado --}}
  <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Hola, {{ $secretary?->nombres ?? 'Juliana' }}
      </h1>
      <p class="text-sm md:text-base text-neutral-600 mt-1">Hoy es {{ now()->locale('es')->translatedFormat('l, j \\d\\e F') }}</p>
    </div>
  </header>


    {{-- Tarjeta principal --}}
    <x-ui.card class="p-8 space-y-8 bg-white border border-neutral-200 shadow-sm">

      {{-- Acciones principales --}}
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
        {{-- Agendar cita --}}
        <a href="{{ route('secretaria.citas.agendar.lookup') }}" class="block">
          <x-ui.card class="bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
            <div class="flex flex-col items-center justify-center py-6">
              <div class="text-3xl mb-2">🗓️</div>
              <p class="font-medium text-neutral-800 text-sm">Agendar cita</p>
            </div>
          </x-ui.card>
        </a>

        {{-- Cancelar cita --}}
        <a href="{{ route('secretaria.citas.cancelar.lookup') }}" class="block">
          <x-ui.card class="bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
            <div class="flex flex-col items-center justify-center py-6">
              <div class="text-3xl mb-2">🚫</div>
              <p class="font-medium text-neutral-800 text-sm">Cancelar cita</p>
            </div>
          </x-ui.card>
        </a>

        {{-- Modificar cita --}}
        <a href="{{ route('secretaria.citas.reprogramar.lookup') }}" class="block">
          <x-ui.card class="bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
            <div class="flex flex-col items-center justify-center py-6">
              <div class="text-3xl mb-2">📝</div>
              <p class="font-medium text-neutral-800 text-sm">Modificar cita</p>
            </div>
          </x-ui.card>
        </a>

        {{-- Crear paciente --}}
        <a href="{{ route('secretaria.pacientes.create') }}" class="block">
          <x-ui.card class="bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
            <div class="flex flex-col items-center justify-center py-6">
              <div class="text-3xl mb-2">👤</div>
              <p class="font-medium text-neutral-800 text-sm">Crear paciente</p>
            </div>
          </x-ui.card>
        </a>

        {{-- Bloquear horario --}}
        <a href="{{ route('secretaria.horarios.bloquear') }}" class="block">
          <x-ui.card class="bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
            <div class="flex flex-col items-center justify-center py-6">
              <div class="text-3xl mb-2">⛔</div>
              <p class="font-medium text-neutral-800 text-sm">Bloquear horario</p>
            </div>
          </x-ui.card>
        </a>
      </div>
        <br>
      {{-- Bandeja de trabajo --}}
      <x-ui.card class="bg-neutral-100 border border-neutral-300 p-5">
        <h2 class="text-base font-semibold text-neutral-900 mb-3">Bandeja de trabajo de hoy</h2>
        <ul class="text-sm text-neutral-700 space-y-1 list-disc pl-5">
          <li>{{ $summary['citas_programadas'] ?? 12 }} citas programadas</li>
          <li>{{ $summary['citas_atendidas'] ?? 5 }} citas atendidas</li>
          <li>{{ $summary['citas_canceladas'] ?? 3 }} citas canceladas</li>
          <li>{{ $summary['pagos_pendientes'] ?? 2 }} pagos pendientes</li>
        </ul>

      </x-ui.card>

    </x-ui.card>
  </div>
@endsection
