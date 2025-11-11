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

    @if ($errors->any())
      <x-ui.alert variant="warning">
        {{ $errors->first() }}
      </x-ui.alert>
    @endif

    <x-ui.card class="p-0 relative">
      <form method="POST" action="{{ route('secretaria.citas.cancelar.confirm', $patient->id_usuario) }}" id="sec-cancelarForm">
        @csrf

        <div class="overflow-x-auto">
          @if ($appointments->isEmpty())
            <div class="p-6 text-sm text-neutral-600">
              No hay citas programadas para cancelar.
            </div>
          @else
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
                        value="{{ $a->id_cita }}"
                        class="form-radio"
                        required
                      >
                    </td>
                    <td class="px-3 py-2">{{ $a->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F') }}</td>
                    <td class="px-3 py-2">{{ $a->fecha_hora_inicio->format('h:i A') }}</td>
                    <td class="px-3 py-2">{{ $a->medico?->nombres }} {{ $a->medico?->apellidos }}</td>
                    <td class="px-3 py-2">{{ $a->servicio?->nombre }}</td>
                    <td class="px-3 py-2">
                      <x-appointment.status-badge :estado="$a->estado" />
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>

        @if ($appointments->isNotEmpty())
          <div class="p-4 border-t border-neutral-200 space-y-4">
            <x-form.textarea name="motivo" label="Motivo de la cancelación (opcional)" rows="3">{{ old('motivo') }}</x-form.textarea>

            <x-ui.button type="button" variant="primary" size="lg" block class="rounded-full" id="sec-abrirModal">
              Cancelar cita
            </x-ui.button>
          </div>
        @endif

        <div id="sec-modalConfirmacion" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
          <div class="bg-white rounded-[var(--radius)] shadow-lg w-[90%] max-w-md p-6">
            <h2 class="text-lg font-semibold text-neutral-900 mb-2">¿Deseas cancelar esta cita?</h2>
            <p class="text-sm text-neutral-600 mb-4">
              Esta acción marcará la cita como cancelada y notificará al paciente.
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

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const modal  = document.getElementById('sec-modalConfirmacion');
        const abrir  = document.getElementById('sec-abrirModal');
        const cerrar = document.getElementById('sec-cerrarModal');
        const form   = document.getElementById('sec-cancelarForm');

        if (!modal || !abrir || !cerrar || !form) return;

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
