{{-- resources/views/access/index.blade.php --}}
@extends('layouts.guest')

@section('title', 'Oris | Acceso y presentación')

@section('content')
  {{-- HERO --}}
  <section class="max-w-4xl mx-auto">
    <div class="text-center md:text-left mb-8">
        <br>
      <h1 class="text-2xl md:text-3xl font-bold text-neutral-900 leading-tight">
        Oris: la forma más simple de gestionar tu salud en <span class="text-primary-600">VitalCare IPS</span>
      </h1>
      <p class="mt-3 text-neutral-700">
        Un portal claro para pacientes y un panel potente para nuestro equipo clínico. Agenda, gestiona y
        atiende con una experiencia moderna, rápida y sin complicaciones.
      </p>
    </div>

      {{-- Imagen destacada --}}
    <div class="flex justify-center mb-8">
        <img
        src="{{ asset('images/index-guest.png') }}"
        alt="Vista general de Oris"
        class="rounded-lg shadow-md max-w-full h-auto"
        >
    </div>

    {{-- Beneficios clave --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <x-ui.card>
        <div class="text-lg font-semibold text-neutral-900 mb-1">Ágil y accesible</div>
        <p class="text-sm text-neutral-700">
          Pacientes sin contraseña: documento + fecha de nacimiento + token. Ingresar es tan fácil como cuidarte.
        </p>
      </x-ui.card>

      <x-ui.card>
        <div class="text-lg font-semibold text-neutral-900 mb-1">Atención organizada</div>
        <p class="text-sm text-neutral-700">
          Agenda inteligente, bloqueos de horario y visibilidad de profesionales para una clínica sin fricción.
        </p>
      </x-ui.card>

      <x-ui.card>
        <div class="text-lg font-semibold text-neutral-900 mb-1">Confianza y claridad</div>
        <p class="text-sm text-neutral-700">
          Historial, servicios y comunicación transparente en un mismo lugar. Todo a tu alcance.
        </p>
      </x-ui.card>
    </div>
  </section>
  {{-- Tarjetas de acceso --}}
  <section class="max-w-4xl mx-auto mt-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      {{-- Pacientes --}}
      <x-ui.card title="Pacientes" subtitle="Conoce, agenda y gestiona tu cuidado">
        <ul class="text-sm text-neutral-700 list-disc pl-5 mb-4 space-y-1">
          <li>Ingresa sin contraseña (documento + fecha de nacimiento + token).</li>
          <li>Agenda nuevas citas, modifica o cancela con libertad.</li>
          <li>Explora servicios y profesionales disponibles para ti.</li>
          <li>Consulta tu historial y mantén el control de tu salud.</li>
        </ul>
        <div class="flex flex-wrap gap-2">
          <x-ui.button variant="primary" :href="route('paciente.login')">Iniciar sesión</x-ui.button>
          <x-ui.button variant="secondary" :href="route('paciente.register')">Crear mi cuenta</x-ui.button>
        </div>
      </x-ui.card>

      {{-- Equipo de la clínica (Staff) --}}
      <x-ui.card title="Equipo VitalCare IPS" subtitle="Administración, secretaría y profesionales">
        <ul class="text-sm text-neutral-700 list-disc pl-5 mb-4 space-y-1">
          <li>Acceso con documento y contraseña</li>
          <li>Agenda centralizada, filtros y bloqueo de espacios de trabajo.</li>
          <li>Gestión de pacientes, reportes y publicación de servicios.</li>
          <li>Enfoque en productividad clínica y calidad de atención.</li>
        </ul>
        <div class="flex flex-wrap gap-2">
          <x-ui.button variant="primary" :href="route('login')">Iniciar sesión</x-ui.button>
        </div>
      </x-ui.card>
    </div>
  </section>

  {{-- Separador visual --}}
  <div class="my-8 h-px bg-neutral-200"></div>

  {{-- ¿Qué es Oris? --}}
  <section class="max-w-4xl mx-auto">
    <x-ui.card title="¿Qué es Oris?" subtitle="La plataforma digital de nuestra IPS">
      <p class="text-sm text-neutral-700">
        Oris es el ecosistema digital de <strong>VitalCare IPS</strong> para la gestión integral de citas,
        pacientes y profesionales. Diseñada para ser clara para quienes se cuidan y poderosa para quienes cuidan.
      </p>
    </x-ui.card>
  </section>



  {{-- Cierre/Confianza --}}
  <section class="max-w-4xl mx-auto mt-8">
    <x-ui.alert variant="info" title="Cuidamos tu tiempo y tu tranquilidad">
      <span class="block">
        Oris une a pacientes y equipo clínico en una experiencia ágil, clara y segura. Porque la salud empieza
        con una buena organización.
      </span>
    </x-ui.alert>
  </section>
@endsection
