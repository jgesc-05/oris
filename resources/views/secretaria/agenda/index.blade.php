@extends('layouts.secretaria')

@section('title', 'Agenda — Secretaría')

@section('secretary-content')
    <div class="space-y-6">
        <header class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <div>
                <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Agenda diaria</h1>
                <p class="text-sm text-neutral-600">
                    Consulta y gestiona las citas programadas. Puedes filtrar por fecha o estado.
                </p>
            </div>
        </header>

        @if (session('status'))
            <x-ui.alert variant="success">
                {{ session('status') }}
            </x-ui.alert>
        @endif

        @if ($errors->has('estado'))
            <x-ui.alert variant="warning">
                {{ $errors->first('estado') }}
            </x-ui.alert>
        @endif

        <x-ui.card class="p-5 space-y-4">
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4" method="GET">
                <x-form.input type="date" name="fecha" label="Fecha" :value="$filters['fecha']" />

                <x-form.select name="estado" label="Estado">
                    <option value="">Todos</option>
                    <option value="Programada" @selected($filters['estado'] === 'Programada')>Programada</option>
                    <option value="Atendida" @selected($filters['estado'] === 'Atendida')>Atendida</option>
                    <option value="Cancelada" @selected($filters['estado'] === 'Cancelada')>Cancelada</option>
                </x-form.select>

                <x-form.input name="paciente" label="Paciente" placeholder="Nombre o documento" :value="$filters['paciente']" />
                
                <div class="flex items-end gap-2">
                    <x-ui.button variant="primary" size="md" class="rounded-full px-6">Filtrar</x-ui.button>
                    <x-ui.button variant="ghost" size="md" class="rounded-full px-6" :href="route('secretaria.agenda')">
                        Limpiar
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card class="p-0 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-neutral-100 text-neutral-700">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Fecha</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Hora</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Paciente</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Servicio</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Médico</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Estado</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white">
                    @forelse ($appointments as $entry)
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-4 py-3">
                                {{ $entry->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F') }}
                            </td>
                            <td class="px-4 py-3">{{ $entry->fecha_hora_inicio->format('h:i A') }}</td>
                            <td class="px-4 py-3">{{ $entry->paciente?->nombres }} {{ $entry->paciente?->apellidos }}</td>
                            <td class="px-4 py-3">{{ $entry->servicio?->nombre }}</td>
                            <td class="px-4 py-3">{{ $entry->medico?->nombres }} {{ $entry->medico?->apellidos }}</td>
                            <td class="px-4 py-3">
                                <x-appointment.status-badge :estado="$entry->estado" />
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $patientId = $entry->paciente?->id_usuario;
                                    $canManageAppointment = $entry->isProgramada() && $patientId;
                                    $disabledClasses = $canManageAppointment ? '' : 'opacity-50 cursor-not-allowed';
                                    $disabledTitle = $canManageAppointment
                                        ? null
                                        : 'Disponible solo para citas programadas';
                                    $reprogramUrl = $canManageAppointment
                                        ? route('secretaria.citas.reprogramar.edit', [$patientId, $entry->id_cita])
                                        : null;
                                    $cancelUrl = $canManageAppointment
                                        ? route('secretaria.citas.cancelar.list', $patientId)
                                        : null;
                                @endphp
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-ui.button variant="ghost" size="sm" class="{{ $disabledClasses }}"
                                        :href="$reprogramUrl" :disabled="!$canManageAppointment" :title="$disabledTitle">
                                        Reprogramar
                                    </x-ui.button>
                                    <x-ui.button variant="ghost" size="sm" class="{{ $disabledClasses }}"
                                        :href="$cancelUrl" :disabled="!$canManageAppointment" :title="$disabledTitle">
                                        Cancelar
                                    </x-ui.button>
                                    @if ($entry->isProgramada())
                                        <form method="POST"
                                            action="{{ route('secretaria.agenda.mark-attended', $entry->id_cita) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button type="submit" variant="success" size="sm">
                                                Marcar atendida
                                            </x-ui.button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-neutral-500">
                                No hay citas para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Paginación IDÉNTICA a la de Pacientes --}}
    <div class="mt-4 flex items-center justify-center gap-2">
        {{-- Botón anterior --}}
        @if ($appointments->onFirstPage())
            <x-ui.button variant="secondary" size="sm" disabled>‹</x-ui.button>
        @else
            <a href="{{ $appointments->previousPageUrl() }}">
                <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">‹</x-ui.button>
            </a>
        @endif

        {{-- Números de página --}}
        @foreach ($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
            <a href="{{ $url }}">
                <x-ui.badge
                    @class([
                        'bg-blue-500 text-black border border-blue-500' => $page == $appointments->currentPage(),
                        'hover:bg-blue-100 transition cursor-pointer' => $page != $appointments->currentPage(),
                    ])>
                    {{ $page }}
                </x-ui.badge>
            </a>
        @endforeach

        {{-- Botón siguiente --}}
        @if ($appointments->hasMorePages())
            <a href="{{ $appointments->nextPageUrl() }}">
                <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">›</x-ui.button>
            </a>
        @else
            <x-ui.button variant="secondary" size="sm" disabled>›</x-ui.button>
        @endif
    </div>

    <p class="text-sm text-neutral-500 text-center mt-2">
        Mostrando {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }} de {{ $appointments->total() }} citas
    </p>
        </x-ui.card>
    </div>
@endsection