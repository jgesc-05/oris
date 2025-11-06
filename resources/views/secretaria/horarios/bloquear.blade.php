@extends('layouts.secretaria')
@section('title', 'Bloquear horario — Secretaría')

@section('secretary-content')
<x-ui.card class="max-w-3xl">
  <form method="POST" action="{{ route('secretaria.horarios.bloquear.store') }}" class="space-y-4">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <x-form.select name="medico_id" label="Médico" required>
        <option value="1">Dr. Ejemplo</option>
      </x-form.select>
      <x-form.input type="date" name="fecha" label="Fecha" required/>
      <x-form.input type="time" name="hora_desde" label="Desde" required/>
      <x-form.input type="time" name="hora_hasta" label="Hasta" required/>
      <x-form.input name="motivo" label="Motivo (opcional)" class="md:col-span-2"/>
    </div>
    <x-ui.button variant="primary" block class="rounded-full">Bloquear</x-ui.button>
  </form>
</x-ui.card>
@endsection
