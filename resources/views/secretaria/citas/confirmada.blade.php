@extends('layouts.secretaria')

@section('title', 'Cita agendada — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-3xl">
    <x-ui.card class="space-y-4 p-6 text-center">
      <div class="text-5xl">✅</div>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Cita agendada correctamente</h1>
      <p class="text-sm md:text-base text-neutral-600">
        Se ha registrado la cita para {{ $appointment['paciente'] ?? 'el paciente' }}. A continuación encontrarás el resumen.
      </p>

      <div class="border border-neutral-200 rounded-[var(--radius)] p-4 text-left space-y-2">
        <p><strong>Fecha y hora:</strong> {{ $appointment['fecha_hora'] }}</p>
        <p><strong>Servicio:</strong> {{ $appointment['servicio'] }}</p>
        <p><strong>Especialidad:</strong> {{ $appointment['especialidad'] ?? '—' }}</p>
        <p><strong>Médico:</strong> {{ $appointment['doctor'] }}</p>
        <p><strong>Referencia:</strong> {{ $appointment['referencia'] }}</p>
      </div>

      <div class="flex flex-col gap-3 md:flex-row md:justify-center">
        <x-ui.button variant="primary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.citas.agendar.lookup') }}">
          Agendar otra cita
        </x-ui.button>
        <x-ui.button variant="secondary" size="md" class="rounded-full px-6"
          href="{{ route('secretaria.inicio') }}">
          Volver al inicio
        </x-ui.button>
      </div>
    </x-ui.card>
  </div>
@endsection
