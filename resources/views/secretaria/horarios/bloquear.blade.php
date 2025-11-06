@extends('layouts.secretaria')
@section('title', 'Bloquear horario — Secretaría')

@section('secretary-content')
<div class="space-y-6 max-w-4xl">
  <header class="space-y-2">
    <x-ui.badge variant="info" class="uppercase tracking-wide">Horarios — Bloquear</x-ui.badge>
    <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Bloquear horario de un médico</h1>
    <p class="text-sm text-neutral-600">
      Selecciona al profesional, define la fecha y el rango horario que quedará bloqueado en la agenda.
    </p>
  </header>

  @php
    $timeSlots = [];
    foreach (range(7, 20) as $hour) {
      foreach ([0, 30] as $minute) {
        $timeSlots[] = sprintf('%02d:%02d', $hour, $minute);
      }
    }
  @endphp

  <x-ui.card class="p-6">
    @if (session('status'))
      <x-ui.alert variant="success" class="mb-4">
        {{ session('status') }}
      </x-ui.alert>
    @endif

    @if ($errors->any())
      <x-ui.alert variant="warning" class="mb-4">
        <ul class="space-y-1 list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </x-ui.alert>
    @endif

    <form method="POST" action="{{ route('secretaria.horarios.bloquear.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @csrf
      <x-form.select name="medico_id" label="Médico" required>
        <option value="">-- Seleccionar --</option>
        @foreach ($medicos as $medico)
          <option value="{{ $medico->id_usuario }}" @selected(old('medico_id') == $medico->id_usuario)>
            {{ $medico->nombres }} {{ $medico->apellidos }}
          </option>
        @endforeach
      </x-form.select>

      <x-form.input
        type="date"
        name="fecha"
        label="Fecha"
        required
        :min="now()->toDateString()"
        value="{{ old('fecha') }}"
      />

      <x-form.select name="hora_desde" label="Desde" required>
        <option value="">-- Seleccionar --</option>
        @foreach ($timeSlots as $slot)
          <option value="{{ $slot }}" @selected(old('hora_desde') === $slot)>
            {{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('h:i A') }}
          </option>
        @endforeach
      </x-form.select>

      <x-form.select name="hora_hasta" label="Hasta" required>
        <option value="">-- Seleccionar --</option>
        @foreach ($timeSlots as $slot)
          <option value="{{ $slot }}" @selected(old('hora_hasta') === $slot)>
            {{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('h:i A') }}
          </option>
        @endforeach
      </x-form.select>

      <x-form.input name="motivo" label="Motivo (opcional)" class="md:col-span-2" value="{{ old('motivo') }}" />

      <div class="md:col-span-2 flex justify-end">
        <x-ui.button variant="primary" size="md" class="rounded-full px-6">
          Bloquear horario
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>

  @if ($blocks->isNotEmpty())
    <x-ui.card class="p-0 overflow-hidden">
      <div class="p-4 border-b border-neutral-200">
        <h2 class="text-base font-semibold text-neutral-900">Bloqueos recientes</h2>
        <p class="text-sm text-neutral-600">Últimos horarios bloqueados por el equipo.</p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-100 text-neutral-700">
            <tr>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Médico</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Fecha</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Desde</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Hasta</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Motivo</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-200 bg-white">
            @foreach ($blocks as $block)
              <tr>
                <td class="px-4 py-3">{{ $block->medico?->nombres }} {{ $block->medico?->apellidos }}</td>
                <td class="px-4 py-3">{{ $block->fecha->translatedFormat('d \\d\\e F Y') }}</td>
                <td class="px-4 py-3">{{ optional($block->hora_desde)->format('h:i A') }}</td>
                <td class="px-4 py-3">{{ optional($block->hora_hasta)->format('h:i A') }}</td>
                <td class="px-4 py-3">{{ $block->motivo ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </x-ui.card>
  @endif
</div>
@endsection
