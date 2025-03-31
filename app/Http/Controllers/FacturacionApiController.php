<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\FacturacionElectronicaAPI;

class FacturacionApiController extends Controller
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
        if (Auth::user()->can('facturacion-electronica-api-crear')) {

            $this->validate(request(),[
                'url' => 'required',
                'token' => 'required',
                'controlador' => 'required',
                'accion' => 'required',
                'proyecto_id' => 'required'                
            ]);

            $api = new FacturacionElectronicaAPI;
            $api->url_api = $request->url;
            $api->token_identificador = $request->token;
            $api->controlador = $request->controlador;
            $api->accion = $request->accion;
            $api->proyecto_id = $request->proyecto_id;

            if ($api->save()) {
                return redirect()->route('proyectos.show',$request->proyecto_id)->with('success','API agregada correctamente!');
            }else{
                return redirect()->route('proyectos.show',$request->proyecto_id)->with('error','Error al crear el API!');
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
    public function edit($id)
    {
        
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
        if (Auth::user()->can('facturacion-electronica-api-editar')) {

            $this->validate(request(),[
                'url' => 'required',
                'token' => 'required',
                'controlador' => 'required',
                'accion' => 'required',
                'proyecto_id' => 'required'               
            ]);

            $api = FacturacionElectronicaAPI::find($id);
            $api->url_api = $request->url;
            $api->token_identificador = $request->token;
            $api->controlador = $request->controlador;
            $api->accion = $request->accion;
            $api->proyecto_id = $request->proyecto_id;

            if ($api->save()) {
                return redirect()->route('proyectos.show',$request->proyecto_id)->with('success','API actualizada correctamente!');
            }else{
                return redirect()->route('proyectos.show',$request->proyecto_id)->with('error','Error al actulizar el API!');
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
    public function destroy($id)
    {
        if (Auth::user()->can('facturacion-electronica-api-eliminar')) {
            $api = FacturacionElectronicaAPI::findOrFail($id);

            $resultado = $api->delete();
            return response()->json($resultado);

        }else{
            abort(403);
        }
    }
}
