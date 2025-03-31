<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TicketPrueba;

class TicketsPruebasController extends Controller
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $ticket)
    {
        if (Auth::user()->can('tickets-pruebas-crear')){

            $ticket_pruebas = new TicketPrueba;
            $ticket_pruebas->TicketId = $ticket;
            $ticket_pruebas->PruebaId = $request->prueba;
            $ticket_pruebas->Observacion = $request->observacion;
            $ticket_pruebas->Fecha = $request->fecha;
            $ticket_pruebas->Hora = $request->hora;

            if ($ticket_pruebas->save()) {
                return redirect()->route('tickets.edit',$ticket)->with('success', 'Prueba agregada satisfactoriamente!');
            }else{
                return redirect()->route('tickets.edit',$ticket)->with('error', 'Error al agregar la prueba!');
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
    public function destroy($ticket,$id)
    {
        if (Auth::user()->can('tickets-pruebas-eliminar')){

            $prueba = TicketPrueba::findOrFail($id);

            if ($prueba->delete()) {
                return redirect()->route('tickets.edit',$ticket)->with('success', 'Prueba eliminada satisfactoriamente!');
            }else{
                return redirect()->route('tickets.edit',$ticket)->with('error', 'Error al aliminar la prueba!');
            }
        }else{
            abort(403);
        }
    }
}
