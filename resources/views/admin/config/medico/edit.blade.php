@extends('layouts.admin')
@section('title', 'Editar médico — Configuración')

@php
  $medico = $medico ?? [
    'id' => $id ?? 1,
    'documento' => '10078965241',
    'especialidad_id' => 1,
    'formacion' => 'Médico general egresado de la UNAB',
    'experiencia' => '8+ años de experiencia clínica',
    'estado' => 'activo',
    'descripcion' => 'Profesional encargado de la salud general.',
    'nombre' => 'Laura Sánchez',
  ];

  $updateUrl = \Illuminate\Support\Facades\Route::has('admin.config.medico.update')
    ? route('admin.config.medico.update', $medico['id'])
    : url("/admin/config/medicos/{$medico['id']}");
@endphp

@section('admin-content')
  <x-ui.card title="Editar médico" subtitle="Actualiza los datos de registro del profesional." class="max-w-5xl">
    <form method="POST" action="{{ $updateUrl }}" class="mt-2 space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form.input name="nombre"    label="Nombre completo" :value="$medico['nombre']" required />
        <x-form.input name="documento" label="Número de documento" :value="$medico['documento']" required />

        <x-form.select name="especialidad_id" label="Especialidad">
          <option value="1" {{ $medico['especialidad_id']==1?'selected':'' }}>Medicina general</option>
          <option value="2" {{ $medico['especialidad_id']==2?'selected':'' }}>Pediatría</option>
          <option value="3" {{ $medico['especialidad_id']==3?'selected':'' }}>Medicina interna</option>
        </x-form.select>

        <x-form.select name="estado" label="Estado">
          <option value="activo"   {{ $medico['estado']==='activo'?'selected':'' }}>Activo</option>
          <option value="inactivo" {{ $medico['estado']==='inactivo'?'selected':'' }}>Inactivo</option>
        </x-form.select>

        <div class="md:col-span-2">
          <x-form.input name="formacion" label="Formación universitaria" :value="$medico['formacion']" />
        </div>

        <div class="md:col-span-2">
          <x-form.input name="experiencia" label="Experiencia" :value="$medico['experiencia']" />
        </div>

        <div class="md:col-span-2">
          <x-form.textarea name="descripcion" label="Descripción" rows="5">
            {{ $medico['descripcion'] }}
          </x-form.textarea>
        </div>
      </div>

      <div class="pt-2 flex items-center gap-3">
        <x-ui.button type="submit" variant="primary">Guardar cambios</x-ui.button>
        <x-ui.button :href="route('admin.config.medico.index')" variant="secondary">Cancelar</x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
