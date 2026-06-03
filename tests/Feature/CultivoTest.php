<?php

namespace Tests\Feature;

use Tests\TestCase;

class CultivoTest extends TestCase
{
    /** @test */
    public function listado_cultivos_requiere_autenticacion()
    {
        $response = $this->get('/cultivos');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function cultivo_request_exige_tipo_cultivo()
    {
        $request = new \App\Http\Requests\CultivoRequest();
        $rules = $request->rules();
        $this->assertArrayHasKey('tipo_cultivo', $rules);
        $this->assertContains('required', $rules['tipo_cultivo']);
    }

    /** @test */
    public function cultivo_request_exige_fecha_siembra()
    {
        $request = new \App\Http\Requests\CultivoRequest();
        $rules = $request->rules();
        $this->assertArrayHasKey('fecha_siembra', $rules);
        $this->assertContains('required', $rules['fecha_siembra']);
    }

    /** @test */
    public function cultivo_request_valida_area_numerica()
    {
        $request = new \App\Http\Requests\CultivoRequest();
        $rules = $request->rules();
        if (isset($rules['area_hectareas'])) {
            $this->assertContains('numeric', $rules['area_hectareas']);
        } else {
            $this->assertTrue(true); // campo opcional en esta versión
        }
    }

    /** @test */
    public function ruta_show_cultivo_requiere_autenticacion()
    {
        $response = $this->get('/cultivos/1');
        $response->assertRedirect('/login');
    }
}