{{-- resources/views/paciente/citas/create.blade.php --}}
@extends('layouts.paciente')

@section('title', 'Agendar cita — Paciente')

@section('patient-content')
@php
  // Cuando tengas backend:
  // Route::post('paciente/citas', ...)->name('paciente.citas.store')
  $storeUrl = \Illuminate\Support\Facades\Route::has('paciente.citas.store')
    ? route('paciente.citas.store')
    : url('/paciente/citas'); // fallback temporal
@endphp

<h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Agendar cita</h1>

@if (session('status'))
  <x-ui.alert variant="success" class="mb-4">
    {{ session('status') }}
  </x-ui.alert>
@endif

<x-ui.card class="max-w-5xl">
  <form method="POST" action="{{ $storeUrl }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @csrf

    {{-- Tipo de servicio / especialidad --}}
    <x-form.select name="especialidad" label="Tipo de servicio" required>
      <option value="">-- Seleccionar --</option>
      <option>Endodoncia</option>
      <option>Ortodoncia</option>
      <option>Odontología general</option>
      <option>Periodoncia</option>
    </x-form.select>

    {{-- Fecha --}}
    <x-form.input name="fecha" label="Fecha" type="date" required />

    {{-- Servicio específico (depende de la especialidad; mock por ahora) --}}
    <x-form.select name="servicio" label="Servicio específico" required>
      <option value="">-- Seleccionar --</option>
      <option>Tratamiento de conducto</option>
      <option>Profilaxis</option>
      <option>Control de ortodoncia</option>
      <option>Valoración</option>
    </x-form.select>

    {{-- Hora --}}
    <x-form.input name="hora" label="Hora" type="time" required />

    {{-- Profesional --}}
    <x-form.select name="medico" label="Odontólogo" required class="md:col-span-2">
      <option value="">-- Seleccionar --</option>
      <option>Luisa Mantilla</option>
      <option>Antonio Londoño</option>
      <option>Sandra Rodríguez</option>
      <option>Camilo Pérez</option>
    </x-form.select>

    <div class="md:col-span-2 pt-2">
      <x-ui.button variant="primary" size="lg" block class="rounded-full">
        Agendar cita
      </x-ui.button>
    </div>
  </form>
</x-ui.card>
@endsection
