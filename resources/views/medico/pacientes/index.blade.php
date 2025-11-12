@extends('layouts.medico')

@section('title', 'Pacientes — Médico')

@section('doctor-content')
    <div class="space-y-6">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Mis pacientes</h1>
                <p class="text-sm text-neutral-600">
                    Lista de pacientes atendidos por mí, con filtros por búsqueda y estado.
                </p>
            </div>
        </header>

        <x-ui.card class="mb-4">
            <form method="GET" class="grid grid-cols-1 lg:grid-cols-4 gap-3">
                {{-- Buscar --}}
                <div class="lg:col-span-2">
                    <label for="q" class="form-label">Buscar</label>
                    <div class="relative">
                        <input id="q" name="q" type="text" value="{{ $search }}"
                            placeholder="Nombre, documento o correo" class="form-control pl-10" />
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">🔎</span>
                    </div>
                </div>

                <div class="lg:col-span-4 flex gap-2 justify-end">
                    <x-ui.button type="submit" variant="primary">Filtrar</x-ui.button>
                    <x-ui.button :href="route('medico.pacientes.index')" variant="ghost">Limpiar</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card class="p-0 overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 text-sm">
                <thead class="bg-neutral-100 text-neutral-700">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Nombre</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Documento</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Correo</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Cita</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Estado</th>
                        <th class="px-4 py-2 text-left font-medium uppercase text-xs">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse ($patients as $patient)
                        @php
                            $ultima = $latestAppointments[$patient->id_usuario] ?? null;
                        @endphp
                        <tr class="hover:bg-neutral-50 transition">
                            <td class="px-4 py-3 text-neutral-900 font-medium">
                                {{ $patient->nombres }} {{ $patient->apellidos }}
                            </td>
                            <td class="px-4 py-3 text-neutral-600">
                                {{ $patient->numero_documento }}
                            </td>
                            <td class="px-4 py-3 text-neutral-600">
                                {{ $patient->correo_electronico }}
                            </td>
                            <td class="px-4 py-3 text-neutral-600">
                                {{ $ultima ? \Carbon\Carbon::parse($ultima)->translatedFormat('d M Y • H:i') : '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($patient->estado === 'activo')
                                    <x-ui.badge variant="success">Activo</x-ui.badge>
                                @else
                                    <x-ui.badge variant="neutral">Inactivo</x-ui.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <x-ui.button variant="ghost" size="sm" :href="route('medico.pacientes.show', $patient->id_usuario)">
                                    Ver detalle
                                </x-ui.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-neutral-500">
                                No hay pacientes asociados a tus atenciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Paginación funcional --}}
@if ($patients instanceof \Illuminate\Pagination\LengthAwarePaginator && $patients->count())
    <div class="mt-4 flex items-center justify-center gap-2">
        {{-- Botón anterior --}}
        @if ($patients->onFirstPage())
            <x-ui.button variant="secondary" size="sm" disabled>‹</x-ui.button>
        @else
            <a href="{{ $patients->previousPageUrl() }}">
                <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">‹</x-ui.button>
            </a>
        @endif

        {{-- Números de página --}}
        @foreach ($patients->getUrlRange(1, $patients->lastPage()) as $page => $url)
            <a href="{{ $url }}">
                <x-ui.badge
                    @class([
                        'bg-blue-500 text-black border border-blue-500' => $page == $patients->currentPage(),
                        'hover:bg-blue-100 transition cursor-pointer' => $page != $patients->currentPage(),
                    ])>
                    {{ $page }}
                </x-ui.badge>
            </a>
        @endforeach

        {{-- Botón siguiente --}}
        @if ($patients->hasMorePages())
            <a href="{{ $patients->nextPageUrl() }}">
                <x-ui.button variant="secondary" size="sm" class="hover:bg-neutral-200 transition">›</x-ui.button>
            </a>
        @else
            <x-ui.button variant="secondary" size="sm" disabled>›</x-ui.button>
        @endif
    </div>

    <p class="text-sm text-neutral-500 text-center mt-2">
        Mostrando {{ $patients->firstItem() }}–{{ $patients->lastItem() }} de {{ $patients->total() }} pacientes
    </p>
@endif

        </x-ui.card>
    </div>
@endsection
