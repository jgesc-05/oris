{{-- resources/views/layouts/medico.blade.php --}}
@extends('layouts.app')

@section('title', trim($__env->yieldContent('title', 'Médico — Oris')))

@section('topbar')
  @php
    $doctor = auth()->user();
    $profile = [
      'name' => $doctor?->nombres ? "{$doctor->nombres} {$doctor->apellidos}" : 'Médico',
    ];
  @endphp

  <x-partials.navbar-top
    :items="$doctorNavItems ?? []"
    :profile="$profile"
    logoutRoute="logout"
  />
@endsection

@section('content')
  @yield('doctor-content')
@endsection
