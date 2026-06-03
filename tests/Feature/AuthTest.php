<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    /** @test */
    public function pagina_login_carga_correctamente()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function pagina_registro_carga_correctamente()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /** @test */
    public function registro_falla_sin_campos_obligatorios()
    {
        $response = $this->post('/register', []);
        $response->assertRedirect();
        $this->assertDatabaseMissing('usuarios', ['correo' => null]);
    }

    /** @test */
    public function login_falla_con_credenciales_invalidas()
    {
        $response = $this->post('/login', [
            'correo'     => 'noexiste@test.com',
            'contrasena' => 'wrongpassword',
        ]);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function acceso_a_dashboard_sin_sesion_redirige_a_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function acceso_a_cultivos_sin_sesion_redirige_a_login()
    {
        $response = $this->get('/cultivos');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function acceso_a_animales_sin_sesion_redirige_a_login()
    {
        $response = $this->get('/animales');
        $response->assertRedirect('/login');
    }
}