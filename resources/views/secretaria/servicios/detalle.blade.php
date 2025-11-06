@extends('layouts.secretaria')

@section('title', $servicio['nombre'].' — Detalle del servicio')

@section('secretary-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">servicios — detalle</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">{{ $servicio['nombre'] }}</h1>
      <p class="text-sm md:text-base text-neutral-600">
        Información para orientar al paciente sobre este servicio.
      </p>
    </header>

    <x-ui.card class="bg-white border border-neutral-200 shadow-sm">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-5">
        <div>
          <div class="text-sm text-neutral-600">
            <strong>Especialidad:</strong> {{ $servicio['especialidad'] }}
          </div>
          <div class="mt-1 text-sm text-neutral-600">
            <strong>Duración aproximada:</strong> {{ $servicio['duracion'] ?? '30 minutos' }}
          </div>
          <div class="mt-1 text-sm text-neutral-600">
            <strong>Profesional a cargo:</strong> {{ $servicio['doctor'] ?? 'Equipo médico especializado' }}
          </div>
        </div>

        <div class="text-5xl text-neutral-700 md:ml-4">
          {{ $servicio['icono'] ?? '🩺' }}
        </div>
      </div>

      <div class="border-t border-neutral-200 p-5">
        <h2 class="text-base font-semibold text-neutral-900 mb-2">Descripción detallada</h2>
        <p class="text-sm text-neutral-700 leading-relaxed">
          {{ $servicio['descripcion_larga'] ?? 'Este servicio está enfocado en brindar atención médica personalizada según las necesidades del paciente, con procesos claros para orientación, diagnóstico y continuidad de cuidado.' }}
        </p>
      </div>

      <div class="border-t border-neutral-200 bg-neutral-50 p-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.servicios.especialidad', ['especialidad' => $servicio['especialidad_slug']]) }}">
          ← Volver a {{ $servicio['especialidad'] }}
        </x-ui.button>

        <x-ui.button variant="primary" size="lg" class="rounded-full px-8 shadow-sm hover:shadow-md"
          href="{{ route('secretaria.citas.agendar.lookup') }}">
          Agendar cita
        </x-ui.button>
      </div>
    </x-ui.card>
  </div>
@endsection
