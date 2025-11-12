{{-- resources/views/admin/config/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Configuración — Admin')

@section('admin-content')
    <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-bold text-neutral-900">Configuración</h1>
        <p class="text-sm text-neutral-600">Administra catálogos y opciones del sistema.</p>
    </div>

    <x-ui.card class="bg-neutral-50">
        {{-- Ajuste del grid: ahora usa 2 columnas en pantallas medianas y mayores, y ocupa todo el ancho --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
            {{-- Especialidades --}}
            <a href="{{ route('admin.config.especialidad.index') }}"
                class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
                <x-ui.card
                    class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-xl border border-neutral-300 flex items-center justify-center text-xl">
                            🧩
                        </div>
                        <div class="flex-1">
                            <div class="text-base font-semibold text-neutral-900">Especialidades</div>
                            <p class="text-sm text-neutral-600 mt-1">Lista de especialidades médicas; crea, edita o
                                desactiva.</p>
                            <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">Ver
                                listado</span>
                        </div>
                    </div>
                </x-ui.card>
            </a>

            {{-- Servicios --}}
            <a href="{{ route('admin.config.servicio.index') }}"
                class="group block focus:outline-none focus:ring-2 focus:ring-primary-600 rounded-xl">
                <x-ui.card
                    class="h-full bg-white border-neutral-200 hover:border-neutral-300 hover:shadow-[var(--shadow-sm)] transition">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-14 h-14 rounded-xl border border-neutral-300 flex items-center justify-center text-xl">
                            🛠️
                        </div>
                        <div class="flex-1">
                            <div class="text-base font-semibold text-neutral-900">Servicios</div>
                            <p class="text-sm text-neutral-600 mt-1">Lista de servicios; gestiona nombre, duración y
                                precio.</p>
                            <span class="inline-block mt-2 text-sm font-medium text-primary-700 group-hover:underline">Ver
                                listado</span>
                        </div>
                    </div>
                </x-ui.card>
            </a>
        </div>
    </x-ui.card>
@endsection
