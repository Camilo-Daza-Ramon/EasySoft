<?php

namespace App\Http\Controllers;

use App\AcuerdoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\CuotaAcuerdoPago;
use Illuminate\Support\Facades\DB;


class AcuerdosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        //
        $coutas = CuotaAcuerdoPago::select('cuotas_acuerdo_pago.id','cuotas_acuerdo_pago.valor_pagar','r.valor')
            ->join('acuerdos_pago AS ap', 'cuotas_acuerdo_pago.acuerdo_id', 'ap.id')
            ->join('ClientesRecaudos AS r' , 'ap.cliente_id', 'r.ClienteId')
            ->where([['cuotas_acuerdo_pago.estado','PENDIENTE'],['r.Fecha','2023-07-03 11:20:09']])->get();

        
        if (Auth::user()->can('acuerdos-pago-listar')) {

            $acuerdos = AcuerdoPago::Documento($request->get('documento'))
                                    ->Estado($request->get('estado'))
                                    ->paginate(15);
            $estados = [
                'ACTIVO',
                'FINALIZADO'
            ];
            return view('adminlte::acuerdos.index',compact(
                'acuerdos',
                'estados'
            ));
            
        }else{
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
        if (Auth::user()->can('acuerdos-pago-crear')) {

           
            return view('adminlte::acuerdos.create');
            
        }else{
            abort(403);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (Auth::user()->can('acuerdos-pago-crear')) {

            $this->validate(request(),[
                'cliente_id' => 'required',
                'deuda' => 'required',
                'cuotas' => 'required',            
                'valor_inicial' => 'required',
                'valor_perdonar' => 'required',
                'fecha_pago' => 'required',
                'valor_pagar' => 'required',
                'perdonar_porcentual' => 'required'
            ]); 


            $acuerdo = new AcuerdoPago;
            $acuerdo->valor_deuda = $request->deuda;
            $acuerdo->total_cuotas = $request->cuotas;           
            $acuerdo->valor_perdonar = $request->descontado;           
            $acuerdo->descripcion = $request->descripcion;
            $acuerdo->estado = 'ACTIVO';
            $acuerdo->tipo_descuento = $request->perdonar_porcentual;
            $acuerdo->descuento = $request->valor_perdonar;
            $acuerdo->cliente_id = $request->cliente_id;

            if($acuerdo->save()){
                $cuotaN = 0;
                foreach ($request->valor_pagar as $key => $valor) {
                    $cuotaN += 1;
                    $cuota = new CuotaAcuerdoPago;
                    $cuota->acuerdo_id = $acuerdo->id;
                    $cuota->cuota = $cuotaN;
                    $cuota->fecha_pago = $request->fecha_pago[$key];
                    $cuota->valor_pagar = floatval($valor);
                    $cuota->estado = 'PENDIENTE';

                    if(!$cuota->save()){
                        DB::rollBack();
                        return redirect()->route('acuerdos.index')->with('error', 'Error. Al crear cuotas.');
                    }
                }

                return redirect()->route('acuerdos.index')->with('success', 'Acuerdo de pago creado correctamente.');

            }else{
                DB::rollBack();
                return redirect()->route('acuerdos.index')->with('error', 'Error. Al crear el acuerdo.');
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
        if (Auth::user()->can('acuerdos-pago-ver')) {

            //consulto las respuestas y observaciones del cliente      
            $acuerdo = AcuerdoPago::findOrFail($id);
            $acuerdo_pago = array();   

            if(count($acuerdo->cuotas_acuerdo) > 0){

                $acuerdo_pago['cuotas'] = $acuerdo->cuotas_acuerdo;
                           
            }else{
                $acuerdo_pago['cuotas'] = 0 ; 
            }        
                     
            $array = array();
            $array['nombre_cliente'] = $acuerdo->cliente->NombreBeneficiario; 
            $array['apellidos_cliente'] = $acuerdo->cliente->Apellidos;
            $array['identificacion'] = $acuerdo->cliente->Identificacion;
            $array['contacto'] = $acuerdo->cliente->TelefonoDeContactoMovil;
            $array['correo'] = $acuerdo->cliente->CorreoElectronico;
            $array['proyecto'] = $acuerdo->cliente->proyecto->NumeroDeProyecto;
            $array['estado_cliente'] = $acuerdo->cliente->Status;
            $array['tarifa'] = $acuerdo->cliente->ValorTarifaInternet;
            $array['deuda'] = $acuerdo->valor_deuda;
            $array['estado_acuerdo'] = $acuerdo->estado;
            $array['tipo_descuento'] = $acuerdo->tipo_descuento;
            $array['valor_perdonado'] = $acuerdo->valor_perdonar;
            $array['descuento'] = $acuerdo->descuento;
            $array['descripcion'] = $acuerdo->descripcion;

            
            
            $acuerdo_pago['datos'] = $array;  
        
            return response()->json($acuerdo_pago);

        }else{
            abort(403);
        }
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

    public function ajax(Request $request){

        if ($request->ajax()) {
           
            $data = Cliente::where('Identificacion', $request->cedula)->first();

            if($data != null){

                $acuerdos_activos = AcuerdoPago::where([['cliente_id',$data->ClienteId],['estado','ACTIVO']])->count();

                if($acuerdos_activos == 0){
                    $cliente = array();

                    if (count($data) > 0) { 

                    
                        $cliente['id'] = $data->ClienteId;
                        $cliente['cedula'] = $data->Identificacion;
                        $cliente['nombre'] = mb_convert_case($data->NombreBeneficiario . ' ' . $data->Apellidos, MB_CASE_TITLE, "UTF-8");
                        $cliente['correo'] = $data->CorreoElectronico;
                        $cliente['direccion'] = $data->DireccionDeCorrespondencia.' - '. $data->Barrio . ' - ' . $data->municipio->NombreMunicipio.' - '. $data->municipio->NombreDepartamento;
                        $cliente['municipio'] = $data->municipio->NombreMunicipio;
                        $cliente['departamento'] = $data->municipio->NombreDepartamento;
                        $cliente['proyecto'] = $data->proyecto->NumeroDeProyecto;
                        $cliente['estado'] = $data->Status;
                        $cliente['telefono'] = $data->TelefonoDeContactoMovil;
                        $cliente['ValorTarifaInternet'] = $data->ValorTarifaInternet;
                        $cliente['total_deuda'] =  (empty($data->historial_factura_pago) ? 0 : $data->historial_factura_pago->total_deuda);
                    }

                    return response()->json($cliente);
                }else{
                
                    $data = false;
                    return response()->json($data);
                }
            }else{
                $data = 'null';
                return response()->json($data);
            }
        }
    }

    public function crear_ajax(Request $request){

        $result = DB::transaction(function () use($request ) {
            
            $acuerdo = new AcuerdoPago;
            $acuerdo->valor_deuda = $request->deuda;
            $acuerdo->total_cuotas = $request->cuotas;
            $acuerdo->valor_perdonar = $request->descontado;
            $acuerdo->descuento = $request->valor_perdonar;
            $acuerdo->tipo_descuento = $request->perdonar_porcentual;
            $acuerdo->descripcion = $request->descripcion;
            $acuerdo->estado = 'ACTIVO';
            $acuerdo->cliente_id = $request->cliente_id;

            if($acuerdo->save()){
                $cuotaN = 0;
                foreach ($request->valor_pagar as $key => $valor) {
                    $cuotaN += 1;
                    $cuota = new CuotaAcuerdoPago;
                    $cuota->acuerdo_id = $acuerdo->id;
                    $cuota->cuota = $cuotaN;
                    $cuota->fecha_pago = $request->fecha_pago[$key];
                    $cuota->valor_pagar = floatval($valor);
                    $cuota->estado = 'PENDIENTE';
    
                    if(!$cuota->save()){
                        DB::rollBack();
                        return ['error', 'Error. Al crear cuotas..'];
                    }
                }
                return ['success', 'Acuerdo de pago creado correctamente.'];
    
            }else{
                DB::rollBack();
                return ['error', 'Error. Al crear el acuerdo.'];
            }
        });
        
        return response()->json(['result' => $result[0],'mensaje' => $result[1]]);

        
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
