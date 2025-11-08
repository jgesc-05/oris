@extends('layouts.paciente')

@section('title', $servicio['nombre'].' — Detalles del servicio')

@section('patient-content')
  {{-- Encabezado --}}
  <div class="mb-8 text-left">
    <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">{{ $servicio['nombre'] }}</h1>
    <p class="text-neutral-600 text-sm mt-1">
      {{ $servicio['descripcion_corta'] ?? 'Conoce más sobre este servicio médico especializado.' }}
    </p>
  </div>

  {{-- Tarjeta de detalle --}}
  <x-ui.card class="bg-white border border-neutral-200 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between p-5 gap-4">
      <div>
        <div class="text-sm text-neutral-600">
          <strong>Especialidad:</strong> {{ $servicio['especialidad'] }}
        </div>
        <div class="mt-1 text-sm text-neutral-600">
        <strong>Duración aproximada:</strong> {{ ($servicio['duracion'] ?? '30') . ' minutos' }}
        </div>
      </div>

      <div class="text-5xl text-neutral-700 md:ml-4">
        {{ $servicio['icono'] ?? '🩺' }}
      </div>
    </div>

    <div class="border-t border-neutral-200 p-5">
      <h2 class="text-base font-semibold text-neutral-900 mb-2">Descripción detallada</h2>
      <p class="text-sm text-neutral-700 leading-relaxed">
        {{ $servicio['descripcion_larga'] ?? 'Este servicio está enfocado en brindar atención médica personalizada según tus necesidades, con los más altos estándares de calidad y tecnología médica avanzada.' }}
      </p>
    </div>

    <div class="border-t border-neutral-200 bg-neutral-50 p-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <x-ui.button
    variant="secondary"
    size="md"
    class="rounded-full px-6"
    href="{{ route('paciente.servicios.especialidad', ['slug' => $especialidad['slug']]) }}"
>
    ← Volver a {{ $servicio['especialidad'] }}
</x-ui.button>


      <x-ui.button
        variant="primary"
        size="lg"
        class="rounded-full px-8 shadow-sm hover:shadow-md"
        href="{{ route('paciente.citas.create') }}"
      >
        Agendar cita
      </x-ui.button>
    </div>
  </x-ui.card>
@endsection
