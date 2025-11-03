{{-- resources/views/admin/config/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Configuración — Admin')

@section('admin-content')
  <div class="mb-4">
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900">Configuración</h1>
    <p class="text-sm text-neutral-600">Administra catálogos y opciones del sistema.</p>
  </div>

  <x-ui.card class="bg-neutral-50">
    <div class="mb-5">
      <h2 class="text-base font-semibold text-neutral-900">Acciones rápidas</h2>
      <p class="text-sm text-neutral-700">
        Crea especialidades, servicios y publica profesionales para que estén disponibles en la agenda.
      </p>
      <br>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      {{-- Crear especialidad --}}
      <a href="{{ route('admin.config.especialidad.create') }}"
         class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
        <x-ui.card class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">🧩</div>
            <div>
              <div class="text-sm font-semibold text-neutral-900">Crear especialidad</div>
              <p class="text-xs text-neutral-600 mt-0.5">Agrupa y clasifica tus servicios (p.ej., Odontología general).</p>
              <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">Configurar</span>
            </div>
          </div>
        </x-ui.card>
      </a>

      {{-- Crear servicio --}}
      <a href="{{ route('admin.config.servicio.create') }}"
         class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
        <x-ui.card class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">🛠️</div>
            <div>
              <div class="text-sm font-semibold text-neutral-900">Crear servicio</div>
              <p class="text-xs text-neutral-600 mt-0.5">Define nombre, duración, precio y especialidad asociada.</p>
              <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">Crear servicio</span>
            </div>
          </div>
        </x-ui.card>
      </a>

      {{-- Publicar odontólogo --}}
      <a href="{{ route('admin.config.publicar-odontologo') }}"
         class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
        <x-ui.card class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">🧑‍⚕️</div>
            <div>
              <div class="text-sm font-semibold text-neutral-900">Publicar odontólogo</div>
              <p class="text-xs text-neutral-600 mt-0.5">Activa profesionales, ajusta agenda y servicios habilitados.</p>
              <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">Gestionar profesionales</span>
            </div>
          </div>
        </x-ui.card>
      </a>
    </div>
  </x-ui.card>
@endsection
