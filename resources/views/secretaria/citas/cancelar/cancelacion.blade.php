@extends('layouts.secretaria')

@section('title', 'Cita cancelada — Secretaría')

@section('secretary-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cita cancelada</h1>

  <div class="border rounded-md bg-neutral-100 p-4 mb-3 space-y-1">
    <h2 class="font-semibold mb-2">Resumen de la operación</h2>
    <p><strong>Paciente:</strong> {{ $patient->nombres }} {{ $patient->apellidos }}</p>
    <p><strong>Fecha:</strong> {{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('l j \\d\\e F') }}</p>
    <p><strong>Hora:</strong> {{ $appointment->fecha_hora_inicio->format('h:i A') }}</p>
    <p><strong>Servicio:</strong> {{ $appointment->servicio?->nombre }}</p>
    <p><strong>Médico:</strong> {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}</p>
    <p><strong>Motivo registrado:</strong> {{ $appointment->motivo_cancelacion ?? 'Cancelada por secretaría' }}</p>
  </div>

  <div class="border rounded-md bg-neutral-50 p-3 mb-6 text-sm text-neutral-700">
    La cita fue cancelada correctamente. Si necesitas registrar otra cancelación o reprogramar, usa las opciones de abajo.
  </div>

  <div class="flex gap-3 justify-center flex-wrap">
    <x-ui.button variant="secondary" href="{{ route('secretaria.citas.cancelar.lookup') }}">
      Cancelar otra cita
    </x-ui.button>

    <x-ui.button variant="primary" href="{{ route('secretaria.inicio') }}">
      Volver al inicio
    </x-ui.button>
  </div>
@endsection
