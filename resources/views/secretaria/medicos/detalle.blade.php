@extends('layouts.secretaria')

@section('title', $medico['nombre'].' — Perfil médico')

@section('secretary-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">médicos — perfil</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">{{ $medico['nombre'] }}</h1>
      <p class="text-sm md:text-base text-neutral-600">
        Especialista en {{ strtolower($medico['especialidad']) }}. Utiliza esta información para orientar a los pacientes.
      </p>
    </header>

    <x-ui.card class="bg-white border border-neutral-200 shadow-sm">
      <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 p-5">
        <div class="space-y-3 md:w-2/3">
          <div>
            <h2 class="text-lg font-semibold text-neutral-900">Especialidad</h2>
            <p class="text-sm text-neutral-600">{{ $medico['especialidad'] }}</p>
          </div>

          <div>
            <h2 class="text-lg font-semibold text-neutral-900">Descripción</h2>
            <p class="text-sm text-neutral-600 leading-relaxed">
              {{ $medico['descripcion'] }}
            </p>
          </div>

          <div>
            <h2 class="text-lg font-semibold text-neutral-900">Formación universitaria</h2>
            <p class="text-sm text-neutral-600">{{ $medico['formacion'] }}</p>
          </div>

          <div>
            <h2 class="text-lg font-semibold text-neutral-900">Experiencia</h2>
            <p class="text-sm text-neutral-600">{{ $medico['experiencia'] }}</p>
          </div>

          <div>
            <h2 class="text-lg font-semibold text-neutral-900">Disponibilidad</h2>
            <p class="text-sm text-neutral-600">{{ $medico['disponibilidad'] }}</p>
          </div>
        </div>

        <div class="md:w-1/3 flex flex-col items-center gap-4">
          <div class="text-6xl">{{ $medico['icono'] ?? '👨‍⚕️' }}</div>
          <x-ui.button variant="primary" size="lg" class="rounded-full px-8 shadow-sm hover:shadow-md"
            href="{{ route('secretaria.citas.agendar.lookup') }}">
            Agendar cita
          </x-ui.button>
        </div>
      </div>

      <div class="border-t border-neutral-200 bg-neutral-50 p-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.medicos.especialidad', ['especialidad' => $medico['especialidad_slug']]) }}">
          ← Volver a {{ $medico['especialidad'] }}
        </x-ui.button>
        <x-ui.button variant="ghost" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.medicos.index') }}">
          Ver todas las especialidades
        </x-ui.button>
      </div>
    </x-ui.card>
  </div>
@endsection
