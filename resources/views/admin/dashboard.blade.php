{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Inicio — Admin')

@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Appointment;
    use App\Models\User;

    $profile = Auth::user();
    \Carbon\Carbon::setLocale('es');
    $currentDate = \Carbon\Carbon::now()->translatedFormat('l, j \d\e F');

    
    $now = \Carbon\Carbon::now();

// Citas programadas en el mes actual
$programmedAppointments = Appointment::where('estado', 'Programada')
    ->whereMonth('created_at', $now->month)
    ->whereYear('created_at', $now->year)
    ->count();

// Pacientes activos creados en el mes actual
$activePatients = User::where('estado', 'activo')
    ->where('id_tipo_usuario', 4)
    ->count();
@endphp

@section('admin-content')

    {{-- Encabezado --}}
    <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-bold text-neutral-900">Hola, {{ $profile->nombres }}</h1>
        <p class="text-sm text-neutral-600">{{ $currentDate }}</p>
    </div>

    {{-- Grid principal --}}
    <div class="grid grid-cols-1 gap-6">
        {{-- Gestión de usuarios y roles --}}
        <x-ui.card title="Gestión de usuarios y roles">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.usuarios.create') }}" class="block">
                    <x-ui.card
                        class="text-center bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">
                                👤
                            </div>
                            <div class="text-sm font-medium text-neutral-900">Crear<br>usuario</div>
                        </div>
                    </x-ui.card>
                </a>

                <a href="{{ route('admin.usuarios.index') }}" class="block">
                    <x-ui.card
                        class="text-center bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">
                                ✏️
                            </div>
                            <div class="text-sm font-medium text-neutral-900">Editar<br>usuario</div>
                        </div>
                    </x-ui.card>
                </a>

                <a href="{{ route('admin.usuarios.index') }}" class="block">
                    <x-ui.card
                        class="text-center bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="w-12 h-12 rounded-xl border border-neutral-300 flex items-center justify-center text-neutral-700">
                                🚫
                            </div>
                            <div class="text-sm font-medium text-neutral-900">Suspender<br>usuario</div>
                        </div>
                    </x-ui.card>
                </a>
            </div>
        </x-ui.card>

        {{-- Estadísticas y reportes mensuales --}}
        <x-ui.card title="Estadísticas y reportes mensuales">
            <div class="grid grid-cols-3 gap-4">
                <x-ui.card class="bg-neutral-50 text-center">
                    <div class="text-3xl font-bold text-neutral-900">{{ $programmedAppointments }}</div>
                    <div class="text-sm text-neutral-600">Citas<br>programadas</div>
                </x-ui.card>

                <x-ui.card class="bg-neutral-50 text-center">
                    <div class="text-3xl font-bold text-neutral-900">{{ $activePatients }}</div>
                    <div class="text-sm text-neutral-600">Pacientes<br>activos</div>
                </x-ui.card>

                <a href="{{ route('admin.reportes.index') }}" class="block">
                    <x-ui.card
                        class="text-center bg-neutral-50 hover:bg-rose-50 border border-neutral-200 hover:border-rose-300 transition cursor-pointer">
                        <div class="flex flex-col items-center gap-2">
                            <div class="text-3xl font-bold text-neutral-900">+</div>
                            <div class="text-sm text-neutral-600">Más</div>
                        </div>
                    </x-ui.card>
                </a>
            </div>
        </x-ui.card>
    </div>
@endsection
