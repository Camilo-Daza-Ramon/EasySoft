<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MantenimientoCliente;
use App\MantenimientoPreventivo;
use App\Cliente;
use App\Mantenimiento;
use App\Novedad;

class MantenimientosClientesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $mantenimiento)
    {
        if (Auth::user()->can('mantenimientos-clientes-crear')) {
            $this->validate(request(),[
                'tipo' => 'required',
                'mantenimiento_tipo' => 'required'
            ]);

            $mant = null;
            $link = null;
            $municipio = null;

            if($request->mantenimiento_tipo == 'PREVENTIVO'){
                $mant = MantenimientoPreventivo::findOrFail($mantenimiento);
                $link = "preventivos";
                $municipio = $mant->Municipio;
            }else{
                $mant = Mantenimiento::findOrFail($mantenimiento);
                $link = "correctivos";
                $municipio = $mant->MunicipioId;
            }

            if ($mant->estado == 'CERRADO') {

                return redirect()->route($link.'.show',$mantenimiento)->with('error','No es posible agregar los clientes a un mantenimiento que ya se ha cerrado');
            }else{
            
                //if ((($mant->TipoMantenimiento == 'COR' || $mant->TipoMantenimiento == 'REDA') && (count($mant->clientes) == 0 )) || ($mant->TipoMantenimiento != 'COR' && $mant->TipoMantenimiento != 'REDA')) {
                if(isset($mant->ClienteId)){

                    if(!empty($mant->ClienteId)){
                        return redirect()->route($link.'.show',$mantenimiento)->with('error','No es posible agregar los clientes a un mantenimiento del cliente. El mantenimiento ya cuenta con el cliente asignado.');
                    }
                }

                $clientes = array();
                $contador = 0;
                $total_cedulas = 0;

                switch ($request->tipo) {
                    case 'INDIVIDUAL':
                        $cedulas = array(intval($request->documento));
                        
                        $clientes = $this->validar_cliente($cedulas, $municipio, $mantenimiento, $request->mantenimiento_tipo);
                        $total_cedulas = 1;
                        break;
                    case 'MASIVO':
                        $cedulas = explode(",", $request->cedulas);
                        $total_cedulas = count(explode(",", $request->cedulas));

                        if ($total_cedulas <= 1) {
                            return redirect()->route($link.'.show',$mantenimiento)->with('warning','El formulario masivo es para más de una cédula.');
                        }

                        $clientes = $this->validar_cliente($cedulas, $municipio, $mantenimiento, $request->mantenimiento_tipo);
                        
                        break;
                    
                    default:
                        // code...
                        break;
                }

                if (count($clientes) > 0) {
                    foreach ($clientes as $dato) {
                        $resultado = $this->add_cliente($mantenimiento, $dato->ClienteId, $request->mantenimiento_tipo);
                        if ($resultado) {
                            $contador +=1;
                        }
                    }

                    return redirect()->route($link.'.show',$mantenimiento)->with('success','Se agregaron '.$contador. ' registros de '. $total_cedulas);
                }else{
                    return redirect()->route($link.'.show',$mantenimiento)->with('warning','No hay clientes para agregar.');
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
    public function destroy($mantenimiento, $id)
    {
        if (Auth::user()->can('mantenimientos-clientes-eliminar')) {
            $mc = MantenimientoCliente::findOrFail($id);
            
            if ($mc->delete()) {

                $link= null;

                if(!empty($mc->ProgMantId)){
                    $link= "preventivos";
                }else{
                    $link= "correctivos";
                }
                
                return redirect()->route($link.'.show',$mantenimiento)->with('success','Cliente eliminado correctamente.');
            }else{
                return redirect()->route($link.'.show',$mantenimiento)->with('error','Error al eliminar el cliente del mantenimiento.');
            }

        }else{
            abort(403);
        }
    }

    private function add_cliente($mantenimiento, $cliente_id, $tipo){

        $mantenimientos_clientes = new MantenimientoCliente;
        $mantenimientos_clientes->ClienteId = $cliente_id;

        if($tipo == 'PREVENTIVO'){
            $mantenimientos_clientes->ProgMantId = $mantenimiento;
        }else{
            $mantenimientos_clientes->Mantid = $mantenimiento;
        }

        if ($mantenimientos_clientes->save()) {
            return true;
        }else{
            return false;
        }
    }

    private function validar_cliente($cedulas, $municipio, $mantenimiento, $tipo){
        $clientes = Cliente::select('Clientes.ClienteId')
                    ->leftJoin('MantenimientoProgramacionClientes', function($join) use ($mantenimiento, $tipo) {
                        $join->on('Clientes.ClienteId','=','MantenimientoProgramacionClientes.ClienteId')
                        //->where('MantenimientoProgramacionClientes.Mantid', '=', $mantenimiento);
                        ->where(function($query) use($mantenimiento, $tipo){
                            if($tipo == 'PREVENTIVO'){
                                $query->where('MantenimientoProgramacionClientes.ProgMantId', '=', $mantenimiento);
                            }else{
                                $query->where('MantenimientoProgramacionClientes.Mantid', '=', $mantenimiento);
                            }
                        });
                    })
                    ->whereNull('MantenimientoProgramacionClientes.ClienteId')
                    ->whereIn('Clientes.Identificacion', $cedulas)
                    ->where([['Clientes.Status', 'ACTIVO'], ['Clientes.municipio_id', $municipio]])
                    ->get();

        return $clientes;
    }
}
