<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PlataformaDeRed;
use App\PlataformaRedAcceso;
use App\PlataformaRedInstruccion;
use App\Proyecto;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(PlataformaDeRed::class, function (Faker\Generator $faker) {
    $idsInstrucciones = PlataformaRedInstruccion::pluck('id')->toArray();
    $idsDatosAcceso = PlataformaRedAcceso::pluck('id')->toArray();
    $idsProyectos = Proyecto::pluck('ProyectoID')->toArray();

    shuffle($idsInstrucciones); 
    shuffle($idsDatosAcceso); 
    shuffle($idsProyectos); 

    return [
        'nombre' => $faker->name,
        'link' => $faker->url,
        'instruccion_id' => $idsInstrucciones[0],
        "dato_acceso_id" => $idsDatosAcceso[0],
        "proyecto_id" => $idsProyectos[0]
    ];
});

