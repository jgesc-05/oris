@extends('layouts.paciente')

@section('title', 'Cita reprogramada — Paciente')

@section('patient-content')
  @php
    $appointment = $appointment ?? session('appointment', []);
  @endphp

  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cita reprogramada</h1>

  {{-- Resumen --}}
  <div class="border rounded-md bg-neutral-100 p-4 mb-3">
    <h2 class="font-semibold mb-2">Resumen de la cita</h2>
    <p>Nueva fecha y hora: {{ $appointment['fecha_hora'] ?? '—' }}</p>
    <p>Médico: {{ $appointment['doctor'] ?? '—' }}</p>
    <p>Servicio: {{ $appointment['servicio'] ?? '—' }}</p>
    <p>Referencia: <span class="font-mono">{{ $appointment['referencia'] ?? '—' }}</span></p>
  </div>

  {{-- Mensaje de confirmación --}}
  <div class="border rounded-md bg-neutral-50 p-3 mb-6 text-sm text-neutral-700">
    Tu cita ha sido actualizada correctamente.
    Se ha enviado una nueva confirmación a tu correo y se actualizará el recordatorio correspondiente.
  </div>

  {{-- Acciones --}}
  <div class="flex gap-3 justify-center">
    <x-ui.button variant="secondary" href="{{ route('paciente.citas.reprogramar.index') }}">
      Reprogramar nuevamente
    </x-ui.button>

    <x-ui.button variant="warning" href="{{ route('paciente.citas.cancelar.index') }}">
      Cancelar cita
    </x-ui.button>
  </div>
@endsection
