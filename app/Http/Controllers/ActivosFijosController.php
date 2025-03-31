<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ActivoFijo;
use App\Insumo;

class ActivosFijosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request,$insumo)
    {
        if (Auth::user()->can('inventarios-activos_fijos-crear')) {

            $validar = ActivoFijo::where('Serial', $request->serial)->count();

            if ($validar > 0) {
                return redirect()->route('inventarios.insumos.show', $insumo)->with('warning', 'El serial ya existe en el inventario!');
            }else{

                $inventario = new ActivoFijo;
                $inventario->EmpresaId = 1;
                $inventario->UbicacionId = 1130;
                $inventario->InsumoId = $insumo;
                $inventario->Grupo = 2;
                $inventario->SubGrupo = 24;
                $inventario->Descripcion = "ONT";
                $inventario->FechaDeAdquisicion = date('Y-m-d');
                $inventario->Proveedor = 8;
                $inventario->Marca = $request->marca; //"N.A";
                $inventario->Serial = $request->serial;
                $inventario->Referencia = $request->referencia;
                $inventario->Modelo = $request->modelo;// "ONU GPON HG8546M";
                $inventario->Año = date('Y');
                $inventario->Cantidad = 1;
                $inventario->Estado = "DISPONIBLE";
                $inventario->PerteneceAlEstado = "N";
                $inventario->AlmEntradaId = 38;
                $inventario->EstanteId = "PISO";
                $inventario->Prendio = 0;
                $inventario->SistemaOperativo = 0;
                $inventario->SeConecta = 0;
                $inventario->DocumentosEntregaCompletos = 0;
                $inventario->Logo = 0;
                $inventario->Serigrafia = 0;
                $inventario->AlmacenId = 2;

                if ($inventario->save()) {
                    return redirect()->route('inventarios.insumos.show', $insumo)->with('success', 'Serial Agregado correctamente');
                }else{
                    return redirect()->route('inventarios.insumos.show', $insumo)->with('error', 'Error al guardar la información.');
                }
            }


        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($insumo,$id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($insumo,$id)
    {
        if (Auth::user()->can('inventarios-activos_fijos-editar')) {

           $activos = ActivoFijo::select('Marca', 'Serial', 'Referencia', 'Modelo', 'Estado')->findOrFail($id);            
           return response()->json(array('activos' => $activos));

        }else{
          abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $insumo, $id)
    {
        if (Auth::user()->can('inventarios-activos_fijos-editar')) {

            $validar = ActivoFijo::where('Serial', $request->serial)->whereNotIn('ActivoFijoId', [$id])->count();

            if ($validar > 0) {
                return redirect()->route('inventarios.insumos.show', $insumo)->with('warning', 'El serial ya existe en el inventario!');
            }else{

                $inventario = ActivoFijo::find($id);

                if(!empty($inventario->cliente_ont_olt)){
                    return redirect()->route('inventarios.insumos.show', $insumo)->with('warning', 'Violación de restricción.');
                }
                
                $inventario->Marca = $request->marca; //"N.A";
                $inventario->Serial = $request->serial;
                $inventario->Referencia = $request->referencia;
                $inventario->Modelo = $request->modelo;// "ONU GPON HG8546M";                
                $inventario->Estado = $request->estado;                

                if ($inventario->save()) {
                    return redirect()->route('inventarios.insumos.show', $insumo)->with('success', 'Activo actualizado correctamente');
                }else{
                    return redirect()->route('inventarios.insumos.show', $insumo)->with('error', 'Error al actualizar la información.');
                }
            }


        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($insumo, $id)
    {
        //
    }

    public function traer_ont(Request $request){
        if ($request->ajax()) {

            /*$inventario = ActivoFijo::select('ActivoFijoId','Descripcion','Modelo', 'Serial', 'Estado')->where([['Serial', $request->serial], ['Estado', 'DISPONIBLE']])->get();*/

            $inventario = ActivoFijo::select('ActivoFijoId','Descripcion','Modelo', 'Serial', 'Estado')->where('Serial', $request->serial)->first();

            if (empty($inventario)) {
                return response()->json(['resultado' => 'No existe el serial indicado.']);
            }elseif ($inventario->Estado == 'ASIGNADA') {
                if(!empty($inventario->cliente_ont_olt)){
                    return response()->json(['resultado' => 'El serial se encuentra ' . $inventario->Estado .' al cliente con cedula: ' . $inventario->cliente_ont_olt->cliente->Identificacion]);
                }else{

                    return response()->json(['resultado' => 'El serial se encuentra ' . $inventario->Estado]);
                }
                
            }elseif ($inventario->Estado <> 'ASIGNADA' && $inventario->Estado <> 'DISPONIBLE') {
                return response()->json(['resultado' => 'El serial se encuentra ' . $inventario->Estado]);
            }

            return response()->json(['inventario' =>$inventario, 'resultado' => true]);
        } 
    }

    /** VERIFICA SI EL ACTIVO FIJO TRAIDO DE 'traer_ont' PERTENECE AL INSUMO ENVIADO EN EL REQUEST */
    public function validar_equipo_insumo(Request $request){
        
        $resultado = $this->traer_ont($request)->getData(true);
        if (!isset($resultado['resultado']) || !$resultado['resultado'] || is_string($resultado['resultado'])) {
            return response()->json($resultado);
        } else {
            $insumo = Insumo::findOrFail($request->codigo_insumo);
            $seriales = $insumo->activo_fijo->pluck('Serial')->toArray();
            if (!in_array($request->serial, $seriales)) {
                return response()->json(['resultado' => 'El serial no pertenece a ese insumo.']); 
            }
            return response()->json(['resultado' => in_array($request->serial, $seriales)]); 
        }
    }
}
