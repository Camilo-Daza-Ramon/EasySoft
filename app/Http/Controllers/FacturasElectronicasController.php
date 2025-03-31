<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FacturaElectronica;

class FacturasElectronicasController extends Controller
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
        $facturacionE = FacturaElectronica::where('FacturaId', $request->factura_id)->first();

        if (empty($facturacionE)){
            $facturacionE = new FacturaElectronica();
            $facturacionE->FacturaId = $request->factura_id;
            $facturacionE->numero_factura_dian = $request->numero_factura_dian;
            $facturacionE->documento_id_feel = $request->documento_id_feel;
            $facturacionE->archivo = $request->archivo;
            //$facturacionE->fecha_reporte = date('Y-m-d');
        }else{

            if (empty($facturacionE->numero_factura_dian)) {
                $facturacionE->numero_factura_dian = $request->numero_factura_dian;
            }

            if (empty($facturacionE->documento_id_feel)) {
                $facturacionE->documento_id_feel = $request->documento_id_feel;
            }

            if (empty($facturacionE->archivo)) {
                $facturacionE->archivo = $request->archivo;
            }
        }

        
        $facturacionE->reportada = $request->reportada;        
        
        $resultado = $facturacionE->save();

        return response()->json(array('result' => $resultado, 'id' => $facturacionE->id));
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
