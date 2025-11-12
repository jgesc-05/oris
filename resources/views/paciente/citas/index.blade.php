@extends('layouts.paciente')

@section('title', 'Mis citas — Paciente')

@section('patient-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Mis citas</h1>

  @if (session('status'))
    <x-ui.alert variant="success" class="mb-4">
      {{ session('status') }}
    </x-ui.alert>
  @endif

  <x-ui.card class="max-w-6xl p-0 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-neutral-100 text-neutral-700">
        <tr>
          <th class="px-3 py-2 text-left">Fecha</th>
          <th class="px-3 py-2 text-left">Hora</th>
          <th class="px-3 py-2 text-left">Médico</th>
          <th class="px-3 py-2 text-left">Servicio</th>
          <th class="px-3 py-2 text-left">Estado</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-neutral-200">
        @forelse($appointments as $appointment)
          <tr>
            <td class="px-3 py-2">
              {{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F Y') }}
            </td>
            <td class="px-3 py-2">{{ $appointment->fecha_hora_inicio->format('h:i A') }}</td>
            <td class="px-3 py-2">
              {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}
            </td>
            <td class="px-3 py-2">{{ $appointment->servicio?->nombre }}</td>
            <td class="px-3 py-2">
              <x-appointment.status-badge :estado="$appointment->estado" />
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-3 py-4 text-center text-neutral-600">
              Aún no has registrado citas.
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
@endsection
