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
                  <form action={{--"{{ route('admin.config.servicio.toggle', $s->id_servicio) }}"--}} method="POST" style="display:inline;">
                    @csrf
                    <x-ui.button 
                        variant="{{ $s->estado === 'activo' ? 'warning' : 'success' }}" 
                        size="sm"
                        type="submit">
                        {{ $s->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                    </x-ui.button>
                  </form>

                  {{-- Botón de eliminar --}}
                  <form action={{--"{{ route('admin.config.servicio.destroy', $s->id_servicio) }}"--}} method="POST"
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
  </x-ui.card>
@endsection