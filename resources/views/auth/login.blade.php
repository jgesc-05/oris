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

      {{-- Correo electrónico --}}
      <x-form.input
        name="correo_electronico"
        type="email"
        label="Correo electrónico"
        placeholder=""
        required
        autocomplete="username"
        value="{{ old('correo_electronico') }}"
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
