@extends('layouts.admin')
@section('title', 'Editar servicio — Configuración')

@section('admin-content')
  <x-ui.card title="Editar servicio" subtitle="Modifica los datos del servicio." class="max-w-5xl">
    <form method="POST" action="{{ route('admin.config.servicio.update', $service->id_servicio) }}" class="mt-2 space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Especialidad --}}
        <x-form.select name="id_tipos_especialidad" label="Especialidad" required>
          @foreach($specialties as $specialty)
            <option value="{{ $specialty->id_tipos_especialidad }}" 
                    {{ $service->id_tipos_especialidad == $specialty->id_tipos_especialidad ? 'selected' : '' }}>
              {{ $specialty->nombre }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.input name="nombre" label="Nombre del servicio" :value="$service->nombre" required />

        {{-- Duración y precio --}}
        <x-form.input name="duracion" label="Duración aproximada" :value="$service->duracion" />
        <x-form.input name="precio_base" label="Precio base (COP)" :value="$service->precio_base" />

        {{-- Estado --}}
        <x-form.select name="estado" label="Estado">
          <option value="activo" {{ $service->estado === 'activo' ? 'selected' : '' }}>Activo</option>
          <option value="inactivo" {{ $service->estado === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </x-form.select>

        <div class="md:col-span-2">
          <x-form.textarea name="descripcion" label="Descripción" rows="5">
            {{ $service->descripcion }}
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