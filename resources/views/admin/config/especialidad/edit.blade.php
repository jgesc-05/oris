@extends('layouts.admin')
@section('title', 'Editar especialidad — Configuración')

@php
  // Datos mock mientras no hay backend
  $especialidad = $especialidad ?? [
    'id' => $id ?? 1,
    'nombre' => 'Odontología general',
    'estado' => 'activo',
    'descripcion' => 'Prevención, diagnóstico y tratamiento básico.',
  ];

  // Acción (fallback mientras no hay controlador real)
  $updateUrl = \Illuminate\Support\Facades\Route::has('admin.config.especialidad.update')
    ? route('admin.config.especialidad.update', $especialidad['id'])
    : url("/admin/config/especialidades/{$especialidad['id']}");
@endphp

@section('admin-content')
  <x-ui.card
    title="Editar especialidad"
    subtitle="Actualiza el nombre, estado y descripción de la especialidad."
    class="max-w-4xl"
  >
    <form method="POST" action="{{ $updateUrl }}" class="mt-2 space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form.input name="nombre" label="Nombre" :value="$especialidad['nombre']" required />
        <x-form.select name="estado" label="Estado">
          <option value="activo"   {{ $especialidad['estado']==='activo'?'selected':'' }}>Activo</option>
          <option value="inactivo" {{ $especialidad['estado']==='inactivo'?'selected':'' }}>Inactivo</option>
        </x-form.select>

        <div class="md:col-span-2">
          <x-form.textarea name="descripcion" label="Descripción" rows="5">
            {{ $especialidad['descripcion'] }}
          </x-form.textarea>
        </div>
      </div>

      <div class="pt-2 flex items-center gap-3">
        <x-ui.button type="submit" variant="primary">Guardar cambios</x-ui.button>
        <x-ui.button :href="route('admin.config.especialidad.index')" variant="secondary">Cancelar</x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
