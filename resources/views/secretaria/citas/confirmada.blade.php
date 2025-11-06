{{-- resources/views/secretaria/citas/confirmada.blade.php --}}
@extends('layouts.secretaria')

@section('title', 'Cita confirmada — Secretaría')

@section('secretary-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cita confirmada</h1>

  {{-- Resumen (mismo estilo que Paciente) --}}
  <div class="border rounded-md bg-neutral-100 p-4 mb-3">
    <h2 class="font-semibold mb-2">Resumen de la cita</h2>
    <p><strong>Paciente:</strong> {{ $appointment['paciente'] ?? '—' }}</p>
    <p><strong>Fecha y hora:</strong> {{ $appointment['fecha_hora'] ?? '—' }}</p>
    <p><strong>Médico:</strong> {{ $appointment['doctor'] ?? '—' }}</p>
    <p><strong>Servicio:</strong> {{ $appointment['servicio'] ?? '—' }}</p>
    @if(!empty($appointment['especialidad']))
      <p><strong>Especialidad:</strong> {{ $appointment['especialidad'] }}</p>
    @endif
    <p><strong>Referencia:</strong> <span class="font-mono">{{ $appointment['referencia'] ?? '—' }}</span></p>
  </div>

  {{-- Mensaje de confirmación (mismo patrón) --}}
  <div class="border rounded-md bg-neutral-50 p-3 mb-6 text-sm text-neutral-700">
    Se envió confirmación al paciente y recordatorio 24 horas antes de la cita.
  </div>

  {{-- Acciones (mantiene tus rutas) --}}
  <div class="flex gap-3 justify-center flex-wrap">
    <x-ui.button variant="primary" href="{{ route('secretaria.citas.agendar.lookup') }}">
      Agendar otra cita
    </x-ui.button>

    <x-ui.button variant="secondary" href="{{ route('secretaria.agenda') }}">
      Ver agenda
    </x-ui.button>

    <x-ui.button variant="secondary" href="{{ route('secretaria.inicio') }}">
      Volver al inicio
    </x-ui.button>
  </div>
@endsection
