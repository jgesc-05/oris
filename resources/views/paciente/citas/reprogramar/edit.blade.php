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
    <form
      id="reprogramarForm"
      method="POST"
      action="{{ route('paciente.citas.reprogramar.update', $appointment->id_cita) }}"
      class="space-y-4"
      data-availability-url="{{ $availabilityUrl }}"
      data-appointment-id="{{ $appointment->id_cita }}"
      data-initial-service="{{ old('id_servicio', $appointment->id_servicio) }}"
      data-initial-doctor="{{ old('id_usuario_medico', $appointment->id_usuario_medico) }}"
      data-initial-date="{{ old('fecha', $appointment->fecha_hora_inicio->toDateString()) }}"
      data-initial-time="{{ old('hora', $appointment->fecha_hora_inicio->format('H:i')) }}"
    >
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Especialidad --}}
        <x-form.select name="id_tipos_especialidad" label="Especialidad" required id="select-especialidad">
          <option value="">-- Seleccionar --</option>
          @foreach ($specialties as $specialty)
            <option value="{{ $specialty->id_tipos_especialidad }}" @selected($selectedSpecialty == $specialty->id_tipos_especialidad)>
              {{ $specialty->nombre }}
            </option>
          @endforeach
        </x-form.select>

        {{-- Servicio --}}
        <x-form.select
          name="id_servicio"
          label="Servicio"
          required
          id="select-servicio"
          {{ $selectedSpecialty ? '' : 'disabled' }}
        >
          <option value="">-- Seleccionar --</option>
          @foreach ($serviceOptions as $service)
            <option value="{{ $service->id_servicio }}"
              @selected(old('id_servicio', $appointment->id_servicio) == $service->id_servicio)>
              {{ $service->nombre }}
            </option>
          @endforeach
        </x-form.select>

        {{-- Fecha --}}
        <x-form.select
          name="fecha"
          label="Fecha"
          required
          id="select-fecha"
          disabled
        >
          <option value="">Selecciona un médico</option>
        </x-form.select>

        {{-- Hora --}}
        <x-form.select name="hora" label="Hora" required id="select-hora" disabled>
          <option value="">Selecciona una fecha</option>
        </x-form.select>

        {{-- Médico --}}
        <x-form.select
          name="id_usuario_medico"
          label="Médico"
          class="md:col-span-2"
          required
          id="select-medico"
          {{ ($selectedSpecialty && old('id_servicio', $appointment->id_servicio)) ? '' : 'disabled' }}
        >
          <option value="">-- Seleccionar --</option>
          @foreach($doctors as $doctor)
            <option value="{{ $doctor->id_usuario }}"
              @selected(old('id_usuario_medico', $appointment->id_usuario_medico) == $doctor->id_usuario)>
              {{ $doctor->nombres }} {{ $doctor->apellidos }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.textarea
          name="notas"
          label="Notas (opcional)"
          class="md:col-span-2"
        >{{ old('notas', $appointment->notas) }}</x-form.textarea>
      </div>

      <div class="pt-2">
        <x-ui.button variant="primary" size="lg" block class="rounded-full">
          Reprogramar cita
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const services = @json($servicesPayload);

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
        select.innerHTML = `<option value=\"\">${placeholder}</option>`;
        select.value = '';
        select.disabled = disable;
      };

      const handleServiceChange = () => {
        if (serviceSelect.value) {
          doctorSelect.disabled = false;
        } else {
          doctorSelect.value = '';
          doctorSelect.disabled = true;
          resetSelect(fechaSelect, 'Selecciona un médico');
          resetSelect(horaSelect, 'Selecciona una fecha');
        }
      };

      const populateServices = () => {
        const specialtyId = Number(specialtySelect.value);
        const filtered = services.filter(service => service.specialty_id === specialtyId);

        resetSelect(serviceSelect, '-- Seleccionar --', !specialtyId);

        if (!specialtyId) {
          return;
        }

        filtered.forEach(service => {
          const option = document.createElement('option');
          option.value = service.id;
          option.textContent = service.name;
          serviceSelect.appendChild(option);
        });

        serviceSelect.disabled = filtered.length === 0;
        if (filtered.length && preselectedService) {
          serviceSelect.value = preselectedService;
          preselectedService = null;
        }

        handleServiceChange();
      };

      const populateDates = () => {
        resetSelect(fechaSelect, slots.length ? '-- Seleccionar --' : 'Sin horarios disponibles', slots.length === 0);
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
        resetSelect(horaSelect, 'Selecciona una fecha');

        const slot = slots.find(item => item.date === date);
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
        resetSelect(fechaSelect, 'Selecciona un médico');
        resetSelect(horaSelect, 'Selecciona una fecha');

        if (!doctorId) {
          slots = [];
          return;
        }

        try {
          const url = new URL(availabilityUrl, window.location.origin);
          url.searchParams.append('id_usuario_medico', doctorId);
          url.searchParams.append('cita_id', appointmentId);
          const response = await fetch(url.toString());
          const data = await response.json();
          slots = data.slots || [];
          populateDates();
        } catch (error) {
          console.error(error);
          slots = [];
        }
      };

      specialtySelect.addEventListener('change', () => {
        populateServices();
        resetSelect(fechaSelect, 'Selecciona un médico');
        resetSelect(horaSelect, 'Selecciona una fecha');
      });

      serviceSelect.addEventListener('change', handleServiceChange);
      doctorSelect.addEventListener('change', (event) => {
        fetchAvailability(event.target.value);
      });

      fechaSelect.addEventListener('change', (event) => {
        populateHours(event.target.value);
      });

      if (specialtySelect.value) {
        populateServices();
      } else {
        handleServiceChange();
      }

      if (initialDoctor) {
        fetchAvailability(initialDoctor).then(() => {
          if (initialDate) {
            populateHours(initialDate);
          }
        });
      }
    });
  </script>
@endpush
@endsection
