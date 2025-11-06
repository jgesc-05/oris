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

  <x-ui.card class="space-y-6 p-6">
    {{-- Grid de servicios (3 por fila, estilo horizontal) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
              <x-ui.button
                as="a"
                :href="route('paciente.servicios.detalle', ['especialidad' => $especialidad['slug'], 'servicio' => $serv['slug']])"
                variant="info"
                size="sm"
                class="rounded-full px-4 py-1 text-xs"
              >
                Ver más
              </x-ui.button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <br>
    {{-- Botones de navegación --}}
    <div class="flex flex-col gap-3 md:flex-row md:justify-between md:items-center">
      <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
                   href="{{ route('paciente.servicios') }}">
        ← Volver a especialidades
      </x-ui.button>

      <x-ui.button variant="primary" size="md" class="rounded-full px-6"
                   href="{{ route('paciente.citas.create') }}">
        Agendar cita
      </x-ui.button>
    </div>
  </x-ui.card>
@endsection
