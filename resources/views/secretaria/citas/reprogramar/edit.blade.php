{{-- resources/views/secretaria/citas/reprogramar.blade.php --}}
@extends('layouts.secretaria')

@section('title', 'Reprogramar cita — Secretaría')

@section('secretary-content')
    @php
        $selectedSpecialty = old('id_tipos_especialidad', $appointment->servicio?->id_tipos_especialidad);
        $selectedService = old('id_servicio', $appointment->id_servicio);
        $selectedDoctor = old('id_usuario_medico', $appointment->id_usuario_medico);
        $selectedDate = old('fecha', optional($appointment->fecha_hora_inicio)->toDateString());
        $selectedTime = old('hora', optional($appointment->fecha_hora_inicio)->format('H:i'));

        $serviceOptions = $selectedSpecialty
            ? $services->where('id_tipos_especialidad', $selectedSpecialty)
            : collect();
    @endphp

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

        @if ($errors->any())
            <x-ui.alert variant="warning" class="mb-4">
                {{ $errors->first() }}
            </x-ui.alert>
        @endif

        {{-- Resumen actual --}}
        <x-ui.card class="p-4 space-y-3 bg-neutral-50 border border-neutral-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <span class="block text-xs uppercase tracking-wide text-neutral-500">Servicio actual</span>
                    <span class="text-sm font-medium text-neutral-900">{{ $appointment->servicio?->nombre ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs uppercase tracking-wide text-neutral-500">Médico asignado</span>
                    <span class="text-sm font-medium text-neutral-900">
                        {{ $appointment->medico?->nombres }} {{ $appointment->medico?->apellidos }}
                    </span>
                </div>
                @php $fh = $appointment->fecha_hora_inicio; @endphp
                <div>
                    <span class="block text-xs uppercase tracking-wide text-neutral-500">Fecha actual</span>
                    <span class="text-sm font-medium text-neutral-900">
                        {{ $fh ? $fh->copy()->locale('es')->translatedFormat('d \\d\\e F') : '—' }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs uppercase tracking-wide text-neutral-500">Hora actual</span>
                    <span class="text-sm font-medium text-neutral-900">{{ $fh ? $fh->format('H:i') : '—' }}</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Formulario --}}
        <x-ui.card class="p-6">
            <form id="secReprogramarForm" method="POST"
                action="{{ route('secretaria.citas.reprogramar.update', [$patient->id_usuario, $appointment->id_cita]) }}"
                class="grid grid-cols-1 md:grid-cols-2 gap-4" data-availability-url="{{ $availabilityUrl }}"
                data-appointment-id="{{ $appointment->id_cita }}" data-initial-specialty="{{ $selectedSpecialty }}"
                data-initial-service="{{ $selectedService }}" data-initial-doctor="{{ $selectedDoctor }}"
                data-initial-date="{{ $selectedDate }}" data-initial-time="{{ $selectedTime }}">
                @csrf
                @method('PUT')

                {{-- Especialidad --}}
                <x-form.select id="select-especialidad" name="id_tipos_especialidad" label="Especialidad" required>
                    @foreach ($specialties as $specialty)
                        <option value="{{ $specialty->id_tipos_especialidad }}" @selected($selectedSpecialty == $specialty->id_tipos_especialidad)>
                            {{ $specialty->nombre }}
                        </option>
                    @endforeach
                </x-form.select>

                {{-- Servicio --}}
                <x-form.select id="select-servicio" name="id_servicio" label="Servicio" required :disabled="!$selectedSpecialty">
                    <option value="">-- Seleccionar --</option>
                    @foreach ($serviceOptions as $service)
                        <option value="{{ $service->id_servicio }}" @selected($selectedService == $service->id_servicio)>{{ $service->nombre }}
                        </option>
                    @endforeach
                </x-form.select>

                {{-- Médico --}}
                <x-form.select id="select-medico" name="id_usuario_medico" label="Médico" required class="md:col-span-2"
                    :disabled="!$selectedSpecialty">
                    <option value="">-- Seleccionar --</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id_usuario }}" @selected($selectedDoctor == $doctor->id_usuario)>{{ $doctor->nombres }}
                            {{ $doctor->apellidos }}</option>
                    @endforeach
                </x-form.select>

                {{-- Fecha --}}
                <x-form.select id="select-fecha" name="fecha" label="Fecha" required :disabled="!$selectedDoctor">
                    <option value="">{{ $selectedDoctor ? 'Cargando…' : 'Selecciona un médico' }}</option>
                </x-form.select>

                {{-- Hora --}}
                <x-form.select id="select-hora" name="hora" label="Hora" required :disabled="!$selectedDate">
                    <option value="">{{ $selectedDate ? 'Cargando…' : 'Selecciona una fecha' }}</option>
                </x-form.select>

                {{-- Notas --}}
                <x-form.textarea name="notas" label="Notas (opcional)"
                    class="md:col-span-2">{{ old('notas', $appointment->notas) }}</x-form.textarea>

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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Datos (del backend)
                const services = @json($servicesPayload); // [{id, name, specialty_id}]
                const doctors = @json($doctorsPayload ?? []); // [{id, nombres, apellidos, specialty_id}]

                // Elementos
                const form = document.getElementById('secReprogramarForm');
                if (!form) return;

                const specialtySelect = document.getElementById('select-especialidad');
                const serviceSelect = document.getElementById('select-servicio');
                const doctorSelect = document.getElementById('select-medico');
                const fechaSelect = document.getElementById('select-fecha');
                const horaSelect = document.getElementById('select-hora');

                // Dataset inicial
                const availabilityUrl = form.dataset.availabilityUrl;
                const appointmentId = form.dataset.appointmentId;
                let initialSpecialty = form.dataset.initialSpecialty || '';
                let initialService = form.dataset.initialService || '';
                let initialDoctor = form.dataset.initialDoctor || '';
                let initialDate = form.dataset.initialDate || '';
                let initialTime = form.dataset.initialTime || '';

                let slots = []; // [{date, label, times:[{value,label}]}]

                // Utils
                const resetSelect = (select, placeholder, disable = true) => {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    select.value = '';
                    select.disabled = !!disable;
                };

                const enable = (el) => {
                    el.disabled = false;
                };
                const disable = (el) => {
                    el.disabled = true;
                };

                // Normaliza cualquier formato de payload del backend
                const normalizeSlots = (raw) => {
                    if (!Array.isArray(raw)) return [];
                    return raw.map(s => {
                        // Admite {date,label,times:[{value,label}]} o {fecha,texto,horas:[{hora,etiqueta}]}
                        const date = s.date ?? s.fecha ?? null;
                        const label = s.label ?? s.texto ?? date ?? '';
                        let times = s.times ?? s.horas ?? [];
                        times = Array.isArray(times) ? times.map(t => ({
                            value: t.value ?? t.hora ?? t.time ?? '',
                            label: t.label ?? t.etiqueta ?? t.text ?? (t.value ?? t.hora ?? '')
                        })) : [];
                        return (date ? {
                            date,
                            label: label || date,
                            times
                        } : null);
                    }).filter(Boolean);
                };

                const populateServices = () => {
                    const specialtyId = String(specialtySelect.value || '');

                    resetSelect(serviceSelect, '-- Seleccionar --', true);
                    resetSelect(doctorSelect, '-- Seleccionar --', true);
                    resetSelect(fechaSelect, 'Selecciona un médico', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!specialtyId) return;

                    const filtered = services.filter(s => String(s.specialty_id) === specialtyId);
                    for (const s of filtered) {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = s.name;
                        serviceSelect.appendChild(opt);
                    }
                    serviceSelect.disabled = filtered.length === 0;

                    if (filtered.length && initialService && filtered.some(s => String(s.id) === String(
                            initialService))) {
                        serviceSelect.value = String(initialService);
                    }

                    populateDoctors(specialtyId);
                };

                const populateDoctors = (specialtyId) => {
                    resetSelect(doctorSelect, '-- Seleccionar --', true);

                    const filtered = doctors.filter(d => String(d.specialty_id) === String(specialtyId));
                    for (const d of filtered) {
                        const opt = document.createElement('option');
                        opt.value = d.id;
                        opt.textContent = `${d.nombres} ${d.apellidos}`;
                        doctorSelect.appendChild(opt);
                    }
                    doctorSelect.disabled = filtered.length === 0;

                    if (filtered.length && initialDoctor && filtered.some(d => String(d.id) === String(
                            initialDoctor))) {
                        doctorSelect.value = String(initialDoctor);
                    }
                };

                const populateDates = () => {
                    // Si hay slots, habilita fecha; si no, muestra sin disponibilidad
                    resetSelect(fechaSelect, slots.length ? '-- Seleccionar --' : 'Sin horarios disponibles', slots
                        .length === 0);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    for (const s of slots) {
                        const opt = document.createElement('option');
                        opt.value = s.date;
                        opt.textContent = s.label || s.date;
                        fechaSelect.appendChild(opt);
                    }

                    if (slots.length && initialDate && slots.some(s => String(s.date) === String(initialDate))) {
                        fechaSelect.value = String(initialDate);
                        populateHours(initialDate);
                    }
                };

                const populateHours = (date) => {
                    resetSelect(horaSelect, 'Selecciona una fecha', true);
                    const slot = slots.find(s => String(s.date) === String(date));
                    if (!slot) return;

                    for (const t of (slot.times || [])) {
                        const opt = document.createElement('option');
                        opt.value = t.value;
                        opt.textContent = t.label || t.value;
                        horaSelect.appendChild(opt);
                    }
                    horaSelect.disabled = (slot.times || []).length === 0;

                    if (initialTime && (slot.times || []).some(t => String(t.value) === String(initialTime))) {
                        horaSelect.value = String(initialTime);
                    }
                };

                const fetchAvailability = async (doctorId) => {
                    resetSelect(fechaSelect, doctorId ? 'Cargando…' : 'Selecciona un médico', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!doctorId) {
                        slots = [];
                        return;
                    }

                    try {
                        const url = new URL(availabilityUrl, window.location.origin);
                        url.searchParams.set('id_usuario_medico', String(doctorId));
                        if (appointmentId) url.searchParams.set('cita_id', String(appointmentId));

                        const res = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        const json = await res.json();

                        // Normaliza con tolerancia
                        const incoming = Array.isArray(json?.slots) ? json.slots :
                            Array.isArray(json) ? json :
                            Array.isArray(json?.data) ? json.data :
                            [];
                        slots = normalizeSlots(incoming);

                        populateDates();

                        // Habilita fecha si hay opciones
                        if (slots.length) enable(fechaSelect);
                        else resetSelect(fechaSelect, 'Sin horarios disponibles', true);

                    } catch (e) {
                        console.error('[Secretaría] Error disponibilidad:', e);
                        resetSelect(fechaSelect, 'No se pudo cargar la disponibilidad', true);
                        resetSelect(horaSelect, 'Selecciona una fecha', true);
                        slots = [];
                    }
                };

                // Eventos
                specialtySelect.addEventListener('change', () => {
                    initialService = '';
                    initialDoctor = '';
                    initialDate = '';
                    initialTime = '';
                    populateServices();
                });

                serviceSelect.addEventListener('change', () => {
                    // El servicio no afecta la disponibilidad, pero puedes enganchar lógica si aplica
                });

                doctorSelect.addEventListener('change', (e) => {
                    initialDate = '';
                    initialTime = '';
                    fetchAvailability(e.target.value);
                });

                fechaSelect.addEventListener('change', (e) => {
                    initialTime = '';
                    populateHours(e.target.value);
                });

                // Inicialización con preselección
                (function init() {
                    // Forzamos specialty inicial (si viene del backend)
                    if (initialSpecialty) specialtySelect.value = String(initialSpecialty);
                    populateServices();

                    // Si ya hay médico inicial, cargamos disponibilidad y aplicamos fecha/hora iniciales
                    if (initialDoctor) {
                        // Si el doctor no quedó en el select (porque no pertenece a la especialidad), no llamamos a fetch
                        const hasDoctor = Array.from(doctorSelect.options).some(o => String(o.value) === String(
                            initialDoctor));
                        if (hasDoctor) {
                            doctorSelect.value = String(initialDoctor);
                            fetchAvailability(initialDoctor).then(() => {
                                if (initialDate) {
                                    const hasDate = Array.from(fechaSelect.options).some(o => String(o
                                        .value) === String(initialDate));
                                    if (hasDate) {
                                        fechaSelect.value = String(initialDate);
                                        populateHours(initialDate);
                                        if (initialTime) {
                                            const hasTime = Array.from(horaSelect.options).some(o => String(
                                                o.value) === String(initialTime));
                                            if (hasTime) horaSelect.value = String(initialTime);
                                        }
                                    }
                                }
                            });
                        } else {
                            // Sin doctor válido, dejamos la UI en estado seleccionable
                            resetSelect(fechaSelect, 'Selecciona un médico', true);
                            resetSelect(horaSelect, 'Selecciona una fecha', true);
                        }
                    }
                })();
            });
        </script>
    @endpush
@endsection
