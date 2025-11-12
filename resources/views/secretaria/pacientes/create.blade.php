@extends('layouts.secretaria')

@section('title', 'Registrar paciente — Secretaría')

@section('secretary-content')
    <div class="space-y-6 max-w-3xl">
        <header class="space-y-2">
            <x-ui.badge variant="info" class="uppercase tracking-wide">pacientes — registro</x-ui.badge>
            <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Registrar nuevo paciente</h1>
            <p class="text-sm md:text-base text-neutral-600">
                Completa los datos para crear el perfil del paciente en el sistema.
            </p>
        </header>

        <x-ui.card class="p-6">
            @if ($errors->any())
                <x-ui.alert variant="warning" class="mb-4">
                    <ul class="space-y-1 list-disc list-inside text-left">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            @endif

            <form method="POST" action="{{ route('secretaria.pacientes.store') }}"
                class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <x-form.select name="id_tipo_documento" label="Tipo de documento"  :placeholder="null">
                    @foreach ($documentTypes ?? [] as $documentType)
                        <option value="{{ $documentType->id_tipo_documento }}" @selected(old('id_tipo_documento') == $documentType->id_tipo_documento)>
                            {{ $documentType->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.input name="numero_documento" label="Número de documento" 
                    value="{{ old('numero_documento') }}" />

                <x-form.input name="nombres" label="Nombres" value="{{ old('nombres') }}" />

                <x-form.input name="apellidos" label="Apellidos"  value="{{ old('apellidos') }}" />

                <x-form.input type="date" name="fecha_nacimiento" label="Fecha de nacimiento" 
                    value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}" />

                <x-form.input type="email" name="correo_electronico" label="Correo electrónico" 
                    value="{{ old('correo_electronico') }}" />

                <x-form.input name="telefono" label="Teléfono" value="{{ old('telefono') }}" />

                <div class="md:col-span-2">
                    <x-form.textarea name="observaciones" label="Observaciones"
                        rows="4">{{ old('observaciones') }}</x-form.textarea>
                </div>

                <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                    <x-ui.button :href="route('secretaria.pacientes.index')" variant="secondary" size="md" class="rounded-full">
                        Cancelar
                    </x-ui.button>
                    <x-ui.button variant="primary" size="lg" class="rounded-full px-8">
                        Guardar paciente
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
