@extends('layouts.secretaria')

@section('title', 'Cita reprogramada — Secretaría')

@section('secretary-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cita reprogramada</h1>

  <div class="border rounded-md bg-neutral-100 p-4 mb-3 space-y-1">
    <h2 class="font-semibold mb-2">Resumen de la cita actualizada</h2>
    <p><strong>Paciente:</strong> {{ $appointment->paciente?->nombres }} {{ $appointment->paciente?->apellidos }}</p>
    <p><strong>Fecha y hora:</strong> {{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('l j \\d\\e F, h:i A') }}</p>
    <p><strong>Médico:</strong> {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}</p>
    <p><strong>Servicio:</strong> {{ $appointment->servicio?->nombre }}</p>
    <p><strong>Estado:</strong> {{ $appointment->estado }}</p>
  </div>

  <div class="border rounded-md bg-neutral-50 p-3 mb-6 text-sm text-neutral-700">
    La cita fue actualizada y se notificó al paciente automáticamente.
  </div>

  <div class="flex gap-3 justify-center flex-wrap">
    <x-ui.button variant="secondary" href="{{ route('secretaria.citas.reprogramar.lookup') }}">
      Reprogramar otra cita
    </x-ui.button>

    <x-ui.button variant="primary" href="{{ route('secretaria.inicio') }}">
      Volver al inicio
    </x-ui.button>
  </div>
@endsection
