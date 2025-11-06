@extends('layouts.secretaria')

@section('title', $especialidad['nombre'].' — Médicos')

@section('secretary-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">médicos — {{ strtolower($especialidad['nombre']) }}</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Especialistas en {{ $especialidad['nombre'] }}
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Personal médico disponible para atender a los pacientes en esta especialidad.
      </p>
    </header>

    <x-ui.card class="space-y-6 p-6">
      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($medicos as $medico)
          <div class="group rounded-xl border border-neutral-200 bg-white hover:border-primary-200 shadow-sm hover:shadow-md transition-all duration-200 ease-out p-5">
            <div class="space-y-2">
              <h2 class="text-lg font-semibold text-neutral-900">{{ $medico['nombre'] }}</h2>
              <p class="text-sm text-neutral-600 leading-snug">{{ $medico['descripcion'] }}</p>
              <div class="text-xs text-neutral-500">
                <strong>Formación:</strong> {{ $medico['formacion'] }}
              </div>
              <div class="text-xs text-neutral-500">
                <strong>Experiencia:</strong> {{ $medico['experiencia'] }}
              </div>
              <div class="text-xs text-neutral-500">
                <strong>Disponibilidad:</strong> {{ $medico['disponibilidad'] ?? 'Consultar agenda' }}
              </div>
              <div class="mt-3 flex gap-3">
                <x-ui.button variant="primary" size="sm" class="rounded-full px-4 py-1 text-xs"
                  href="{{ route('secretaria.citas.agendar.lookup') }}">
                  Agendar
                </x-ui.button>
                <x-ui.button variant="ghost" size="sm" class="rounded-full px-4 py-1 text-xs"
                  :href="route('secretaria.medicos.detalle', ['especialidad' => $medico['especialidad_slug'], 'medico' => $medico['slug']])">
                  Ver perfil
                </x-ui.button>
              </div>
            </div>
          </div>
        @endforeach
      </section>

      <div class="flex flex-col md:flex-row justify-between items-center gap-3">
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.medicos.index') }}">
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
