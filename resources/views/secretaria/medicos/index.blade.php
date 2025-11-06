@extends('layouts.secretaria')

@section('title', 'Médicos — Secretaría')

@section('secretary-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">médicos — secretaría</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Especialidades médicas
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Consulta los especialistas disponibles por área para orientar tus gestiones.
      </p>
    </header>

    <x-ui.card class="space-y-6 p-6">
      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($especialidades as $esp)
          <div class="group rounded-xl border border-neutral-200 bg-white hover:border-primary-200 shadow-sm hover:shadow-md transition-all duration-200 ease-out p-5 focus-within:outline-none focus-within:ring-2 focus-within:ring-primary-300">
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
                    :href="route('secretaria.medicos.especialidad', ['especialidad' => $esp['slug']])">
                    Ver médicos
                  </x-ui.button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </section>

      <x-ui.alert variant="info" class="mb-0">
        ¿No encuentras la especialidad que buscas? Contáctanos para confirmar disponibilidad.
      </x-ui.alert>
    </x-ui.card>
  </div>
@endsection
