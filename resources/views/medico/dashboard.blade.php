@extends('layouts.medico')

@section('title', 'Inicio — Médico')

@php
    \Carbon\Carbon::setLocale('es');
@endphp

@section('doctor-content')
    <div class="space-y-8">
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-widest text-neutral-500">Panel médico</p>
                <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
                    Hola, {{ $doctor?->nombres }} 👋
                </h1>
                <p class="text-sm md:text-base text-neutral-600 mt-1">
                    Hoy es {{ now()->translatedFormat('l, j \\d\\e F Y') }}
                </p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-ui.card class="bg-white border border-neutral-200">
                <p class="text-sm text-neutral-500">Citas hoy</p>
                <p class="text-3xl font-semibold text-neutral-900">{{ $indicators['programadas'] }}</p>
                <p class="text-xs text-neutral-500 mt-1">Programadas</p>
            </x-ui.card>
            <x-ui.card class="bg-white border border-neutral-200">
                <p class="text-sm text-neutral-500">Completadas</p>
                <p class="text-3xl font-semibold text-emerald-600">{{ $indicators['completadas'] }}</p>
                <p class="text-xs text-neutral-500 mt-1">Atendidas hoy</p>
            </x-ui.card>
            <x-ui.card class="bg-white border border-neutral-200">
                <p class="text-sm text-neutral-500">Canceladas</p>
                <p class="text-3xl font-semibold text-rose-600">{{ $indicators['canceladas'] }}</p>
                <p class="text-xs text-neutral-500 mt-1">Hoy</p>
            </x-ui.card>
            <x-ui.card class="bg-white border border-neutral-200">
                <p class="text-sm text-neutral-500">Pacientes distintos</p>
                <p class="text-3xl font-semibold text-sky-600">{{ $indicators['pacientes'] }}</p>
                <p class="text-xs text-neutral-500 mt-1">En agenda</p>
            </x-ui.card>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <x-ui.card class="xl:col-span-2" title="Agenda del día" subtitle="Citas confirmadas para hoy">
                <div class="space-y-4">
                    @forelse ($todayAppointments as $appointment)
                        <div
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border border-neutral-200 rounded-2xl px-4 py-3 hover:border-primary-200 transition">
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <p class="text-xl font-semibold text-neutral-900">
                                        {{ optional($appointment->fecha_hora_inicio)->format('H:i') }}</p>
                                    <p class="text-xs text-neutral-500">
                                        {{ optional($appointment->fecha_hora_fin)->format('H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900">
                                        {{ optional($appointment->paciente)->nombres }}
                                        {{ optional($appointment->paciente)->apellidos }}
                                    </p>
                                    <p class="text-xs text-neutral-600 flex items-center gap-1">
                                        🩺 {{ optional($appointment->servicio)->nombre ?? 'Servicio sin asignar' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @php
                                    $estado = $appointment->estado;
                                    $badgeVariant = match ($estado) {
                                        'Completada' => 'success',
                                        'Cancelada' => 'warning',
                                        'Reprogramada' => 'info',
                                        default => 'neutral',
                                    };
                                    $patientId = optional($appointment->paciente)->id_usuario;
                                @endphp
                                <x-ui.badge :variant="$badgeVariant">{{ $estado }}</x-ui.badge>
                                <x-ui.button variant="ghost" size="sm" :href="$patientId ? route('medico.pacientes.show', $patientId) : null">
                                    Ver ficha
                                </x-ui.button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-neutral-500">
                            No tienes citas programadas para hoy.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>

            <div class="space-y-6">
                <x-ui.card title="Indicadores del mes" subtitle="Con base en tus citas agendadas">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Productividad</span>
                            <span class="text-sm font-semibold text-neutral-900">{{ $productivity }}%</span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-2">
                            <div class="h-2 rounded-full bg-[var(--color-primary-500)]"
                                style="width: {{ $productivity }}%"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-center text-sm">
                            <div>
                                <p class="text-neutral-500 text-xs">Totales</p>
                                <p class="text-xl font-semibold">{{ $monthStats['total'] }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-500 text-xs">Completadas</p>
                                <p class="text-xl font-semibold text-emerald-600">{{ $monthStats['completadas'] }}</p>
                            </div>
                            <div>
                                <p class="text-neutral-500 text-xs">Reprogramadas</p>
                                <p class="text-xl font-semibold text-amber-600">{{ $monthStats['reprogramadas'] }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-neutral-500 text-center">
                            Canceladas este mes: {{ $monthStats['canceladas'] }}
                        </p>
                    </div>
                </x-ui.card>

                <x-ui.card title="Próximas citas" subtitle="Siguientes 5 pacientes confirmados">
                    <div class="space-y-4">
                        @forelse ($upcomingAppointments as $appointment)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-neutral-900">
                                        {{ optional($appointment->paciente)->nombres }}
                                        {{ optional($appointment->paciente)->apellidos }}
                                    </p>
                                    <p class="text-xs text-neutral-500">
                                        {{ optional($appointment->fecha_hora_inicio)->translatedFormat('d M - H:i') }}
                                    </p>
                                </div>
                                <x-ui.badge variant="neutral">
                                    {{ optional($appointment->servicio)->nombre ?? 'Servicio' }}
                                </x-ui.badge>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-500 text-center">No hay próximas citas.</p>
                        @endforelse
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>
@endsection
