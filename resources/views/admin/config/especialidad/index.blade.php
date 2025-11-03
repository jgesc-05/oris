{{-- resources/views/admin/config/especialidad/index.blade.php --}}
@extends('layouts.admin')
@section('title','Especialidades — Configuración')

@section('admin-content')
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
          @foreach([
            ['id'=>1,'nombre'=>'Medicina general','estado'=>'activo'],
            ['id'=>2,'nombre'=>'Pediatría','estado'=>'activo'],
            ['id'=>3,'nombre'=>'Cardiología','estado'=>'inactivo'],
          ] as $e)
            <tr>
              <td class="px-4 py-3 text-sm">{{ $e['nombre'] }}</td>
              <td class="px-4 py-3">
                @if($e['estado']==='activo')
                  <x-ui.badge variant="success">Activo</x-ui.badge>
                @else
                  <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex gap-2">
                  <x-ui.button variant="ghost" size="sm" :href="route('admin.config.especialidad.edit',$e['id'])">Editar</x-ui.button>
                  <x-ui.button variant="warning" size="sm">Desactivar</x-ui.button>
                  <x-ui.button variant="ghost" size="sm">Eliminar</x-ui.button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </x-ui.card>
@endsection
