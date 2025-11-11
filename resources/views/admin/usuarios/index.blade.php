{{-- resources/views/admin/usuarios/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Usuarios — Admin')
@if (session('success'))
  <x-ui.alert variant="success" title="Operación exitosa" class="mb-4">
    {{ session('success') }}
  </x-ui.alert>
@endif


@section('admin-content')
  {{-- Mensaje de éxito - DEBE ESTAR AL PRINCIPIO --}}


  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900">Usuarios</h1>
    <x-ui.button variant="primary" :href="route('admin.usuarios.create')">
      + Crear nuevo usuario
    </x-ui.button>
  </div>

  

  {{-- Filtros + búsqueda --}}
  <x-ui.card class="mb-4">
  <form method="GET" action="{{ route('admin.usuarios.index') }}">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3">
      {{-- Búsqueda --}}
      <div class="lg:col-span-2">
        <label class="form-label" for="q">Buscar</label>
        <div class="relative">
          <input id="q" name="q" type="text" placeholder="Buscar por nombre, cédula o correo"
            value="{{ request('q') }}"
            class="form-control pl-10" />
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">🔎</span>
        </div>
      </div>

      {{-- Rol --}}
      <div>
        <x-form.select name="rol" label="Rol">
          <option value="">Todos</option>
          <option value="Administrador" {{ request('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
          <option value="Médico" {{ request('rol') == 'Médico' ? 'selected' : '' }}>Médico</option>
          <option value="Secretaria" {{ request('rol') == 'Secretaria' ? 'selected' : '' }}>Secretaria</option>
          <option value="Paciente" {{ request('rol') == 'Paciente' ? 'selected' : '' }}>Paciente</option>
        </x-form.select>
      </div>

      {{-- Estado --}}
      <div>
        <x-form.select name="estado" label="Estado">
          <option value="">Todos</option>
          <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
          <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </x-form.select>
      </div>

      {{-- Fecha de registro --}}
      <div>
        <x-form.select name="fecha" label="Fecha de registro">
          <option value="">Todos</option>
          option value="hoy" {{ request('fecha') == 'hoy' ? 'selected' : '' }}>Hoy</option>
          <option value="7d" {{ request('fecha') == '7d' ? 'selected' : '' }}>Últimos 7 días</option>
          <option value="30d" {{ request('fecha') == '30d' ? 'selected' : '' }}>Últimos 30 días</option>
        </x-form.select>
        <x-ui.button type="submit" variant="primary">Filtrar</x-ui.button>
        <x-ui.button variant="secondary" :href="route('admin.usuarios.index')">Limpiar</x-ui.button>
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
    @foreach ($users as $u)
        <tr>
            <td class="px-4 py-3 text-sm text-neutral-900">{{ "$u->nombres $u->apellidos" }}</td>
            <td class="px-4 py-3 text-sm text-neutral-700">{{ $u->correo_electronico }}</td>
            <td class="px-4 py-3 text-sm text-neutral-700">
                {{ $u->userType->nombre ?? 'Sin rol' }}
            </td>
            <td class="px-4 py-3">
                @if ($u->estado === 'activo')
                    <x-ui.badge variant="success">Activo</x-ui.badge>
                @else
                    <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                @endif
            </td>
            <td class="px-4 py-3 text-sm text-neutral-700">
                {{ $u->ultimo_acceso ? $u->ultimo_acceso->format('d/m/Y H:i') : 'Sin registro' }}
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                    <x-ui.button variant="secondary" size="sm" :href="route('admin.usuarios.show', $u->id_usuario)">Ver</x-ui.button>
                    <x-ui.button variant="ghost" size="sm" :href="route('admin.usuarios.edit', $u->id_usuario)">Editar</x-ui.button>
                    <form method="POST" action="{{ route('admin.usuarios.toggle-state', $u->id_usuario) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <x-ui.button 
                            variant="warning" 
                            size="sm"
                            type="submit">
                            {{ $u->estado === 'activo' ? 'Suspender' : 'Activar' }}
                        </x-ui.button>
                    </form>
                    <form method="POST" action="{{ route('admin.usuarios.destroy', $u->id_usuario) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a {{ $u->nombres }} {{ $u->apellidos }}? Esta acción no se puede deshacer.')">
                      @csrf
                      @method('DELETE')
                      <x-ui.button variant="ghost" size="sm" type="submit">
                          Eliminar
                      </x-ui.button>
                  </form>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>

      </table>
    </div>

    {{--Paginación funcional --}}
      <div class="mt-4 flex items-center justify-center gap-2">
      {{-- Botón anterior --}}
      @if ($users->onFirstPage())
          <x-ui.button variant="secondary" size="sm" disabled>‹</x-ui.button>
      @else
          <a href="{{ $users->previousPageUrl() }}">
              <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">‹</x-ui.button>
          </a>
      @endif

      {{-- Números de página --}}
      @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
          <a href="{{ $url }}">
              <x-ui.badge
                  @class([
                      'bg-blue-500 text-white border border-blue-500' => $page == $users->currentPage(),
                      'hover:bg-blue-100 transition cursor-pointer' => $page != $users->currentPage(),
                  ])>
                  {{ $page }}
              </x-ui.badge>
          </a>
      @endforeach

      {{-- Botón siguiente --}}
      @if ($users->hasMorePages())
          <a href="{{ $users->nextPageUrl() }}">
              <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">›</x-ui.button>
          </a>
      @else
          <x-ui.button variant="secondary" size="sm" disabled>›</x-ui.button>
      @endif
  </div>
    <p class="text-sm text-neutral-500 text-center mt-2">
        Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }} usuarios
    </p>
  </x-ui.card>
@endsection
