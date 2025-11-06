@extends('layouts.secretaria')

@section('title', 'Cita cancelada — Secretaría')

@section('secretary-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cita cancelada</h1>

  {{-- Resumen (mismo estilo que paciente) --}}
  <div class="border rounded-md bg-neutral-100 p-4 mb-3">
    <h2 class="font-semibold mb-2">Resumen de la operación</h2>
    <p><strong>Paciente:</strong> {{ $patient->nombres }} {{ $patient->apellidos }}</p>
    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($appointment['fecha'])->translatedFormat('l j \\d\\e F') }}</p>
    <p><strong>Hora:</strong> {{ $appointment['hora_humana'] }}</p>
    <p><strong>Servicio:</strong> {{ $appointment['servicio'] }}</p>
    <p><strong>Médico:</strong> {{ $appointment['medico'] }}</p>
    <p><strong>Estado previo:</strong> {{ $appointment['estado'] }}</p>
  </div>

  {{-- Mensaje informativo (paralelo al de paciente) --}}
  <div class="border rounded-md bg-neutral-50 p-3 mb-6 text-sm text-neutral-700">
    La cita fue cancelada correctamente. Si necesitas registrar otra cancelación o reprogramar,
    usa las opciones de abajo.
  </div>

  {{-- Acciones (alineación y estilo como paciente) --}}
  <div class="flex gap-3 justify-center">
    <x-ui.button variant="secondary" href="{{ route('secretaria.citas.cancelar.lookup') }}">
      Cancelar otra cita
    </x-ui.button>

    <x-ui.button variant="primary" href="{{ route('secretaria.inicio') }}">
      Volver al inicio
    </x-ui.button>
  </div>
@endsection
