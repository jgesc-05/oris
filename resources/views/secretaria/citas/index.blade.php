@extends('layouts.secretaria')

@php
  $actionLabels = [
    'agendar'     => 'Agendar cita',
    'reprogramar' => 'Reprogramar cita',
    'cancelar'    => 'Cancelar cita',
  ];
  $label = $actionLabels[$action] ?? 'Gestionar cita';
  $submitRoute = match($action) {
    'reprogramar' => route('secretaria.citas.reprogramar.lookup.submit'),
    'cancelar'    => route('secretaria.citas.cancelar.lookup.submit'),
    default       => route('secretaria.citas.agendar.lookup.submit'),
  };
@endphp

@section('title', $label.' — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-3xl">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">citas — {{ $label }}</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">{{ $label }}</h1>
      <p class="text-sm md:text-base text-neutral-600">
        Ingresa los datos del paciente para continuar con el proceso.
      </p>
    </header>

    <x-ui.card class="p-6">
      @if ($errors->any())
        <x-ui.alert variant="warning" class="mb-4">
          {{ $errors->first() }}
        </x-ui.alert>
      @endif

      <form method="POST" action="{{ $submitRoute }}" class="space-y-4">
        @csrf

        <x-form.select name="id_tipo_documento" label="Tipo de documento">
          <option value="1" @selected(old('id_tipo_documento')==='1')>Cédula de ciudadanía</option>
          <option value="TI" @selected(old('id_tipo_documento')==='TI')>Tarjeta de identidad</option>
          <option value="CE" @selected(old('id_tipo_documento')==='CE')>Cédula de extranjería</option>
          <option value="PA" @selected(old('id_tipo_documento')==='PA')>Pasaporte</option>
        </x-form.select>

        <x-form.input
          name="numero_documento"
          label="Número de documento"
          inputmode="numeric"
          value="{{ old('numero_documento') }}"
        />

        <x-form.input
          name="fecha_nacimiento"
          label="Fecha de nacimiento"
          type="date"
          value="{{ old('fecha_nacimiento') }}"
          max="{{ now()->toDateString() }}"
        />

        <x-ui.button variant="primary" size="lg" block class="rounded-full">
          Continuar
        </x-ui.button>
      </form>
    </x-ui.card>
  </div>
@endsection
