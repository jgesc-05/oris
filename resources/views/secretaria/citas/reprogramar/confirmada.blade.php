@extends('layouts.secretaria')

@section('title', 'Cita reprogramada — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-3xl">
    <x-ui.card class="space-y-4 p-6 text-center">
      <div class="text-5xl">🔄</div>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">La cita fue reprogramada</h1>
      <p class="text-sm md:text-base text-neutral-600">
        Se actualizó la cita con la nueva información. A continuación, el resumen del cambio.
      </p>

      <div class="border border-neutral-200 rounded-[var(--radius)] p-4 text-left space-y-2">
        <p><strong>Fecha y hora:</strong> {{ $appointment['fecha_hora'] }}</p>
        <p><strong>Servicio:</strong> {{ $appointment['servicio'] }}</p>
        <p><strong>Médico:</strong> {{ $appointment['doctor'] }}</p>
        <p><strong>Referencia:</strong> {{ $appointment['referencia'] }}</p>
      </div>

      <div class="flex flex-col gap-3 md:flex-row md:justify-center">
        <x-ui.button variant="primary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.citas.reprogramar.lookup') }}">
          Reprogramar otra cita
        </x-ui.button>
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.inicio') }}">
          Volver al inicio
        </x-ui.button>
      </div>
    </x-ui.card>
  </div>
@endsection
