<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('admin.*', function ($view) {
            $items = [
                ['label'=>'Inicio',      'href'=>route('admin.dashboard'),        'active'=>request()->routeIs('admin.dashboard')],
                ['label'=>'Usuarios',    'href'=>route('admin.usuarios.index'),   'active'=>request()->routeIs('admin.usuarios.*')],
                ['label'=>'Pacientes',   'href'=>route('admin.pacientes.index'),  'active'=>request()->routeIs('admin.pacientes.*')],
                ['label'=>'Reportes',    'href'=>route('admin.reportes.index'),   'active'=>request()->routeIs('admin.reportes.*')],
                ['label'=>'Configuración','href'=>route('admin.config'),          'active'=>request()->routeIs('admin.config')],
            ];
            $view->with('adminNavItems', $items);
        });

        \Illuminate\Support\Facades\View::composer(['layouts.paciente', 'paciente.*'], function ($view) {
            $items = [
                ['label' => 'Inicio',    'href' => route('paciente.inicio'),    'active' => request()->routeIs('paciente.inicio')],
                ['label' => 'Servicios', 'href' => route('paciente.servicios'), 'active' => request()->routeIs('paciente.servicios*')],
                ['label' => 'Médicos',   'href' => route('paciente.medicos'),   'active' => request()->routeIs('paciente.medicos*')],
            ];

            $view->with('patientNavItems', $items);
        });

        \Illuminate\Support\Facades\View::composer(['layouts.secretaria', 'secretaria.*'], function ($view) {
            $items = [
                ['label' => 'Inicio',    'href' => route('secretaria.inicio'),    'active' => request()->routeIs('secretaria.inicio')],
                ['label' => 'Agenda',    'href' => route('secretaria.agenda'),    'active' => request()->routeIs('secretaria.agenda')],
                ['label' => 'Pacientes', 'href' => route('secretaria.pacientes.index'), 'active' => request()->routeIs('secretaria.pacientes.*')],
                ['label' => 'Médicos',   'href' => route('secretaria.medicos.index'),   'active' => request()->routeIs('secretaria.medicos.*')],
                ['label' => 'Servicios', 'href' => route('secretaria.servicios.index'), 'active' => request()->routeIs('secretaria.servicios.*')],
            ];

            $view->with('secretaryNavItems', $items);
        });

        \Illuminate\Support\Facades\View::composer(['layouts.medico', 'medico.*'], function ($view) {
            $items = [
                ['label' => 'Inicio',    'href' => route('medico.dashboard'),        'active' => request()->routeIs('medico.dashboard')],
                ['label' => 'Agenda',    'href' => route('medico.agenda'),           'active' => request()->routeIs('medico.agenda')],
                ['label' => 'Pacientes', 'href' => route('medico.pacientes.index'),  'active' => request()->routeIs('medico.pacientes.*')],
            ];

            $view->with('doctorNavItems', $items);
        });
    }

}
