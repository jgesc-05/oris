{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('title', 'Iniciar sesión — Equipo de la IPS')

@section('content')
  @php
    // Fallback seguro por si aún no existe la ruta nombrada
    $staffLoginPostUrl = \Illuminate\Support\Facades\Route::has('login')
      ? route('login')    // POST /login cuando implementes backend
      : url('/login');
  @endphp

  <div class="max-w-xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-6">Iniciar sesión</h1>

    <form action="{{ $staffLoginPostUrl }}" method="POST" class="space-y-4">
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
        value="{{ old('numero_documento') }}"
      />

      {{-- Contraseña --}}
      <x-form.input
        name="password"
        type="password"
        label="Contraseña"
        placeholder=""
        required
        autocomplete="current-password"
      />

      <p class="text-sm text-neutral-600">
        Al continuar, aceptas los <a href="#" class="underline">Términos de uso</a> y la
        <a href="#" class="underline">Política de privacidad</a>.
      </p>

      <x-ui.button variant="primary" size="lg" block="true" class="rounded-full">
        Iniciar sesión
      </x-ui.button>
    </form>

    {{-- (Opcional) Enlace a recuperación de contraseña --}}
    <p class="text-center text-sm text-neutral-700 mt-4">
      <a href="#" class="text-primary-700 font-medium hover:underline">¿Olvidaste tu contraseña?</a>
    </p>
  </div>
@endsection
