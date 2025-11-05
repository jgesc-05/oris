@extends('layouts.admin')
@section('title', 'Editar servicio — Configuración')

@php
  $servicio = $servicio ?? [
    'id' => $id ?? 1,
    'especialidad_id' => 1,
    'especialidad_nombre' => 'Medicina general',
    'nombre' => 'Limpieza dental',
    'duracion' => '40 minutos',
    'precio' => '120000',
    'estado' => 'activo',
    'descripcion' => 'Limpieza para remover placa y sarro.',
  ];

  $updateUrl = \Illuminate\Support\Facades\Route::has('admin.config.servicio.update')
    ? route('admin.config.servicio.update', $servicio['id'])
    : url("/admin/config/servicios/{$servicio['id']}");
@endphp

@section('admin-content')
  <x-ui.card title="Editar servicio" subtitle="Modifica los datos del servicio." class="max-w-5xl">
    <form method="POST" action="{{ $updateUrl }}" class="mt-2 space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form.select name="especialidad_id" label="Especialidad">
          {{-- Opciones mock: reemplazar por loop real cuando haya backend --}}
          <option value="1" {{ $servicio['especialidad_id']==1?'selected':'' }}>Medicina general</option>
          <option value="2" {{ $servicio['especialidad_id']==2?'selected':'' }}>Ortodoncia</option>
        </x-form.select>

        <x-form.input name="nombre" label="Nombre del servicio" :value="$servicio['nombre']" required />

        <x-form.input name="duracion" label="Duración aproximada" :value="$servicio['duracion']" />
        <x-form.input name="precio"   label="Precio base (COP)"    :value="$servicio['precio']" />

        <x-form.select name="estado" label="Estado">
          <option value="activo"   {{ $servicio['estado']==='activo'?'selected':'' }}>Activo</option>
          <option value="inactivo" {{ $servicio['estado']==='inactivo'?'selected':'' }}>Inactivo</option>
        </x-form.select>

        <div class="md:col-span-2">
          <x-form.textarea name="descripcion" label="Descripción" rows="5">
            {{ $servicio['descripcion'] }}
          </x-form.textarea>
        </div>
      </div>

      <div class="pt-2 flex items-center gap-3">
        <x-ui.button type="submit" variant="primary">Guardar cambios</x-ui.button>
        <x-ui.button :href="route('admin.config.servicio.index')" variant="secondary">Cancelar</x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
