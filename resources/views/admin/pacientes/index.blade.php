{{-- resources/views/admin/pacientes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pacientes — Admin')

@php
  // Menú superior (centrado)
  $navItems = [
    ['label' => 'Inicio',        'href' => route('admin.dashboard')],
    ['label' => 'Usuarios',      'href' => route('admin.usuarios.index')],
    ['label' => 'Pacientes',     'href' => route('admin.pacientes.index'), 'active' => true],
    ['label' => 'Reportes',      'href' => route('admin.reportes.index')],
    ['label' => 'Configuración', 'href' => route('admin.config')],
  ];
  $profile = ['name' => 'Pablo'];
@endphp

@section('content')

  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Pacientes</h1>

  {{-- Filtros + búsqueda --}}
  <x-ui.card class="mb-4">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3">
      {{-- Búsqueda --}}
      <div class="lg:col-span-2">
        <label class="form-label" for="q">Buscar</label>
        <div class="relative">
          <input id="q" name="q" type="text" placeholder="Buscar por nombre, cédula o correo"
                 class="form-control pl-10" />
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">🔎</span>
        </div>
      </div>

      {{-- Estado (Activo / Inactivo) --}}
      <div>
        <x-form.select name="estado" label="Estado">
          <option value="">Todos</option>
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
        </x-form.select>
      </div>

      {{-- Fecha de registro (mock) --}}
      <div>
        <x-form.select name="fecha" label="Fecha de registro">
          <option value="">Todos</option>
          <option value="hoy">Hoy</option>
          <option value="7d">Últimos 7 días</option>
          <option value="30d">Últimos 30 días</option>
        </x-form.select>
      </div>
    </div>
  </x-ui.card>

  {{-- Tabla de pacientes (solo lectura) --}}
  <x-ui.card>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Nombre</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Documento</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Correo</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Estado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Última cita</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-200">
          @foreach([
            ['nombre'=>'Laura Sánchez Pérez','doc'=>'1032456789','correo'=>'laura.sanchez@email.com','estado'=>'Activo','ultima'=>'20/09/2025 10:15'],
            ['nombre'=>'Andrés Ramírez López','doc'=>'1011234567','correo'=>'andres.ramirez@email.com','estado'=>'Inactivo','ultima'=>'05/09/2025 09:10'],
            ['nombre'=>'Camila Torres Mejía','doc'=>'1098765432','correo'=>'camila.torres@gmail.com','estado'=>'Activo','ultima'=>'21/09/2025 08:42'],
            ['nombre'=>'Felipe Martínez Ríos','doc'=>'1007654321','correo'=>'felipe.martinez@email.com','estado'=>'Activo','ultima'=>'18/09/2025 12:00'],
            ['nombre'=>'Valentina Castro','doc'=>'1029988776','correo'=>'valentina.castro@hotmail.com','estado'=>'Activo','ultima'=>'22/09/2025 14:20'],
            ['nombre'=>'Juan Pablo Ortiz','doc'=>'1002233445','correo'=>'jp.ortiz23@yahoo.com','estado'=>'Activo','ultima'=>'18/09/2025 16:45'],
          ] as $p)
            <tr>
              <td class="px-4 py-3 text-sm text-neutral-900">{{ $p['nombre'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $p['doc'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $p['correo'] }}</td>
              <td class="px-4 py-3">
                @if($p['estado'] === 'Activo')
                  <x-ui.badge variant="success">Activo</x-ui.badge>
                @else
                  <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                @endif
              </td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $p['ultima'] }}</td>
              <td class="px-4 py-3 text-sm">
                <div class="flex items-center gap-2">
                  <x-ui.button variant="secondary" size="sm" :href="route('admin.pacientes.show', 1)">Ver</x-ui.button>
                  <x-ui.button variant="ghost" size="sm" :href="route('admin.pacientes.show', 1).'#historial'">Historial</x-ui.button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Paginación mock --}}
    <div class="mt-4 flex items-center justify-center gap-2">
      <x-ui.button variant="secondary" size="sm">‹</x-ui.button>
      <x-ui.badge>1</x-ui.badge>
      <x-ui.button variant="secondary" size="sm">›</x-ui.button>
    </div>
  </x-ui.card>
@endsection
