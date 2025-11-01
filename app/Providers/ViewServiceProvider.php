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
    }

}
