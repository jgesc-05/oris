{{-- resources/views/admin/reportes/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Reportes — Admin')

@section('admin-content')

  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Reportes</h1>

  {{-- Filtros (barra horizontal) --}}
  <x-ui.card class="mb-6">
    <form method="GET" action="{{ route('admin.reportes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
      {{-- Desde --}}
      <div class="lg:col-span-3">
        <x-form.input
          name="desde"
          type="date"
          label="Desde"
          value="{{ request('desde') }}"
        />
      </div>

      {{-- Hasta --}}
      <div class="lg:col-span-3">
        <x-form.input
          name="hasta"
          type="date"
          label="Hasta"
          value="{{ request('hasta') }}"
        />
      </div>

      {{-- Médico --}}
      <div class="lg:col-span-3">
        <x-form.select name="medico" label="Médico">
        <option value="" {{ empty($filtros['medico']) ? 'selected' : '' }}>-- Todos --</option>
          @foreach ($usuariosMedicos as $m)
            <option value="{{ $m->id_usuario }}" {{ $filtros['medico'] == $m->id_usuario ? 'selected' : '' }}>
              {{ "$m->nombres $m->apellidos" }}
            </option>
          @endforeach
        </x-form.select>
      </div>

      {{-- Servicio --}}
      <div class="lg:col-span-3">
      <x-form.select name="servicio" label="Servicio">
        <option value="" {{ empty($filtros['servicio']) ? 'selected' : '' }}>-- Todos --</option>
        @foreach ($serviciosSelect as $s)
          <option value="{{ $s->id_servicio }}" {{ $filtros['servicio'] == $s->id_servicio ? 'selected' : '' }}>
            {{ $s->nombre }}
          </option>
        @endforeach
      </x-form.select>
      </div>

      {{-- Botones --}}
      <div class="lg:col-span-6 flex items-end pb-4 gap-2">
        <x-ui.button type="submit" variant="primary" class="w-full lg:w-auto">
          Filtrar
        </x-ui.button>
        <x-ui.button variant="secondary" :href="route('admin.reportes.index')">Limpiar</x-ui.button>
      </div>
    </form>
  </x-ui.card>

  {{-- Título de métricas --}}
  <h2 class="text-lg font-semibold text-neutral-900 mb-3">Métricas generales</h2>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Distribución de citas por servicio --}}
    <x-ui.card>
      <div class="text-center font-semibold mb-3">Distribución de citas por servicio</div>
      <canvas id="serviciosChart" height="220"></canvas>
    </x-ui.card>

    {{-- Ocupación por médico --}}
    <x-ui.card>
      <div class="text-center font-semibold mb-3">Ocupación por médico</div>
      <canvas id="medicosChart" height="220"></canvas>
    </x-ui.card>
  </div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Datos desde el backend
  const serviciosPorMedico = @json($serviciosPorMedico);
  const serviciosList = @json($serviciosList);
  let selectedServicio = @json($filtros['servicio']);

  function renderServiciosOptions(medicoId) {
    const servicioSelect = document.querySelector('select[name="servicio"]');
    if (!servicioSelect) return;

    const servicios = medicoId && serviciosPorMedico[medicoId]
      ? serviciosPorMedico[medicoId]
      : serviciosList;

    const currentValue = servicioSelect.value || selectedServicio || '';
    servicioSelect.innerHTML = '';

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '-- Todos --';
    servicioSelect.appendChild(defaultOption);

    servicios.forEach(({ id, nombre }) => {
      const option = document.createElement('option');
      option.value = id;
      option.textContent = nombre;
      if (String(id) === String(currentValue)) {
        option.selected = true;
      }
      servicioSelect.appendChild(option);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    const medicoSelect = document.querySelector('select[name="medico"]');
    if (!medicoSelect) return;

    renderServiciosOptions(medicoSelect.value);

    medicoSelect.addEventListener('change', (event) => {
      selectedServicio = '';
      renderServiciosOptions(event.target.value);
    });
  });

  const serviciosData = @json($serviciosChart);
  const medicosData = @json($medicosChart);

  // ============================
  //  Gráfico de servicios (pie)
  // ============================
  if (serviciosData.length > 0) {
    const totalServicios = serviciosData.reduce((sum, s) => sum + s.total, 0);
    const porcentajes = serviciosData.map(s => ((s.total / totalServicios) * 100).toFixed(1));

    new Chart(document.getElementById('serviciosChart'), {
      type: 'pie',
      data: {
        labels: serviciosData.map(s => `${s.servicio} (${((s.total / totalServicios) * 100).toFixed(1)}%)`),
        datasets: [{
          data: serviciosData.map(s => s.total),
          backgroundColor: ['#60a5fa','#f87171','#34d399','#fbbf24','#a78bfa','#facc15','#4ade80']
        }]
      },
      options: {
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  } else {
    document.getElementById('serviciosChart').outerHTML = '<p class="text-center text-sm text-neutral-500 mt-10">No hay datos para mostrar.</p>';
  }

  // ============================
  //  Gráfico de médicos (bar)
  // ============================
  if (medicosData.length > 0) {
    new Chart(document.getElementById('medicosChart'), {
      type: 'bar',
      data: {
        labels: medicosData.map(m => m.medico),
        datasets: [{
          label: 'Número de citas',
          data: medicosData.map(m => m.total),
          backgroundColor: '#93c5fd'
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });
  } else {
    document.getElementById('medicosChart').outerHTML = '<p class="text-center text-sm text-neutral-500 mt-10">No hay datos para mostrar.</p>';
  }
</script>
@endpush
