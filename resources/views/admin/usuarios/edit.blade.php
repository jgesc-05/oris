{{-- resources/views/admin/usuarios/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Editar usuario — Admin')

@section('admin-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-6">Editar usuario</h1>

  {{-- Mensajes de feedback --}}
  @if(session('success'))
    <div class="mb-6">
      <x-ui.alert type="success" message="{{ session('success') }}" />
    </div>
  @endif

  @if ($errors->any())
    <div class="mb-6">
      <x-ui.alert variant="warning" title="Ocurrieron algunos errores:">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </x-ui.alert>
    </div>
  @endif
  {{-- Fin Mensajes de feedback --}}

  <x-ui.card class="max-w-3xl mx-auto">
    <form method="POST" action="{{ route('admin.usuarios.update', $user->id_usuario) }}" class="space-y-8">
      @csrf
      @method('PUT')

      {{-- Sección 1: Documento --}}
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neutral-700 border-b pb-2">Documento de identidad</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Tipo de documento --}}
          <x-form.select name="id_tipo_documento" label="Tipo de documento" required>
            <option value="">-- Seleccionar --</option>
            @foreach($tiposDocumento as $tipo)
              <option value="{{ $tipo->id_tipo_documento }}" 
                {{ old('id_tipo_documento', $user->id_tipo_documento) == $tipo->id_tipo_documento ? 'selected' : '' }}>
                {{ $tipo->name }}
              </option>
            @endforeach
          </x-form.select>

          {{-- Número de documento --}}
          <x-form.input name="numero_documento" label="Número de documento" 
            value="{{ old('numero_documento', $user->numero_documento) }}" required />
        </div>
      </div>

      <hr class="border-neutral-200">

      {{-- Sección 2: Nombres y Apellidos --}}
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neutral-700 border-b pb-2">Información personal</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Nombres --}}
          <x-form.input name="nombres" label="Nombres" 
            value="{{ old('nombres', $user->nombres) }}" required />

          {{-- Apellidos --}}
          <x-form.input name="apellidos" label="Apellidos" 
            value="{{ old('apellidos', $user->apellidos) }}" required />
        </div>
      </div>

      <hr class="border-neutral-200">

      {{-- Sección 3: Rol y Fecha de nacimiento (Punto de control dinámico) --}}
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neutral-700 border-b pb-2">Información de rol</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Rol del usuario --}}
          <x-form.select 
            name="id_tipo_usuario" 
            label="Rol del usuario" 
            required 
            id="tipo_usuario" 
            onchange="toggleDynamicFields(this.value)"
          >
            <option value="">-- Seleccionar --</option>
            @foreach($tiposUsuario as $tipo)
              <option value="{{ $tipo->id_tipo_usuario }}" 
                {{ old('id_tipo_usuario', $user->id_tipo_usuario) == $tipo->id_tipo_usuario ? 'selected' : '' }}>
                {{ $tipo->nombre }}
              </option>
            @endforeach
          </x-form.select>

          {{-- Fecha de nacimiento --}}
          <x-form.input type="date" name="fecha_nacimiento" label="Fecha de nacimiento" 
            value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ? \Carbon\Carbon::parse($user->fecha_nacimiento)->format('Y-m-d') : '') }}" required/>
        </div>
      </div>
      
      {{-- Sección DINÁMICA: Información para MÉDICO (Rol 2) --}}
      {{-- Se usa style para el estado inicial según el rol actual o old() --}}
      <div id="medica-section" class="space-y-4 md:col-span-2 border-t pt-4 mt-2" 
        style="display: {{ old('id_tipo_usuario', $user->id_tipo_usuario) == 2 ? 'block' : 'none' }};">
        <h2 class="text-lg font-semibold text-neutral-700 border-b pb-2">Información médica</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Especialidad MÉDICA (Dinámico desde BD) --}}
          <x-form.select name="id_especialidad" label="Especialidad médica" required>
            @foreach($especialidades as $esp)
              <option value="{{ $esp->id_especialidad }}"
                {{ old('id_tipos_especialidad', optional($user->doctor)->id_tipos_especialidad) == $esp->id_tipos_especialidad ? 'selected' : '' }}>
                {{ $esp->nombre }}
              </option>
            @endforeach
          </x-form.select>

          {{-- Tarjeta profesional / Licencia (Datos del médico) --}}
          <x-form.input name="numero_tarjeta_profesional" label="Tarjeta profesional / Licencia"
            value="{{ old('numero_tarjeta_profesional', optional($user->doctor)->numero_licencia) }}" required />
            
          {{-- Universidad (Datos del médico) --}}
          <x-form.input name="universidad" label="Universidad"
            value="{{ old('universidad', optional($user->doctor)->universidad) }}" required />
            
          {{-- Experiencia () --}}
          <x-form.input name="experiencia" label="Experiencia"
            value="{{ old('experiencia', optional($user->doctor)->experiencia) }}" required/>
        </div>
        
        {{-- Descripción profesional (Datos del médico) --}}
        <x-form.textarea name="descripcion" label="Descripción profesional" rows="3" required>
          {{ old('descripcion', optional($user->doctor)->descripcion) }}
        </x-form.textarea>
      </div>
      
      <hr class="border-neutral-200">

      {{-- Sección 4: Fecha ingreso y Teléfono --}}
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neutral-700 border-b pb-2">Información laboral y contacto</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Fecha de ingreso a la IPS --}}
          <x-form.input type="date" name="fecha_ingreso_ips" label="Fecha de ingreso a la IPS" 
            value="{{ old('fecha_ingreso_ips', $user->fecha_ingreso_ips ? \Carbon\Carbon::parse($user->fecha_ingreso_ips)->format('Y-m-d') : '') }}" />

          {{-- Teléfono --}}
          <x-form.input name="telefono" label="Teléfono" 
            value="{{ old('telefono', $user->telefono) }}" />
        </div>
      </div>

      <hr class="border-neutral-200">

      {{-- Sección 5: Correo y Contraseña (Sección oculta si el Rol es 4 - Paciente) --}}
      <div class="space-y-4" id="credenciales-section" 
        style="display: {{ old('id_tipo_usuario', $user->id_tipo_usuario) == 4 ? 'none' : 'block' }};">
        <h2 class="text-lg font-semibold text-neutral-700 border-b pb-2">Credenciales de acceso</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          {{-- Correo electrónico --}}
          <x-form.input type="email" name="correo_electronico" label="Correo electrónico" 
            value="{{ old('correo_electronico', $user->correo_electronico) }}" required />

          {{-- Contraseña --}}
          <x-form.input type="password" name="password" label="Contraseña" 
            placeholder="Dejar en blanco para mantener la actual" />
        </div>

        {{-- Confirmar contraseña --}}
        <div class="max-w-md">
          <x-form.input type="password" name="password_confirmation" label="Confirmar contraseña" 
            placeholder="Confirmar nueva contraseña" />
        </div>
      </div>

      <hr class="border-neutral-200">

      {{-- Sección 6: Observaciones --}}
      <div class="space-y-4">
        <h2 class="text-lg font-semibold text-neutral-700 border-b pb-2">Información adicional</h2>
        <div>
          <x-form.textarea name="observaciones" label="Observaciones" rows="3">
            {{ old('observaciones', $user->observaciones) }}
          </x-form.textarea>
        </div>
      </div>

      {{-- Botón de envío --}}
      <div class="pt-6 text-center">
        <x-ui.button type="submit" variant="primary" class="px-8 py-3">
          Actualizar usuario
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>

  {{-- Script de JavaScript para la lógica dinámica --}}
  <script>
    function toggleDynamicFields(value) {
      const medicaSection = document.getElementById('medica-section');
      const credencialesSection = document.getElementById('credenciales-section');

      // 1. Mostrar/Ocultar Información Médica (Rol 2: Médico)
      medicaSection.style.display = (value == 2) ? 'block' : 'none';

      // 2. Mostrar/Ocultar Credenciales de Acceso (Rol 4: Paciente)
      credencialesSection.style.display = (value == 4) ? 'none' : 'block';
    }

    document.addEventListener('DOMContentLoaded', () => {
      const tipoUsuarioSelect = document.getElementById('tipo_usuario');
      // Aseguramos que el estado inicial se refleje al cargar la página (útil tras errores de validación)
      if (tipoUsuarioSelect) {
        toggleDynamicFields(tipoUsuarioSelect.value);
      }
    });
  </script>
@endsection