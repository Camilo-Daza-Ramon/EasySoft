<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InfraestructurasContactos;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class InfraestructurasContactosController extends Controller
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
    public function store(Request $request, $infra)
    {
        if (!Auth::user()->can('infraestructura-contactos-crear')) {
            return abort(403);
        }

        $this->validate($request, [
            'nombre' => 'required',
            'celular' => 'required|digits_between:1,10',
            'telefono' => 'required|digits_between:1,10',
            'cargo_presentativo' => 'required'
        ], [
            'celular.digits_between' => 'El campo :attribute debe contener entre :min y :max dígitos.',
            'telefono.digits_between' => 'El campo :attribute debe contener entre :min y :max dígitos.'
        ]);

        $contacto = new InfraestructurasContactos();
        $contacto->nombre = $request->nombre;
        $contacto->celular = $request->celular;
        $contacto->telefono = $request->telefono;
        $contacto->cargo_presentativo = $request->cargo_presentativo;
        $contacto->infraestructura_id = $infra;

        if ($contacto->save()) {
            return redirect()->route('infraestructuras.show',$infra)->with('success','Contacto agregado correctamente.');
        }else{
            return redirect()->route('infraestructuras.show',$infra)->with('error','Error al agregar el contacto.');
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
    public function edit($infraestructura, $contacto)
    {
        if (!Auth::user()->can('infraestructura-contactos-editar')) {
            return abort(403);
        }
        return response()->json(InfraestructurasContactos::findOrFail($contacto));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $infraestructura, $contacto_id)
    {
        if (!Auth::user()->can('infraestructura-contactos-editar')) {
            return abort(403);
        }

        $contacto = InfraestructurasContactos::findOrFail($contacto_id);
        
        $this->validate($request, [
            'nombre' => 'required',
            'celular' => 'required|digits_between:1,10',
            'telefono' => 'required|digits_between:1,10',
            'cargo_presentativo' => 'required'
        ], [
            'celular.digits_between' => 'El campo :attribute debe contener entre :min y :max dígitos.',
            'telefono.digits_between' => 'El campo :attribute debe contener entre :min y :max dígitos.'
        ]);

        $contacto->nombre = $request->nombre;
        $contacto->celular = $request->celular;
        $contacto->telefono = $request->telefono;
        $contacto->cargo_presentativo = $request->cargo_presentativo;

        if ($contacto->save()) {
            return redirect()->route('infraestructuras.show',$infraestructura)->with('success','Contacto actualizado correctamente.');
        }else{
            return redirect()->route('infraestructuras.show',$infraestructura)->with('error','Error al actualizar el contacto.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($infraestructura, $contacto_id)
    {
        if (!Auth::user()->can('infraestructura-contactos-eliminar')) {
            return abort(403);
        }

        $contacto = InfraestructurasContactos::findOrFail($contacto_id);

        if ($contacto->delete()) {
            return redirect()->route('infraestructuras.show',$infraestructura)->with('success','Contacto eliminado correctamente.');
        }else{
            return redirect()->route('infraestructuras.show',$infraestructura)->with('error','Error al eliminar el contacto.');
        }
    }
}
