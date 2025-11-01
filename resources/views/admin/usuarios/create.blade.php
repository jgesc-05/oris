{{-- resources/views/admin/usuarios/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Registro de usuario — Admin')

@section('admin-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-6">Registro de usuario</h1>

  <x-ui.card class="max-w-3xl mx-auto">
    <form method="POST" action="#" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tipo de documento --}}
        <x-form.select name="tipo_documento" label="Tipo de documento">
          <option value="cc">Cédula de ciudadanía</option>
          <option value="ce">Cédula de extranjería</option>
          <option value="ti">Tarjeta de identidad</option>
        </x-form.select>

        {{-- Número de documento --}}
        <x-form.input name="documento" label="Número de documento" />

        {{-- Nombres --}}
        <x-form.input name="nombres" label="Nombres" />

        {{-- Apellidos --}}
        <x-form.input name="apellidos" label="Apellidos" />

        {{-- Rol --}}
        <x-form.select name="rol" label="Rol">
          <option value="secretaria">Secretaria</option>
          <option value="odontologo">Odontólogo</option>
          <option value="paciente">Paciente</option>
          <option value="administrador">Administrador</option>
        </x-form.select>

        {{-- Fecha de nacimiento --}}
        <x-form.input
        name="fecha_nacimiento"
        label="Fecha de nacimiento"
        type="date"
        required
        autocomplete="bday"
        min="1900-01-01"
        max="{{ now()->format('Y-m-d') }}"
        />

        {{-- Teléfono --}}
        <x-form.input name="telefono" label="Teléfono"  />

        {{-- Correo electrónico --}}
        <x-form.input type="email" name="correo" label="Correo electrónico" />

        {{-- Observaciones --}}
        <div class="md:col-span-2">
          <x-form.input name="observaciones" label="Observaciones"  />
        </div>
      </div>

      <div class="pt-4 text-center">
        <x-ui.button type="submit" variant="primary" class="px-8 py-3">
          Registrarse
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
