@extends('layouts.paciente')

@section('title', 'Servicios — Paciente')

@section('patient-content')
  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">servicios — paciente</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Servicios disponibles para ti
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Explora las opciones que tenemos para acompañarte en tu cuidado. Puedes agendar o solicitar más información cuando lo necesites.
      </p>
    </header>

    <section class="grid gap-4 md:grid-cols-2">
      @foreach ($services as $service)
        <x-ui.card :title="$service['title']">
          <p class="text-sm text-neutral-700">{{ $service['description'] }}</p>
          <x-slot name="footer">
            <div class="flex flex-wrap gap-3">
              <x-ui.button variant="primary" size="sm" href="#">Agendar servicio</x-ui.button>
              <x-ui.button variant="ghost" size="sm" href="#">Solicitar información</x-ui.button>
            </div>
          </x-slot>
        </x-ui.card>
      @endforeach
    </section>

    <x-ui.alert variant="success" title="¿Necesitas ayuda?">
      Nuestro equipo está listo para orientarte. Escríbenos desde el portal o comunícate al (601) 555 1234 para resolver tus dudas.
    </x-ui.alert>
  </div>
@endsection
