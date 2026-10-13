<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_la_raiz_lleva_a_la_pagina_de_acceso(): void
    {
        $this->get('/')->assertRedirect('/acceder');
    }

    public function test_la_pagina_de_acceso_carga(): void
    {
        $this->get('/acceder')->assertOk();
    }
}
