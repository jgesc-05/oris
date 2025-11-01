{{-- resources/views/auth/paciente/login.blade.php --}}
@extends('layouts.guest')

@section('title', 'Iniciar sesión — Pacientes')

@section('content')
  @php
    // Fallbacks seguros por si aún no están definidas las rutas
    $loginPostUrl = \Illuminate\Support\Facades\Route::has('paciente.login')
      ? route('paciente.login')
      : url('/paciente/login');

    $registerUrl = \Illuminate\Support\Facades\Route::has('paciente.register')
      ? route('paciente.register')
      : url('/paciente/registro');
  @endphp

  <div class="max-w-xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-6">Iniciar sesión</h1>

    <form action="{{ $loginPostUrl }}" method="POST" class="space-y-4">
      @csrf

      {{-- Tipo de documento --}}
      <x-form.select name="tipo_documento" label="Tipo de documento" required>
        <option value="CC" @selected(old('tipo_documento')==='CC')>Cédula de ciudadanía</option>
        <option value="TI" @selected(old('tipo_documento')==='TI')>Tarjeta de identidad</option>
        <option value="CE" @selected(old('tipo_documento')==='CE')>Cédula de extranjería</option>
        <option value="PA" @selected(old('tipo_documento')==='PA')>Pasaporte</option>
      </x-form.select>

      {{-- Número de documento --}}
      <x-form.input
        name="numero_documento"
        label="Número de documento"
        placeholder=""
        required
        inputmode="numeric"
        autocomplete="username"
      />

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


      {{-- Copy legal breve --}}
      <p class="text-sm text-neutral-600">
        Al continuar, aceptas los <a href="#" class="underline">Términos de uso</a> y la
        <a href="#" class="underline">Política de privacidad</a>.
      </p>

      {{-- CTA: botón grande, ancho completo y redondeado tipo “pill” --}}
      <x-ui.button variant="primary" size="lg" block="true" class="rounded-full">
        Iniciar sesión
      </x-ui.button>
    </form>

    {{-- Enlace a registro --}}
    <p class="text-center text-sm text-neutral-700 mt-4">
      ¿No tienes una cuenta?
      <a href="{{ $registerUrl }}" class="text-primary-700 font-medium hover:underline">Regístrate</a>
    </p>
  </div>
@endsection
