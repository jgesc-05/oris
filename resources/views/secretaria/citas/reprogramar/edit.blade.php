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
        $appointmentStart = $appointment->fecha_hora_inicio;
        $currentDateLabel = $appointmentStart
            ? $appointmentStart->copy()->locale('es')->translatedFormat('d \\d\\e F')
            : null;
        $currentTimeLabel = $appointmentStart ? $appointmentStart->format('H:i') : null;

        $serviceOptions = $selectedSpecialty
            ? $services->where('id_tipos_especialidad', $selectedSpecialty)
            : collect();

        $initialSlotsCollection = collect($initialSlots ?? []);
        $initialDateOptions = $initialSlotsCollection
            ->map(fn($s) => ['value' => $s['date'] ?? null, 'label' => $s['label'] ?? ($s['date'] ?? '')])
            ->filter(fn($o) => $o['value']);

        $selectedSlot = $initialSlotsCollection->firstWhere('date', $selectedDate) ?? [];
        $initialTimeOptions = collect($selectedSlot['times'] ?? []);

        $shouldInjectCurrentDateOption =
            $selectedDate && !$initialDateOptions->contains(fn($o) => $o['value'] === $selectedDate);
        $shouldInjectCurrentTimeOption =
            $selectedTime && !$initialTimeOptions->contains(fn($t) => ($t['value'] ?? null) === $selectedTime);
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

        {{-- Resumen --}}
        <x-ui.card class="p-4 space-y-3 bg-neutral-50 border border-neutral-200">
            @php $fh = $appointment->fecha_hora_inicio; @endphp
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
                data-initial-date="{{ $selectedDate }}" data-initial-time="{{ $selectedTime }}"
                data-initial-date-label="{{ $currentDateLabel }}" data-initial-time-label="{{ $currentTimeLabel }}">
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
                        <option value="{{ $service->id_servicio }}" @selected($selectedService == $service->id_servicio)>
                            {{ $service->nombre }}
                        </option>
                    @endforeach
                </x-form.select>

                {{-- Médico (filtrado por especialidad) --}}
                <x-form.select id="select-medico" name="id_usuario_medico" label="Médico" required class="md:col-span-2"
                    :disabled="!$selectedSpecialty">
                    <option value="">-- Seleccionar --</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id_usuario }}" @selected($selectedDoctor == $doctor->id_usuario)>
                            {{ $doctor->nombres }} {{ $doctor->apellidos }}
                        </option>
                    @endforeach
                </x-form.select>

                {{-- Fecha --}}
                <x-form.select id="select-fecha" name="fecha" label="Fecha" required :disabled="!$selectedDoctor">
                    <option value="">
                        @if (!$selectedDoctor)
                            Selecciona un médico
                        @elseif($initialDateOptions->isEmpty())
                            Cargando…
                        @else
                            -- Seleccionar --
                        @endif
                    </option>
                    @if ($shouldInjectCurrentDateOption && $selectedDate)
                        <option value="{{ $selectedDate }}" selected>
                            Fecha agendada — {{ $currentDateLabel ?? $selectedDate }}
                        </option>
                    @endif
                    @foreach ($initialDateOptions as $opt)
                        <option value="{{ $opt['value'] }}" @selected($selectedDate === $opt['value'])>{{ $opt['label'] }}</option>
                    @endforeach
                </x-form.select>

                {{-- Hora --}}
                <x-form.select id="select-hora" name="hora" label="Hora" required :disabled="!$selectedDate">
                    <option value="">
                        @if (!$selectedDate)
                            Selecciona una fecha
                        @elseif($initialTimeOptions->isEmpty())
                            Cargando…
                        @else
                            -- Seleccionar --
                        @endif
                    </option>
                    @if ($shouldInjectCurrentTimeOption && $selectedTime)
                        <option value="{{ $selectedTime }}" selected>
                            Hora agendada — {{ $currentTimeLabel ?? $selectedTime }}
                        </option>
                    @endif
                    @foreach ($initialTimeOptions as $time)
                        <option value="{{ $time['value'] ?? '' }}" @selected(($time['value'] ?? '') === $selectedTime)">
                            {{ $time['label'] ?? ($time['value'] ?? '') }}
                        </option>
                    @endforeach
                </x-form.select>

                {{-- Notas --}}
                <x-form.textarea name="notas" label="Notas (opcional)" class="md:col-span-2">
                    {{ old('notas', $appointment->notas) }}
                </x-form.textarea>

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
            (() => {
                const services = @json($servicesPayload);
                const doctors = @json($doctorsPayload ?? []);
                const initialSlotsPayload = @json($initialSlots ?? []);

                const createDateFromParts = (date, time = '00:00') => {
                    if (!date) return null;
                    const d = new Date(`${date}T${time}:00`);
                    return Number.isNaN(d.getTime()) ? null : d;
                };

                const normalizeSlots = (raw) => {
                    if (!Array.isArray(raw)) return [];
                    return raw
                        .map(slot => {
                            const date = slot.date ?? slot.fecha ?? null;
                            const label = slot.label ?? slot.texto ?? date ?? '';
                            let times = slot.times ?? slot.horas ?? [];
                            times = Array.isArray(times) ?
                                times.map(t => ({
                                    value: t.value ?? t.hora ?? t.time ?? '',
                                    label: t.label ?? t.etiqueta ?? t.text ?? (t.value ?? t.hora ?? '')
                                })) : [];
                            return date ? {
                                date,
                                label: label || date,
                                times
                            } : null;
                        })
                        .filter(Boolean);
                };

                const sanitizeSlots = (rawSlots = []) => {
                    const now = new Date();
                    const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());

                    return (rawSlots || [])
                        .map(slot => {
                            if (!slot?.date) return null;
                            const slotDate = createDateFromParts(slot.date);
                            if (!slotDate || slotDate < todayStart) return null;

                            const filteredTimes = (slot.times || []).filter(time => {
                                if (!time?.value) return false;
                                const slotDateTime = createDateFromParts(slot.date, time.value);
                                return slotDateTime && slotDateTime >= now;
                            });

                            if (!filteredTimes.length) return null;
                            return {
                                ...slot,
                                times: filteredTimes
                            };
                        })
                        .filter(Boolean);
                };

                const init = () => {
                    const form = document.getElementById('secReprogramarForm');
                    if (!form) return;

                    const specialtySelect = document.getElementById('id_tipos_especialidad');
                    const serviceSelect = document.getElementById('id_servicio');
                    const doctorSelect = document.getElementById('id_usuario_medico');
                    const fechaSelect = document.getElementById('fecha');
                    const horaSelect = document.getElementById('hora');

                    const availabilityUrl = form.dataset.availabilityUrl;
                    const appointmentId = form.dataset.appointmentId;
                    let initialSpecialty = form.dataset.initialSpecialty || '';
                    let initialService = form.dataset.initialService || '';
                    let initialDoctor = form.dataset.initialDoctor || '';
                    let initialDate = form.dataset.initialDate || '';
                    let initialTime = form.dataset.initialTime || '';
                    const initialDateLabel = form.dataset.initialDateLabel || '';
                    const initialTimeLabel = form.dataset.initialTimeLabel || '';

                    let slots = [];
                    let hydratedFromInitial = false;

                    const resetSelect = (el, placeholder, disable = true) => {
                        el.innerHTML = `<option value="">${placeholder}</option>`;
                        el.value = '';
                        el.disabled = !!disable;
                    };

                    const addCurrentDateOptionIfMissing = () => {
                        if (!initialDate) return false;
                        const exists = Array.from(fechaSelect.options).some(o => String(o.value) === String(
                            initialDate));
                        if (exists) return false;
                        const opt = document.createElement('option');
                        opt.value = String(initialDate);
                        opt.textContent = initialDateLabel ?
                            `Fecha agendada — ${initialDateLabel}` :
                            `Fecha agendada — ${initialDate}`;
                        opt.dataset.fromAppointment = '1';
                        fechaSelect.appendChild(opt);
                        return true;
                    };

                    const addCurrentTimeOptionIfMissing = (date) => {
                        if (!initialTime || !initialDate || String(date) !== String(initialDate)) return false;
                        const exists = Array.from(horaSelect.options).some(o => String(o.value) === String(
                            initialTime));
                        if (exists) return false;
                        const opt = document.createElement('option');
                        opt.value = String(initialTime);
                        opt.textContent = initialTimeLabel ?
                            `Hora agendada — ${initialTimeLabel}` :
                            `Hora agendada — ${initialTime}`;
                        opt.dataset.fromAppointment = '1';
                        horaSelect.appendChild(opt);
                        return true;
                    };

                    const populateServices = () => {
                        const specialtyId = String(specialtySelect.value || '');

                        resetSelect(serviceSelect, '-- Seleccionar --', true);
                        resetSelect(doctorSelect, '-- Seleccionar --', true);
                        resetSelect(fechaSelect, 'Selecciona un médico', true);
                        resetSelect(horaSelect, 'Selecciona una fecha', true);

                        if (!specialtyId) return;

                        const filteredServices = services.filter(service => String(service.specialty_id) ===
                            specialtyId);
                        filteredServices.forEach(service => {
                            const option = document.createElement('option');
                            option.value = String(service.id);
                            option.textContent = service.name;
                            serviceSelect.appendChild(option);
                        });

                        serviceSelect.disabled = filteredServices.length === 0;

                        if (
                            filteredServices.length &&
                            initialService &&
                            filteredServices.some(service => String(service.id) === String(initialService))
                        ) {
                            serviceSelect.value = String(initialService);
                        }

                        populateDoctors(specialtyId);
                    };

                    const populateDoctors = (specialtyId) => {
                        resetSelect(doctorSelect, '-- Seleccionar --', true);
                        const filteredDoctors = doctors.filter(doctor => String(doctor.specialty_id) === String(
                            specialtyId));

                        filteredDoctors.forEach(doctor => {
                            const option = document.createElement('option');
                            option.value = String(doctor.id);
                            option.textContent = `${doctor.nombres} ${doctor.apellidos}`;
                            doctorSelect.appendChild(option);
                        });

                        doctorSelect.disabled = filteredDoctors.length === 0;

                        if (
                            filteredDoctors.length &&
                            initialDoctor &&
                            filteredDoctors.some(doc => String(doc.id) === String(initialDoctor))
                        ) {
                            doctorSelect.value = String(initialDoctor);
                        }
                    };

                    const populateDates = () => {
                        const hasSlots = slots.length > 0;
                        resetSelect(fechaSelect, hasSlots ? '-- Seleccionar --' : 'Sin horarios disponibles', !
                            hasSlots && !initialDate);
                        resetSelect(horaSelect, 'Selecciona una fecha', true);

                        slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot.date;
                            option.textContent = slot.label || slot.date;
                            fechaSelect.appendChild(option);
                        });

                        addCurrentDateOptionIfMissing();
                        fechaSelect.disabled = fechaSelect.options.length <= 1;

                        const hasInitialDate = initialDate &&
                            Array.from(fechaSelect.options).some(option => String(option.value) === String(
                                initialDate));

                        if (hasInitialDate) {
                            fechaSelect.value = String(initialDate);
                            populateHours(initialDate);
                        }
                    };

                    const populateHours = (date) => {
                        resetSelect(horaSelect, 'Selecciona una fecha', true);
                        const slot = slots.find(s => String(s.date) === String(date));

                        if (slot) {
                            (slot.times || []).forEach(time => {
                                const option = document.createElement('option');
                                option.value = String(time.value);
                                option.textContent = time.label || time.value;
                                horaSelect.appendChild(option);
                            });
                        }

                        addCurrentTimeOptionIfMissing(date);
                        horaSelect.disabled = horaSelect.options.length <= 1;

                        const hasInitialTime = initialTime &&
                            Array.from(horaSelect.options).some(option => String(option.value) === String(
                                initialTime));

                        if (hasInitialTime) {
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

                            const response = await fetch(url.toString(), {
                                headers: {
                                    Accept: 'application/json'
                                }
                            });
                            const payload = await response.json();

                            const payloadSlots = Array.isArray(payload?.slots) ?
                                payload.slots :
                                Array.isArray(payload) ?
                                payload :
                                Array.isArray(payload?.data) ?
                                payload.data : [];

                            slots = sanitizeSlots(normalizeSlots(payloadSlots));
                            populateDates();
                        } catch (error) {
                            console.error('[Secretaría] Error disponibilidad:', error);
                            slots = [];
                            resetSelect(fechaSelect, 'No se pudo cargar la disponibilidad', true);
                        }
                    };

                    const hydrateInitial = () => {
                        if (!Array.isArray(initialSlotsPayload) || !initialSlotsPayload.length) return false;
                        slots = sanitizeSlots(normalizeSlots(initialSlotsPayload));
                        if (!slots.length) return false;
                        populateDates();
                        if (initialDate) populateHours(initialDate);
                        return true;
                    };

                    specialtySelect.addEventListener('change', () => {
                        initialService = '';
                        initialDoctor = '';
                        initialDate = '';
                        initialTime = '';
                        populateServices();
                    });

                    doctorSelect.addEventListener('change', (event) => {
                        initialDate = '';
                        initialTime = '';
                        fetchAvailability(event.target.value);
                    });

                    fechaSelect.addEventListener('change', (event) => {
                        initialTime = '';
                        populateHours(event.target.value);
                    });

                    if (initialSpecialty) {
                        specialtySelect.value = String(initialSpecialty);
                    }
                    populateServices();

                    hydratedFromInitial = hydrateInitial();

                    if (!hydratedFromInitial && initialDoctor) {
                        const doctorExists = Array.from(doctorSelect.options).some(option => String(option.value) ===
                            String(initialDoctor));
                        if (doctorExists) {
                            doctorSelect.value = String(initialDoctor);
                            fetchAvailability(initialDoctor).then(() => {
                                if (!initialDate) return;
                                const hasDate = Array.from(fechaSelect.options).some(option => String(option
                                    .value) === String(initialDate));
                                if (!hasDate) return;
                                fechaSelect.value = String(initialDate);
                                populateHours(initialDate);
                                if (!initialTime) return;
                                const hasTime = Array.from(horaSelect.options).some(option => String(option
                                    .value) === String(initialTime));
                                if (hasTime) horaSelect.value = String(initialTime);
                            });
                        } else {
                            resetSelect(fechaSelect, 'Selecciona un médico', true);
                            resetSelect(horaSelect, 'Selecciona una fecha', true);
                        }
                    }
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
            })();
        </script>
    @endpush
@endsection
