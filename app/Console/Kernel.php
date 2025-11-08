<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define el horario de comandos de la aplicación.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('citas:recordatorios')->dailyAt('08:00');
    }

    /**
     * Registrar los comandos para Artisan.
     */
    protected function commands(): void
    {
        // Carga todos los comandos definidos en app/Console/Commands
        $this->load(__DIR__.'/Commands');

        // Carga las rutas de consola definidas en routes/console.php
        require base_path('routes/console.php');
    }
}
