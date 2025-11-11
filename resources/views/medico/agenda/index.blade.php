@extends('layouts.medico')

@section('title', 'Agenda — Médico')

@php
    \Carbon\Carbon::setLocale('es');
@endphp

@section('doctor-content')
    <div class="space-y-6">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-widest text-neutral-500">Mi agenda</p>
                <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Citas programadas</h1>
                <p class="text-sm text-neutral-600">
                    Filtra por fecha, estado o paciente para revisar tu agenda personal.
                </p>
            </div>
            <x-ui.button :href="route('medico.dashboard')" variant="ghost">
                ← Volver al panel
            </x-ui.button>
        </header>

        <x-ui.card class="p-5 space-y-4">
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end" method="GET">
                <x-form.input type="date" name="fecha" label="Fecha" :value="$filters['fecha']" />

                <x-form.select name="estado" label="Estado">
                    <option value="">Todos</option>
                    <option value="Programada" @selected($filters['estado'] === 'Programada')>Programada</option>
                    <option value="Completada" @selected($filters['estado'] === 'Completada')>Completada</option>
                    <option value="Cancelada" @selected($filters['estado'] === 'Cancelada')>Cancelada</option>
                    <option value="Reprogramada" @selected($filters['estado'] === 'Reprogramada')>Reprogramada</option>
                </x-form.select>

                <x-form.input name="paciente" label="Paciente" placeholder="Nombre o documento" :value="$filters['paciente']" />
                <br>
                <div class="flex items-end gap-2">
                    <x-ui.button variant="primary" size="md" class="rounded-full px-6">Filtrar</x-ui.button>
                    <x-ui.button variant="ghost" :href="route('medico.agenda')">Limpiar</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card class="p-0 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-neutral-100 text-neutral-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium uppercase text-xs">Fecha</th>
                        <th class="px-4 py-3 text-left font-medium uppercase text-xs">Hora</th>
                        <th class="px-4 py-3 text-left font-medium uppercase text-xs">Paciente</th>
                        <th class="px-4 py-3 text-left font-medium uppercase text-xs">Servicio</th>
                        <th class="px-4 py-3 text-left font-medium uppercase text-xs">Estado</th>
                        <th class="px-4 py-3 text-left font-medium uppercase text-xs">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white">
                    @forelse ($appointments as $entry)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-4 py-3">
                                {{ optional($entry->fecha_hora_inicio)->translatedFormat('d \\d\\e F') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ optional($entry->fecha_hora_inicio)->format('H:i') }} —
                                {{ optional($entry->fecha_hora_fin)->format('H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ optional($entry->paciente)->nombres }} {{ optional($entry->paciente)->apellidos }}
                            </td>
                            <td class="px-4 py-3">{{ optional($entry->servicio)->nombre ?? 'Sin servicio' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $variant = match ($entry->estado) {
                                        'Completada' => 'success',
                                        'Cancelada' => 'warning',
                                        'Reprogramada' => 'info',
                                        default => 'primary',
                                    };
                                @endphp
                                <x-ui.badge :variant="$variant">{{ $entry->estado }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                @if ($entry->paciente)
                                    <x-ui.button variant="ghost" size="sm" :href="route('medico.pacientes.show', $entry->paciente->id_usuario)">
                                        Ver paciente
                                    </x-ui.button>
                                @else
                                    <span class="text-xs text-neutral-400">Sin paciente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-neutral-500">
                                No hay citas para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.card>
    </div>
@endsection
