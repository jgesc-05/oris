@extends('layouts.guest')

@section('title', 'Iniciar sesión — Pacientes')

@section('content')
    @php
        $loginPostUrl = \Illuminate\Support\Facades\Route::has('paciente.login')
            ? route('paciente.login.submit')
            : url('/paciente/login');

        $registerUrl = \Illuminate\Support\Facades\Route::has('paciente.register')
            ? route('paciente.register')
            : url('/paciente/registro');
    @endphp

    <div class="max-w-xl mx-auto">
        <h1 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-6">Iniciar sesión</h1>

        {{-- Mensajes de éxito o error --}}
        @if (session('status'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 mb-4 text-red-700 bg-red-100 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ $loginPostUrl }}" method="POST" class="space-y-4">
            @csrf

            {{-- Tipo de documento --}}
            <x-form.select name="id_tipo_documento" label="Tipo de documento" required :placeholder="null">
                @foreach ($documentTypes ?? [] as $documentType)
                    <option value="{{ $documentType->id_tipo_documento }}" @selected(old('id_tipo_documento') == $documentType->id_tipo_documento)>
                        {{ $documentType->name }}
                    </option>
                @endforeach
            </x-form.select>

            {{-- Número de documento --}}
            <x-form.input name="numero_documento" label="Número de documento" required inputmode="numeric"
                autocomplete="username" />

            {{-- Fecha de nacimiento --}}
            <x-form.input name="fecha_nacimiento" label="Fecha de nacimiento" type="date" required autocomplete="bday"
                min="1900-01-01" max="{{ now()->format('Y-m-d') }}" />

            <p class="text-sm text-neutral-600">
                Al continuar, aceptas los <a href="#" class="underline">Términos de uso</a> y la
                <a href="#" class="underline">Política de privacidad</a>.
            </p>

            <x-ui.button variant="primary" size="lg" block="true" class="rounded-full">
                Iniciar sesión
            </x-ui.button>
        </form>

        <p class="text-center text-sm text-neutral-700 mt-4">
            ¿No tienes una cuenta?
            <a href="{{ $registerUrl }}" class="text-primary-700 font-medium hover:underline">Regístrate</a>
        </p>
    </div>
@endsection
