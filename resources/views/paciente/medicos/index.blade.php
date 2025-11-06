@extends('layouts.paciente')

@section('title', 'Médicos — Paciente')

@section('patient-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">médicos — paciente</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Especialidades médicas
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Selecciona una especialidad para conocer a los médicos disponibles y su experiencia.
      </p>
    </header>

    <x-ui.card class="space-y-6 p-6">
      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($especialidades as $esp)
          <div class="group block rounded-xl border border-neutral-200 bg-neutral-50 hover:bg-white hover:border-rose-200 shadow-sm hover:shadow-md transition-all duration-200 ease-out p-5">
            <div class="flex items-center gap-5">
              <div class="flex flex-col items-center justify-center w-24 text-center">
                <div class="text-4xl mb-2 text-neutral-700 group-hover:text-primary-600 transition-colors">
                  {{ $esp['icono'] }}
                </div>
                <h2 class="font-semibold text-sm text-neutral-900 leading-tight">
                  {{ $esp['nombre'] }}
                </h2>
              </div>

              <div class="h-16 border-l border-neutral-300"></div>

              <div class="flex-1">
                <p class="text-sm text-neutral-600 leading-snug">
                  {{ $esp['descripcion'] }}
                </p>
                <div class="mt-3">
                  <x-ui.button variant="primary" size="sm" class="rounded-full px-4 py-1 text-xs"
                    :href="route('paciente.medicos.especialidad', ['especialidad' => $esp['slug']])">
                    Ver médicos
                  </x-ui.button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </section>
        <br>
      <x-ui.alert variant="info" class="mb-0">
        ¿Buscas una especialidad diferente? Contáctanos y te ayudaremos a encontrar la mejor opción para ti.
      </x-ui.alert>
    </x-ui.card>
  </div>
@endsection
