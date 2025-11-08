@extends('layouts.secretaria')

@section('title', 'Agendar cita — Secretaría')

@section('secretary-content')
  <div class="space-y-6 max-w-5xl">
    @php
      $fechaNacimiento = $patient->fecha_nacimiento
        ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->format('Y-m-d')
        : '—';
      $selectedSpecialty = old('id_tipos_especialidad');
      $serviceOptions = $selectedSpecialty
        ? $services->where('id_tipos_especialidad', $selectedSpecialty)
        : collect();
    @endphp

    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">citas — agendar</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Agendar cita para {{ $patient->nombres }} {{ $patient->apellidos }}
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        Completa la información de la cita. Todos los campos son obligatorios.
      </p>
    </header>

    @if ($errors->any())
      <x-ui.alert variant="warning">
        {{ $errors->first() }}
      </x-ui.alert>
    @endif

    <x-ui.card class="p-6">
      <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
          <dt class="text-xs uppercase tracking-wide text-neutral-500">Documento</dt>
          <dd class="text-sm font-medium text-neutral-900">{{ $patient->numero_documento }}</dd>
        </div>
        <div>
          <dt class="text-xs uppercase tracking-wide text-neutral-500">Fecha de nacimiento</dt>
          <dd class="text-sm font-medium text-neutral-900">{{ $fechaNacimiento }}</dd>
        </div>
        <div>
          <dt class="text-xs uppercase tracking-wide text-neutral-500">Correo</dt>
          <dd class="text-sm font-medium text-neutral-900">{{ $patient->correo_electronico ?? '—' }}</dd>
        </div>
      </dl>

      <form
        id="secAgendarForm"
        method="POST"
        action="{{ route('secretaria.citas.create.store', $patient->id_usuario) }}"
        class="grid grid-cols-1 md:grid-cols-2 gap-4"
        data-availability-url="{{ $availabilityUrl }}"
        data-initial-doctor="{{ old('id_usuario_medico') }}"
        data-initial-date="{{ old('fecha') }}"
        data-initial-time="{{ old('hora') }}"
      >
        @csrf

        <x-form.select name="id_tipos_especialidad" label="Especialidad" required>
          @foreach ($specialties as $specialty)
            <option value="{{ $specialty->id_tipos_especialidad }}" @selected($selectedSpecialty == $specialty->id_tipos_especialidad)>
              {{ $specialty->nombre }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.select name="id_servicio" label="Servicio" required :disabled="!$selectedSpecialty">
          <option value="">-- Seleccionar --</option>
          @foreach ($serviceOptions as $service)
            <option value="{{ $service->id_servicio }}" @selected(old('id_servicio') == $service->id_servicio)>
              {{ $service->nombre }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.select name="id_usuario_medico" label="Médico" class="md:col-span-2" required :disabled="!$selectedSpecialty">
          <option value="">-- Seleccionar --</option>
          @foreach ($doctors as $doctor)
            <option value="{{ $doctor->id_usuario }}" @selected(old('id_usuario_medico') == $doctor->id_usuario)>
              {{ $doctor->nombres }} {{ $doctor->apellidos }}
            </option>
          @endforeach
        </x-form.select>

        <x-form.select name="fecha" label="Fecha" required :disabled="!old('id_usuario_medico')">
          <option value="">Selecciona un médico</option>
        </x-form.select>

        <x-form.select name="hora" label="Hora" required :disabled="!old('fecha')">
          <option value="">Selecciona una fecha</option>
        </x-form.select>

        <x-form.textarea name="notas" label="Notas para el médico (opcional)" class="md:col-span-2">
          {{ old('notas') }}
        </x-form.textarea>

        <div class="md:col-span-2 pt-2 flex gap-3 justify-end">
          <x-ui.button :href="route('secretaria.citas.agendar.lookup')" variant="secondary" size="md" class="rounded-full">Volver</x-ui.button>
          <x-ui.button variant="primary" size="lg" class="rounded-full px-8">
            Confirmar agendamiento
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>

  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const services = @json($servicesPayload);
        const doctors = @json($doctorsPayload ?? []);

        const form = document.getElementById('secAgendarForm');
        if (!form) return;

        const specialtySelect = form.querySelector('[name="id_tipos_especialidad"]');
        const serviceSelect = form.querySelector('[name="id_servicio"]');
        const doctorSelect = form.querySelector('[name="id_usuario_medico"]');
        const fechaSelect = form.querySelector('[name="fecha"]');
        const horaSelect = form.querySelector('[name="hora"]');

        const availabilityUrl = form.dataset.availabilityUrl;
        const initialDoctor = form.dataset.initialDoctor;
        let initialServiceValue = @json(old('id_servicio'));
        const initialDate = form.dataset.initialDate;
        const initialTime = form.dataset.initialTime;

        let slots = [];

        const createDateFromParts = (date, time = '00:00') => {
          if (!date) return null;
          const iso = `${date}T${time}:00`;
          const parsed = new Date(iso);
          return Number.isNaN(parsed.getTime()) ? null : parsed;
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

              return { ...slot, times: filteredTimes };
            })
            .filter(Boolean);
        };

        const resetSelect = (select, placeholder, disable = true) => {
          select.innerHTML = `<option value="">${placeholder}</option>`;
          select.value = '';
          select.disabled = disable;
        };

        const populateServices = () => {
          const specialtyId = String(specialtySelect.value || '');
          const filtered = services.filter(service => String(service.specialty_id) === specialtyId);

          resetSelect(serviceSelect, '-- Seleccionar --', true);
          resetSelect(doctorSelect, '-- Seleccionar --', true);
          resetSelect(fechaSelect, 'Selecciona un médico', true);
          resetSelect(horaSelect, 'Selecciona una fecha', true);

          if (!specialtyId) return;

          filtered.forEach(service => {
            const option = document.createElement('option');
            option.value = service.id;
            option.textContent = service.name;
            serviceSelect.appendChild(option);
          });

          serviceSelect.disabled = filtered.length === 0;

          if (filtered.length && initialServiceValue && filtered.some(s => String(s.id) === String(initialServiceValue))) {
            serviceSelect.value = initialServiceValue;
            initialServiceValue = null;
          }

          populateDoctors(specialtyId);
        };

        const populateDoctors = (specialtyId) => {
          const filtered = doctors.filter(doctor => String(doctor.specialty_id) === String(specialtyId));

          resetSelect(doctorSelect, '-- Seleccionar --', filtered.length === 0);

          filtered.forEach(doctor => {
            const option = document.createElement('option');
            option.value = doctor.id;
            option.textContent = `${doctor.nombres} ${doctor.apellidos}`;
            doctorSelect.appendChild(option);
          });

          if (filtered.length && initialDoctor && filtered.some(doc => String(doc.id) === String(initialDoctor))) {
            doctorSelect.value = initialDoctor;
          }
        };

        const populateDates = () => {
          resetSelect(fechaSelect, slots.length ? '-- Seleccionar --' : 'Sin horarios disponibles', slots.length === 0);
          resetSelect(horaSelect, 'Selecciona una fecha', true);

          slots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.date;
            option.textContent = slot.label;
            fechaSelect.appendChild(option);
          });

          if (slots.length && initialDate && slots.some(s => String(s.date) === String(initialDate))) {
            fechaSelect.value = initialDate;
            populateHours(initialDate);
          }
        };

        const populateHours = (date) => {
          resetSelect(horaSelect, 'Selecciona una fecha', true);

          const slot = slots.find(s => String(s.date) === String(date));
          if (!slot) return;

          slot.times.forEach(time => {
            const option = document.createElement('option');
            option.value = time.value;
            option.textContent = time.label;
            horaSelect.appendChild(option);
          });

          horaSelect.disabled = slot.times.length === 0;

          if (initialTime && slot.times.some(t => String(t.value) === String(initialTime))) {
            horaSelect.value = initialTime;
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
            url.searchParams.append('id_usuario_medico', String(doctorId));

            const response = await fetch(url.toString());
            const data = await response.json();

            slots = sanitizeSlots(Array.isArray(data.slots) ? data.slots : []);
            populateDates();
          } catch (error) {
            console.error('[Secretaría] Error al cargar disponibilidad', error);
            slots = [];
            resetSelect(fechaSelect, 'No se pudo cargar la disponibilidad', true);
          }
        };

        specialtySelect?.addEventListener('change', populateServices);
        doctorSelect?.addEventListener('change', (event) => fetchAvailability(event.target.value));
        fechaSelect?.addEventListener('change', (event) => populateHours(event.target.value));

        // Inicialización
        if (specialtySelect?.value) {
          populateServices();

          if (initialDoctor) {
            fetchAvailability(initialDoctor).then(() => {
              if (initialDate) {
                populateHours(initialDate);
              }
            });
          }
        }
      });
    </script>
  @endpush
@endsection
