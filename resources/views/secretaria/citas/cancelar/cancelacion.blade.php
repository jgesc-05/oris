@extends('layouts.secretaria')

@section('title', 'Cita cancelada — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-3xl">
    <x-ui.card class="space-y-4 p-6 text-center">
      <div class="text-5xl">🗑️</div>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">La cita fue cancelada</h1>
      <p class="text-sm md:text-base text-neutral-600">
        Se registró la cancelación de la cita seleccionada. A continuación, el resumen del procedimiento.
      </p>

      <div class="border border-neutral-200 rounded-[var(--radius)] p-4 text-left space-y-2">
        <p><strong>Paciente:</strong> {{ $patient->nombres }} {{ $patient->apellidos }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($appointment['fecha'])->translatedFormat('l j \\d\\e F') }}</p>
        <p><strong>Hora:</strong> {{ $appointment['hora_humana'] }}</p>
        <p><strong>Servicio:</strong> {{ $appointment['servicio'] }}</p>
        <p><strong>Médico:</strong> {{ $appointment['medico'] }}</p>
        <p><strong>Estado previo:</strong> {{ $appointment['estado'] }}</p>
      </div>

      <div class="flex flex-col gap-3 md:flex-row md:justify-center">
        <x-ui.button variant="primary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.citas.cancelar.lookup') }}">
          Cancelar otra cita
        </x-ui.button>
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.inicio') }}">
          Volver al inicio
        </x-ui.button>
      </div>
    </x-ui.card>
  </div>
@endsection
