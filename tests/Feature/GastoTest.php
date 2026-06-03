<?php

namespace Tests\Feature;

use Tests\TestCase;

class GastoTest extends TestCase
{
    /** @test */
    public function listado_gastos_requiere_autenticacion()
    {
        $response = $this->get('/gastos');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function gasto_request_exige_categoria()
    {
        $request = new \App\Http\Requests\GastoRequest();
        $rules = $request->rules();
        $this->assertArrayHasKey('categoria', $rules);
        $this->assertContains('required', $rules['categoria']);
    }

    /** @test */
    public function gasto_request_exige_descripcion()
    {
        $request = new \App\Http\Requests\GastoRequest();
        $rules = $request->rules();
        $this->assertContains('required', $rules['descripcion']);
    }

    /** @test */
    public function gasto_request_exige_valor()
    {
        $request = new \App\Http\Requests\GastoRequest();
        $rules = $request->rules();
        $this->assertContains('required', $rules['valor']);
        $this->assertContains('numeric', $rules['valor']);
    }

    /** @test */
    public function gasto_request_rechaza_valor_negativo()
    {
        $request = new \App\Http\Requests\GastoRequest();
        $rules = $request->rules();
        $this->assertContains('min:0', $rules['valor']);
    }

    /** @test */
    public function gasto_request_acepta_cultivo_id_nullable()
    {
        $request = new \App\Http\Requests\GastoRequest();
        $rules = $request->rules();
        $this->assertContains('nullable', $rules['cultivo_id']);
    }
}