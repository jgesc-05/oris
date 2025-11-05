@extends('layouts.paciente')

@section('title', 'Mis citas — Paciente')

@section('patient-content')
  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Mis citas</h1>

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
        @foreach($appointments as $a)
          <tr>
            <td class="px-3 py-2">{{ $a['fecha'] }}</td>
            <td class="px-3 py-2">{{ $a['hora'] }}</td>
            <td class="px-3 py-2">{{ $a['doctor'] }}</td>
            <td class="px-3 py-2">{{ $a['servicio'] }}</td>
            <td class="px-3 py-2">{{ $a['estado'] }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </x-ui.card>
@endsection
