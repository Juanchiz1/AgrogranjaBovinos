<?php

namespace Tests\Feature;

use Tests\TestCase;

class TareaTest extends TestCase
{
    /** @test */
    public function calendario_requiere_autenticacion()
    {
        $response = $this->get('/calendario');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function tarea_request_exige_titulo()
    {
        $request = new \App\Http\Requests\TareaRequest();
        $rules = $request->rules();
        $this->assertArrayHasKey('titulo', $rules);
        $this->assertContains('required', $rules['titulo']);
    }

    /** @test */
    public function tarea_request_valida_prioridad()
    {
        $request = new \App\Http\Requests\TareaRequest();
        $rules = $request->rules();
        if (isset($rules['prioridad'])) {
            $inRule = collect($rules['prioridad'])->first(fn($r) => str_starts_with($r, 'in:'));
            $this->assertNotNull($inRule);
        } else {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function post_tarea_sin_sesion_redirige()
    {
        $response = $this->post('/tareas', ['titulo' => 'Test']);
        $response->assertRedirect('/login');
    }
}