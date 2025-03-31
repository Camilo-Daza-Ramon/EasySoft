<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Insumo;
use App\Departamento;
use App\Proyecto;
use App\ActivoFijo;
use Excel;

class InsumosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('inventarios-ver')) {
            $insumos = Insumo::withCount('activo_fijo')->Buscar($request->get('palabra'))->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
                ->where(function ($query) {
                    if (Auth::user()->proyectos()->count() > 0) {
                        $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                    }
                })
                ->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

            $estados = ActivoFijo::groupBy('Estado')->pluck('Estado');

            return view('adminlte::inventarios.insumos.index', compact('insumos', 'proyectos', 'departamentos', 'estados'));
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);
        $activos_fijos = $insumo->activo_fijo()->Buscar($request->get('serial'))->paginate(15);
        $estados = ['DISPONIBLE', 'ASIGNADA'];
        return view('adminlte::inventarios.insumos.show', compact('insumo', 'activos_fijos', 'estados'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function exportar()
    {
        if (!Auth::user()->can('inventarios-exportar')) {
            abort(403);
            return;
        }

        Excel::create('inventario-insumos', function ($excel) {
            $excel->sheet('FINDETER-23', function ($sheet) {
                $datos = $this->generarHojaFindeter(12)->toArray();
                $datos = json_decode(json_encode($datos), true);

                $sheet->fromArray($datos, true, 'A1', true);
            });

            $excel->sheet('FINDETER-27', function ($sheet) {
                $datos = $this->generarHojaFindeter(13)->toArray();
                $datos = json_decode(json_encode($datos), true);

                $sheet->fromArray($datos, true, 'A1', true);
            });

            $excel->sheet('Activos Fijos', function ($sheet) {
                $now = date('Y-m-d');
                $datos = ActivoFijo::selectRaw(
                    "'' as Identificacion,
                    '' as municipio,
                    '' as ClienteId,
                    1 as Cantidad,
                    ActivosFijos.Descripcion,
                    ActivosFijos.Marca,
                    ActivosFijos.Modelo,
                    ActivosFijos.Serial,
                    ActivosFijos.MAC,
                    '' as ip"
                    )   
                    ->leftJoin('instalaciones as i', 'ActivosFijos.ActivoFijoId', '=', 'i.activo_fijo_id')
                    ->whereBetween('ActivosFijos.FechaDeAdquisicion', ['2024-08-01', $now])
                    ->whereNull('i.activo_fijo_id')
                    ->distinct()
                    ->get()
                    ->toArray();
                $datos = json_decode(json_encode($datos), true);

                $sheet->fromArray($datos, true, 'A1', true);
            });
        })->export('xlsx');
    }

    private function generarHojaFindeter($proyecto_id)
    {
        $now = date('Y-m-d');
        return ActivoFijo::selectRaw(
            "c.Identificacion, 
            m.NombreMunicipio as municipio,
            c.ClienteId,
            1 as Cantidad,
            ActivosFijos.Descripcion,
            ActivosFijos.Marca,
            ActivosFijos.Modelo,
            ActivosFijos.Serial,
            ActivosFijos.MAC,
            '' as ip"
            )
            ->join('instalaciones as i', 'ActivosFijos.ActivoFijoId', '=', 'i.activo_fijo_id')
            ->join('Clientes as c', function ($join) use ($proyecto_id) {
                $join->on('c.ClienteId', '=', 'i.ClienteId')
                    ->where('c.ProyectoId', '=', $proyecto_id);
            })
            ->join('Municipios as m', 'c.municipio_id', '=', 'm.MunicipioId')
            //->whereBetween('ActivosFijos.FechaDeAdquisicion', ['2024-08-01', $now])
            ->where('c.Status', '=', 'ACTIVO')
            ->distinct()
            ->get();
    }
}
