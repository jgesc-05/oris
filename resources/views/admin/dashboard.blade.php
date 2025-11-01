@extends('layouts.app')

@php
  $navItems = [
    ['label' => 'Inicio', 'href' => route('admin.dashboard')],
    ['label' => 'Usuarios', 'href' => route('admin.usuarios.index')],
    ['label' => 'Pacientes', 'href' => route('admin.pacientes.index')],
    ['label' => 'Reportes', 'href' => route('admin.reportes.index')],
    ['label' => 'Configuración', 'href' => route('admin.config')],
  ];

  $profile = ['name' => 'Laura G.']; // O con 'avatar' => asset('images/admin.png')
@endphp

@section('content')
  {{-- Aquí tu contenido del dashboard --}}
@endsection
