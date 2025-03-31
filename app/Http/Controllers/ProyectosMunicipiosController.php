<?php

namespace App\Http\Controllers;

use App\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ProyectoMunicipio;
use App\ProyectoMunicipioMeta;

class ProyectosMunicipiosController extends Controller
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
    public function store(Request $request)
    {
        if (Auth::user()->can('proyectos-municipios-crear')) {

            $this->validate(request(),[
                'proyecto_id' => 'required',
                'municipio' => 'required'                
            ]);

            $validar = ProyectoMunicipio::select('proyectos_municipios.*', 'proyectos_municipios_metas.meta_id')->where([
                ['proyecto_id', $request->proyecto_id],
                ['municipio_id', $request->municipio]
            ])
            ->leftJoin('proyectos_municipios_metas', function($join) use ($request) {
                $join->on('proyectos_municipios.id','=','proyectos_municipios_metas.proyecto_municipio_id')
                ->where('proyectos_municipios_metas.meta_id', '=', $request->meta);
            })->first();

            if (!empty($validar->meta_id)) {
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('warning','El municipio que desea agregar ya se encuentra asignado!');
            }

            $result = DB::transaction(function () use($request, $validar) {

                $municipio = null;

                if(!empty($validar)){
                    $municipio = ProyectoMunicipio::find($validar->id);
                }else{
                    $municipio = new ProyectoMunicipio;
                    $municipio->proyecto_id = $request->proyecto_id;
                    $municipio->municipio_id = $request->municipio;
                }                
                

                if ($municipio->save()) {

                    if (!empty($request->meta)) {
                        $this->validate(request(),[
                            'total_accesos' => 'required'                
                        ]);

                        $meta = new ProyectoMunicipioMeta;
                        $meta->meta_id = $request->meta;
                        $meta->proyecto_municipio_id = $municipio->id;
                        $meta->total_accesos = $request->total_accesos;

                        if (!$meta->save()) {
                            DB::rollBack();
                            return ['error', 'No se pudo agregar la Meta!'];
                        }
                    }

                    return ['success', 'Municipio Agregado correctamente!'];


                }else{
                    DB::rollBack();
                    return ['error', 'No se agregó el municipio.'];
                }
            });


            return redirect()->route('proyectos.show', $request->proyecto_id)->with($result[0],$result[1]);

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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $meta_id = null)
    {
        if (Auth::user()->can('proyectos-municipios-editar')) {            

            $municipios = ProyectoMunicipio::select('proyectos_municipios.id', 'municipio_id', 'DeptId', 'proyectos_municipios_metas.id as pmm_id', 'meta_id', 'total_accesos')
            ->join('Municipios', 'proyectos_municipios.municipio_id', 'Municipios.MunicipioId')
            ->leftJoin('proyectos_municipios_metas', function($join) use($meta_id){
                
                if(isset($meta_id) && !empty($meta_id) && $meta_id != null){

                    $join->on('proyectos_municipios.id', 'proyectos_municipios_metas.proyecto_municipio_id')->where('proyectos_municipios_metas.id', $meta_id);
                }else{
                    $join->on('proyectos_municipios.id', 'proyectos_municipios_metas.proyecto_municipio_id');
                }
            })
            ->findOrFail($id);            

            return response()->json(['municipios' => $municipios]);

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
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('proyectos-municipios-editar')) {

            $this->validate(request(),[
                'municipio' => 'required'
            ]);


            $result = DB::transaction(function () use($request, $id) {

                $municipio_meta = null;

                $municipio_meta =  ProyectoMunicipioMeta::where('proyecto_municipio_id', $id)
                ->where(function($query)use($request){

                    if(isset($request->meta_id)){

                        if(!empty($request->meta_id) && $request->meta_id != null){
                            $query->where('id', $request->meta_id);
                        }
                    }

                })->first();

                if(empty($municipio_meta)){
                    $municipio_meta =  new ProyectoMunicipioMeta;
                    $municipio_meta->proyecto_municipio_id = $id;

                }

                $municipio_meta->meta_id = $request->meta;
                $municipio_meta->total_accesos = $request->total_accesos;


                if ($municipio_meta->save()) {

                    return ['success', 'Información actualizada correctamente!'];


                }else{
                    DB::rollBack();
                    return ['error', 'No se pudo actualizar'];
                }
            });


            return redirect()->route('proyectos.show', $request->proyecto_id)->with($result[0],$result[1]);

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
    public function destroy($id, Request $request)
    {
        if (Auth::user()->can('proyectos-municipios-eliminar')) {

            $municipio = ProyectoMunicipio::findOrFail($id);
            $result = false;

            $proyecto_id = $municipio->proyecto_id;

            if(isset($request->meta_id)){

                if(!empty($request->meta_id) && $request->meta_id != null){                   

                    $proyecto_municipio_meta = ProyectoMunicipioMeta::findOrFail($request->meta_id);

                    $result = $proyecto_municipio_meta->delete();
                }
            }else{
                $municipio->meta()->delete();
                $result = $municipio->delete();
            }

            if($result){
                return redirect()->route('proyectos.show', $proyecto_id)->with('success','El municipio se eliminó correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto_id)->with('error','Error al eliminar el municipio!');
            }

        }else{
            abort(403);
        }
    }

    public function ajax(Request $request){
        if($request->ajax()){
            $municipios = ProyectoMunicipio::select('proyectos_municipios.id','proyectos_municipios.municipio_id','m.NombreMunicipio as nombre')
            ->join('Municipios as m', function($join) use ($request) {
                $join->on('proyectos_municipios.municipio_id','=','m.MunicipioId')
                ->where([['proyectos_municipios.proyecto_id', $request->proyecto_id], ['m.DeptId', $request->departamento_id]]);
            })->get();
            
            return response()->json($municipios);
        }
    }


    /** Traer municipios por proyecto */
    public function municipiosProyecto($id){
        return response()->json([
            'municipios' => Proyecto::with("municipio")->where('ProyectoID', '=', $id)->firstOrFail()
        ]);
    }
}

