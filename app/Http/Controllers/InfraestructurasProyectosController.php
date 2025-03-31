<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infraestructura;
use Illuminate\Support\Facades\Auth;

class InfraestructurasProyectosController extends Controller
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
    public function store(Request $request, $infraestructura)
    {
        if (!Auth::user()->can('infraestructura-proyectos-crear')){
            return abort(403);
        }

        $this->validate($request, [
            'proyectos.*' => 'required',
        ]);

        try {
            $infra = Infraestructura::findOrFail($infraestructura);

            $infra->proyectos()->attach($request->proyectos);

            return redirect()->route('infraestructuras.show', $infraestructura)->with('success', 'Proyectos asociados correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('infraestructuras.show', $infraestructura)->with('error', 'Problemas al asociar los proectos.');
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
    public function destroy($infraestructura, $proyecto)
    {
        if (!Auth::user()->can('infraestructura-proyectos-eliminar')) {
            return abort(403);
        }

        try {
            $infra = Infraestructura::findOrFail($infraestructura);

            $infra->proyectos()->detach($proyecto);

            return redirect()->route('infraestructuras.show', $infraestructura)->with('success', 'Proyecto desasociados correctamente.');
        } catch (\Throwable $th) {
            return redirect()->route('infraestructuras.show', $infraestructura)->with('error', 'Problemas al desasociar los proectos.');
        }
    }
}
