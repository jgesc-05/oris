@extends('layouts.admin')
@section('title', 'Crear servicio — Configuración')

@section('admin-content')
  <x-ui.card
    title="Crear servicio"
    subtitle="Registra un nuevo servicio dentro de una especialidad existente. Define su duración y precio base."
    class="max-w-4xl"
  >
    <form method="POST" action="{{ route('admin.config.servicio.store') }}" class="mt-2">
      @csrf

      {{-- Layout: 2 columnas --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tipo de especialidad --}}
        <div>
          <x-form.select name="id_tipos_especialidad" label="Tipo de especialidad" required>
            <option value="">-- Seleccionar --</option>
            @foreach ($specialties as $esp)
              <option value="{{ $esp->id_tipos_especialidad }}">
                {{ $esp->nombre }}
              </option>
            @endforeach
          </x-form.select>
          <p class="text-xs text-neutral-500 mt-1">
            Relaciona el servicio con una especialidad existente.
          </p>
        </div>

        {{-- Nombre --}}
        <div>
          <x-form.input
            name="nombre"
            label="Nombre"
            placeholder=""
            required
          />
          <p class="text-xs text-neutral-500 mt-1">
            Nombre con el que aparecerá en el sistema.
          </p>
        </div>

        {{-- Duración aproximada --}}
        <div>
          <x-form.input
            name="duracion"
            label="Duración aproximada"
            placeholder="Ej. 40 minutos"
          />
        </div>

        {{-- Precio base --}}
        <div>
          <x-form.input
            name="precio"
            label="Precio base"
            placeholder="$120.000"
            type="number"
            step="0.01"
          />
        </div>

        {{-- Descripción --}}
        <div class="md:col-span-2">
          <x-form.textarea
            name="descripcion"
            label="Descripción"
            rows="4"
            placeholder=""
          />
        </div>
      </div>

      {{-- Acciones --}}
      <div class="mt-6 flex items-center gap-3">
        <x-ui.button type="submit" variant="primary" size="lg" class="rounded-full">
          Crear
        </x-ui.button>

        <x-ui.button :href="route('admin.config')" variant="secondary" size="lg">
          Cancelar
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
