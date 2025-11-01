{{-- resources/views/admin/reportes/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Reportes — Admin')

@section('admin-content')

  <h1 class="text-xl md:text-2xl font-bold text-neutral-900 mb-4">Reportes</h1>

  {{-- Filtros (barra horizontal) --}}
    <x-ui.card class="mb-6">
    <form class="grid grid-cols-12 gap-4 items-end">
        {{-- Desde --}}
        <div class="col-span-12 sm:col-span-3">
        <label for="desde" class="form-label mb-1">Desde</label>
        <input id="desde" name="desde" type="date" class="form-control h-10 w-full" />
        </div>

        {{-- Hasta --}}
        <div class="col-span-12 sm:col-span-3">
        <label for="hasta" class="form-label mb-1">Hasta</label>
        <input id="hasta" name="hasta" type="date" class="form-control h-10 w-full" />
        </div>

        {{-- Odontólogo --}}
        <div class="col-span-12 sm:col-span-3">
        <x-form.select name="odontologo" label="Odontólogo" class="w-full">
            <option value="">-- Seleccionar --</option>
            <option>Juan Pérez</option>
            <option>Andrés Martínez</option>
            <option>Ana Morales</option>
            <option>Camila Ortega</option>
        </x-form.select>
        </div>

        {{-- Servicio --}}
        <div class="col-span-12 sm:col-span-3">
        <x-form.select name="servicio" label="Servicio" class="w-full">
            <option value="">-- Seleccionar --</option>
            <option>Cirugía Oral</option>
            <option>Endodoncia</option>
            <option>Ortodoncia</option>
            <option>Odontología General</option>
        </x-form.select>
        </div>

        {{-- Tipo de servicio --}}
        <div class="col-span-12 sm:col-span-3">
        <x-form.select name="tipo_servicio" label="Tipo de servicio" class="w-full">
            <option value="">-- Seleccionar --</option>
            <option value="presencial">Presencial</option>
            <option value="teleconsulta">Teleconsulta</option>
            <option value="domicilio">Domicilio</option>
        </x-form.select>
        </div>

        {{-- Botón Filtrar --}}
        <div class="col-span-12 sm:col-span-3 justify-self-end">
        <x-ui.button type="submit" variant="primary" class="h-10 px-5">Filtrar</x-ui.button>
        </div>
    </form>
    </x-ui.card>

  {{-- Título de métricas --}}
  <h2 class="text-lg font-semibold text-neutral-900 mb-3">Métricas generales por mes</h2>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Distribución de citas por servicio (gráfico tipo pastel mock) --}}
    <x-ui.card>
      <div class="text-center font-semibold mb-3">Distribución de citas por servicio</div>
      <div class="flex items-center justify-center">
        <svg viewBox="0 0 42 42" width="260" height="260" aria-label="Distribución por servicio" role="img">
          <circle cx="21" cy="21" r="15.915" fill="#fff"/>
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#6B7280"
                  stroke-width="6" stroke-dasharray="48 52" stroke-dashoffset="25"/>
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#9CA3AF"
                  stroke-width="6" stroke-dasharray="23 77" stroke-dashoffset="-23"/>
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#D1D5DB"
                  stroke-width="6" stroke-dasharray="17 83" stroke-dashoffset="-46"/>
          <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="#E5E7EB"
                  stroke-width="6" stroke-dasharray="12 88" stroke-dashoffset="-63"/>
        </svg>
      </div>

      <div class="mt-4 grid grid-cols-2 gap-2 text-sm text-neutral-700">
        <div class="flex items-center gap-2"><span class="w-3 h-3 bg-neutral-700 inline-block rounded"></span> Cirugía Oral (48%)</div>
        <div class="flex items-center gap-2"><span class="w-3 h-3 bg-neutral-500 inline-block rounded"></span> Endodoncia (23%)</div>
        <div class="flex items-center gap-2"><span class="w-3 h-3 bg-neutral-400 inline-block rounded"></span> Ortodoncia (17%)</div>
        <div class="flex items-center gap-2"><span class="w-3 h-3 bg-neutral-300 inline-block rounded"></span> Odontología General (12%)</div>
      </div>
    </x-ui.card>

    {{-- Ocupación por odontólogo (barras mock) --}}
    <x-ui.card>
      <div class="text-center font-semibold mb-3">Ocupación por odontólogo</div>
      <div class="grid grid-cols-7 gap-3 items-end h-56">
        @php
          $barras = [
            ['label'=>'Juan','v'=>80],
            ['label'=>'Andrés','v'=>100],
            ['label'=>'María','v'=>40],
            ['label'=>'Ana','v'=>60],
            ['label'=>'Leonardo','v'=>75],
            ['label'=>'Camila','v'=>120],
            ['label'=>'Otros','v'=>55],
          ];
          $max = 120;
        @endphp
        @foreach($barras as $b)
          <div class="flex flex-col items-center gap-2">
            <div class="w-8 bg-neutral-400 rounded"
                 style="height: {{ max(6, round(($b['v'] / $max) * 200)) }}px"></div>
            <span class="text-xs text-neutral-700">{{ $b['label'] }}</span>
          </div>
        @endforeach
      </div>
      <p class="mt-3 text-xs text-neutral-500">Datos simulados — se integrarán con el backend.</p>
    </x-ui.card>
  </div>

@endsection
