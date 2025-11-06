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

      <div class="flex gap-2">
        <x-ui.button variant="secondary" size="sm" :href="route('secretaria.citas.agendar.lookup')">Agendar cita</x-ui.button>
        <x-ui.button variant="ghost" size="sm">Exportar</x-ui.button>
      </div>
    </header>

    <x-ui.card class="p-5 space-y-4">
      <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-form.input type="date" name="fecha" label="Fecha" :value="now()->toDateString()" />
        <x-form.select name="estado" label="Estado">
          <option value="">Todos</option>
          <option value="confirmada">Confirmada</option>
          <option value="pendiente">Pendiente</option>
          <option value="reprogramada">Reprogramada</option>
        </x-form.select>
        <x-form.input name="paciente" label="Paciente" placeholder="Nombre o documento" />
        <div class="flex items-end">
          <x-ui.button variant="primary" size="md" class="rounded-full px-6">Filtrar</x-ui.button>
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
          @foreach ($entries as $entry)
            <tr class="hover:bg-neutral-50 transition">
              <td class="px-4 py-3">{{ \Carbon\Carbon::parse($entry['fecha'])->translatedFormat('d \\d\\e F') }}</td>
              <td class="px-4 py-3">{{ $entry['hora'] }}</td>
              <td class="px-4 py-3">{{ $entry['paciente'] }}</td>
              <td class="px-4 py-3">{{ $entry['servicio'] }}</td>
              <td class="px-4 py-3">{{ $entry['medico'] }}</td>
              <td class="px-4 py-3">
                @php $estado = strtolower($entry['estado']); @endphp
                @if ($estado === 'confirmada')
                  <x-ui.badge variant="success">Confirmada</x-ui.badge>
                @elseif ($estado === 'pendiente')
                  <x-ui.badge variant="warning">Pendiente</x-ui.badge>
                @else
                  <x-ui.badge variant="info">Reprogramada</x-ui.badge>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex gap-2">
                  <x-ui.button variant="ghost" size="sm" :href="route('secretaria.citas.reprogramar.lookup')">Reprogramar</x-ui.button>
                  <x-ui.button variant="ghost" size="sm" :href="route('secretaria.citas.cancelar.lookup')">Cancelar</x-ui.button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </x-ui.card>
  </div>
@endsection
