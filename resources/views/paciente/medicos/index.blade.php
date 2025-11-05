@extends('layouts.paciente')

@section('title', 'Médicos — Paciente')

@section('patient-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">médicos — paciente</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Tu equipo de especialistas
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Conoce quiénes te acompañan en tu proceso. Puedes revisar la disponibilidad y agendar una cita directamente.
      </p>
    </header>

    <section class="grid gap-4 md:grid-cols-2">
      @foreach ($doctors as $doctor)
        <x-ui.card>
          <div class="flex flex-col gap-2">
            <h2 class="text-lg font-semibold text-neutral-900">{{ $doctor['name'] }}</h2>
            <p class="text-sm font-medium text-primary-700">{{ $doctor['specialty'] }}</p>
            <p class="text-sm text-neutral-600">{{ $doctor['availability'] }}</p>
          </div>
          <x-slot name="footer">
            <div class="flex flex-wrap gap-3">
              <x-ui.button variant="primary" size="sm" href="#">Agendar cita</x-ui.button>
              <x-ui.button variant="ghost" size="sm" href="#">Ver perfil</x-ui.button>
            </div>
          </x-slot>
        </x-ui.card>
      @endforeach
    </section>

    <x-ui.alert variant="info">
      ¿Buscas un especialista diferente? Contáctanos y te ayudaremos a encontrar la mejor opción para ti.
    </x-ui.alert>
  </div>
@endsection
