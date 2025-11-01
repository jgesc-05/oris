{{-- resources/views/admin/pacientes/show.blade.php --}}
@extends('layouts.admin')
@section('title', 'Paciente #'.$id.' — Admin')

@php
  // Mock de datos del paciente (temporal mientras no hay backend)
  $paciente = [
    'nombre'          => 'Laura Sánchez',
    'documento'       => '10907652345',
    'kpis'            => ['asistidas'=>8, 'canceladas'=>2, 'reprogramadas'=>1],
    'ultima_atencion' => 'Valoración general',
    'proxima_atencion'=> 'Valoración general',
    'observaciones'   => 'Ninguna',
  ];

  // Fallback de rutas para los CTAs
  $histUrl = \Illuminate\Support\Facades\Route::has('admin.pacientes.historial')
    ? route('admin.pacientes.historial', $id)
    : route('admin.pacientes.show', $id) . '#historial';

  $agendaUrl = \Illuminate\Support\Facades\Route::has('admin.pacientes.agenda')
    ? route('admin.pacientes.agenda', $id)
    : url("/admin/pacientes/{$id}/agenda");
@endphp

@section('admin-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Paciente</h1>

  <div class="max-w-xl">
    <x-ui.card>
      {{-- Encabezado --}}
      <div class="mb-3">
        <div class="text-xl font-semibold text-neutral-900">{{ $paciente['nombre'] }}</div>
        <div class="text-neutral-700 text-sm">{{ $paciente['documento'] }}</div>
      </div>

      {{-- KPIs --}}
      <div class="grid grid-cols-3 gap-4 my-4">
        <div class="text-center">
          <div class="text-2xl font-bold text-neutral-900">{{ $paciente['kpis']['asistidas'] }}</div>
          <div class="text-xs text-neutral-700 leading-tight">Citas<br>asistidas</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-neutral-900">{{ $paciente['kpis']['canceladas'] }}</div>
          <div class="text-xs text-neutral-700 leading-tight">Citas<br>canceladas</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-neutral-900">{{ $paciente['kpis']['reprogramadas'] }}</div>
          <div class="text-xs text-neutral-700 leading-tight">Citas<br>reprogramadas</div>
        </div>
      </div>

      {{-- Secciones --}}
      <div class="space-y-4">
        <div>
          <div class="font-semibold text-neutral-900">Última atención</div>
          <div class="text-neutral-800 text-sm">{{ $paciente['ultima_atencion'] }}</div>
        </div>
        <div>
          <div class="font-semibold text-neutral-900">Próxima atención</div>
          <div class="text-neutral-800 text-sm">{{ $paciente['proxima_atencion'] }}</div>
        </div>
        <div>
          <div class="font-semibold text-neutral-900">Observaciones</div>
          <div class="text-neutral-800 text-sm">{{ $paciente['observaciones'] }}</div>
        </div>
      </div>

      @slot('footer')
        <div class="grid grid-cols-2 -mx-5 -mb-4 mt-4 border-t border-neutral-200">
          <a href="{{ $histUrl }}" class="text-center px-4 py-3 text-sm font-medium text-info-600 hover:underline">
            Ver historial
          </a>
          <a href="{{ $agendaUrl }}" class="text-center px-4 py-3 text-sm font-medium text-info-600 hover:underline border-l border-neutral-200">
            Ver agenda
          </a>
        </div>
      @endslot
    </x-ui.card>
  </div>
@endsection
