@extends('layouts.secretaria')

@section('title', 'Paciente — Secretaría')

@section('secretary-content')
  @php
    $fechaNacimiento = $patient->fecha_nacimiento
      ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->translatedFormat('d \\d\\e F Y')
      : '—';
  @endphp

  <div class="space-y-6 max-w-5xl">
    <header class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
          {{ $patient->nombres }} {{ $patient->apellidos }}
        </h1>
        <p class="text-sm text-neutral-600">Documento: {{ $patient->numero_documento }}</p>
      </div>
      <x-ui.button variant="secondary" size="sm" class="rounded-full px-6" :href="route('secretaria.pacientes.index')">
        ← Volver al listado
      </x-ui.button>
    </header>

    <x-ui.card class="p-6 space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Correo electrónico</span>
          <span class="text-sm font-medium text-neutral-900">{{ $patient->correo_electronico ?? '—' }}</span>
        </div>
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Teléfono</span>
          <span class="text-sm font-medium text-neutral-900">{{ $patient->telefono ?? '—' }}</span>
        </div>
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Fecha de nacimiento</span>
          <span class="text-sm font-medium text-neutral-900">{{ $fechaNacimiento }}</span>
        </div>
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Estado</span>
          <span class="text-sm font-medium text-neutral-900 text-success-700">
            {{ ucfirst($patient->estado ?? 'activo') }}
          </span>
        </div>
      </div>

      <div>
        <span class="block text-xs uppercase tracking-wide text-neutral-500">Observaciones</span>
        <p class="text-sm text-neutral-700 mt-1">
          {{ $patient->observaciones ?? 'Sin observaciones registradas.' }}
        </p>
      </div>
    </x-ui.card>
  </div>
@endsection
