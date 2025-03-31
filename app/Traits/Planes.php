<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\PlanComercial;

trait Planes {

    public function listar($proyecto, $estrato, $municipio) {

        $planes_comerciales_1 = PlanComercial::select(
            'PlanesComerciales.PlanId', 
            'PlanesComerciales.nombre', 
            'PlanesComerciales.ValorDelServicio', 
            'PlanesComerciales.DescripcionPlan', 
            'PlanesComerciales.Estrato', 
            'PlanesComerciales.TipoDePlan', 
            'PlanesComerciales.VelocidadInternet', 
            'PlanesComerciales.Status'
        )
        ->join('Proyectos as p', 'PlanesComerciales.ProyectoId', '=', 'p.ProyectoID')
        ->leftJoin('planes_municipios as pm', 'PlanesComerciales.PlanId', '=', 'pm.plan_comercial_id')
        ->where([
            ['PlanesComerciales.Status', 'A'],
            ['p.ProyectoID', $proyecto],
            ['PlanesComerciales.Estrato', $estrato]
        ])->whereNull('pm.plan_comercial_id');

        $planes_comerciales_2 = PlanComercial::select(
            'PlanesComerciales.PlanId', 
            'PlanesComerciales.nombre', 
            'PlanesComerciales.ValorDelServicio', 
            'PlanesComerciales.DescripcionPlan', 
            'PlanesComerciales.Estrato', 
            'PlanesComerciales.TipoDePlan', 
            'PlanesComerciales.VelocidadInternet', 
            'PlanesComerciales.Status'
        )
        ->join('planes_municipios as pm', 'PlanesComerciales.PlanId', '=', 'pm.plan_comercial_id')
        ->join('proyectos_municipios as prm', 'pm.proyecto_municipio_id', '=', 'prm.id')
        ->where([
            ['PlanesComerciales.Status', 'A'],
            ['prm.proyecto_id', $proyecto],
            ['prm.municipio_id', $municipio],
            ['PlanesComerciales.Estrato', $estrato]
        ])->union($planes_comerciales_1);

        $planes_comerciales_3 = PlanComercial::select(
            'PlanesComerciales.PlanId', 
            'PlanesComerciales.nombre', 
            'PlanesComerciales.ValorDelServicio', 
            'PlanesComerciales.DescripcionPlan', 
            'PlanesComerciales.Estrato', 
            'PlanesComerciales.TipoDePlan', 
            'PlanesComerciales.VelocidadInternet', 
            'PlanesComerciales.Status'
        )
        ->join('planes_municipios as pm', 'PlanesComerciales.PlanId', '=', 'pm.plan_comercial_id')
        ->join('proyectos_municipios as prm', 'pm.proyecto_municipio_id', '=', 'prm.id')
        ->where([
            ['PlanesComerciales.Status', 'A'],
            ['prm.proyecto_id', $proyecto],
            ['prm.municipio_id', $municipio],
            ['PlanesComerciales.Estrato', 'GENERAL']
        ])->union($planes_comerciales_2)->get();

        return $planes_comerciales_3;

    }

}