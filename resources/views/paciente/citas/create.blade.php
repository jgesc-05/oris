{{-- resources/views/paciente/citas/create.blade.php --}}
@extends('layouts.paciente')

@section('title', 'Agendar cita — Paciente')

@section('patient-content')
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Agendar cita</h1>

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
        $selectedSpecialty = old('id_tipos_especialidad');
        $serviceOptions = $selectedSpecialty
            ? $services->where('id_tipos_especialidad', $selectedSpecialty)
            : collect();
    @endphp

    <x-ui.card class="max-w-5xl">
        <form id="agendarForm" method="POST" action="{{ route('paciente.citas.store') }}"
            class="grid grid-cols-1 md:grid-cols-2 gap-4" data-availability-url="{{ $availabilityUrl }}"
            data-initial-doctor="{{ old('id_usuario_medico') }}" data-initial-date="{{ old('fecha') }}"
            data-initial-time="{{ old('hora') }}">
            @csrf

            {{-- Especialidad --}}
            <x-form.select name="id_tipos_especialidad" label="Especialidad"  id="select-especialidad">
                @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id_tipos_especialidad }}" @selected($selectedSpecialty == $specialty->id_tipos_especialidad)>
                        {{ $specialty->nombre }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Servicio --}}
            <x-form.select name="id_servicio" label="Servicio"  id="select-servicio" disabled>
                <option value="">-- Seleccionar --</option>
                @foreach ($serviceOptions as $service)
                    <option value="{{ $service->id_servicio }}" @selected(old('id_servicio') == $service->id_servicio)>
                        {{ $service->nombre }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Médico --}}
            <x-form.select name="id_usuario_medico" label="Médico"  class="md:col-span-2" id="select-medico"
                disabled>
                <option value="">-- Seleccionar --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id_usuario }}" @selected(old('id_usuario_medico') == $doctor->id_usuario)>
                        {{ $doctor->nombres }} {{ $doctor->apellidos }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Fecha --}}
            <x-form.select name="fecha" label="Fecha" id="select-fecha" disabled>
                <option value="">Selecciona un médico</option>
            </x-form.select>

            {{-- Hora --}}
            <x-form.select name="hora" label="Hora"  id="select-hora" disabled>
                <option value="">Selecciona una fecha</option>
            </x-form.select>

            {{-- Notas --}}
            <x-form.textarea name="notas" label="Notas para el médico (opcional)"
                class="md:col-span-2">{{ old('notas') }}</x-form.textarea>

            <div class="md:col-span-2 pt-2">
                <x-ui.button variant="primary" size="lg" block class="rounded-full">
                    Agendar cita
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('[Agendar] Init script');
                const services = @json($servicesPayload);
                const doctors = @json($doctorsPayload ?? []);
                console.log('[Agendar] Servicios cargados:', services);
                console.log('[Agendar] Doctores cargados:', doctors);

                const form = document.getElementById('agendarForm');
                const specialtySelect = document.getElementById('id_tipos_especialidad');
                const serviceSelect = document.getElementById('id_servicio');
                const doctorSelect = document.getElementById('id_usuario_medico');
                const fechaSelect = document.getElementById('fecha');
                const horaSelect = document.getElementById('hora');

                const availabilityUrl = form.dataset.availabilityUrl;
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
                    const specialtyId = Number(specialtySelect.value);
                    console.log('[Agendar] populateServices -> specialtyId:', specialtyId);
                    const filtered = services.filter(service => service.specialty_id === specialtyId);
                    console.log('[Agendar] Servicios filtrados:', filtered);

                    resetSelect(serviceSelect, '-- Seleccionar --', true);
                    resetSelect(doctorSelect, '-- Seleccionar --', true);
                    resetSelect(fechaSelect, 'Selecciona un médico', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!specialtyId) {
                        console.log('[Agendar] Sin especialidad, servicios permanecen deshabilitados');
                        return;
                    }

                    // Poblar servicios
                    filtered.forEach(service => {
                        const option = document.createElement('option');
                        option.value = service.id;
                        option.textContent = service.name;
                        serviceSelect.appendChild(option);
                    });

                    // Habilitar select de servicios si hay opciones
                    if (filtered.length > 0) {
                        serviceSelect.disabled = false;
                    }

                    // Poblar doctores de la especialidad
                    populateDoctors(specialtyId);
                };

                const populateDoctors = (specialtyId) => {
                    console.log('[Agendar] populateDoctors -> specialtyId:', specialtyId);
                    const filtered = doctors.filter(doctor => doctor.specialty_id === specialtyId);
                    console.log('[Agendar] Doctores filtrados:', filtered);

                    resetSelect(doctorSelect, '-- Seleccionar --', true);

                    filtered.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = `${doctor.nombres} ${doctor.apellidos}`;
                        doctorSelect.appendChild(option);
                    });

                    // Habilitar select de doctores si hay opciones
                    if (filtered.length > 0) {
                        doctorSelect.disabled = false;
                    }
                };

                const handleServiceChange = () => {
                    console.log('[Agendar] Cambio servicio, valor actual:', serviceSelect.value);
                    // El servicio es informativo, no afecta la disponibilidad
                    // Los horarios dependen solo del médico
                };

                const populateDates = () => {
                    console.log('[Agendar] populateDates con slots:', slots);
                    resetSelect(fechaSelect, slots.length ? '-- Seleccionar --' : 'Sin horarios disponibles', slots
                        .length === 0);
                    resetSelect(horaSelect, 'Selecciona una fecha');

                    slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.date;
                        option.textContent = slot.label;
                        fechaSelect.appendChild(option);
                    });

                    if (slots.length && initialDate && slots.some(slot => slot.date === initialDate)) {
                        fechaSelect.value = initialDate;
                        populateHours(initialDate);
                    }
                };

                const populateHours = (date) => {
                    console.log('[Agendar] populateHours para fecha:', date);
                    resetSelect(horaSelect, 'Selecciona una fecha');

                    const slot = slots.find(item => item.date === date);
                    console.log('[Agendar] Slot encontrado:', slot);
                    if (!slot) {
                        return;
                    }

                    slot.times.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time.value;
                        option.textContent = time.label;
                        horaSelect.appendChild(option);
                    });

                    horaSelect.disabled = slot.times.length === 0;

                    if (initialTime && slot.times.some(time => time.value === initialTime)) {
                        horaSelect.value = initialTime;
                    }

                    validateFutureSelection();
                };

                const fetchAvailability = async (doctorId) => {
                    console.log('[Agendar] fetchAvailability para médico:', doctorId);
                    resetSelect(fechaSelect, 'Cargando...', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!doctorId) {
                        console.log('[Agendar] Médico no seleccionado, slots vacíos');
                        slots = [];
                        resetSelect(fechaSelect, 'Selecciona un médico', true);
                        return;
                    }

                    try {
                        const url = new URL(availabilityUrl, window.location.origin);
                        url.searchParams.append('id_usuario_medico', doctorId);
                        console.log('[Agendar] Solicitando disponibilidad a:', url.toString());
                        const response = await fetch(url.toString());
                        const data = await response.json();
                        console.log('[Agendar] Disponibilidad recibida:', data);
                        slots = sanitizeSlots(Array.isArray(data.slots) ? data.slots : []);
                        console.log('[Agendar] Slots sanitizados:', slots);
                        populateDates();
                    } catch (error) {
                        console.error('[Agendar] Error al cargar disponibilidad:', error);
                        slots = [];
                        resetSelect(fechaSelect, 'Error al cargar fechas', true);
                    }
                };

                // Event Listeners
                specialtySelect.addEventListener('change', () => {
                    console.log('[Agendar] Evento change especialidad');
                    populateServices();
                });

                serviceSelect.addEventListener('change', () => {
                    console.log('[Agendar] Evento change servicio');
                    handleServiceChange();
                });

                doctorSelect.addEventListener('change', (event) => {
                    console.log('[Agendar] Evento change médico:', event.target.value);
                    fetchAvailability(event.target.value);
                });

                fechaSelect.addEventListener('change', (event) => {
                    console.log('[Agendar] Evento change fecha:', event.target.value);
                    populateHours(event.target.value);
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
                if (specialtySelect.value) {
                    console.log('[Agendar] Inicialización con especialidad preseleccionada:', specialtySelect.value);
                    populateServices();

                    if (initialDoctor) {
                        console.log('[Agendar] Inicialización con doctor preseleccionado:', initialDoctor);
                        fetchAvailability(initialDoctor).then(() => {
                            if (initialDate) {
                                console.log('[Agendar] Inicialización con fecha preseleccionada:', initialDate);
                                populateHours(initialDate);
                            }
                        });
                    }
                }

                console.log('[Agendar] Script listo para interacción');
            });
        </script>
    @endpush
@endsection
