<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SolicitudComentario;


class SolicitudesComentariosController extends Controller
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
     * @param  int  $solicitud
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $solicitud)
    {
      if (Auth::user()->can('solicitudes-comentarios-crear')) {
        $this->validate(request(),[
            'comentario' => 'required'
        ]);

        $comentario = new SolicitudComentario;
        $comentario->comentario = $request->comentario;
        $comentario->solicitud_id = $solicitud;
        $comentario->user_id = Auth::user()->id;

        if ($comentario->save()) {
          return redirect()->route('solicitudes.show',$solicitud)->with('success','Comentario agregado correctamente!');
        }else{
          return redirect()->route('solicitudes.show',$solicitud)->with('error', 'Error al agregar el comentario');
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
}
