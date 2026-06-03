<?php

namespace Tests\Feature;

use Tests\TestCase;

class BovinoTest extends TestCase
{
    /** @test */
    public function hato_bovino_requiere_autenticacion()
    {
        $response = $this->get('/bovino');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function modulo_ordenos_requiere_autenticacion()
    {
        $response = $this->get('/bovino/ordenos');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function modulo_reproduccion_requiere_autenticacion()
    {
        $response = $this->get('/bovino/reproduccion');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function modulo_sanidad_requiere_autenticacion()
    {
        $response = $this->get('/bovino/sanidad');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function modulo_pesaje_requiere_autenticacion()
    {
        $response = $this->get('/bovino/pesaje');
        $response->assertRedirect('/login');
    }
}