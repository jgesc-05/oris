{{-- resources/views/admin/usuarios/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Usuarios — Admin')

@section('admin-content')

  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900">Usuarios</h1>

    <x-ui.button variant="primary" :href="route('admin.usuarios.create')">
      + Crear nuevo usuario
    </x-ui.button>
  </div>

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

      {{-- Rol --}}
      <div>
        <x-form.select name="rol" label="Rol">
          <option value="">Todos</option>
          <option value="admin">Administrador</option>
          <option value="secretaria">Secretaria</option>
          <option value="odontologo">Odontólogo</option>
          <option value="paciente">Paciente</option>
        </x-form.select>
      </div>

      {{-- Estado --}}
      <div>
        <x-form.select name="estado" label="Estado">
          <option value="">Todos</option>
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
        </x-form.select>
      </div>

      {{-- Fecha de registro --}}
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

  {{-- Tabla --}}
  <x-ui.card>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Nombre</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Correo electrónico</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Rol</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Estado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Último acceso</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Acciones</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-neutral-200">
          @foreach([
            ['nombre'=>'Laura González Pérez','correo'=>'lauragonzalez@saludosn…','rol'=>'Secretaria','estado'=>'Activo','ultimo'=>'20/09/2025 10:15'],
            ['nombre'=>'Andrés Ramírez López','correo'=>'andres.ramirez@salud…','rol'=>'Odontólogo','estado'=>'Activo','ultimo'=>'19/09/2025 17:30'],
            ['nombre'=>'Camila Torres Mejía','correo'=>'camila.torres@gmail.com','rol'=>'Paciente','estado'=>'Activo','ultimo'=>'21/09/2025 08:42'],
            ['nombre'=>'Felipe Martínez Ríos','correo'=>'felipe.martinez@salud…','rol'=>'Administrador','estado'=>'Activo','ultimo'=>'20/09/2025 12:00'],
            ['nombre'=>'Valentina Castro Ruiz','correo'=>'valentina.castro@hot…','rol'=>'Paciente','estado'=>'Inactivo','ultimo'=>'05/09/2025 09:10'],
            ['nombre'=>'Santiago Vargas León','correo'=>'santiago.vargas@salud…','rol'=>'Odontólogo','estado'=>'Activo','ultimo'=>'20/09/2025 10:15'],
          ] as $u)
            <tr>
              <td class="px-4 py-3 text-sm text-neutral-900">{{ $u['nombre'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $u['correo'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $u['rol'] }}</td>
              <td class="px-4 py-3">
                @if($u['estado']==='Activo')
                  <x-ui.badge variant="success">Activo</x-ui.badge>
                @else
                  <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                @endif
              </td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $u['ultimo'] }}</td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">

                  <x-ui.button variant="secondary" size="sm" :href="route('admin.usuarios.show', 1)">Ver</x-ui.button>
                  <x-ui.button variant="ghost" size="sm">Editar</x-ui.button>
                  <x-ui.button variant="warning" size="sm">Suspender</x-ui.button>
                  <x-ui.button variant="ghost" size="sm">Eliminar</x-ui.button>
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
