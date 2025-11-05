{{-- resources/views/admin/config/servicio/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Crear servicio — Configuración')

@section('admin-content')
  <x-ui.card
    title="Crear servicio"
    subtitle="Registra un nuevo servicio dentro de una especialidad existente. Define su duración y precio base."
    class="max-w-4xl"
  >
    @php
      $storeUrl = \Illuminate\Support\Facades\Route::has('admin.config.servicio.store')
        ? route('admin.config.servicio.store')
        : url('/admin/config/servicio');
    @endphp

    <form method="POST" action="{{ $storeUrl }}" class="mt-2">
      @csrf

      {{-- Layout: 2 columnas --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tipo de especialidad --}}
        <div>
          <x-form.select name="especialidad" label="Tipo de especialidad *">
            <option value="">-- Seleccionar --</option>
            <option value="medicina-general">Medicina general</option>
            <option value="ortodoncia">Ortodoncia</option>
            <option value="endodoncia">Endodoncia</option>
            <option value="cirugia-oral">Cirugía oral</option>
          </x-form.select>
          <p class="text-xs text-neutral-500 mt-1">
            Relaciona el servicio con una especialidad existente.
          </p>
        </div>

        {{-- Nombre --}}
        <div>
          <x-form.input
            name="nombre"
            label="Nombre *"
            placeholder="Limpieza dental"
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
            type="text"
          />
        </div>

        {{-- Descripción (ocupa ancho completo) --}}
        <div class="md:col-span-2">
          <x-form.textarea
            name="descripcion"
            label="Descripción"
            rows="4"
            placeholder="Procedimiento que elimina la placa y el sarro acumulados, ayudando a prevenir caries y enfermedades de las encías."
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
