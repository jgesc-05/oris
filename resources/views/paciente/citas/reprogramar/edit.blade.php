{{-- resources/views/paciente/citas/reprogramar.blade.php --}}
@extends('layouts.paciente')

@section('title', 'Reprogramar cita — Paciente')

@section('patient-content')
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Reprogramar cita</h1>

    @if ($errors->any())
        <x-ui.alert variant="warning" class="mb-4">
            {{ $errors->first() }}
        </x-ui.alert>
    @endif

    @if (session('status'))
        <x-ui.alert variant="success" class="mb-4">
            {{ session('status') }}
        </x-ui.alert>
    @endif

    @php
        // Valores base (prioriza old() y cae al valor de la cita)
        $selectedSpecialty = old('id_tipos_especialidad', $appointment->servicio?->id_tipos_especialidad);
        $selectedService = old('id_servicio', $appointment->id_servicio);
        $selectedDoctor = old('id_usuario_medico', $appointment->id_usuario_medico);
        $selectedDate = old('fecha', optional($appointment->fecha_hora_inicio)->toDateString());
        $selectedTime = old('hora', optional($appointment->fecha_hora_inicio)->format('H:i'));

        $serviceOptions = $selectedSpecialty
            ? $services->where('id_tipos_especialidad', $selectedSpecialty)
            : collect();
    @endphp

    <x-ui.card class="max-w-5xl">
        <form id="reprogramarForm" method="POST"
            action="{{ route('paciente.citas.reprogramar.update', $appointment->id_cita) }}"
            class="grid grid-cols-1 md:grid-cols-2 gap-4" data-availability-url="{{ $availabilityUrl }}"
            data-appointment-id="{{ $appointment->id_cita }}" data-initial-service="{{ $selectedService }}"
            data-initial-doctor="{{ $selectedDoctor }}" data-initial-date="{{ $selectedDate }}"
            data-initial-time="{{ $selectedTime }}">
            @csrf
            @method('PUT')

            {{-- Especialidad --}}
            <x-form.select name="id_tipos_especialidad" label="Especialidad" >
                @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id_tipos_especialidad }}" @selected($selectedSpecialty == $specialty->id_tipos_especialidad)>
                        {{ $specialty->nombre }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Servicio --}}
            <x-form.select name="id_servicio" label="Servicio"  :disabled="!$selectedSpecialty">
                <option value="">-- Seleccionar --</option>
                @foreach ($serviceOptions as $service)
                    <option value="{{ $service->id_servicio }}" @selected($selectedService == $service->id_servicio)>
                        {{ $service->nombre }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Médico --}}
            <x-form.select name="id_usuario_medico" label="Médico" class="md:col-span-2" :disabled="!$selectedSpecialty">
                <option value="">-- Seleccionar --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id_usuario }}" @selected($selectedDoctor == $doctor->id_usuario)>
                        {{ $doctor->nombres }} {{ $doctor->apellidos }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Fecha --}}
            <x-form.select name="fecha" label="Fecha" :disabled="!$selectedDoctor">
                <option value="">{{ $selectedDoctor ? 'Cargando…' : 'Selecciona un médico' }}</option>
            </x-form.select>

            {{-- Hora --}}
            <x-form.select name="hora" label="Hora"  :disabled="!$selectedDate">
                <option value="">{{ $selectedDate ? 'Cargando…' : 'Selecciona una fecha' }}</option>
            </x-form.select>

            {{-- Notas --}}
            <x-form.textarea name="notas" label="Notas (opcional)"
                class="md:col-span-2">{{ old('notas', $appointment->notas) }}</x-form.textarea>

            <div class="md:col-span-2 pt-2">
                <x-ui.button variant="primary" size="lg" block class="rounded-full">
                    Reprogramar cita
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('[Reprogramar] Init');

                // Backend payloads (mismo shape que en "agendar")
                const services = @json($servicesPayload); // [{ id, name, specialty_id }, ...]
                const doctors = @json($doctorsPayload ?? []); // [{ id, nombres, apellidos, specialty_id }, ...]

                // Selecciona por "name" para evitar desajustes de id
                const form = document.getElementById('reprogramarForm');
                const specialtySelect = document.querySelector('[name="id_tipos_especialidad"]');
                const serviceSelect = document.querySelector('[name="id_servicio"]');
                const doctorSelect = document.querySelector('[name="id_usuario_medico"]');
                const fechaSelect = document.querySelector('[name="fecha"]');
                const horaSelect = document.querySelector('[name="hora"]');

                const availabilityUrl = form.dataset.availabilityUrl;
                const appointmentId = form.dataset.appointmentId;

                const initialService = form.dataset.initialService; // NUEVO
                const initialDoctor = form.dataset.initialDoctor;
                const initialDate = form.dataset.initialDate;
                const initialTime = form.dataset.initialTime;

                let slots = [];

                const createDateFromParts = (date, time = '00:00') => {
                    if (!date) return null;
                    const isoString = `${date}T${time}:00`;
                    const candidate = new Date(isoString);
                    return Number.isNaN(candidate.getTime()) ? null : candidate;
                };

                const sanitizeSlots = (rawSlots = []) => {
                    const now = new Date();
                    const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());

                    return (rawSlots || [])
                        .map(slot => {
                            if (!slot || !slot.date) return null;

                            const slotDate = createDateFromParts(slot.date);
                            if (!slotDate || slotDate < todayStart) {
                                return null;
                            }

                            const filteredTimes = (slot.times || []).filter(time => {
                                if (!time || !time.value) return false;
                                const slotDateTime = createDateFromParts(slot.date, time.value);
                                return slotDateTime && slotDateTime >= now;
                            });

                            if (!filteredTimes.length) {
                                return null;
                            }

                            return {
                                ...slot,
                                times: filteredTimes,
                            };
                        })
                        .filter(Boolean);
                };

                const validateFutureSelection = () => {
                    horaSelect.setCustomValidity('');
                    const selectedDate = fechaSelect.value;
                    const selectedTime = horaSelect.value;

                    if (!selectedDate || !selectedTime) {
                        return true;
                    }

                    const selectedDateTime = createDateFromParts(selectedDate, selectedTime);
                    if (!selectedDateTime) {
                        return true;
                    }

                    if (selectedDateTime < new Date()) {
                        const message = 'Selecciona una fecha y hora futuras.';
                        horaSelect.setCustomValidity(message);
                        horaSelect.reportValidity();
                        return false;
                    }

                    return true;
                };

                const resetSelect = (select, placeholder, disable = true) => {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    select.value = '';
                    select.disabled = disable;
                };

                const populateServices = () => {
                    const specialtyId = String(specialtySelect.value || '');
                    const filtered = services.filter(s => String(s.specialty_id) === specialtyId);

                    resetSelect(serviceSelect, '-- Seleccionar --', true);
                    resetSelect(doctorSelect, '-- Seleccionar --', true);
                    resetSelect(fechaSelect, 'Selecciona un médico', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!specialtyId) return;

                    filtered.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = String(s.id);
                        opt.textContent = s.name;
                        serviceSelect.appendChild(opt);
                    });

                    if (filtered.length) {
                        serviceSelect.disabled = false;

                        // Preselección del servicio (todo en string)
                        if (initialService && filtered.some(s => String(s.id) === String(initialService))) {
                            serviceSelect.value = String(initialService);
                        }
                    }

                    populateDoctors(specialtyId);
                };

                const populateDoctors = (specialtyId) => {
                    const filtered = doctors.filter(d => String(d.specialty_id) === String(specialtyId));
                    resetSelect(doctorSelect, '-- Seleccionar --', true);

                    filtered.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = String(d.id);
                        opt.textContent = `${d.nombres} ${d.apellidos}`;
                        doctorSelect.appendChild(opt);
                    });

                    if (filtered.length) {
                        doctorSelect.disabled = false;

                        // Preselección médico robusta (string-string)
                        if (initialDoctor && filtered.some(doc => String(doc.id) === String(initialDoctor))) {
                            doctorSelect.value = String(initialDoctor);
                        }
                    }
                };

                const populateDates = () => {
                    resetSelect(fechaSelect, slots.length ? '-- Seleccionar --' : 'Sin horarios disponibles', slots
                        .length === 0);
                    resetSelect(horaSelect, 'Selecciona una fecha');

                    slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.date; // string
                        option.textContent = slot.label;
                        fechaSelect.appendChild(option);
                    });

                    if (slots.length && initialDate && slots.some(s => String(s.date) === String(initialDate))) {
                        fechaSelect.value = String(initialDate);
                        populateHours(initialDate);
                    }
                };

                const populateHours = (date) => {
                    resetSelect(horaSelect, 'Selecciona una fecha');
                    const slot = slots.find(s => String(s.date) === String(date));
                    if (!slot) return;

                    slot.times.forEach(time => {
                        const option = document.createElement('option');
                        option.value = String(time.value);
                        option.textContent = time.label;
                        horaSelect.appendChild(option);
                    });

                    horaSelect.disabled = slot.times.length === 0;

                    if (initialTime && slot.times.some(t => String(t.value) === String(initialTime))) {
                        horaSelect.value = String(initialTime);
                    }

                    validateFutureSelection();
                };

                const fetchAvailability = async (doctorId) => {
                    resetSelect(fechaSelect, 'Cargando...', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!doctorId) {
                        slots = [];
                        resetSelect(fechaSelect, 'Selecciona un médico', true);
                        return;
                    }

                    try {
                        const url = new URL(availabilityUrl, window.location.origin);
                        url.searchParams.append('id_usuario_medico', String(doctorId));
                        // Para reprogramar: el backend debe excluir la franja de la cita actual
                        if (appointmentId) url.searchParams.append('cita_id', String(appointmentId));

                        console.log('[Reprogramar] Fetch disponibilidad:', url.toString());
                        const response = await fetch(url.toString());
                        const data = await response.json();
                        slots = sanitizeSlots(Array.isArray(data.slots) ? data.slots : []);
                        console.log('[Reprogramar] Slots sanitizados:', slots);
                        populateDates();
                    } catch (e) {
                        console.error('[Reprogramar] Error disponibilidad:', e);
                        slots = [];
                        resetSelect(fechaSelect, 'Error al cargar fechas', true);
                    }
                };

                // Listeners
                specialtySelect.addEventListener('change', () => {
                    populateServices();
                });

                serviceSelect.addEventListener('change', () => {
                    // El servicio no altera disponibilidad; se mantiene por consistencia del flujo
                });

                doctorSelect.addEventListener('change', (e) => {
                    fetchAvailability(e.target.value);
                });

                fechaSelect.addEventListener('change', (e) => {
                    populateHours(e.target.value);
                    validateFutureSelection();
                });

                horaSelect.addEventListener('change', () => {
                    validateFutureSelection();
                });

                form.addEventListener('submit', (event) => {
                    if (!validateFutureSelection()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });

                // Inicialización
                if (specialtySelect && specialtySelect.value) {
                    populateServices();

                    if (initialDoctor) {
                        fetchAvailability(initialDoctor).then(() => {
                            if (initialDate) populateHours(initialDate);
                        });
                    }
                }

                console.log('[Reprogramar] Ready');
            });
        </script>
    @endpush
@endsection
