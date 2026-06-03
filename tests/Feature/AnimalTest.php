<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class AnimalTest extends TestCase
{
    private function crearUsuario(): int
    {
        return DB::table('usuarios')->insertGetId([
            'nombre'      => 'Test',
            'apellido'    => 'User',
            'correo'      => 'test@agrogranja.com',
            'contrasena'  => bcrypt('password'),
            'creado_en'   => now(),
            'actualizado_en' => now(),
        ]);
    }

    /** @test */
    public function listado_animales_requiere_autenticacion()
    {
        $response = $this->get('/animales');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function animal_request_rechaza_especie_vacia()
    {
        $request = new \App\Http\Requests\AnimalRequest();
        $rules = $request->rules();
        $this->assertContains('required', $rules['especie']);
    }

    /** @test */
    public function animal_request_valida_estado_correcto()
    {
        $request = new \App\Http\Requests\AnimalRequest();
        $rules = $request->rules();
        $inRule = collect($rules['estado'])->first(fn($r) => str_starts_with($r, 'in:'));
        $this->assertStringContainsString('activo', $inRule);
        $this->assertStringContainsString('vendido', $inRule);
        $this->assertStringContainsString('muerte', $inRule);
    }

    /** @test */
    public function animal_request_acepta_peso_numerico_positivo()
    {
        $request = new \App\Http\Requests\AnimalRequest();
        $rules = $request->rules();
        $this->assertContains('numeric', $rules['peso_promedio']);
        $this->assertContains('min:0', $rules['peso_promedio']);
    }

    /** @test */
    public function animal_request_valida_unidad_peso()
    {
        $request = new \App\Http\Requests\AnimalRequest();
        $rules = $request->rules();
        $inRule = collect($rules['unidad_peso'])->first(fn($r) => str_starts_with($r, 'in:'));
        $this->assertStringContainsString('kg', $inRule);
        $this->assertStringContainsString('lb', $inRule);
    }
}