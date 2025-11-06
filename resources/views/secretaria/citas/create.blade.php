@extends('layouts.secretaria')

@section('title', 'Agendar cita — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-5xl">
    @php
      $fechaNacimiento = $patient->fecha_nacimiento
        ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->format('Y-m-d')
        : '—';
    @endphp

    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">citas — agendar</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Agendar cita para {{ $patient->nombres }} {{ $patient->apellidos }}</h1>
      <p class="text-sm md:text-base text-neutral-600">
        Completa la información de la cita. Todos los campos son obligatorios.
      </p>
    </header>

    <x-ui.card class="p-6">
      <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
          <dt class="text-xs uppercase tracking-wide text-neutral-500">Documento</dt>
          <dd class="text-sm font-medium text-neutral-900">{{ $patient->numero_documento }}</dd>
        </div>
        <div>
          <dt class="text-xs uppercase tracking-wide text-neutral-500">Fecha de nacimiento</dt>
          <dd class="text-sm font-medium text-neutral-900">{{ $fechaNacimiento }}</dd>
        </div>
        <div>
          <dt class="text-xs uppercase tracking-wide text-neutral-500">Correo</dt>
          <dd class="text-sm font-medium text-neutral-900">{{ $patient->correo_electronico ?? '—' }}</dd>
        </div>
      </dl>

      <form method="POST" action="{{ route('secretaria.citas.create.store', $patient->id_usuario) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf

        <x-form.select name="especialidad" label="Tipo de servicio" required>
          <option value="">-- Seleccionar --</option>
          <option @selected(old('especialidad')==='Medicina general')>Medicina general</option>
          <option @selected(old('especialidad')==='Pediatría')>Pediatría</option>
          <option @selected(old('especialidad')==='Cardiología')>Cardiología</option>
        </x-form.select>

        <x-form.select name="servicio" label="Servicio específico" required>
          <option value="">-- Seleccionar --</option>
          <option @selected(old('servicio')==='Consulta general')>Consulta general</option>
          <option @selected(old('servicio')==='Chequeo preventivo')>Chequeo preventivo</option>
          <option @selected(old('servicio')==='Exámenes especializados')>Exámenes especializados</option>
        </x-form.select>

        <x-form.input
          name="fecha"
          label="Fecha"
          type="date"
          required
          :value="old('fecha')"
          :min="now()->toDateString()"
        />

        <x-form.select name="hora" label="Hora" required>
          <option value="">-- Seleccionar --</option>
          @foreach($timeSlots as $slot)
            <option value="{{ $slot }}" @selected(old('hora') === $slot)>
              {{ \Carbon\Carbon::createFromFormat('H:i', $slot)->format('h:i A') }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.select name="medico" label="Médico" class="md:col-span-2" required>
          <option value="">-- Seleccionar --</option>
          <option @selected(old('medico')==='Dr. Andrés Salazar')>Dr. Andrés Salazar</option>
          <option @selected(old('medico')==='Dra. Laura Hernández')>Dra. Laura Hernández</option>
          <option @selected(old('medico')==='Dra. Catalina Díaz')>Dra. Catalina Díaz</option>
        </x-form.select>

        <div class="md:col-span-2 pt-2 flex gap-3 justify-end">
          <x-ui.button :href="route('secretaria.citas.agendar.lookup')" variant="secondary" size="md" class="rounded-full">Volver</x-ui.button>
          <x-ui.button variant="primary" size="lg" class="rounded-full px-8">
            Confirmar agendamiento
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>
@endsection
