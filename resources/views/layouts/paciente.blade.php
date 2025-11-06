{{-- resources/views/layouts/paciente.blade.php --}}
@extends('layouts.app')

@section('title', trim($__env->yieldContent('title', 'Paciente — Oris')))

@section('topbar')
  @php
    $patient = auth('paciente')->user();
    $profile = [
      'name' => $patient?->nombres ? "{$patient->nombres} {$patient->apellidos}" : 'Paciente',
    ];
  @endphp

  <x-partials.navbar-top
    :items="$patientNavItems ?? []"
    :profile="$profile"
    logoutRoute="paciente.logout"
  />
@endsection

@section('content')
  @yield('patient-content')
@endsection
