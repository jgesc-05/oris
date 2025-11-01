{{-- resources/views/admin/config.blade.php --}}
@extends('layouts.admin')
@section('title', 'Configuración — Admin')

@php
  $linkTipoServicio = \Illuminate\Support\Facades\Route::has('admin.config.tipo-servicio.create')
      ? route('admin.config.tipo-servicio.create') : '#';

  $linkServicio = \Illuminate\Support\Facades\Route::has('admin.config.servicios.create')
      ? route('admin.config.servicios.create') : '#';

  $linkPublicarOdontologo = \Illuminate\Support\Facades\Route::has('admin.config.odontologos.publish')
      ? route('admin.config.odontologos.publish') : '#';
@endphp

@section('admin-content')
  {{-- Encabezado de sección --}}
  <div class="mb-4">
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900">Configuración</h1>
    <p class="text-sm text-neutral-600">Administra catálogos y opciones del sistema.</p>
  </div>

  {{-- Panel principal "bonito" --}}
  <x-ui.card class="bg-neutral-50">
    {{-- Bloque descriptivo --}}
    <div class="mb-5">
      <h2 class="text-base font-semibold text-neutral-900">Acciones rápidas</h2>
      <p class="text-sm text-neutral-700">
        Crea tipos de servicio, servicios y publica profesionales para que estén disponibles en la agenda.
      </p>
      <br>
    </div>

    {{-- Grid de acciones --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      {{-- Tile: Crear tipo de servicio --}}
      <a href="{{ $linkTipoServicio }}" class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
        <x-ui.card class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">
              🧩
            </div>
            <div>
              <div class="text-sm font-semibold text-neutral-900">Crear tipo de servicio</div>
              <p class="text-xs text-neutral-600 mt-0.5">Agrupa y clasifica tus servicios (ej. Odontología general, Ortodoncia).</p>
              <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">
                Configurar
              </span>
            </div>
          </div>
        </x-ui.card>
      </a>

      {{-- Tile: Crear servicio --}}
      <a href="{{ $linkServicio }}" class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
        <x-ui.card class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">
              🛠️
            </div>
            <div>
              <div class="text-sm font-semibold text-neutral-900">Crear servicio</div>
              <p class="text-xs text-neutral-600 mt-0.5">Define nombre, duración, precio y tipo de servicio asociado.</p>
              <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">
                Crear servicio
              </span>
            </div>
          </div>
        </x-ui.card>
      </a>

      {{-- Tile: Publicar odontólogo --}}
      <a href="{{ $linkPublicarOdontologo }}" class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
        <x-ui.card class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">
              🧑‍⚕️
            </div>
            <div>
              <div class="text-sm font-semibold text-neutral-900">Publicar odontólogo</div>
              <p class="text-xs text-neutral-600 mt-0.5">Activa profesionales, ajusta agenda y servicios habilitados.</p>
              <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">
                Gestionar profesionales
              </span>
            </div>
          </div>
        </x-ui.card>
      </a>
    </div>
  </x-ui.card>

  {{-- Sección futura opcional: ajustes del sistema --}}
  {{--
  <div class="mt-6">
    <x-ui.card title="Ajustes del sistema" subtitle="Preferencias generales (pendiente)">
      <p class="text-sm text-neutral-700">Aquí podrás definir zona horaria, formatos y notificaciones.</p>
    </x-ui.card>
  </div>
  --}}
@endsection
