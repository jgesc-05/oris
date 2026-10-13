<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgendamientoCitasTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $secretaria;
    private User $paciente;
    private User $medico;
    private Service $servicio;
    private Carbon $dia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secretaria = User::where('correo_electronico', 'maria.secretaria@vitalcare.com')->firstOrFail();
        $this->paciente = User::whereHas('userType', fn ($q) => $q->where('nombre', 'Paciente'))->firstOrFail();
        $this->medico = User::where('correo_electronico', 'andres.mejia@vitalcare.com')->firstOrFail();
        $this->servicio = Service::firstOrFail();

        // Próximo día hábil, con la agenda del médico vacía para que la prueba no dependa de los datos de ejemplo
        $this->dia = Carbon::tomorrow();
        while ($this->dia->isWeekend()) {
            $this->dia->addDay();
        }
        Appointment::where('id_usuario_medico', $this->medico->id_usuario)
            ->whereDate('fecha_hora_inicio', $this->dia->toDateString())
            ->delete();
    }

    private function agendar(string $fecha, string $hora)
    {
        return $this->actingAs($this->secretaria)->post(
            route('secretaria.citas.create.store', $this->paciente->id_usuario),
            [
                'id_tipos_especialidad' => $this->servicio->id_tipos_especialidad,
                'id_servicio' => $this->servicio->id_servicio,
                'id_usuario_medico' => $this->medico->id_usuario,
                'fecha' => $fecha,
                'hora' => $hora,
            ]
        );
    }

    public function test_la_secretaria_agenda_una_cita_en_un_horario_libre(): void
    {
        $respuesta = $this->agendar($this->dia->toDateString(), '09:00');

        $respuesta->assertSessionHasNoErrors();
        $this->assertDatabaseHas('appointments', [
            'id_usuario_paciente' => $this->paciente->id_usuario,
            'id_usuario_medico' => $this->medico->id_usuario,
            'fecha_hora_inicio' => $this->dia->toDateString() . ' 09:00:00',
            'estado' => Appointment::STATUS_PROGRAMADA,
        ]);
    }

    public function test_no_se_puede_agendar_en_una_fecha_pasada(): void
    {
        $respuesta = $this->agendar(Carbon::yesterday()->toDateString(), '09:00');

        $respuesta->assertSessionHasErrors('fecha');
    }

    public function test_no_se_puede_agendar_dos_citas_del_mismo_medico_a_la_misma_hora(): void
    {
        $this->agendar($this->dia->toDateString(), '10:00')->assertSessionHasNoErrors();

        $this->agendar($this->dia->toDateString(), '10:00')->assertSessionHasErrors('hora');

        $this->assertSame(1, Appointment::where('id_usuario_medico', $this->medico->id_usuario)
            ->where('fecha_hora_inicio', $this->dia->toDateString() . ' 10:00:00')
            ->count());
    }

    public function test_no_se_puede_agendar_fuera_del_horario_de_la_clinica(): void
    {
        $this->agendar($this->dia->toDateString(), '12:30')->assertSessionHasErrors('hora');
    }
}
