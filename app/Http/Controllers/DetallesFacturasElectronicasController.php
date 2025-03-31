<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetalleFacturaElectronica;

class DetallesFacturasElectronicasController extends Controller
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

        $facturacionE_detalles = new DetalleFacturaElectronica();        
        $facturacionE_detalles->factura_electronica_id = $request->factura_electronica_id;
        $facturacionE_detalles->fecha = (empty($request->fecha))? date('Y-m-d h:i:s') : $request->fecha;
        $facturacionE_detalles->concepto = $request->concepto;
        $facturacionE_detalles->detalles = $request->detalles;
        $resultado = $facturacionE_detalles->save();

        return response()->json(array('result' => $resultado));
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
