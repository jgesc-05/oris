@extends('layouts.paciente')

@section('title', 'Reprogramar cita — Paciente')

@section('patient-content')
    <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Reprogramar cita</h1>

    @if ($errors->any())
        <x-ui.alert variant="warning" class="mb-4">
            {{ $errors->first() }}
        </x-ui.alert>
    @endif

    @php
        $selectedSpecialty = old('id_tipos_especialidad', $appointment->servicio?->id_tipos_especialidad);
        $serviceOptions = $selectedSpecialty
            ? $services->where('id_tipos_especialidad', $selectedSpecialty)
            : collect();
    @endphp

    <x-ui.card class="max-w-5xl">
        <form id="reprogramarForm" method="POST"
            action="{{ route('paciente.citas.reprogramar.update', $appointment->id_cita) }}"
            class="grid grid-cols-1 md:grid-cols-2 gap-4" data-availability-url="{{ $availabilityUrl }}"
            data-appointment-id="{{ $appointment->id_cita }}"
            data-initial-service="{{ old('id_servicio', $appointment->id_servicio) }}"
            data-initial-doctor="{{ old('id_usuario_medico', $appointment->id_usuario_medico) }}"
            data-initial-date="{{ old('fecha', $appointment->fecha_hora_inicio->toDateString()) }}"
            data-initial-time="{{ old('hora', $appointment->fecha_hora_inicio->format('H:i')) }}">
            @csrf
            @method('PUT')

            {{-- Especialidad --}}
            <x-form.select name="id_tipos_especialidad" label="Especialidad" required id="select-especialidad">
                @foreach ($specialties as $specialty)
                    <option value="{{ $specialty->id_tipos_especialidad }}" @selected($selectedSpecialty == $specialty->id_tipos_especialidad)>
                        {{ $specialty->nombre }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Servicio --}}
            <x-form.select name="id_servicio" label="Servicio" required id="select-servicio" disabled>
                @foreach ($serviceOptions as $service)
                    <option value="{{ $service->id_servicio }}" @selected(old('id_servicio', $appointment->id_servicio) == $service->id_servicio)>
                        {{ $service->nombre }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Médico --}}
            <x-form.select name="id_usuario_medico" label="Médico" class="md:col-span-2" required id="select-medico"
                disabled>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id_usuario }}" @selected(old('id_usuario_medico', $appointment->id_usuario_medico) == $doctor->id_usuario)>
                        {{ $doctor->nombres }} {{ $doctor->apellidos }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Fecha --}}
            <x-form.select name="fecha" label="Fecha" required id="select-fecha" disabled>
                <option value="">Selecciona un médico</option>
            </x-form.select>

            {{-- Hora --}}
            <x-form.select name="hora" label="Hora" required id="select-hora" disabled>
                <option value="">Selecciona una fecha</option>
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
                console.log('[Reprogramar] Script inicializado');
                const services = @json($servicesPayload);
                const doctors = @json($doctorsPayload ?? []);
                console.log('[Reprogramar] Servicios disponibles:', services);
                console.log('[Reprogramar] Doctores disponibles:', doctors);

                const form = document.getElementById('reprogramarForm');
                const specialtySelect = document.getElementById('select-especialidad');
                const serviceSelect = document.getElementById('select-servicio');
                const doctorSelect = document.getElementById('select-medico');
                const fechaSelect = document.getElementById('select-fecha');
                const horaSelect = document.getElementById('select-hora');

                const availabilityUrl = form.dataset.availabilityUrl;
                const appointmentId = form.dataset.appointmentId;
                let preselectedService = form.dataset.initialService;
                const initialDoctor = form.dataset.initialDoctor;
                const initialDate = form.dataset.initialDate;
                const initialTime = form.dataset.initialTime;

                let slots = [];

                const resetSelect = (select, placeholder, disable = true) => {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    select.value = '';
                    select.disabled = disable;
                };

                const populateServices = () => {
                    const specialtyId = Number(specialtySelect.value);
                    console.log('[Reprogramar] populateServices -> specialty:', specialtyId);
                    const filteredServices = services.filter(service => service.specialty_id === specialtyId);
                    console.log('[Reprogramar] Servicios filtrados:', filteredServices);

                    resetSelect(serviceSelect, '-- Seleccionar --', true);
                    resetSelect(doctorSelect, '-- Seleccionar --', true);
                    resetSelect(fechaSelect, 'Selecciona un médico', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!specialtyId) {
                        return;
                    }

                    filteredServices.forEach(service => {
                        const option = document.createElement('option');
                        option.value = service.id;
                        option.textContent = service.name;
                        serviceSelect.appendChild(option);
                    });

                    if (filteredServices.length) {
                        serviceSelect.disabled = false;
                    }

                    if (filteredServices.length && preselectedService) {
                        serviceSelect.value = preselectedService;
                        preselectedService = null;
                    }

                    populateDoctors(specialtyId);
                    handleServiceChange();
                };

                const populateDoctors = (specialtyId) => {
                    console.log('[Reprogramar] populateDoctors -> specialty:', specialtyId);
                    const filtered = doctors.filter(doctor => doctor.specialty_id === specialtyId);
                    console.log('[Reprogramar] Doctores filtrados:', filtered);

                    resetSelect(doctorSelect, '-- Seleccionar --', true);

                    filtered.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = `${doctor.nombres} ${doctor.apellidos}`;
                        doctorSelect.appendChild(option);
                    });

                    if (filtered.length) {
                        doctorSelect.disabled = false;
                    }

                    if (filtered.length && initialDoctor && filtered.some(doc => String(doc.id) === String(
                            initialDoctor))) {
                        doctorSelect.value = initialDoctor;
                    }
                };

                const handleServiceChange = () => {
                    console.log('[Reprogramar] handleServiceChange valor:', serviceSelect.value);
                    if (serviceSelect.value) {
                        doctorSelect.disabled = false;
                    } else {
                        doctorSelect.value = '';
                        doctorSelect.disabled = true;
                        resetSelect(fechaSelect, 'Selecciona un médico');
                        resetSelect(horaSelect, 'Selecciona una fecha');
                    }
                };

                const populateDates = () => {
                    console.log('[Reprogramar] populateDates con slots:', slots);
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
                    console.log('[Reprogramar] populateHours fecha:', date);
                    resetSelect(horaSelect, 'Selecciona una fecha');

                    const slot = slots.find(item => item.date === date);
                    console.log('[Reprogramar] Slot encontrado:', slot);
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
                };

                const fetchAvailability = async (doctorId) => {
                    console.log('[Reprogramar] fetchAvailability médico:', doctorId);
                    resetSelect(fechaSelect, 'Cargando...', true);
                    resetSelect(horaSelect, 'Selecciona una fecha', true);

                    if (!doctorId) {
                        console.log('[Reprogramar] Sin médico seleccionado');
                        slots = [];
                        resetSelect(fechaSelect, 'Selecciona un médico', true);
                        return;
                    }

                    try {
                        const url = new URL(availabilityUrl, window.location.origin);
                        url.searchParams.append('id_usuario_medico', doctorId);
                        if (appointmentId) {
                            url.searchParams.append('cita_id', appointmentId);
                        }
                        console.log('[Reprogramar] Consultando disponibilidad:', url.toString());
                        const response = await fetch(url.toString());
                        const data = await response.json();
                        console.log('[Reprogramar] Disponibilidad recibida:', data);
                        slots = data.slots || [];
                        populateDates();
                    } catch (error) {
                        console.error('[Reprogramar] Error al cargar disponibilidad:', error);
                        slots = [];
                        resetSelect(fechaSelect, 'Error al cargar fechas', true);
                    }
                };

                specialtySelect.addEventListener('change', () => {
                    console.log('[Reprogramar] Cambio especialidad');
                    populateServices();
                });

                serviceSelect.addEventListener('change', () => {
                    console.log('[Reprogramar] Cambio servicio');
                    handleServiceChange();
                });

                doctorSelect.addEventListener('change', (event) => {
                    console.log('[Reprogramar] Cambio médico:', event.target.value);
                    fetchAvailability(event.target.value);
                });

                fechaSelect.addEventListener('change', (event) => {
                    console.log('[Reprogramar] Cambio fecha:', event.target.value);
                    populateHours(event.target.value);
                });

                if (specialtySelect.value) {
                    console.log('[Reprogramar] Especialidad inicial:', specialtySelect.value);
                    populateServices();
                } else {
                    resetSelect(serviceSelect, '-- Seleccionar --', true);
                    resetSelect(doctorSelect, '-- Seleccionar --', true);
                }

                handleServiceChange();

                if (initialDoctor) {
                    console.log('[Reprogramar] Doctor inicial:', initialDoctor);
                    fetchAvailability(initialDoctor).then(() => {
                        if (initialDate) {
                            console.log('[Reprogramar] Fecha inicial:', initialDate);
                            populateHours(initialDate);
                        }
                    });
                }

                console.log('[Reprogramar] Script listo');
            });
        </script>
    @endpush
@endsection
