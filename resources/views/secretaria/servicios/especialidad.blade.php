@extends('layouts.secretaria')

@section('title', $especialidad['nombre'].' — Servicios')

@section('secretary-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">servicios — {{ strtolower($especialidad['nombre']) }}</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        {{ $especialidad['nombre'] }}
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Lista de servicios disponibles dentro de esta especialidad para asesorar al paciente.
      </p>
    </header>

    <x-ui.card class="space-y-6 p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($servicios as $serv)
          <div class="group block rounded-xl border border-neutral-200 bg-neutral-50 hover:bg-white hover:border-primary-200 shadow-sm hover:shadow-md transition-all duration-200 ease-out p-5">
            <div class="flex items-center gap-5">
              <div class="flex flex-col items-center justify-center w-24 text-center">
                <div class="text-4xl mb-2 text-neutral-700 group-hover:text-primary-600 transition-colors">
                  {{ $serv['icono'] ?? '🩺' }}
                </div>
                <h2 class="font-semibold text-sm text-neutral-900 leading-tight">
                  {{ $serv['nombre'] }}
                </h2>
              </div>

              <div class="h-16 border-l border-neutral-300"></div>

              <div class="flex-1">
                <p class="text-sm text-neutral-600 leading-snug">
                  {{ $serv['descripcion'] }}
                </p>
                <div class="mt-3 flex gap-2">
                  <x-ui.button
                    as="a"
                    :href="route('secretaria.servicios.detalle', ['especialidad' => $especialidad['slug'], 'servicio' => $serv['slug']])"
                    variant="primary"
                    size="sm"
                    class="rounded-full px-4 py-1 text-xs"
                  >
                    Ver detalle
                  </x-ui.button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
        <br>
      <div class="flex flex-col md:flex-row justify-between items-center gap-3">
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.servicios.index') }}">
          ← Volver a especialidades
        </x-ui.button>
        <x-ui.button variant="primary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.citas.agendar.lookup') }}">
          Agendar cita
        </x-ui.button>
      </div>
    </x-ui.card>
  </div>
@endsection
