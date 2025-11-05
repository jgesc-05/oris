@extends('layouts.paciente')

@section('title', 'Cita confirmada — Paciente')

@section('patient-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cita confirmada</h1>

  {{-- Resumen --}}
  <div class="border rounded-md bg-neutral-100 p-4 mb-3">
    <h2 class="font-semibold mb-2">Resumen de la cita</h2>
    <p>Fecha y hora: {{ $appointment['fecha_hora'] }}</p>
    <p>Odontólogo: {{ $appointment['doctor'] }}</p>
    <p>Servicio: {{ $appointment['servicio'] }}</p>
    <p>Referencia: <span class="font-mono">{{ $appointment['referencia'] }}</span></p>
  </div>

  {{-- Mensaje de confirmación --}}
  <div class="border rounded-md bg-neutral-50 p-3 mb-6 text-sm text-neutral-700">
    Se ha enviado una confirmación a tu correo y un recordatorio 24 horas antes de la cita.
  </div>

  {{-- Acciones --}}
  <div class="flex gap-3 justify-center">
    <x-ui.button variant="secondary" href="{{ route('paciente.citas.reprogramar.index') }}">
      Reprogramar cita
    </x-ui.button>

    <x-ui.button variant="warning" href="{{ route('paciente.citas.cancelar.index') }}">
      Cancelar cita
    </x-ui.button>
  </div>
@endsection
