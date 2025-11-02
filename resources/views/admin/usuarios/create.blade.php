{{-- resources/views/admin/usuarios/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Registro de usuario — Admin')

@section('admin-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-6">Registro de usuario</h1>

  <x-ui.card class="max-w-3xl mx-auto">


    <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tipo de documento --}}
        <x-form.select name="id_tipo_documento" label="Tipo de documento" required>
          <option value="">-- Seleccionar --</option>
          @foreach ($tiposDocumento as $tipo)
          <option value="{{ $tipo->id_tipo_documento }}" @selected(old('id_tipo_documento') == $tipo->id_tipo_documento)>
  {{ $tipo->name }}
</option>
          @endforeach
        </x-form.select>

        {{-- Número de documento --}}
        <x-form.input
          name="numero_documento"
          label="Número de documento"
          required
          value="{{ old('numero_documento') }}"
        />

        {{-- Nombres --}}
        <x-form.input
          name="nombres"
          label="Nombres"
          required
          value="{{ old('nombres') }}"
        />

        {{-- Apellidos --}}
        <x-form.input
          name="apellidos"
          label="Apellidos"
          required
          value="{{ old('apellidos') }}"
        />

        {{-- Rol (tipo de usuario) --}}
        <x-form.select name="id_tipo_usuario" label="Rol del usuario" required>
  <option value="">-- Seleccionar --</option>
  @foreach ($tiposUsuario as $tipo)
    @if ($tipo->id_tipo_usuario != 4)
      <option value="{{ $tipo->id_tipo_usuario }}" @selected(old('id_tipo_usuario') == $tipo->id_tipo_usuario)>
        {{ $tipo->nombre }}
      </option>
    @endif
  @endforeach
</x-form.select>


        {{-- Fecha de nacimiento --}}
        <x-form.input
          name="fecha_nacimiento"
          label="Fecha de nacimiento"
          type="date"
          max="{{ now()->format('Y-m-d') }}"
          value="{{ old('fecha_nacimiento') }}"
        />

        {{-- Fecha de ingreso a la IPS --}}
        <x-form.input
          name="fecha_ingreso_ips"
          label="Fecha de ingreso a la IPS"
          type="date"
          value="{{ old('fecha_ingreso_ips') }}"
        />

        {{-- Teléfono --}}
        <x-form.input
          name="telefono"
          label="Teléfono"
          value="{{ old('telefono') }}"
        />

        {{-- Correo electrónico --}}
        <x-form.input
          name="correo_electronico"
          type="email"
          label="Correo electrónico"
          required
          value="{{ old('correo_electronico') }}"
        />

        {{-- Contraseña --}}
        <x-form.input
          name="password"
          type="password"
          label="Contraseña"
          required
        />

        {{-- Confirmación de contraseña --}}
        <x-form.input
          name="password_confirmation"
          type="password"
          label="Confirmar contraseña"
          required
        />

        {{-- Observaciones --}}
        <div class="md:col-span-2">
          <x-form.input
            name="observaciones"
            label="Observaciones"
            value="{{ old('observaciones') }}"
          />
        </div>
      </div>

      <div class="pt-4 text-center">
        {{-- Toca ver cómo modificar este botón --}}
        <x-ui.button type="submit" variant="primary" class="px-8 py-3">
          Crear usuario
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
