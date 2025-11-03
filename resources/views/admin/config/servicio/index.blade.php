{{-- resources/views/admin/config/servicio/index.blade.php --}}
@extends('layouts.admin')
@section('title','Servicios — Configuración')

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
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Duración</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Precio</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Estado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-200">
          @foreach([
            ['id'=>1,'nombre'=>'Consulta general','esp'=>'Medicina general','duracion'=>'30 min','precio'=>'$60.000','estado'=>'activo'],
            ['id'=>2,'nombre'=>'Control pediátrico','esp'=>'Pediatría','duracion'=>'25 min','precio'=>'$55.000','estado'=>'activo'],
            ['id'=>3,'nombre'=>'Evaluación cardiológica','esp'=>'Cardiología','duracion'=>'40 min','precio'=>'$120.000','estado'=>'inactivo'],
          ] as $s)
            <tr>
              <td class="px-4 py-3 text-sm">{{ $s['nombre'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $s['esp'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $s['duracion'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $s['precio'] }}</td>
              <td class="px-4 py-3">
                @if($s['estado']==='activo')
                  <x-ui.badge variant="success">Activo</x-ui.badge>
                @else
                  <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex gap-2">
                  <x-ui.button variant="ghost" size="sm" :href="route('admin.config.servicio.edit',$s['id'])">Editar</x-ui.button>
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
