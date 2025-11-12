{{-- resources/views/auth/paciente/register.blade.php --}}
@extends('layouts.guest')

@section('title', 'Registro — Pacientes')

@section('content')
  @php
    // Fallbacks seguros por si aún no están definidas las rutas
    $registerPostUrl = \Illuminate\Support\Facades\Route::has('paciente.register')
      ? route('paciente.register')
      : url('/paciente/registro');

    $loginUrl = \Illuminate\Support\Facades\Route::has('paciente.login')
      ? route('paciente.login')
      : url('/paciente/login');
  @endphp

            @if (session('status'))
                <x-ui.alert variant="success" class="mb-4">
                    {{ session('status') }}
                </x-ui.alert>
            @endif

            @if ($errors->any())
                <x-ui.alert variant="warning" class="mb-4">
                    <ul class="space-y-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            @endif

  <div class="max-w-4xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-6">Registro</h1>

    <form action="{{ $registerPostUrl }}" method="POST" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Tipo de documento --}}
        <x-form.select name="id_tipo_documento" label="Tipo de documento"  :placeholder="null">
          @foreach (($documentTypes ?? []) as $documentType)
            <option value="{{ $documentType->id_tipo_documento }}"
              @selected(old('id_tipo_documento') == $documentType->id_tipo_documento)>
              {{ $documentType->name }}
            </option>
          @endforeach
        </x-form.select>


        {{-- Número de documento --}}
        <x-form.input
          name="numero_documento"
          label="Número de documento"
          placeholder=""
          inputmode="numeric"
          autocomplete="username"
          value="{{ old('numero_documento') }}"
        />

        {{-- Nombres --}}
        <x-form.input
          name="nombres"
          label="Nombres"
          placeholder=""
          autocomplete="given-name"
          value="{{ old('nombres') }}"
        />

        {{-- Apellidos --}}
        <x-form.input
          name="apellidos"
          label="Apellidos"
          placeholder=""
          autocomplete="family-name"
          value="{{ old('apellidos') }}"
        />

        {{-- Fecha de nacimiento --}}
        <x-form.input
          name="fecha_nacimiento"
          label="Fecha de nacimiento"
          type="date"
          autocomplete="bday"
          min="1900-01-01"
          max="{{ now()->format('Y-m-d') }}"
          value="{{ old('fecha_nacimiento') }}"
        />

        {{-- Correo electrónico --}}
        <x-form.input
        name="correo_electronico"
        type="email"
        label="Correo electrónico"
        placeholder=""
        autocomplete="email"
        value="{{ old('correo_electronico') }}"
        />

        {{-- Teléfono --}}
        <x-form.input
          name="telefono"
          label="Teléfono"
          placeholder=""
          inputmode="tel"
          autocomplete="tel"
          value="{{ old('telefono') }}"
        />

        {{-- Observaciones --}}
        <x-form.input
          name="observaciones"
          label="Observaciones"
          placeholder=""
          value="{{ old('observaciones') }}"
        />
      </div>



@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

      {{-- Copy legal breve --}}
      <p class="text-sm text-neutral-600">
        Al registrarte, aceptas los <a href="#" class="underline">Términos de uso</a> y la
        <a href="#" class="underline">Política de privacidad</a>.
      </p>

      {{-- CTA: botón grande y redondeado tipo “pill” --}}
      <x-ui.button variant="primary" size="lg" block="true" class="rounded-full">
        Registrarse
      </x-ui.button>

      {{-- Enlace a login --}}
      <p class="text-center text-sm text-neutral-700">
        ¿Ya tienes cuenta?
        <a href="{{ $loginUrl }}" class="text-primary-700 font-medium hover:underline">Inicia sesión</a>
      </p>
    </form>
  </div>
@endsection
