@extends('layouts.paciente')

@section('title', 'Cancelar cita — Paciente')

@section('patient-content')
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Cancelar cita</h1>

    <x-ui.card class="max-w-5xl p-0 relative">
        <form method="POST" action="{{ route('paciente.citas.cancelar.submit') }}" id="cancelarForm">
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
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="px-3 py-2 text-center">
                                    <input type="radio" name="cita_id" value="{{ $appointment->id_cita }}"
                                        class="form-radio">
                                </td>
                                <td class="px-3 py-2">
                                    {{ $appointment->fecha_hora_inicio->locale('es')->translatedFormat('d \\d\\e F') }}
                                </td>
                                <td class="px-3 py-2">{{ $appointment->fecha_hora_inicio->format('h:i A') }}</td>
                                <td class="px-3 py-2">
                                    {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}
                                </td>
                                <td class="px-3 py-2">{{ $appointment->servicio?->nombre }}</td>
                                <td class="px-3 py-2">
                                    <x-ui.badge
                                        variant="{{ $appointment->estado === 'Confirmada' ? 'success' : 'neutral' }}">
                                        {{ $appointment->estado }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-neutral-600">
                                    No tienes citas próximas para cancelar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <br>

            @if (($appointments ?? collect())->isNotEmpty())
                <div class="px-4">
                    <x-form.textarea name="motivo" label="Motivo (opcional)">
                        {{ old('motivo') }}
                    </x-form.textarea>
                </div>
            @endif

            {{-- Botón principal --}}
            <div class="p-4">
                <x-ui.button
                    type="button"
                    variant="primary"
                    size="lg"
                    block
                    class="rounded-full"
                    id="abrirModal"
                    :disabled="($appointments ?? collect())->isEmpty()"
                >
                    Cancelar cita
                </x-ui.button>
            </div>

            {{-- Modal de confirmación --}}
            <div id="modalConfirmacion" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white rounded-[var(--radius)] shadow-lg w-[90%] max-w-md p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-2">¿Deseas cancelar esta cita?</h2>
                    <p class="text-sm text-neutral-600 mb-4">
                        Esta acción eliminará la cita seleccionada y no podrás recuperarla. ¿Estás seguro de continuar?
                    </p>

                    <div class="flex justify-end gap-3">
                        <x-ui.button type="button" variant="secondary" size="sm" id="cerrarModal">
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

    {{-- Script del modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalConfirmacion');
            const abrir = document.getElementById('abrirModal');
            const cerrar = document.getElementById('cerrarModal');
            const form = document.getElementById('cancelarForm');

            abrir?.addEventListener('click', () => {
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
@endsection
