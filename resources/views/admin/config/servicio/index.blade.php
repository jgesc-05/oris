{{-- resources/views/admin/config/servicio/index.blade.php --}}
@extends('layouts.admin')
@section('title','Servicios — Configuración')

@if (session('success'))
  <x-ui.alert variant="success" title="Operación exitosa" class="mb-4">
    {{ session('success') }}
  </x-ui.alert>
@endif

@section('admin-content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl md:text-2xl font-bold">Servicios</h1>
    <x-ui.button variant="primary" :href="route('admin.config.servicio.create')">+ Nuevo servicio</x-ui.button>
  </div>

  <x-ui.card>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Servicio</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Especialidad</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Estado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Acciones</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-neutral-200">
          @foreach($services as $s)
            <tr>
              <td class="px-4 py-3 text-sm">{{ $s->nombre }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">
                {{ $s->tipoEspecialidad->nombre ?? 'Sin especialidad' }}
              </td>
              <td class="px-4 py-3">
                @if($s->estado === 'activo')
                  <x-ui.badge variant="success">Activo</x-ui.badge>
                @else
                  <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                @endif
              </td>

              <td class="px-4 py-3">
                <div class="flex gap-2">
                  {{-- Botón de editar --}}
                  <x-ui.button variant="ghost" size="sm" :href="route('admin.config.servicio.edit', $s->id_servicio)">
                    Editar
                  </x-ui.button>

                  {{-- Botón de activar/desactivar --}}
                  <form action="{{ route('admin.config.servicio.toggle', $s->id_servicio) }}" method="POST" style="display:inline;">
                    @csrf
                    <x-ui.button 
                        variant="{{ $s->estado === 'activo' ? 'warning' : 'success' }}" 
                        size="sm"
                        type="submit">
                        {{ $s->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                    </x-ui.button>
                  </form>

                  {{-- Botón de eliminar --}}
                  <form action="{{ route('admin.config.servicio.destroy', $s->id_servicio) }}" method="POST"
                        onsubmit="return confirm('¿Estás seguro de que quieres eliminar este servicio? Esta acción es irreversible.');"
                        style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <x-ui.button type="submit" variant="ghost" size="sm">
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
      @if ($services->onFirstPage())
          <x-ui.button variant="secondary" size="sm" disabled>‹</x-ui.button>
      @else
          <a href="{{ $services->previousPageUrl() }}">
              <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">‹</x-ui.button>
          </a>
      @endif

      {{-- Números de página --}}
      @foreach ($services->getUrlRange(1, $services->lastPage()) as $page => $url)
          <a href="{{ $url }}">
              <x-ui.badge
                  @class([
                      'bg-blue-500 text-white border border-blue-500' => $page == $services->currentPage(),
                      'hover:bg-blue-100 transition cursor-pointer' => $page != $services->currentPage(),
                  ])>
                  {{ $page }}
              </x-ui.badge>
          </a>
      @endforeach

      {{-- Botón siguiente --}}
      @if ($services->hasMorePages())
          <a href="{{ $users->nextPageUrl() }}">
              <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">›</x-ui.button>
          </a>
      @else
          <x-ui.button variant="secondary" size="sm" disabled>›</x-ui.button>
      @endif
  </div>
    <p class="text-sm text-neutral-500 text-center mt-2">
        Mostrando {{ $services->firstItem() }}–{{ $services->lastItem() }} de {{ $services->total() }} servicios
    </p>
  </x-ui.card>
@endsection