@extends('layouts.medico')

@section('title', 'Ficha del paciente — Médico')

@php
  \Carbon\Carbon::setLocale('es');
@endphp

@section('doctor-content')
  <div class="space-y-6">
    <div class="flex items-center justify-between text-sm text-neutral-500">
      <div>
        <a href="{{ route('medico.dashboard') }}" class="hover:underline">Inicio</a>
        <span class="mx-2">/</span>
        <a href="{{ route('medico.pacientes.index') }}" class="hover:underline">Pacientes</a>
        <span class="mx-2">/</span>
        <span class="text-neutral-800 font-medium">{{ $patient->nombres }} {{ $patient->apellidos }}</span>
      </div>
      <a href="{{ route('medico.pacientes.index') }}" class="text-info-600 hover:underline">← Volver</a>
    </div>

    <x-ui.card class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div class="flex items-center gap-4">
        <span class="w-14 h-14 rounded-full bg-[var(--color-primary-500)] text-white flex items-center justify-center text-xl font-semibold">
          {{ strtoupper(substr($patient->nombres, 0, 1)) }}
        </span>
        <div>
          <p class="text-sm uppercase tracking-widest text-neutral-500">Paciente</p>
          <h1 class="text-2xl font-semibold text-neutral-900">
            {{ $patient->nombres }} {{ $patient->apellidos }}
          </h1>
          <p class="text-sm text-neutral-600">{{ $patient->numero_documento }} • {{ $patient->correo_electronico }}</p>
        </div>
      </div>
      <div class="flex gap-2">
        <x-ui.button variant="ghost" type="button">Agregar nota</x-ui.button>
        <x-ui.button variant="primary" :href="$patient->correo_electronico ? 'mailto:'.$patient->correo_electronico : null">
          Enviar indicaciones
        </x-ui.button>
      </div>
    </x-ui.card>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <x-ui.card class="text-center">
        <p class="text-xs uppercase tracking-widest text-neutral-500">Citas totales</p>
        <p class="text-3xl font-semibold text-neutral-900">{{ $stats['total'] }}</p>
      </x-ui.card>
      <x-ui.card class="text-center">
        <p class="text-xs uppercase tracking-widest text-neutral-500">Completadas</p>
        <p class="text-3xl font-semibold text-emerald-600">{{ $stats['completadas'] }}</p>
      </x-ui.card>
      <x-ui.card class="text-center">
        <p class="text-xs uppercase tracking-widest text-neutral-500">Canceladas</p>
        <p class="text-3xl font-semibold text-amber-600">{{ $stats['canceladas'] }}</p>
      </x-ui.card>
      <x-ui.card class="text-center">
        <p class="text-xs uppercase tracking-widest text-neutral-500">Próximas</p>
        <p class="text-3xl font-semibold text-sky-600">{{ $stats['proximas'] }}</p>
      </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <x-ui.card class="lg:col-span-2" title="Historial reciente" subtitle="Últimas interacciones en consulta">
        <div class="space-y-4">
          @forelse ($timeline as $entry)
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 border rounded-2xl px-4 py-3">
              <div>
                <p class="text-sm font-semibold text-neutral-900">
                  {{ optional($entry->fecha_hora_inicio)->translatedFormat('d \\d\\e F Y • H:i') }}
                </p>
                <p class="text-xs text-neutral-500">
                  {{ optional($entry->servicio)->nombre ?? 'Servicio no especificado' }}
                </p>
              </div>
              @php
                $variant = match ($entry->estado) {
                  'Completada' => 'success',
                  'Cancelada' => 'warning',
                  'Reprogramada' => 'info',
                  default => 'neutral',
                };
              @endphp
              <x-ui.badge :variant="$variant">{{ $entry->estado }}</x-ui.badge>
            </div>
          @empty
            <p class="text-sm text-neutral-500 text-center py-8">Sin citas registradas todavía.</p>
          @endforelse
        </div>
      </x-ui.card>

      <div class="space-y-6">
        <x-ui.card title="Próxima cita">
          @if ($nextAppointment)
            <p class="text-sm text-neutral-600">Fecha</p>
            <p class="text-xl font-semibold text-neutral-900">
              {{ $nextAppointment->fecha_hora_inicio->translatedFormat('l, d \\d\\e F H:i') }}
            </p>
            <p class="text-sm text-neutral-600 mt-2">Servicio</p>
            <p class="text-base font-medium text-neutral-900">
              {{ optional($nextAppointment->servicio)->nombre ?? 'Servicio no definido' }}
            </p>
          @else
            <p class="text-sm text-neutral-500">No hay una próxima cita agendada.</p>
          @endif
        </x-ui.card>

        <x-ui.card title="Última atención">
          @if ($lastAppointment)
            <p class="text-sm text-neutral-600">Fecha</p>
            <p class="text-xl font-semibold text-neutral-900">
              {{ $lastAppointment->fecha_hora_inicio->translatedFormat('l, d \\d\\e F H:i') }}
            </p>
            <p class="text-sm text-neutral-600 mt-2">Servicio</p>
            <p class="text-base font-medium text-neutral-900">
              {{ optional($lastAppointment->servicio)->nombre ?? 'Servicio no definido' }}
            </p>
          @else
            <p class="text-sm text-neutral-500">Aún no has atendido a este paciente.</p>
          @endif
        </x-ui.card>

        <x-ui.card title="Datos de contacto">
          <dl class="space-y-3 text-sm">
            <div class="flex justify-between">
              <dt class="text-neutral-500">Correo</dt>
              <dd class="text-neutral-900">{{ $patient->correo_electronico ?? '—' }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-neutral-500">Teléfono</dt>
              <dd class="text-neutral-900">{{ $patient->telefono ?? '—' }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-neutral-500">Nacimiento</dt>
              <dd class="text-neutral-900">
                {{ $patient->fecha_nacimiento ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->translatedFormat('d \\d\\e F Y') : '—' }}
              </dd>
            </div>
          </dl>
        </x-ui.card>
      </div>
    </div>
  </div>
@endsection
