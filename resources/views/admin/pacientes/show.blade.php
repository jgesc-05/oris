{{-- resources/views/admin/pacientes/show.blade.php --}}
@extends('layouts.admin')
@section('title', 'Paciente #'.$id.' — Admin')

@php
  // Mock de datos (mientras no hay backend)
  $paciente = [
    'nombre'    => 'Laura Sánchez',
    'documento' => '10907652345',
    'correo'    => 'laura.sanchez@email.com',
    'telefono'  => '+57 300 123 4567',
    'estado'    => 'Activo',
    'kpis'      => ['asistidas'=>8, 'canceladas'=>2, 'reprogramadas'=>1],
    'ultima'    => 'Valoración general',
    'proxima'   => 'Limpieza dental',
    'notas'     => 'Paciente sin antecedentes, controles al día.',
  ];

  $histUrl  = route('admin.pacientes.show', $id) . '#historial';
  $agendaUrl= url("/admin/pacientes/{$id}/agenda");
@endphp

@section('admin-content')
  {{-- Encabezado como en show de usuarios --}}
  <div class="mb-4">
    <div class="flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        {{-- Avatar/Inicial --}}
        <span class="w-12 h-12 rounded-full bg-[var(--color-primary-500)] text-white flex items-center justify-center font-semibold text-lg">
          {{ strtoupper(substr($paciente['nombre'],0,1)) }}
        </span>
        <div>
          <h1 class="text-xl md:text-2xl font-bold text-neutral-900">{{ $paciente['nombre'] }}</h1>
          <p class="text-sm text-neutral-600">Documento: {{ $paciente['documento'] }}</p>
        </div>
      </div>

      {{-- Acciones rápidas --}}
      <div class="flex items-center gap-2">
        <x-ui.button variant="secondary" size="sm" :href="$histUrl">Ver historial</x-ui.button>
        <x-ui.button variant="ghost" size="sm">Editar</x-ui.button>
        <x-ui.button variant="warning" size="sm">Suspender</x-ui.button>
      </div>
    </div>
  </div>

  {{-- Tarjeta: resumen + KPIs (como usuarios) --}}
  <x-ui.card class="mb-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="text-center">
        <div class="text-3xl font-bold text-neutral-900">{{ $paciente['kpis']['asistidas'] }}</div>
        <div class="text-sm text-neutral-600">Citas asistidas</div>
      </div>
      <div class="text-center">
        <div class="text-3xl font-bold text-neutral-900">{{ $paciente['kpis']['canceladas'] }}</div>
        <div class="text-sm text-neutral-600">Citas canceladas</div>
      </div>
      <div class="text-center">
        <div class="text-3xl font-bold text-neutral-900">{{ $paciente['kpis']['reprogramadas'] }}</div>
        <div class="text-sm text-neutral-600">Citas reprogramadas</div>
      </div>
    </div>
  </x-ui.card>

  {{-- Tarjeta: info de contacto + estado --}}
  <x-ui.card title="Información del paciente" class="mb-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <div class="text-sm text-neutral-600">Correo</div>
        <div class="text-sm font-medium text-neutral-900">{{ $paciente['correo'] }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Teléfono</div>
        <div class="text-sm font-medium text-neutral-900">{{ $paciente['telefono'] }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Estado</div>
        <div class="mt-1">
          @if($paciente['estado']==='Activo')
            <x-ui.badge variant="success">Activo</x-ui.badge>
          @else
            <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
          @endif
        </div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Documento</div>
        <div class="text-sm font-medium text-neutral-900">{{ $paciente['documento'] }}</div>
      </div>
    </div>
  </x-ui.card>

  {{-- Tarjeta: última / próxima atención + notas (estructura “secciones” del show de usuarios) --}}
  <x-ui.card title="Atenciones" subtitle="Últimos y próximos servicios">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <div class="text-sm text-neutral-600">Última atención</div>
        <div class="text-sm font-medium text-neutral-900">{{ $paciente['ultima'] }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Próxima atención</div>
        <div class="text-sm font-medium text-neutral-900">{{ $paciente['proxima'] }}</div>
      </div>
      <div>
        <div class="text-sm text-neutral-600">Observaciones</div>
        <div class="text-sm font-medium text-neutral-900">{{ $paciente['notas'] }}</div>
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
