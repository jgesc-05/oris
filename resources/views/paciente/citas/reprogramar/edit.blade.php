@extends('layouts.paciente')

@section('title', 'Reprogramar cita — Paciente')

@section('patient-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Reprogramar cita</h1>

  <x-ui.card class="max-w-5xl">
    <form method="POST" action="{{ route('paciente.citas.reprogramar.update', $cita['id']) }}" class="space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Especialidad --}}
        <x-form.select name="especialidad" label="Tipo de servicio" required>
          @foreach($especialidades as $e)
            <option value="{{ $e }}" @selected(old('especialidad',$cita['especialidad']) === $e)>{{ $e }}</option>
          @endforeach
        </x-form.select>

        {{-- Fecha --}}
        <x-form.input
          type="date"
          name="fecha"
          label="Fecha"
          :value="old('fecha', $cita['fecha'])"
          required
        />

        {{-- Servicio específico --}}
        <x-form.select name="servicio" label="Servicio específico" required>
          @foreach($servicios as $s)
            <option value="{{ $s }}" @selected(old('servicio',$cita['servicio']) === $s)>{{ $s }}</option>
          @endforeach
        </x-form.select>

        {{-- Hora --}}
        <x-form.select name="hora" label="Hora" required>
          @foreach($horas as $h)
            <option value="{{ $h }}" @selected(old('hora',$cita['hora']) === $h)>{{ \Carbon\Carbon::createFromFormat('H:i',$h)->format('g:i A') }}</option>
          @endforeach
        </x-form.select>

        {{-- Médico --}}
        <x-form.select name="medico" label="Odontólogo" class="md:col-span-2" required>
          @foreach($medicos as $m)
            <option value="{{ $m }}" @selected(old('medico',$cita['medico']) === $m)>{{ $m }}</option>
          @endforeach
        </x-form.select>
      </div>

      <div class="pt-2">
        <x-ui.button variant="primary" size="lg" block class="rounded-full">
          Reprogramar cita
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@endsection
