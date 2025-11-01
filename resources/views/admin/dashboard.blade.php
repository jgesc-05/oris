{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Inicio — Admin')

@php
  // Perfil (mock por ahora)
  $profile = ['name' => 'Pablo'];
@endphp

@section('admin-content')

  {{-- Encabezado --}}
  <div class="mb-4">
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900">Hola, {{ $profile['name'] }}</h1>
    <p class="text-sm text-neutral-600">Jueves, 25 de septiembre</p>
  </div>

  {{-- Grid principal --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Gestión de usuarios y roles --}}
    <x-ui.card title="Gestión de usuarios y roles">
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <x-ui.card class="text-center bg-neutral-50 hover:bg-neutral-100 cursor-pointer">
          <div class="flex flex-col items-center gap-2">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">👤</div>
            <div class="text-sm font-medium text-neutral-900">Crear<br>usuario</div>
          </div>
        </x-ui.card>

        <x-ui.card class="text-center bg-neutral-50 hover:bg-neutral-100 cursor-pointer">
          <div class="flex flex-col items-center gap-2">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">✏️</div>
            <div class="text-sm font-medium text-neutral-900">Editar<br>usuario</div>
          </div>
        </x-ui.card>

        <x-ui.card class="text-center bg-neutral-50 hover:bg-neutral-100 cursor-pointer">
          <div class="flex flex-col items-center gap-2">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">🚫</div>
            <div class="text-sm font-medium text-neutral-900">Suspender<br>usuario</div>
          </div>
        </x-ui.card>
      </div>
    </x-ui.card>

    {{-- Gestión de pacientes --}}
    <x-ui.card title="Gestión de pacientes">
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <x-ui.card class="text-center bg-neutral-50 hover:bg-neutral-100 cursor-pointer">
          <div class="flex flex-col items-center gap-2">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">🧑‍⚕️</div>
            <div class="text-sm font-medium text-neutral-900">Lista de<br>pacientes</div>
          </div>
        </x-ui.card>

        <x-ui.card class="text-center bg-neutral-50 hover:bg-neutral-100 cursor-pointer">
          <div class="flex flex-col items-center gap-2">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">📋</div>
            <div class="text-sm font-medium text-neutral-900">Historial de<br>citas</div>
          </div>
        </x-ui.card>
      </div>
    </x-ui.card>

    {{-- Estadísticas y reportes mensuales --}}
    <x-ui.card title="Estadísticas y reportes mensuales">
      <div class="grid grid-cols-3 gap-4">
        <x-ui.card class="bg-neutral-50 text-center">
          <div class="text-3xl font-bold text-neutral-900">30</div>
          <div class="text-sm text-neutral-600">Citas<br>confirmadas</div>
        </x-ui.card>

        <x-ui.card class="bg-neutral-50 text-center">
          <div class="text-3xl font-bold text-neutral-900">100</div>
          <div class="text-sm text-neutral-600">Pacientes<br>activos</div>
        </x-ui.card>

        <x-ui.card class="bg-neutral-50 text-center">
          <div class="text-2xl font-bold text-neutral-900">＋</div>
          <div class="text-sm text-neutral-600">Más</div>
        </x-ui.card>
      </div>
      <div class="mt-4">
        <x-ui.button variant="secondary" size="sm" :href="route('admin.reportes.index')">Ver reportes</x-ui.button>
      </div>
    </x-ui.card>

    {{-- Notificaciones y alertas --}}
    <x-ui.card title="Notificaciones y alertas" class="border-info-200">
      <ul class="list-disc pl-5 text-sm text-neutral-800 space-y-1">
        <li>Hoy se han agendado 10 citas.</li>
        <li>El sistema de notificación SMS falló durante 10 minutos.</li>
        <li>El respaldo en la base de datos se completó correctamente.</li>
      </ul>
    </x-ui.card>
  </div>
@endsection
