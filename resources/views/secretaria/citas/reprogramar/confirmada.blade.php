@extends('layouts.secretaria')

@section('title', 'Cita reprogramada — Secretaría')

@section('secretary-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cita reprogramada</h1>

  {{-- Resumen (estilo igual al paciente) --}}
  <div class="border rounded-md bg-neutral-100 p-4 mb-3">
    <h2 class="font-semibold mb-2">Resumen de la cita actualizada</h2>
    <p><strong>Fecha y hora:</strong> {{ $appointment['fecha_hora'] }}</p>
    <p><strong>Servicio:</strong> {{ $appointment['servicio'] }}</p>
    <p><strong>Médico:</strong> {{ $appointment['doctor'] }}</p>
    <p><strong>Referencia:</strong> <span class="font-mono">{{ $appointment['referencia'] }}</span></p>
  </div>

  {{-- Mensaje informativo --}}
  <div class="border rounded-md bg-neutral-50 p-3 mb-6 text-sm text-neutral-700">
    La cita ha sido actualizada correctamente. Se notificará al paciente y se ajustará la agenda automáticamente.
  </div>

  {{-- Acciones (centradas, como paciente) --}}
  <div class="flex gap-3 justify-center">
    <x-ui.button variant="secondary" href="{{ route('secretaria.citas.reprogramar.lookup') }}">
      Reprogramar otra cita
    </x-ui.button>

    <x-ui.button variant="primary" href="{{ route('secretaria.inicio') }}">
      Volver al inicio
    </x-ui.button>
  </div>
@endsection
