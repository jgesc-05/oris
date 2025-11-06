@extends('layouts.secretaria')

@section('title', 'Reprogramar cita — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-5xl">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">citas — reprogramar</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Reprogramar cita de {{ $patient->nombres }} {{ $patient->apellidos }}
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Ajusta la fecha, hora o profesional de la cita seleccionada.
      </p>
    </header>

    <x-ui.card class="p-6 space-y-5">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-neutral-50 border border-neutral-200 rounded-[var(--radius)] p-4">
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Servicio actual</span>
          <span class="text-sm font-medium text-neutral-900">{{ $appointment['servicio'] }}</span>
        </div>
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Médico asignado</span>
          <span class="text-sm font-medium text-neutral-900">{{ $appointment['medico'] }}</span>
        </div>
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Fecha actual</span>
          <span class="text-sm font-medium text-neutral-900">{{ \Carbon\Carbon::parse($appointment['fecha'])->translatedFormat('d \\d\\e F') }}</span>
        </div>
        <div>
          <span class="block text-xs uppercase tracking-wide text-neutral-500">Hora actual</span>
          <span class="text-sm font-medium text-neutral-900">{{ $appointment['hora_humana'] }}</span>
        </div>
      </div>

      <form method="POST" action="{{ route('secretaria.citas.reprogramar.update', [$patient->id_usuario, $appointment['id']]) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        @method('PUT')

        <x-form.select name="especialidad" label="Tipo de servicio" required>
          @foreach($especialidades as $especialidad)
            <option value="{{ $especialidad }}" @selected(old('especialidad', $appointment['servicio']) === $especialidad)>
              {{ $especialidad }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.select name="servicio" label="Servicio específico" required>
          @foreach($servicios as $servicio)
            <option value="{{ $servicio }}" @selected(old('servicio', $appointment['servicio']) === $servicio)>
              {{ $servicio }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.input
          type="date"
          name="fecha"
          label="Nueva fecha"
          :value="old('fecha', $appointment['fecha'])"
          :min="now()->toDateString()"
          required
        />

        <x-form.select name="hora" label="Nueva hora" required>
          @foreach($horas as $hora)
            <option value="{{ $hora }}" @selected(old('hora', $appointment['hora']) === $hora)>
              {{ \Carbon\Carbon::createFromFormat('H:i', $hora)->format('g:i A') }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.select name="medico" label="Profesional" class="md:col-span-2" required>
          @foreach($medicos as $medico)
            <option value="{{ $medico }}" @selected(old('medico', $appointment['medico']) === $medico)>
              {{ $medico }}
            </option>
          @endforeach
        </x-form.select>

        <div class="md:col-span-2 flex justify-end gap-3 pt-2">
          <x-ui.button :href="route('secretaria.citas.reprogramar.seleccion', $patient->id_usuario)" variant="secondary" size="md" class="rounded-full">
            Volver
          </x-ui.button>
          <x-ui.button variant="primary" size="lg" class="rounded-full px-8">
            Confirmar cambio
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>
@endsection
