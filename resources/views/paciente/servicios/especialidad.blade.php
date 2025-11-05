@extends('layouts.paciente')

@section('title', $especialidad['nombre'].' — Servicios')

@section('patient-content')
  {{-- Encabezado --}}
  <div class="mb-8 text-left">
    <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
      {{ $especialidad['nombre'] }}
    </h1>
    <p class="text-neutral-600 text-sm mt-1">
      Conoce los servicios disponibles dentro de esta especialidad
    </p>
  </div>

  {{-- Grid de servicios (3 por fila, estilo horizontal) --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    @foreach ($servicios as $serv)
      <div class="group block rounded-xl border border-neutral-200 bg-neutral-50 hover:bg-white hover:border-rose-200 shadow-sm hover:shadow-md transition-all duration-200 ease-out p-5">
        <div class="flex items-center gap-5">
          {{-- Ícono o imagen del servicio --}}
          <div class="flex flex-col items-center justify-center w-24 text-center">
            <div class="text-4xl mb-2 text-neutral-700 group-hover:text-rose-700 transition-colors">
              {{ $serv['icono'] ?? '🩺' }}
            </div>
            <h2 class="font-semibold text-sm text-neutral-900 leading-tight">
              {{ $serv['nombre'] }}
            </h2>
          </div>

          {{-- Línea divisoria vertical --}}
          <div class="h-16 border-l border-neutral-300"></div>

          {{-- Descripción y botón --}}
          <div class="flex-1">
            <p class="text-sm text-neutral-600 leading-snug">
              {{ $serv['descripcion'] }}
            </p>
            <div class="mt-3">
              <x-ui.button variant="primary" size="sm" class="rounded-full px-4 py-1 text-xs">
                Agendar cita
              </x-ui.button>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Botón general --}}
  <div class="text-center">
    <x-ui.button variant="secondary" size="lg" class="rounded-full px-8 shadow-sm hover:shadow-md"
                 href="{{ route('paciente.servicios') }}">
      ← Volver a especialidades
    </x-ui.button>
  </div>
@endsection
