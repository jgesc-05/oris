@extends('layouts.paciente')

@section('title', 'Inicio — Paciente')

@section('patient-content')
  @php
    $firstName = $patient?->nombres ?? 'Paciente';
    $currentDate = \Carbon\Carbon::now()->locale('es')->translatedFormat('l, j \d\e F');
  @endphp

  <div class="space-y-6">
    <header class="space-y-2">
      <x-ui.badge variant="info" class="uppercase tracking-wide">inicio — paciente</x-ui.badge>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">
        Hola, {{ $firstName }} 👋
      </h1>
      <p class="text-sm md:text-base text-neutral-600">
        {{ ucfirst($currentDate) }} · Aquí verás tus citas, documentos y novedades personalizadas.
      </p>
    </header>

    <section class="grid gap-4 md:grid-cols-3">
      <x-ui.card class="bg-white">
        <div class="text-xs uppercase tracking-wide text-neutral-500">Próxima cita</div>
        <div class="mt-3 text-lg font-semibold text-neutral-900">Sin citas programadas</div>
        <p class="mt-2 text-sm text-neutral-600">
          Agenda tu próxima visita para recibir recordatorios automáticos.
        </p>
        <x-slot name="footer">
          <x-ui.button variant="primary" size="sm" href="#">Agendar cita</x-ui.button>
        </x-slot>
      </x-ui.card>

      <x-ui.card class="bg-white">
        <div class="text-xs uppercase tracking-wide text-neutral-500">Documentos recientes</div>
        <div class="mt-3 flex items-baseline gap-2">
          <span class="text-3xl font-semibold text-neutral-900">0</span>
          <span class="text-sm text-neutral-500">resultados nuevos</span>
        </div>
        <p class="mt-2 text-sm text-neutral-600">
          Cuando subamos resultados o autorizaciones, los encontrarás aquí.
        </p>
        <x-slot name="footer">
          <x-ui.button variant="secondary" size="sm" href="#">Ver historial</x-ui.button>
        </x-slot>
      </x-ui.card>

      <x-ui.card class="bg-white">
        <div class="text-xs uppercase tracking-wide text-neutral-500">Mensajes</div>
        <div class="mt-3 text-lg font-semibold text-neutral-900">Todo está al día</div>
        <p class="mt-2 text-sm text-neutral-600">
          Te notificaremos cuando tu equipo médico tenga novedades importantes.
        </p>
        <x-slot name="footer">
          <x-ui.button variant="ghost" size="sm" href="#">Configurar notificaciones</x-ui.button>
        </x-slot>
      </x-ui.card>
    </section>

    <section class="grid gap-4 md:grid-cols-2">
      <x-ui.card title="Seguimiento personalizado" subtitle="Toma control de tu cuidado en pocos pasos.">
        <div class="space-y-4 text-sm text-neutral-700">
          <div class="flex items-start gap-3">
            <span class="text-primary-600">①</span>
            <p>
              Completa tu historial clínico para que podamos acompañarte mejor.
            </p>
          </div>
          <div class="flex items-start gap-3">
            <span class="text-primary-600">②</span>
            <p>
              Agenda o actualiza tus citas según tus necesidades.
            </p>
          </div>
          <div class="flex items-start gap-3">
            <span class="text-primary-600">③</span>
            <p>
              Revisa tus resultados en cualquier momento desde el portal.
            </p>
          </div>
        </div>
      </x-ui.card>

      <div class="space-y-4">
        <x-ui.alert variant="info" title="Consejo del día">
          Mantén tus datos de contacto actualizados para recibir notificaciones oportunas sobre tus citas y resultados.
        </x-ui.alert>

        <x-ui.card title="Atajos rápidos">
          <div class="flex flex-col gap-3">
            <x-ui.button variant="primary" block href="#">Solicitar autorización</x-ui.button>
            <x-ui.button variant="secondary" block href="{{ route('paciente.servicios') }}">Explorar servicios</x-ui.button>
            <x-ui.button variant="ghost" block href="{{ route('paciente.medicos') }}">Conoce a tu equipo médico</x-ui.button>
          </div>
        </x-ui.card>
      </div>
    </section>
  </div>
@endsection
