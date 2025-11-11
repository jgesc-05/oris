@extends('layouts.medico')

@section('title', 'Pacientes — Médico')

@php
  \Carbon\Carbon::setLocale('es');
@endphp

@section('doctor-content')
  <div class="space-y-6">
    <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <p class="text-sm uppercase tracking-widest text-neutral-500">Mis pacientes</p>
        <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Panel de pacientes</h1>
        <p class="text-sm text-neutral-600">Accede rápidamente a los historiales y datos de contacto.</p>
      </div>
      <x-ui.button :href="route('medico.dashboard')" variant="ghost">
        ← Volver a la agenda
      </x-ui.button>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <x-ui.card class="bg-white border border-neutral-200">
        <p class="text-sm text-neutral-500">Total vinculados</p>
        <p class="text-3xl font-semibold text-neutral-900">{{ $stats['total'] }}</p>
      </x-ui.card>
      <x-ui.card class="bg-white border border-neutral-200">
        <p class="text-sm text-neutral-500">Activos</p>
        <p class="text-3xl font-semibold text-emerald-600">{{ $stats['activos'] }}</p>
      </x-ui.card>
      <x-ui.card class="bg-white border border-neutral-200">
        <p class="text-sm text-neutral-500">Inactivos</p>
        <p class="text-3xl font-semibold text-amber-600">{{ $stats['inactivos'] }}</p>
      </x-ui.card>
    </div>

    <x-ui.card class="p-0 overflow-hidden">
      <form method="GET" class="border-b border-neutral-200 p-4 flex flex-col md:flex-row gap-3 md:items-end">
        <div class="flex-1">
          <label for="q" class="text-xs uppercase tracking-wide text-neutral-500">Buscar</label>
          <input type="text" id="q" name="q" value="{{ $search }}"
                 placeholder="Nombre, documento o correo"
                 class="form-control mt-1" />
        </div>
        <div class="flex gap-2">
          <x-ui.button type="submit" variant="primary">Filtrar</x-ui.button>
          <x-ui.button :href="route('medico.pacientes.index')" variant="ghost">Limpiar</x-ui.button>
        </div>
      </form>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200 text-sm">
          <thead class="bg-neutral-50">
            <tr>
              <th class="px-4 py-3 text-left font-medium uppercase text-xs text-neutral-500">Paciente</th>
              <th class="px-4 py-3 text-left font-medium uppercase text-xs text-neutral-500">Documento</th>
              <th class="px-4 py-3 text-left font-medium uppercase text-xs text-neutral-500">Última cita</th>
              <th class="px-4 py-3 text-left font-medium uppercase text-xs text-neutral-500">Estado</th>
              <th class="px-4 py-3 text-left font-medium uppercase text-xs text-neutral-500">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-neutral-100">
            @forelse ($patients as $patient)
              @php
                $ultima = $latestAppointments[$patient->id_usuario] ?? null;
              @endphp
              <tr class="hover:bg-neutral-50 transition">
                <td class="px-4 py-4">
                  <p class="font-semibold text-neutral-900">{{ $patient->nombres }} {{ $patient->apellidos }}</p>
                  <p class="text-xs text-neutral-500">{{ $patient->correo_electronico }}</p>
                </td>
                <td class="px-4 py-4 text-neutral-700">{{ $patient->numero_documento }}</td>
                <td class="px-4 py-4 text-neutral-700">
                  {{ $ultima ? \Carbon\Carbon::parse($ultima)->translatedFormat('d M Y • H:i') : '—' }}
                </td>
                <td class="px-4 py-4">
                  @if ($patient->estado === 'activo')
                    <x-ui.badge variant="success">Activo</x-ui.badge>
                  @else
                    <x-ui.badge variant="neutral">{{ ucfirst($patient->estado) }}</x-ui.badge>
                  @endif
                </td>
                <td class="px-4 py-4">
                  <x-ui.button variant="ghost" size="sm" :href="route('medico.pacientes.show', $patient->id_usuario)">
                    Ver
                  </x-ui.button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-10 text-center text-neutral-500">
                  Todavía no tienes pacientes asociados a tus atenciones.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </x-ui.card>
  </div>
@endsection
