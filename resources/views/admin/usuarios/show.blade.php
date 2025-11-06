{{-- resources/views/admin/usuarios/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Usuario #'.$id.' — Admin')

@section('admin-content')
  @php
    // Mock de usuario (reemplazar luego por datos reales)
    $usuario = [
      'id'        => $id,
      'nombre'    => 'Laura González Pérez',
      'correo'    => 'lauragonzalez@saludsonrisa.com',
      'documento' => '1032456789',
      'rol'       => 'Secretaria',     // Admin | Secretaria | Médico | Paciente
      'estado'    => 'Activo',         // Activo | Inactivo
      'ultimo'    => '20/09/2025 10:15',
      'telefono'  => '+57 302 123 4567',
      'notas'     => 'Sin observaciones.',
    ];
    $isActivo = $usuario['estado'] === 'Activo';
  @endphp

  {{-- Breadcrumb + volver --}}
  <div class="mb-4 flex items-center justify-between">
    <div class="text-sm text-neutral-600">
      <a href="{{ route('admin.dashboard') }}" class="hover:underline">Inicio</a>
      <span class="mx-2">/</span>
      <a href="{{ route('admin.usuarios.index') }}" class="hover:underline">Usuarios</a>
      <span class="mx-2">/</span>
      <span class="text-neutral-900 font-medium">Usuario #{{ $usuario['id'] }}</span>
    </div>
    <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-info-600 hover:underline">← Volver a la lista</a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Columna principal --}}
    <div class="lg:col-span-2 space-y-4">

      {{-- Resumen del usuario --}}
      <x-ui.card>
        <div class="flex items-start justify-between gap-4">
          <div>
            <h1 class="text-xl md:text-2xl font-bold text-neutral-900">
              {{ $usuario['nombre'] }}
            </h1>
            <p class="text-sm text-neutral-700">ID: {{ $usuario['id'] }}</p>
          </div>

          <div class="flex flex-col items-end gap-2">
            <div class="flex items-center gap-2">
              {{-- Estado --}}
              @if($isActivo)
                <x-ui.badge variant="success">Activo</x-ui.badge>
              @else
                <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
              @endif
              {{-- Rol --}}
              <x-ui.badge variant="info">{{ $usuario['rol'] }}</x-ui.badge>
            </div>
            <div class="text-xs text-neutral-600">
              Último acceso: {{ $usuario['ultimo'] }}
            </div>
          </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <div class="text-sm font-medium text-neutral-900">Correo electrónico</div>
            <div class="text-sm text-neutral-700">{{ $usuario['correo'] }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-neutral-900">Documento</div>
            <div class="text-sm text-neutral-700">{{ $usuario['documento'] }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-neutral-900">Teléfono</div>
            <div class="text-sm text-neutral-700">{{ $usuario['telefono'] }}</div>
          </div>
          <div>
            <div class="text-sm font-medium text-neutral-900">Notas</div>
            <div class="text-sm text-neutral-700">{{ $usuario['notas'] }}</div>
          </div>
        </div>

        @slot('footer')
          <div class="flex flex-wrap items-center gap-2">
            <x-ui.button variant="secondary" size="sm" :href="route('admin.usuarios.index')">Volver</x-ui.button>
            <x-ui.button variant="primary" size="sm" :href="route('admin.usuarios.create')">Crear nuevo</x-ui.button>
            <x-ui.button variant="ghost" size="sm">Restablecer contraseña</x-ui.button>
            @if($isActivo)
              <x-ui.button variant="warning" size="sm">Suspender</x-ui.button>
            @else
              <x-ui.button variant="success" size="sm">Reactivar</x-ui.button>
            @endif
          </div>
        @endslot
      </x-ui.card>

      {{-- Actividad reciente (mock) --}}
      <x-ui.card title="Actividad reciente">
        <ul class="list-disc pl-5 text-sm text-neutral-800 space-y-1">
          <li>Accedió al sistema — 20/09/2025 10:15</li>
          <li>Editó datos de un paciente — 19/09/2025 12:04</li>
          <li>Creó nueva cita — 18/09/2025 09:40</li>
        </ul>
      </x-ui.card>
    </div>

    {{-- Columna lateral: accesos rápidos --}}
    <div class="space-y-4">
      <x-ui.card title="Acciones rápidas">
        <div class="grid grid-cols-1 gap-2">
          <x-ui.button variant="secondary" :href="route('admin.usuarios.index')">Ir a usuarios</x-ui.button>
          <x-ui.button variant="secondary">Editar usuario</x-ui.button>
          <x-ui.button variant="secondary">Cambiar rol</x-ui.button>
          <x-ui.button variant="secondary">Eliminar</x-ui.button>
        </div>
      </x-ui.card>

      <x-ui.card title="Permisos (mock)">
        <div class="flex flex-wrap gap-2">
          <x-ui.badge>Ver agenda</x-ui.badge>
          <x-ui.badge>Crear citas</x-ui.badge>
          <x-ui.badge>Editar pacientes</x-ui.badge>
          <x-ui.badge>Reportes</x-ui.badge>
        </div>
      </x-ui.card>
    </div>
  </div>
@endsection
