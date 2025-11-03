@extends('layouts.app')

@section('title', 'Panel del Paciente')

@section('content')
  @php
    $patient = auth('paciente')->user();
  @endphp

  <section class="space-y-6">
    <header class="text-center">
      <h1 class="text-3xl font-semibold text-neutral-900">Hola, {{ $patient?->nombres ?? 'Paciente' }} 👋</h1>
      <p class="mt-2 text-neutral-600">Bienvenido a tu panel. Aquí verás tus próximas citas, mensajes y acceso rápido a tus servicios.</p>
    </header>

    <div class="grid gap-6 md:grid-cols-2">
      <article class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-medium text-neutral-800">Próximas citas</h2>
        <p class="mt-2 text-neutral-600">Aún no hay citas registradas. Cuando agendes una, aparecerá aquí.</p>
        <a href="#" class="mt-4 inline-flex items-center text-primary-600 hover:text-primary-700">
          Agendar una cita
          <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0-6.75-6.75M19.5 12l-6.75 6.75" />
          </svg>
        </a>
      </article>

      <article class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-medium text-neutral-800">Documentos y resultados</h2>
        <p class="mt-2 text-neutral-600">Consulta tus resultados y documentos importantes en esta sección.</p>
        <button type="button" class="mt-4 inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700">
          Ver documentos
        </button>
      </article>
    </div>

    <article class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm">
      <h2 class="text-xl font-medium text-neutral-800">Últimas novedades</h2>
      <p class="mt-2 text-neutral-600">Mantente al día con las actualizaciones de tu IPS. Pronto verás tus mensajes aquí.</p>
    </article>
  </section>
@endsection
