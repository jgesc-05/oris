@extends('layouts.secretaria')

@section('title', 'Inicio — Secretaría')

@section('secretary-content')
  <div class="space-y-6">
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
          Hola, {{ $secretary?->nombres ?? 'Secretaría' }}
        </h1>
        <p class="text-sm md:text-base text-neutral-600">
          Hoy es {{ now()->translatedFormat('l, j \\d\\e F Y') }}
        </p>
      </div>

      <div class="flex gap-2">
        <x-ui.button variant="primary" size="sm" :href="route('secretaria.citas.agendar.lookup')">Agendar cita</x-ui.button>
        <x-ui.button variant="secondary" size="sm" :href="route('secretaria.agenda')">Ver agenda</x-ui.button>
      </div>
    </header>

    <x-ui.card class="p-6 space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-ui.card class="bg-neutral-50 text-center shadow-none border border-neutral-200">
          <div class="text-sm uppercase tracking-wide text-neutral-500">Citas agendadas hoy</div>
          <div class="mt-2 text-3xl font-semibold text-neutral-900">{{ $summary['agendadas_hoy'] }}</div>
        </x-ui.card>
        <x-ui.card class="bg-neutral-50 text-center shadow-none border border-neutral-200">
          <div class="text-sm uppercase tracking-wide text-neutral-500">Citas pendientes</div>
          <div class="mt-2 text-3xl font-semibold text-neutral-900">{{ $summary['pendientes'] }}</div>
        </x-ui.card>
        <x-ui.card class="bg-neutral-50 text-center shadow-none border border-neutral-200">
          <div class="text-sm uppercase tracking-wide text-neutral-500">Pacientes del día</div>
          <div class="mt-2 text-3xl font-semibold text-neutral-900">{{ $summary['pacientes_hoy'] }}</div>
        </x-ui.card>
      </div>

      <div class="space-y-3">
        <div class="flex items-center justify-between">
          <h2 class="text-base font-semibold text-neutral-900">Agenda del día</h2>
          <x-ui.button variant="ghost" size="sm" :href="route('secretaria.agenda')">Ver completa</x-ui.button>
        </div>

        <div class="border border-neutral-200 rounded-[var(--radius)] overflow-hidden">
          <table class="min-w-full text-sm">
            <thead class="bg-neutral-100 text-neutral-700">
              <tr>
                <th class="px-4 py-2 text-left font-medium">Hora</th>
                <th class="px-4 py-2 text-left font-medium">Paciente</th>
                <th class="px-4 py-2 text-left font-medium">Servicio</th>
                <th class="px-4 py-2 text-left font-medium">Médico</th>
                <th class="px-4 py-2 text-left font-medium">Acciones</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white">
              @foreach ($agenda as $item)
                <tr>
                  <td class="px-4 py-3 text-neutral-900 font-medium">{{ $item['hora'] }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $item['paciente'] }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $item['servicio'] }}</td>
                  <td class="px-4 py-3 text-neutral-700">{{ $item['medico'] }}</td>
                  <td class="px-4 py-3 text-neutral-700">
                    <div class="flex gap-2">
                      <x-ui.button variant="ghost" size="sm" :href="route('secretaria.citas.reprogramar.lookup')">Reprogramar</x-ui.button>
                      <x-ui.button variant="ghost" size="sm" :href="route('secretaria.citas.cancelar.lookup')">Cancelar</x-ui.button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </x-ui.card>
  </div>
@endsection
