<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RespuestaEncuestaCliente;
use App\RespuestaEncuestaTotalV;
use App\Departamento;
use Excel;

class RespuestasEncuestasClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('encuestas-respuestas-listar')) {
            //$respuestas = RespuestaEncuestaCliente::paginate(15);
            $respuestas = RespuestaEncuestaTotalV::
            Buscar($request->get('palabra'))
            ->Fecha($request->get('desde'), $request->get('hasta'))
            ->Tipo($request->get('tipo'))
            ->Municipio($request->get('municipio'))
            ->orderBy('fecha', 'DESC')
            ->paginate(15);

            $departamentos = Departamento::where('Status', 'A')->orderBy('NombreDelDepartamento', 'ASC')->get();
            $tipos = ['LLAMADA','PUNTO FISICO'];

            return view('adminlte::atencion-clientes.respuestas.index', compact('respuestas','departamentos','tipos'));
        }else{
            abort(403);
        }

        /*SELECT rec.id, ac.identificacion as cedula, encuesta_satisfaccion_id as pregunta, respuesta, rec.created_at as fecha, CONVERT(VARCHAR(50), encuesta_satisfaccion_id) as identificador, '0' as telefono, 'ATENCION' as 'tipo'
        FROM [Sisteco].[dbo].[respuestas_encuestas_clientes] rec
        INNER JOIN atencion_clientes as ac ON rec.atencion_cliente_id = ac.id
        UNION
        SELECT id, cedula, pregunta, respuesta, fecha_hora, llamada_id, telefono, 'LLAMADA' as 'tipo'
        FROM respuestas_telefonicasV*/
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
        //
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


    public function exportar(Request $request){

        
        if (Auth::user()->can('encuestas-respuestas-exportar')) {

            Excel::create('respuestas-encuentas', function($excel) use($request) {
     
                $excel->sheet('Respuestas-Encuentas', function($sheet) use($request) {

                    $datos = "";

                    $respuestas = RespuestaEncuestaTotalV::
                    Buscar($request->get('palabra'))
                    ->Fecha($request->get('desde'), $request->get('hasta'))
                    ->Tipo($request->get('tipo'))
                    ->Municipio($request->get('municipio'))
                    ->orderBy('fecha', 'DESC')
                    ->get();

                    foreach ($respuestas as $key) {


                        $datos[] = array(
                            'ID' => $key->id,
                            'CEDULA' => $key->cedula,
                            'MUNICIPIO' => (count($key->cliente) > 0)? $key->cliente->municipio->NombreMunicipio : (($key->tipo == 'ATENCION')? $key->atencion->municipio->NombreMunicipio : ''),
                            'FECHA' => $key->fecha,
                            'TIPO ATENCION' => ($key->tipo == 'ATENCION')? 'PUNTO FISICO' : 'LLAMADA',
                            'IDENTIFICADOR' => $key->identificador,
                            'TELEFONO' => $key->telefono,
                            'PREGUNTA' => $key->encuesta->descripcion,
                            'CALIFICACION' => $key->respuesta,

                        );
                    }


                    if (count($datos) == 0) {
                        return redirect()->route('respuestas.index')->with('warning', 'No hay datos para exportar.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos);
     
                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }
}
