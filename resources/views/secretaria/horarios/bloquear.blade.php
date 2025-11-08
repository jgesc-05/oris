@extends('layouts.secretaria')
@section('title', 'Bloquear horario — Secretaría')

@section('secretary-content')
<div class="space-y-6 max-w-4xl">
  <header class="space-y-2">
    <x-ui.badge variant="info" class="uppercase tracking-wide">Horarios — Bloquear</x-ui.badge>
    <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Bloquear horario de un médico</h1>
    <p class="text-sm text-neutral-600">
      Selecciona al profesional, define la fecha y el rango horario que quedará bloqueado en la agenda.
    </p>
  </header>

  @php
    $oldFecha = old('fecha');
    $oldHoraDesde = old('hora_desde');
    $oldHoraHasta = old('hora_hasta');
  @endphp

  <x-ui.card class="p-6">
    @if (session('status'))
      <x-ui.alert variant="success" class="mb-4">
        {{ session('status') }}
      </x-ui.alert>
    @endif

    @if ($errors->any())
      <x-ui.alert variant="warning" class="mb-4">
        <ul class="space-y-1 list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </x-ui.alert>
    @endif

    <form
      id="secretaryBlockForm"
      method="POST"
      action="{{ route('secretaria.horarios.bloquear.store') }}"
      class="grid grid-cols-1 md:grid-cols-2 gap-4"
      data-availability-url="{{ $availabilityUrl }}"
      data-initial-doctor="{{ old('medico_id') }}"
      data-initial-date="{{ $oldFecha }}"
      data-initial-start="{{ $oldHoraDesde }}"
      data-initial-end="{{ $oldHoraHasta }}"
    >
      @csrf
      <x-form.select name="medico_id" label="Médico" required>
        <option value="">-- Seleccionar --</option>
        @foreach ($medicos as $medico)
          <option value="{{ $medico->id_usuario }}" @selected(old('medico_id') == $medico->id_usuario)>
            {{ $medico->nombres }} {{ $medico->apellidos }}
          </option>
        @endforeach
      </x-form.select>

      <x-form.select name="fecha" label="Fecha" required :disabled="!old('medico_id')">
        <option value="">{{ old('medico_id') ? 'Selecciona un médico' : 'Selecciona un médico' }}</option>
        @if ($oldFecha)
          <option value="{{ $oldFecha }}" selected>
            {{ \Carbon\Carbon::parse($oldFecha)->locale('es')->translatedFormat('l j \\d\\e F') }}
          </option>
        @endif
      </x-form.select>

      <x-form.select name="hora_desde" label="Desde" required :disabled="!$oldFecha">
        <option value="">{{ $oldFecha ? 'Selecciona una fecha' : 'Selecciona una fecha' }}</option>
        @if ($oldHoraDesde)
          <option value="{{ $oldHoraDesde }}" selected>
            {{ \Carbon\Carbon::createFromFormat('H:i', $oldHoraDesde)->format('h:i A') }}
          </option>
        @endif
      </x-form.select>

      <x-form.select name="hora_hasta" label="Hasta" required :disabled="!$oldHoraDesde">
        <option value="">{{ $oldHoraDesde ? 'Selecciona un inicio' : 'Selecciona un inicio' }}</option>
        @if ($oldHoraHasta)
          <option value="{{ $oldHoraHasta }}" selected>
            {{ \Carbon\Carbon::createFromFormat('H:i', $oldHoraHasta)->format('h:i A') }}
          </option>
        @endif
      </x-form.select>

      <x-form.input name="motivo" label="Motivo (opcional)" class="md:col-span-2" value="{{ old('motivo') }}" />

      <div class="md:col-span-2 flex justify-end">
        <x-ui.button variant="primary" size="md" class="rounded-full px-6">
          Bloquear horario
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>

  @if ($blocks->isNotEmpty())
    <x-ui.card class="p-0 overflow-hidden">
      <div class="p-4 border-b border-neutral-200">
        <h2 class="text-base font-semibold text-neutral-900">Bloqueos recientes</h2>
        <p class="text-sm text-neutral-600">Últimos horarios bloqueados por el equipo.</p>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-neutral-100 text-neutral-700">
            <tr>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Médico</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Fecha</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Desde</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Hasta</th>
              <th class="px-4 py-2 text-left uppercase text-xs font-medium">Motivo</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-200 bg-white">
            @foreach ($blocks as $block)
              <tr>
                <td class="px-4 py-3">{{ $block->medico?->nombres }} {{ $block->medico?->apellidos }}</td>
                <td class="px-4 py-3">{{ $block->fecha->translatedFormat('d \\d\\e F Y') }}</td>
                <td class="px-4 py-3">{{ optional($block->hora_desde)->format('h:i A') }}</td>
                <td class="px-4 py-3">{{ optional($block->hora_hasta)->format('h:i A') }}</td>
                <td class="px-4 py-3">{{ $block->motivo ?? '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </x-ui.card>
  @endif
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('secretaryBlockForm');
      if (!form) return;

      const doctorSelect = form.querySelector('[name="medico_id"]');
      const dateSelect = form.querySelector('[name="fecha"]');
      const startSelect = form.querySelector('[name="hora_desde"]');
      const endSelect = form.querySelector('[name="hora_hasta"]');

      const availabilityUrl = form.dataset.availabilityUrl;
      let initialDoctor = form.dataset.initialDoctor || '';
      let initialDate = form.dataset.initialDate || '';
      let initialStart = form.dataset.initialStart || '';
      let initialEnd = form.dataset.initialEnd || '';

      let slots = [];

      const formatTimeLabel = (time) => {
        if (!time) return '';
        const [hours, minutes] = time.split(':').map(Number);
        const suffix = hours >= 12 ? 'PM' : 'AM';
        const normalized = ((hours + 11) % 12) + 1;
        return `${String(normalized).padStart(2, '0')}:${String(minutes).padStart(2, '0')} ${suffix}`;
      };

      const createDateFromParts = (date, time = '00:00') => {
        if (!date) return null;
        const parsed = new Date(`${date}T${time}:00`);
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

      const minutesFromTime = (time) => {
        const [h, m] = time.split(':').map(Number);
        return h * 60 + m;
      };

      const timeFromMinutes = (minutes) => {
        const h = Math.floor(minutes / 60) % 24;
        const m = minutes % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
      };

      const populateDates = () => {
        resetSelect(dateSelect, 'Selecciona una fecha', slots.length === 0);
        resetSelect(startSelect, 'Selecciona una fecha');
        resetSelect(endSelect, 'Selecciona un inicio');

        if (!slots.length) {
          return;
        }

        dateSelect.disabled = false;
        slots.forEach(slot => {
          const option = document.createElement('option');
          option.value = slot.date;
          option.textContent = slot.label ?? slot.date;
          dateSelect.appendChild(option);
        });

        if (initialDate && slots.some(slot => slot.date === initialDate)) {
          dateSelect.value = initialDate;
          populateTimes(initialDate);
          initialDate = '';
        }
      };

      const populateTimes = (dateValue) => {
        resetSelect(startSelect, 'Selecciona una fecha');
        resetSelect(endSelect, 'Selecciona un inicio');

        const dateSlot = slots.find(slot => slot.date === dateValue);
        if (!dateSlot) {
          return;
        }

        startSelect.disabled = false;

        dateSlot.times.forEach(time => {
          const option = document.createElement('option');
          option.value = time.value;
          option.textContent = time.label ?? formatTimeLabel(time.value);
          startSelect.appendChild(option);
        });

        if (initialStart && dateSlot.times.some(time => time.value === initialStart)) {
          startSelect.value = initialStart;
          populateEndOptions(initialStart);
          initialStart = '';
        }
      };

      const populateEndOptions = (startValue) => {
        resetSelect(endSelect, 'Selecciona un inicio');

        const dateSlot = slots.find(slot => slot.date === dateSelect.value);
        if (!dateSlot) {
          return;
        }

        const times = dateSlot.times.map(time => time.value);
        const startIndex = times.indexOf(startValue);

        if (startIndex === -1) {
          return;
        }

        const options = [];
        let previousMinutes = minutesFromTime(startValue);

        for (let i = startIndex; i < times.length; i++) {
          const currentMinutes = minutesFromTime(times[i]);
          if (i > startIndex && currentMinutes - previousMinutes !== 30) {
            break;
          }

          const endCandidate = timeFromMinutes(currentMinutes + 30);
          options.push(endCandidate);
          previousMinutes = currentMinutes;
        }

        if (!options.length) {
          return;
        }

        endSelect.disabled = false;
        options.forEach(optionValue => {
          const option = document.createElement('option');
          option.value = optionValue;
          option.textContent = formatTimeLabel(optionValue);
          endSelect.appendChild(option);
        });

        if (initialEnd && options.includes(initialEnd)) {
          endSelect.value = initialEnd;
          initialEnd = '';
        }
      };

      const loadSlotsForDoctor = async (doctorId) => {
        resetSelect(dateSelect, doctorId ? 'Cargando fechas...' : 'Selecciona un médico', true);
        resetSelect(startSelect, 'Selecciona una fecha');
        resetSelect(endSelect, 'Selecciona un inicio');
        slots = [];

        if (!doctorId || !availabilityUrl) {
          return;
        }

        try {
          const response = await fetch(`${availabilityUrl}?id_usuario_medico=${doctorId}`, {
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
            },
          });

          if (!response.ok) {
            throw new Error('No se pudo cargar la disponibilidad');
          }

          const payload = await response.json();
          slots = sanitizeSlots(payload.slots || []);
          populateDates();
        } catch (error) {
          console.error(error);
          resetSelect(dateSelect, 'No se pudo cargar la disponibilidad', true);
        }
      };

      doctorSelect.addEventListener('change', (event) => {
        initialDate = '';
        initialStart = '';
        initialEnd = '';
        loadSlotsForDoctor(event.target.value);
      });

      dateSelect.addEventListener('change', (event) => {
        initialStart = '';
        initialEnd = '';
        populateTimes(event.target.value);
      });

      startSelect.addEventListener('change', (event) => {
        initialEnd = '';
        populateEndOptions(event.target.value);
      });

      if (initialDoctor) {
        loadSlotsForDoctor(initialDoctor).then(() => {
          if (initialDate && !dateSelect.value) {
            populateTimes(initialDate);
          }
        });
        initialDoctor = '';
      }
    });
  </script>
@endpush
@endsection
