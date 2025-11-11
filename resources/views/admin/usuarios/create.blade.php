{{-- resources/views/admin/usuarios/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Registro de usuario — Admin')

@section('admin-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-6">Registro de usuario</h1>

  <x-ui.card class="max-w-3xl mx-auto">

  @if (session('success'))
  <x-ui.alert variant="success" title="¡Registro exitoso!">
    {{ session('success') }}
  </x-ui.alert>
@endif

@if ($errors->any())
  <x-ui.alert variant="warning" title="Ocurrieron algunos errores:">
    <ul class="list-disc list-inside space-y-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </x-ui.alert>
@endif



    <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tipo de documento --}}
        <x-form.select name="id_tipo_documento" label="Tipo de documento">
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
          value="{{ old('numero_documento') }}"
          :error="$errors->first('numero_documento')"
        />

        {{-- Nombres --}}
        <x-form.input
          name="nombres"
          label="Nombres"
          value="{{ old('nombres') }}"
          :error="$errors->first('nombres')"
        />

        {{-- Apellidos --}}
        <x-form.input
          name="apellidos"
          label="Apellidos"
          value="{{ old('apellidos') }}"
          :error="$errors->first('apellidos')"
        />

        {{-- Rol (tipo de usuario) --}}
        <x-form.select id="tipo_usuario" name="id_tipo_usuario" label="Rol del usuario"  onchange="toggleDoctorFields(this.value)">
          <option value="">-- Seleccionar --</option>
          @foreach ($tiposUsuario as $tipo)
            @if ($tipo->id_tipo_usuario != 4)
              <option value="{{ $tipo->id_tipo_usuario }}" @selected(old('id_tipo_usuario') == $tipo->id_tipo_usuario)>
                {{ $tipo->nombre }}
              </option>
            @endif
          @endforeach
        </x-form.select>


{{-- Campos específicos para DOCTOR --}}
<div id="doctor-fields" class="hidden md:col-span-2 border-t pt-4 mt-2">
  <h2 class="text-lg font-semibold text-neutral-800 mb-4">Información del médico</h2>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.select name="id_tipos_especialidad" label="Especialidad médica">
      <option value="">-- Seleccionar especialidad --</option>
      @foreach ($especialidades as $esp)
        <option value="{{ $esp->id_tipos_especialidad }}">{{ $esp->nombre }}</option>
      @endforeach
    </x-form.select>

    <x-form.input
      name="universidad"
      
      label="Universidad"
      value="{{ old('universidad') }}"
    />

    <x-form.input
      name="numero_licencia"
      label="Número de licencia"
      value="{{ old('numero_licencia') }}"
    />

    <x-form.input
      name="experiencia"
      label="Experiencia (en años)"
      value="{{ old('experiencia') }}"
    />

    <div class="md:col-span-2">
      <x-form.textarea
        name="descripcion"
        label="Descripción profesional"
        value="{{ old('descripcion') }}"
      />

    </div>
  </div>
</div>

        {{-- Fecha de nacimiento --}}
        <x-form.input
          name="fecha_nacimiento"
          label="Fecha de nacimiento"
          type="date"
          max="{{ now()->format('Y-m-d') }}"
          value="{{ old('fecha_nacimiento') }}"
          :error="$errors->first('fecha_nacimiento')"
        />

        {{-- Fecha de ingreso a la IPS --}}
        <x-form.input
          name="fecha_ingreso_ips"
          label="Fecha de ingreso a la IPS"
          type="date"
          value="{{ old('fecha_ingreso_ips') }}"
          :error="$errors->first('fecha_ingreso_ips')"
        />

        {{-- Teléfono --}}
        <x-form.input
          name="telefono"
          label="Teléfono"
          value="{{ old('telefono') }}"
          :error="$errors->first('telefono')"
        />

        {{-- Correo electrónico --}}
        <x-form.input
          name="correo_electronico"
          type="email"
          label="Correo electrónico"
          value="{{ old('correo_electronico') }}"
          :error="$errors->first('correo_electronico')"
        />

        {{-- Contraseña --}}
        <x-form.input
          name="password"
          type="password"
          label="Contraseña"
          :error="$errors->first('password')"
        />

        {{-- Confirmación de contraseña --}}
        <x-form.input
          name="password_confirmation"
          type="password"
          label="Confirmar contraseña"
          :error="$errors->first('password_confirmation')"
        />

        {{-- Observaciones --}}
        <div class="md:col-span-2">
          <x-form.input
            name="observaciones"
            label="Observaciones"
            value="{{ old('observaciones') }}"
            :error="$errors->first('observaciones')"
          />
        </div>
      </div>

      <div class="pt-4 text-center">
        <x-ui.button type="submit" variant="primary" class="px-8 py-3">
          Crear usuario
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
  <script>
  function toggleDoctorFields(value) {
    const doctorFields = document.getElementById('doctor-fields');
    doctorFields.classList.toggle('hidden', value != 2);
  }

  document.addEventListener('DOMContentLoaded', () => {
    const tipoUsuarioSelect = document.getElementById('tipo_usuario');
    if (tipoUsuarioSelect) {
      toggleDoctorFields(tipoUsuarioSelect.value);
    }
  });
</script>
@endsection
