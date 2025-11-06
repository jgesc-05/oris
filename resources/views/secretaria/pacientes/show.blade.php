@extends('layouts.secretaria')

@section('title', 'Paciente — Secretaría')

@php
  $fechaNacimiento = $patient->fecha_nacimiento
    ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->translatedFormat('d \\d\\e F Y')
    : '—';

  // URLs de navegación (no cambian tu lógica)
  $histUrl   = route('secretaria.pacientes.show', $patient->id_usuario) . '#historial';
  $agendaUrl = route('secretaria.agenda');

  // KPIs opcionales (si el controlador no los envía, no se muestran)
  // Ej: pásalos como ['asistidas'=>8,'canceladas'=>2,'reprogramadas'=>1]
  $kpis = $kpis ?? null;
@endphp

@section('secretary-content')
  {{-- Breadcrumb + volver --}}
  <div class="mb-4 flex items-center justify-between">
    <div class="text-sm text-neutral-600">
      <a href="{{ route('secretaria.inicio') }}" class="hover:underline">Inicio</a>
      <span class="mx-2">/</span>
      <a href="{{ route('secretaria.pacientes.index') }}" class="hover:underline">Pacientes</a>
      <span class="mx-2">/</span>
      <span class="text-neutral-900 font-medium">
        Paciente #{{ $patient->id_usuario }}
      </span>
    </div>
    <a href="{{ route('secretaria.pacientes.index') }}" class="text-sm text-info-600 hover:underline">
      ← Volver a la lista
    </a>
  </div>

  {{-- Encabezado con avatar + acciones (como Admin) --}}
  <div class="mb-4">
    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        {{-- Avatar inicial --}}
        <span class="w-12 h-12 rounded-full bg-[var(--color-primary-500)] text-white flex items-center justify-center font-semibold text-lg">
          {{ strtoupper(substr($patient->nombres ?? 'U', 0, 1)) }}
        </span>
        <div>
          <h1 class="text-xl md:text-2xl font-bold text-neutral-900">
            {{ $patient->nombres }} {{ $patient->apellidos }}
          </h1>
          <p class="text-sm text-neutral-600">
            Documento: {{ $patient->numero_documento }}
          </p>
        </div>
      </div>

      {{-- Acciones rápidas --}}
      <div class="flex items-center gap-2">
        <x-ui.button variant="secondary" size="sm" :href="$histUrl">Ver historial</x-ui.button>
        <x-ui.button variant="ghost" size="sm" :href="route('secretaria.citas.agendar.lookup')">Agendar cita</x-ui.button>
        <x-ui.button variant="warning" size="sm" :href="$agendaUrl">Ver agenda</x-ui.button>
      </div>
    </div>
  </div>

  {{-- Tarjeta KPIs (solo si existen) --}}
  @if($kpis && is_array($kpis))
    <x-ui.card class="mb-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="text-center">
          <div class="text-3xl font-bold text-neutral-900">{{ $kpis['asistidas'] ?? 0 }}</div>
          <div class="text-sm text-neutral-600">Citas asistidas</div>
        </div>
        <div class="text-center">
          <div class="text-3xl font-bold text-neutral-900">{{ $kpis['canceladas'] ?? 0 }}</div>
          <div class="text-sm text-neutral-600">Citas canceladas</div>
        </div>
        <div class="text-center">
          <div class="text-3xl font-bold text-neutral-900">{{ $kpis['reprogramadas'] ?? 0 }}</div>
          <div class="text-sm text-neutral-600">Citas reprogramadas</div>
        </div>
      </div>
    </x-ui.card>
  @endif

  {{-- Tarjeta: información del paciente (como Admin) --}}
  <x-ui.card title="Información del paciente" class="mb-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <div class="text-sm text-neutral-600">Correo</div>
        <div class="text-sm font-medium text-neutral-900">{{ $patient->correo_electronico ?? '—' }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Teléfono</div>
        <div class="text-sm font-medium text-neutral-900">{{ $patient->telefono ?? '—' }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Fecha de nacimiento</div>
        <div class="text-sm font-medium text-neutral-900">{{ $fechaNacimiento }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Estado</div>
        <div class="mt-1">
          @if(($patient->estado ?? 'activo') === 'activo')
            <x-ui.badge variant="success">Activo</x-ui.badge>
          @else
            <x-ui.badge variant="neutral">{{ ucfirst($patient->estado ?? 'inactivo') }}</x-ui.badge>
          @endif
        </div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Documento</div>
        <div class="text-sm font-medium text-neutral-900">{{ $patient->numero_documento }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Creación en sistema</div>
        <div class="text-sm font-medium text-neutral-900">
          {{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->translatedFormat('d \\d\\e F Y, g:i A') : '—' }}
        </div>
      </div>
    </div>
  </x-ui.card>

  {{-- Tarjeta: atenciones (misma estructura visual que Admin) --}}
  @php
    // Si no tienes última/próxima en el controlador, mostramos placeholders
    $ultima  = $ultima  ?? '—';
    $proxima = $proxima ?? '—';
    $notas   = $patient->observaciones ?? ($notas ?? 'Sin observaciones registradas.');
  @endphp
  <x-ui.card title="Atenciones" subtitle="Últimos y próximos servicios">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <div class="text-sm text-neutral-600">Última atención</div>
        <div class="text-sm font-medium text-neutral-900">{{ $ultima }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Próxima atención</div>
        <div class="text-sm font-medium text-neutral-900">{{ $proxima }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Observaciones</div>
        <div class="text-sm font-medium text-neutral-900">{{ $notas }}</div>
      </div>
    </div>

    @slot('footer')
      <div class="grid grid-cols-2 -mx-5 -mb-4 mt-4 border-t border-neutral-200">
        <a href="{{ $histUrl }}" class="text-center px-4 py-3 text-sm font-medium text-info-600 hover:underline">
          Ver historial completo
        </a>
        <a href="{{ $agendaUrl }}" class="text-center px-4 py-3 text-sm font-medium text-info-600 hover:underline border-l border-neutral-200">
          Administrar agenda
        </a>
      </div>
    @endslot
  </x-ui.card>
@endsection
