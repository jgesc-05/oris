@extends('layouts.admin')
@section('title', 'Editar especialidad — Configuración')



@section('admin-content')
  <x-ui.card
    title="Editar especialidad"
    subtitle="Actualiza el nombre, estado y descripción de la especialidad."
    class="max-w-4xl"
  >

  @if ($errors->any())
  <x-ui.alert variant="warning" title="Ocurrieron algunos errores:">
    <ul class="list-disc list-inside space-y-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </x-ui.alert>
@endif

    <form method="POST" action="{{ route('admin.config.especialidad.update', $specialty->id_tipos_especialidad) }}" class="mt-2 space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form.input name="nombre" label="Nombre" :value="$specialty['nombre']" />
        <x-form.select name="estado" label="Estado">
          <option value="activo"   {{ $specialty['estado']==='activo'?'selected':'' }}>Activo</option>
          <option value="inactivo" {{ $specialty['estado']==='inactivo'?'selected':'' }}>Inactivo</option>
        </x-form.select>

        <div class="md:col-span-2">
          <x-form.textarea name="descripcion" label="Descripción" rows="5">
            {{ $specialty['descripcion'] }}
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
