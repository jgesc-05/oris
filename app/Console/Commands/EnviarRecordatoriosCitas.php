<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminderNotification;
use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecordatorioCitaMail;
use App\Models\Appointment;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:loop';
    protected $description = 'Revisa constantemente las citas y envía recordatorios cada cierto tiempo.';

    public function handle()
    {
        $this->info('Iniciando bucle para revisar citas...');

        while (true) {
            $ahora = Carbon::now();
            $limite = $ahora->copy()->addDays(3); // citas en las próximas 72h //De prueba, idealmente es cada 1 día

            $citas = Appointment::whereBetween('fecha_hora_inicio', [$ahora, $limite])
                ->where('recordatorio_enviado', false)
                ->get();

            foreach ($citas as $cita) {
                try {
                    Mail::to($cita->paciente->correo_electronico)
                        ->send(new AppointmentReminderNotification($cita));

                    $cita->update(['recordatorio_enviado' => true]);

                    $this->info("📨 Recordatorio enviado a {$cita->paciente->correo_electronico}");
                } catch (\Exception $e) {
                    $this->error("Error enviando a {$cita->paciente->correo_electronico}: {$e->getMessage()}");
                }
            }

            $this->info(' Esperando 1 hora para la próxima revisión...');
            sleep(3600); // Espera 1 hora antes de volver a revisar
        }
    }
}
