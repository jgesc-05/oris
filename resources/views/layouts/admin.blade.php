{{-- resources/views/layouts/admin.blade.php --}}
@extends('layouts.app')

@section('title', trim($__env->yieldContent('title', 'Admin — Oris')))

{{-- Usa los items inyectados por ViewServiceProvider: $adminNavItems --}}
@section('topbar')
  <x-partials.navbar-top
    :items="$adminNavItems"
    :profile="['name' => $profile['name'] ?? 'Usuario']"
    brand="VitalCare IPS"
  />
@endsection

{{-- Reexpone una sección específica para el área admin --}}
@section('content')
  @yield('admin-content')
@endsection
