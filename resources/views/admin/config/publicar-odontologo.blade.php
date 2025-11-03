{{-- resources/views/admin/config/publicar-odontologo.blade.php --}}
@extends('layouts.admin')
@section('title', 'Publicar odontólogo — Configuración')

@section('admin-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Publicar odontólogo</h1>

  <x-ui.card>
    @php
      // Cuando tengas backend, crea la ruta admin.config.publicar-odontologo.store
      $storeUrl = \Illuminate\Support\Facades\Route::has('admin.config.publicar-odontologo.store')
        ? route('admin.config.publicar-odontologo.store')
        : url('/admin/config/publicar-odontologo'); // fallback temporal
    @endphp

    <form method="POST" action="{{ $storeUrl }}" class="space-y-5">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Número de documento --}}
        <x-form.input
          name="numero_documento"
          label="Número de documento"
          placeholder="10078965241"
          required
          inputmode="numeric"
          autocomplete="off"
        />

        {{-- Tipo de especialidad --}}
        <x-form.select name="tipo_especialidad" label="Tipo de especialidad" required>
          <option value="" disabled selected>-- Seleccionar --</option>
          <option>Odontología general</option>
          <option>Endodoncia</option>
          <option>Ortodoncia</option>
          <option>Periodoncia</option>
          <option>Cirugía oral</option>
          <option>Rehabilitación oral</option>
        </x-form.select>

        {{-- Formación universitaria --}}
        <x-form.input
          name="formacion"
          label="Formación universitaria"
          placeholder="Odontología general egresada de la UNAB"
          required
        />

        {{-- Experiencia --}}
        <x-form.input
          name="experiencia"
          label="Experiencia"
          placeholder="Más de ocho años de experiencia clínica"
          required
        />
      </div>

      {{-- Descripción (a todo el ancho) --}}
      <x-form.textarea
        name="descripcion"
        label="Descripción"
        rows="4"
        placeholder="Perfil profesional, enfoque clínico y tratamientos que realiza…"
      />

      <div class="pt-2">
        <x-ui.button type="submit" variant="primary" size="lg" class="rounded-full">
          Publicar
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
