@extends('layouts.secretaria')

@section('title', 'Cancelar cita — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-5xl">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">citas — cancelar</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Cancelar cita para {{ $patient->nombres }} {{ $patient->apellidos }}
      </h1>
      <p class="text-sm text-neutral-600">
        Selecciona la cita que deseas cancelar y confirma la acción.
      </p>
    </header>

    <x-ui.card class="p-0 relative">
      <form method="POST" action="{{ route('secretaria.citas.cancelar.confirm', $patient->id_usuario) }}" id="sec-cancelarForm">
        @csrf

        {{-- Tabla de citas --}}
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-100 text-neutral-700">
            <tr>
              <th class="px-3 py-2 w-10"></th>
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
                <td class="px-3 py-2 text-center">
                  <input
                    type="radio"
                    name="cita_id"
                    value="{{ data_get($a, 'id', data_get($a, 'cita_id')) }}"
                    class="form-radio"
                  >
                </td>
                <td class="px-3 py-2">{{ data_get($a, 'fecha', '—') }}</td>
                <td class="px-3 py-2">{{ data_get($a, 'hora', data_get($a, 'hora_humana', '—')) }}</td>
                <td class="px-3 py-2">{{ data_get($a, 'doctor', data_get($a, 'medico', '—')) }}</td>
                <td class="px-3 py-2">{{ data_get($a, 'servicio', '—') }}</td>
                <td class="px-3 py-2">
                  @php
                    $estado = data_get($a, 'estado', 'Pendiente');
                  @endphp
                  <x-ui.badge variant="{{ in_array($estado, ['Confirmada','Programada']) ? 'success' : 'neutral' }}">
                    {{ $estado }}
                  </x-ui.badge>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Botón principal --}}
      <div class="p-4">
        <x-ui.button type="button" variant="primary" size="lg" block class="rounded-full" id="sec-abrirModal">
          Cancelar cita
        </x-ui.button>
      </div>

      {{-- Modal de confirmación (misma UX que Paciente) --}}
      <div id="sec-modalConfirmacion" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-[var(--radius)] shadow-lg w-[90%] max-w-md p-6">
          <h2 class="text-lg font-semibold text-neutral-900 mb-2">¿Deseas cancelar esta cita?</h2>
          <p class="text-sm text-neutral-600 mb-4">
            Esta acción eliminará la cita seleccionada y no podrás recuperarla. ¿Estás seguro de continuar?
          </p>

          <div class="flex justify-end gap-3">
            <x-ui.button type="button" variant="secondary" size="sm" id="sec-cerrarModal">
              No, mantener
            </x-ui.button>

            <x-ui.button type="submit" variant="warning" size="sm">
              Sí, cancelar
            </x-ui.button>
          </div>
        </div>
      </div>
    </form>
  </x-ui.card>

  {{-- Script del modal (ids propios para no chocar con otras vistas) --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal  = document.getElementById('sec-modalConfirmacion');
      const abrir  = document.getElementById('sec-abrirModal');
      const cerrar = document.getElementById('sec-cerrarModal');
      const form   = document.getElementById('sec-cancelarForm');

      abrir.addEventListener('click', () => {
        const seleccionada = form.querySelector('input[name="cita_id"]:checked');
        if (!seleccionada) {
          alert('Por favor selecciona una cita para cancelar.');
          return;
        }
        modal.classList.remove('hidden');
      });

      cerrar.addEventListener('click', () => {
        modal.classList.add('hidden');
      });
    });
  </script>
</div>
@endsection
