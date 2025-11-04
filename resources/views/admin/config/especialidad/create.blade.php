{{-- resources/views/admin/config/especialidad/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Especialidad — Configuración')

@section('admin-content')
  <x-ui.card
    title="Crear especialidad"
    subtitle="Define una especialidad para clasificar tus prestaciones (ej. Odontología general, Ortodoncia…)."
    class="max-w-4xl"
  >
    @php
      $storeUrl = \Illuminate\Support\Facades\Route::has('admin.config.especialidad.store')
        ? route('admin.config.especialidad.store')
        : url('/admin/config/especialidad'); // fallback temporal
    @endphp

    @if ($errors->any())
  <x-ui.alert variant="warning" title="Ocurrieron algunos errores:">
    <ul class="list-disc list-inside space-y-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </x-ui.alert>
@endif

    <form method="POST" action="{{ route('admin.config.especialidad.createSp') }}" class="mt-2">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <x-form.input
            name="nombre"
            label="Nombre"
            placeholder=""
            required
            autocomplete="off"
            :error="$errors->first('nombre')"
          />
          <p class="text-xs text-neutral-500 mt-1">Nombre visible para pacientes y personal.</p>
        </div>

        <div>
          <x-form.select name="estado" label="Estado">
            <option value="activo" selected>Activo</option>
            <option value="inactivo">Inactivo</option>
          </x-form.select>
          <p class="text-xs text-neutral-500 mt-1">Si está inactiva, no aparecerá en nuevas asignaciones.</p>
        </div>

        <div class="md:col-span-2">
          <x-form.textarea
            name="descripcion"
            label="Descripción"
            rows="5"
            placeholder="Describe el alcance, casos típicos y consideraciones…"
            :error="$errors->first('descripcion')"
          />
        </div>
      </div>

      <div class="mt-6 flex items-center gap-3">
        <x-ui.button type="submit" variant="primary" size="lg" class="rounded-full">Crear</x-ui.button>
        <x-ui.button :href="route('admin.config')" variant="secondary" size="lg">Cancelar</x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
