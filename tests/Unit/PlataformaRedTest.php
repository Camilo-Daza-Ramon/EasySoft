<?php

namespace Tests\Unit;

use App\Municipio;
use App\PlataformaDeRed;
use App\PlataformaRedAcceso;
use App\PlataformaRedInstruccion;
use App\Proyecto;
use App\User;
use Faker\Generator;
use Faker\Factory as Faker; 
use Tests\TestCase;

class PlataformaRedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetPlataformas()
    {
        $user = User::where('email', '=', 'desarrollo.web2@sisteco.co')->first();
        $this->actingAs($user);

        $plataformas = factory(PlataformaDeRed::class, 1)->create()->each(function ($plataforma) {
            $idsMunicipios = Municipio::pluck('MunicipioId')->toArray();

            shuffle($idsMunicipios);
            $plataforma->municipios()->attach($idsMunicipios[0]);
        });
        $response = $this->get(route('gestion.index'));
        $response->assertStatus(200);

        foreach ($plataformas as $plataforma) {
            $response->assertSee($plataforma->nombre);
        }
    }

    public function testCreatePlataforma()
    {
        try {
            $user = User::where('email', '=', 'desarrollo.web2@sisteco.co')->first();
            $this->actingAs($user);
    
            $data = $this->generatePlataforma();
            $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
            $res = $this->post(route('gestion.store'), $data);
            $res->assertSuccessful();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }    
    }

    public function testUpdatePlataforma()
    {
        $user = User::where('email', '=', 'desarrollo.web2@sisteco.co')->first();
        $this->actingAs($user);

        $idsPlataformas = PlataformaDeRed::pluck('id')->toArray();
        shuffle($idsPlataformas);

        $data = $this->generatePlataforma();
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $res = $this->put(route('gestion.update', ['id' => $idsPlataformas[0]]), $data);
        $res->assertSuccessful();
    }

    public function testDeletePlataforma()
    {
        $user = User::where('email', '=', 'desarrollo.web2@sisteco.co')->first();
        $this->actingAs($user);

        $idsPlataformas = PlataformaDeRed::pluck('id')->toArray();
        shuffle($idsPlataformas);

        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
        $res = $this->delete(route('gestion.destroy', ['id' => $idsPlataformas[0]]));
        $res->assertSuccessful();
    }

    private function generatePlataforma()
    {
        $faker = Faker::create(); 

        $idsInstrucciones = PlataformaRedInstruccion::pluck('id')->toArray();
        $idsDatosAcceso = PlataformaRedAcceso::pluck('id')->toArray();
        $idsProyectos = Proyecto::pluck('ProyectoID')->toArray();
        $idsMunicipios = Municipio::pluck('MunicipioId')->toArray();
        
        
        shuffle($idsInstrucciones);
        shuffle($idsDatosAcceso);
        shuffle($idsProyectos);
        shuffle($idsMunicipios);

        return [
            "nombre" => $faker->name,
            "link" => $faker->url,
            "instrucciones" => $idsInstrucciones[0],
            "datos_acceso" => $idsDatosAcceso[0],
            "proyecto" => $idsProyectos[0],
            "municipios" => [$idsMunicipios[0]]
        ];
    }
}
