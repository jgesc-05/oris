{{-- resources/views/admin/config/especialidad/index.blade.php --}}
@extends('layouts.admin')
@section('title','Especialidades — Configuración')

@section('admin-content')
  @if (session('success'))
    <x-ui.alert variant="success" title="{{ session('title') ?? 'Operación exitosa' }}" class="mb-4">
      {{ session('success') }}
    </x-ui.alert>
  @endif

  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl md:text-2xl font-bold">Especialidades</h1>
    <x-ui.button variant="primary" :href="route('admin.config.especialidad.create')">+ Nueva especialidad</x-ui.button>
  </div>

  <x-ui.card>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Nombre</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Estado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-200">
  @foreach($specialties as $e)
    <tr>
      <td class="px-4 py-3 text-sm">{{ $e->nombre }}</td>
      <td class="px-4 py-3">
        @if($e->estado === 'activo')
          <x-ui.badge variant="success">Activo</x-ui.badge>
        @else
          <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
        @endif
      </td>
      <td class="px-4 py-3">
        <div class="flex gap-2">
          <x-ui.button variant="ghost" size="sm" :href="route('admin.config.especialidad.edit', $e->id_tipos_especialidad)">Editar</x-ui.button>
          <form action="{{ route('admin.config.especialidad.toggle', $e->id_tipos_especialidad) }}" method="POST" style="display:inline;">
    @csrf
    <x-ui.button 
        variant="{{ $e->estado === 'activo' ? 'warning' : 'success' }}" 
        size="sm"
        type="submit">
        {{ $e->estado === 'activo' ? 'Desactivar' : 'Activar' }}
    </x-ui.button>
</form>

<form action="{{ route('admin.config.especialidad.destroy', $e->id_tipos_especialidad) }}" method="POST"
      onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta especialidad? Esta acción es irreversible.');"
      style="display: inline;"> {{-- Para que el botón no ocupe toda la línea --}}

    @csrf
    @method('DELETE')

    {{-- botón de eliminar --}}
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
      @if ($specialties->onFirstPage())
          <x-ui.button variant="secondary" size="sm" disabled>‹</x-ui.button>
      @else
          <a href="{{ $users->previousPageUrl() }}">
              <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">‹</x-ui.button>
          </a>
      @endif

      {{-- Números de página --}}
      @foreach ($specialties->getUrlRange(1, $specialties->lastPage()) as $page => $url)
          <a href="{{ $url }}">
              <x-ui.badge
                  @class([
                      'bg-blue-500 text-white border border-blue-500' => $page == $specialties->currentPage(),
                      'hover:bg-blue-100 transition cursor-pointer' => $page != $specialties->currentPage(),
                  ])>
                  {{ $page }}
              </x-ui.badge>
          </a>
      @endforeach

      {{-- Botón siguiente --}}
      @if ($specialties->hasMorePages())
          <a href="{{ $users->nextPageUrl() }}">
              <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">›</x-ui.button>
          </a>
      @else
          <x-ui.button variant="secondary" size="sm" disabled>›</x-ui.button>
      @endif
  </div>
    <p class="text-sm text-neutral-500 text-center mt-2">
        Mostrando {{ $specialties->firstItem() }}–{{ $specialties->lastItem() }} de {{ $specialties->total() }} especialidades
    </p>
  </x-ui.card>
@endsection
