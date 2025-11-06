{{-- resources/views/layouts/secretaria.blade.php --}}
@extends('layouts.app')

@section('title', trim($__env->yieldContent('title', 'Secretaría — Oris')))

@section('topbar')
  @php
    $secretary = auth()->user();
    $profile = [
      'name' => $secretary?->nombres ? "{$secretary->nombres} {$secretary->apellidos}" : 'Secretaría',
    ];
  @endphp

  <x-partials.navbar-top
    :items="$secretaryNavItems ?? []"
    :profile="$profile"
    logoutRoute="logout"
  />
@endsection

@section('content')
  @yield('secretary-content')
@endsection
