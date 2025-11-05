{{-- resources/views/admin/usuarios/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Editar usuario — Admin')

@section('admin-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-6">Editar usuario</h1>

  <x-ui.card class="max-w-3xl mx-auto">
    <form method="POST" action="#" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tipo de documento --}}
        <x-form.select name="tipo_documento" label="Tipo de documento" value="cc">
          <option value="cc" selected>Cédula de ciudadanía</option>
          <option value="ce">Cédula de extranjería</option>
          <option value="ti">Tarjeta de identidad</option>
        </x-form.select>

        {{-- Número de documento --}}
        <x-form.input name="documento" label="Número de documento" value="18909834510" />

        {{-- Nombres --}}
        <x-form.input name="nombres" label="Nombres" value="Javier" />

        {{-- Apellidos --}}
        <x-form.input name="apellidos" label="Apellidos" value="Rodríguez Pinzón" />

        {{-- Rol --}}
        <x-form.select name="rol" label="Rol">
          <option value="secretaria" selected>Secretaria</option>
          <option value="medico">Médico</option>
          <option value="paciente">Paciente</option>
          <option value="administrador">Administrador</option>
        </x-form.select>

        {{-- Fecha de nacimiento --}}
        <x-form.input type="date" name="fecha_nacimiento" label="Fecha de nacimiento" value="1995-04-25" />

        {{-- Teléfono --}}
        <x-form.input name="telefono" label="Teléfono" value="3021785678" />

        {{-- Correo electrónico --}}
        <x-form.input type="email" name="correo" label="Correo electrónico" value="javier@gmail.com" />

        {{-- Observaciones --}}
        <div class="md:col-span-2">
          <x-form.input name="observaciones" label="Observaciones" value="Ninguna" />
        </div>
      </div>

      <div class="pt-4 text-center">
        <x-ui.button type="submit" variant="primary" class="px-8 py-3">
          Guardar cambios
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
