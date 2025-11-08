@extends('layouts.paciente')

@section('title', 'Médicos por especialidad')

@section('patient-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">médicos — paciente</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Médicos activos
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Especialistas disponibles en esta área para acompañar tu proceso de cuidado.
      </p>
    </header>

    <x-ui.card class="space-y-6 p-6">
      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($doctors as $doctor)
          <div class="group block rounded-xl border border-neutral-200 bg-neutral-50 hover:bg-white hover:border-rose-200 shadow-sm hover:shadow-md transition-all duration-200 ease-out p-5">
            <div class="space-y-2">
              <h2 class="text-lg font-semibold text-neutral-900">{{ $doctor['nombre'] }}</h2>
              <p class="text-sm text-neutral-600 leading-snug">{{ $doctor['descripcion'] }}</p>
              <div class="text-xs text-neutral-500">
                <strong>Universidad:</strong> {{ $doctor['universidad'] }}
              </div>
              <div class="text-xs text-neutral-500">
                <strong>Experiencia:</strong> {{ $doctor['experiencia'] }}
              </div>
              <div class="mt-3 flex gap-3">
                <x-ui.button variant="primary" size="sm" class="rounded-full px-4 py-1 text-xs"
                  href="{{ route('paciente.citas.create') }}">
                  Agendar cita
                </x-ui.button>
                <x-ui.button variant="ghost" size="sm" class="rounded-full px-4 py-1 text-xs"
                  href="{{ route('paciente.medicos.detalle', ['especialidad' => $especialidadSlug, 'medico' => $doctor['slug']]) }}">
                  Ver perfil
                </x-ui.button>
              </div>
            </div>
          </div>
        @endforeach
      </section>

      <br>

      <div class="flex flex-col md:flex-row justify-between items-center gap-3">
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('paciente.medicos') }}">
          ← Volver a especialidades
        </x-ui.button>
        <x-ui.button variant="primary" size="md" class="rounded-full px-6"
          href="{{ route('paciente.citas.create') }}">
          Agendar cita
        </x-ui.button>
      </div>
    </x-ui.card>
  </div>
@endsection
