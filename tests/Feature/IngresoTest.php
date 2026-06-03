<?php

namespace Tests\Feature;

use Tests\TestCase;

class IngresoTest extends TestCase
{
    /** @test */
    public function listado_ingresos_requiere_autenticacion()
    {
        $response = $this->get('/ingresos');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function ingreso_request_exige_descripcion()
    {
        $request = new \App\Http\Requests\IngresoRequest();
        $rules = $request->rules();
        $this->assertContains('required', $rules['descripcion']);
    }

    /** @test */
    public function ingreso_request_exige_valor_total()
    {
        $request = new \App\Http\Requests\IngresoRequest();
        $rules = $request->rules();
        $this->assertContains('required', $rules['valor_total']);
        $this->assertContains('numeric', $rules['valor_total']);
    }

    /** @test */
    public function ingreso_request_rechaza_valor_negativo()
    {
        $request = new \App\Http\Requests\IngresoRequest();
        $rules = $request->rules();
        $this->assertContains('min:0', $rules['valor_total']);
    }

    /** @test */
    public function ingreso_request_acepta_animal_id_nullable()
    {
        $request = new \App\Http\Requests\IngresoRequest();
        $rules = $request->rules();
        $this->assertContains('nullable', $rules['animal_id']);
    }
}