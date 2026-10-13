<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccesoPorRolTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function usuario(string $correo): User
    {
        return User::where('correo_electronico', $correo)->firstOrFail();
    }

    public function test_un_visitante_sin_sesion_es_enviado_al_login(): void
    {
        $this->get('/secretaria/agenda')->assertRedirect('/login');
    }

    public function test_la_secretaria_inicia_sesion_y_llega_a_su_panel(): void
    {
        $respuesta = $this->post('/login', [
            'correo_electronico' => 'maria.secretaria@vitalcare.com',
            'password' => '12345678',
        ]);

        $respuesta->assertRedirect(route('secretaria.inicio'));
        $this->assertAuthenticatedAs($this->usuario('maria.secretaria@vitalcare.com'));
    }

    public function test_una_contrasena_incorrecta_no_inicia_sesion(): void
    {
        $this->post('/login', [
            'correo_electronico' => 'maria.secretaria@vitalcare.com',
            'password' => 'incorrecta',
        ]);

        $this->assertGuest();
    }

    public function test_un_usuario_inactivo_no_puede_iniciar_sesion(): void
    {
        $secretaria = $this->usuario('maria.secretaria@vitalcare.com');
        $secretaria->estado = 'inactivo';
        $secretaria->save();

        $this->post('/login', [
            'correo_electronico' => 'maria.secretaria@vitalcare.com',
            'password' => '12345678',
        ]);

        $this->assertGuest();
    }

    public function test_la_secretaria_no_puede_entrar_al_panel_de_administracion(): void
    {
        $this->actingAs($this->usuario('maria.secretaria@vitalcare.com'))
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_el_medico_no_puede_entrar_a_la_agenda_de_la_secretaria(): void
    {
        $this->actingAs($this->usuario('andres.mejia@vitalcare.com'))
            ->get('/secretaria/agenda')
            ->assertForbidden();
    }
}
