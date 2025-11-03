{{-- resources/views/admin/config/medico/index.blade.php --}}
@extends('layouts.admin')
@section('title','Médicos — Configuración')

@section('admin-content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl md:text-2xl font-bold">Médicos</h1>
    <x-ui.button variant="primary" :href="route('admin.config.medico.create')">+ Nuevo médico</x-ui.button>
  </div>

  <x-ui.card>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Nombre</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Documento</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Especialidad</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Estado</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-neutral-600 uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-200">
          @foreach([
            ['id'=>1,'nombre'=>'Dra. Laura Sánchez','doc'=>'10907652345','esp'=>'Medicina general','estado'=>'activo'],
            ['id'=>2,'nombre'=>'Dr. Andrés Ramírez','doc'=>'1011234567','esp'=>'Pediatría','estado'=>'activo'],
            ['id'=>3,'nombre'=>'Dr. Felipe Martínez','doc'=>'1007654321','esp'=>'Cardiología','estado'=>'inactivo'],
          ] as $m)
            <tr>
              <td class="px-4 py-3 text-sm">{{ $m['nombre'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $m['doc'] }}</td>
              <td class="px-4 py-3 text-sm text-neutral-700">{{ $m['esp'] }}</td>
              <td class="px-4 py-3">
                @if($m['estado']==='activo')
                  <x-ui.badge variant="success">Activo</x-ui.badge>
                @else
                  <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex gap-2">
                  <x-ui.button variant="ghost" size="sm" :href="route('admin.config.medico.edit',$m['id'])">Editar</x-ui.button>
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
