<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ClienteContrato;
use App\ContratoArchivo;
use App\Cliente;
use App\Custom\ActaNoFirma;
use App\Proyecto;
use App\ProyectoClausula;

use App\Custom\Data;
use App\Traits\Contratos;
use App\Traits\DeclaracionesJuramentadas;

use Carbon\Carbon;

use Storage;
use DB;

class ContratosArchivosController extends Controller
{
    use Contratos, DeclaracionesJuramentadas;
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
        if (Auth::user()->can('contrato-archivo-crear')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'accion' => 'required',
                'contrato_id' => 'required'
            ]);

            $contrato = ClienteContrato::find($request->contrato_id);

            $buscar = ContratoArchivo::where([['contrato_id', $request->contrato_id], ['nombre', $request->nombre]])->count();

            if ($buscar > 0) {
                return redirect()->route('clientes.contratos.show', [$contrato->ClienteId, $request->contrato_id])->with('warning', 'El archivo ya existe.');
            }else{

                $nombre = $request->nombre;
                //Declaramos una ruta
                $directory = 'contratos/' . $request->contrato_id;

                //Si no existe el directorio, lo creamos
                if (!file_exists($directory)) {
                    //Creamos el directorio
                    Storage::disk('public')->makeDirectory($directory);
                }

                if ($request->accion == 'subir') {
                    $this->validate(request(),[
                        'archivo' => 'required|mimes:pdf|max:5000'
                    ]);

                    $file = $request->archivo;

                    if (!empty($file)) {
                        //Obtenemos el tipo de archivo que se esta subiendo
                        $extension = strtolower($file->getClientOriginalExtension());

                        //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                        $documento = $directory.'/'.$nombre.'.'.$extension;

                        //Indicamos que queremos guardar un nuevo archivo en el disco local            
                        Storage::disk('public')->put($documento, \File::get($file));
                    }


                }else{

                    $extension = 'pdf';

                    //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                    $documento = $directory.'/'.$nombre.'.'.$extension;



                    $data = new Data;
                    $data_contrato = $data->contrato($request->contrato_id);

                    $ruta = Storage::disk('public')->path($documento);
                    switch ($request->nombre) {
                        case 'contrato':
                            $this->contrato_generar('F', $data_contrato, $ruta);
                            break;
                        case 'acta_juramentada':
                            $this->declaracion_generar('F', $data_contrato, $ruta);
                            break;
                        case 'constancia_no_firma':
                            $auditoriasController = new AuditoriasController(); 
                            return $auditoriasController->generarActaNoFirmaPDF($contrato->ClienteId);
                            break;
                    }            
                }

                $existe = Storage::disk('public')->exists($documento);

                if ($existe) {

                    $archivo = new ContratoArchivo;
                    $archivo->nombre = $nombre;
                    $archivo->archivo = $documento;
                    $archivo->tipo_archivo = $extension;
                    $archivo->estado = 'APROBADO';
                    $archivo->contrato_id = $request->contrato_id;

                    if ($archivo->save()) {

                        return redirect()->route('clientes.contratos.show', [$contrato->ClienteId, $request->contrato_id])->with('success', 'Archivo subido satisfactoriamente!');
                    }else{
                        return redirect()->route('clientes.contratos.show', [$contrato->ClienteId, $request->contrato_id])->with('error', 'Error al guardar la información en la base.');
                    }
                }else{
                    return redirect()->route('clientes.contratos.show', [$contrato->ClienteId, $request->contrato_id])->with('error', 'El archivo no fue subido.');
                }

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

        /*$contratos = ClienteContrato::select('clientes_contratos.*','cr.fecha_reemplazo','cr.antiguo_cliente_contrato_id')
        ->join('clientes_reemplazos as cr', 'clientes_contratos.ClienteId','=','cr.cliente_nuevo_id')
        ->whereIn('clientes_contratos.id', [
            
        ])->get();

        foreach ($contratos as $contrato) {

            //DB::statement('exec actualizar_vigencia_contrato_soft ?,?', [$contrato->cliente->softv->id_softv, $contrato->vigencia_meses]);

            $fechas_acuerdo_otrosi = ['2022-06-11','2022-11-30'];
            $fecha_limite_contrato = ['2022-12-31','2023-08-31'];

            if($contrato->fecha_reemplazo > '2022-11-01'){ //|| $contrato->fecha_reemplazo > '2022-11-30'){
                //contrato hasta el 31 de agosto 2023
                $date2 = new \DateTime($fecha_limite_contrato[1]);
            }elseif($contrato->fecha_reemplazo >= '2022-06-01'){ //|| $contrato->fecha_reemplazo > '2022-06-11'){
                //contrato hasta el 31 de diciembre 2022
                $date2 = new \DateTime($fecha_limite_contrato[0]);
            }elseif($contrato->fecha_reemplazo >= '2022-04-01'){
                //contrato hasta el 31 de diciembre 2022
                $date2 = new \DateTime($fecha_limite_contrato[0]);
            }elseif($contrato->fecha_reemplazo < '2022-04-01'){
                //contrato 18 meses

                $contrato_antiguo = ClienteContrato::find($contrato->antiguo_cliente_contrato_id);

                $d1 = new \DateTime($contrato_antiguo->fecha_instalacion);
                $d2 = new \DateTime($contrato_antiguo->fecha_final);
                $diferencia_antiguo = $d1->diff($d2);


                $fecha_estimada = date("Y-m-d",strtotime($contrato->fecha_reemplazo."+ 18 month"));
                $fecha_estimada = date("Y-m-d",strtotime($fecha_estimada."- ". $diferencia_antiguo->y ." year"));                                    
                $fecha_estimada = date("Y-m-d",strtotime($fecha_estimada."- ". $diferencia_antiguo->m ." month"));
                $fecha_estimada = date("Y-m-d",strtotime($fecha_estimada."- ". $diferencia_antiguo->d ." days"));

                $date2 = new \DateTime($fecha_estimada);
            }

            $date1 = new \DateTime($contrato->fecha_reemplazo);

            //$date2 = new \DateTime($contrato->cliente->proyecto->fecha_fin_proyecto);

            $diff = $date1->diff($date2);

            $contrato = ClienteContrato::find($contrato->id);

            $vigencia = ($diff->m) + ($diff->y * 12);

            if($vigencia == 0){
                $vigencia = 1;
            }

            $contrato->vigencia_meses = $vigencia + 1;
            
            $contrato->save();

        }*/




        /*$archivos = array('contrato', 'acta_juramentada');
        $extension = 'pdf';

        $contratos = ClienteContrato::whereIn('id', [
            21593,20945,21805,10423,21102,21650,21682,9342,10832,21659,10856,10586,10881,21086,9518,21038,21021,21834,21576,20956,10924,20934,10696,21814,10670,10901,10426,21115,21045,10676,21851,21596,21702,21106,21854,21018,10371,20937,21639,21662,10878,21871,21083,21679,20977,10710,21579,10835,7593,10137,20931,10921,9016,10898,21768,20959,21791,10927,10814,20998,21811,21642,10852,21110,20953,8952,8635,8658,20990,10904,21094,20965,21734,10146,21534,21665,8166,21582,8319,21780,21075,21880,21027,10887,20923,6346,21671,21820,21645,10818,10824,10795,21688,21800,21097,10802,21602,21651,10862,10682,10096,10908,10884,20943,21731,21685,21608,21585,21708,20962,20987,21837,10890,10702,21823,10438,10841,20920,10821,21004,10844,21054,21691,20984,10143,21817,21860,21605,21048,21010,8787,10865,10911,10679,8495,21611,21657,21809,21019,10177,21786,21763,21084,10711,21571,10830,21826,10705,21674,8767,21694,10922,8770,20954,10893,8581,20932,10875,10847,10643,10916,21849,9383,8189,10688,21594,10853,20948,21104,9579,10809,21783,10833,20993,21806,10149,21039,10827,21789,9337,21677,21537,21081,20926,21078,21866,10646,10896,21746,9386,20929,6745,21766,10850,21654,21846,10427,10578,20996,10919,21107,20951,21852,21803,21092,21067,21855,21832,21680,20938,21663,21686,8776,9395,10879,10836,20979,10885,10655,21005,21818,10138,8337,20985,9958,9815,10729,10899,10694,20999,21812,10815,21643,21112,10860,20918,9343,21683,10857,10882,10906,21087,21116,21660,10839,21022,21666,21835,10715,21583,21070,21875,10108,20957,20982,10925,10819,10697,10796,9392,21838,21815,10677,10902,8942,21640,21046,8141,21858,21002,21603,10812
        ])->get();

        foreach ($contratos as $contrato) {

            foreach ($archivos as $archivo ) {
                $nombre = $archivo;
                //Declaramos una ruta
                $directory = 'contratos/' . $contrato->id;

                $existe_archivo = Storage::disk('public')->exists($directory.'/'.$nombre.'.'.$extension);

                //dd($existe_archivo);

                if(!$existe_archivo){

                    //Si no existe el directorio, lo creamos
                    if (!file_exists($directory)) {
                        //Creamos el directorio
                        Storage::disk('public')->makeDirectory($directory);
                    }

                    //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                    $documento = $directory.'/'.$nombre.'.'.$extension;

                    $data = new Data;
                    $data_contrato = $data->contrato($contrato->id);
                    

                    $ruta = Storage::disk('public')->path($documento);

                    switch ($archivo) {
                        case 'contrato':
                            $this->contrato_generar('F', $data_contrato, $ruta);
                            break;
                        case 'acta_juramentada':
                            $this->declaracion_generar('F', $data_contrato, $ruta);
                            break;
                    }

                    $existe = Storage::disk('public')->exists($documento);

                    if ($existe) {

                        $archivo = new ContratoArchivo;
                        $archivo->nombre = $nombre;
                        $archivo->archivo = $documento;
                        $archivo->tipo_archivo = $extension;
                        $archivo->estado = 'APROBADO';
                        $archivo->contrato_id = $contrato->id;

                        $archivo->save();
                    }
                }


            }
        }*/

        dd("terminó");
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
        if (Auth::user()->can('contrato-archivo-eliminar')){
        
            $archivo = ContratoArchivo::findOrFail($id);

            $contrato_id = $archivo->contrato_id;

            //validar si existen dos registros con el mismo nombre
            $validar = ContratoArchivo::where([['contrato_id', $archivo->contrato_id], ['nombre',$archivo->nombre], ['tipo_archivo', $archivo->tipo_archivo]])->count();

            $eliminar_archivo = false;

            if ($validar < 2) {
                $eliminar_archivo = true;
            }

            if ($archivo->delete()) {

                if ($eliminar_archivo) {
                    //Eliminamos el archivo existente
                    if (Storage::disk('public')->exists($archivo->archivo)){
                        Storage::disk('public')->delete($archivo->archivo);
                    }
                }            

                return redirect()->route('clientes.contratos.show', [$archivo->contrato->ClienteId, $contrato_id])->with('success','Archivo eliminado con exíto!');
            }else{
                return redirect()->route('clientes.contratos.show', [$archivo->contrato->ClienteId, $contrato_id])->with('error','No se pudo eliminar el archivo.');
            }

        }else{
            abort(403);
        }
    }

    public function ajax(Request $request){

        if (Auth::user()->can('contrato-archivo-ajax')){
        

            $data = new Data;
            $proyecto = Proyecto::findOrFail($request->proyecto);
            $clausulas_permanencia = ProyectoClausula::where('proyecto_id', $request->proyecto)->get();//$proyecto->clausula;
            
            $data_contrato = $data->contrato(null);
            $data_contrato['proyecto'] = $request->proyecto;
            $data_contrato['vigencia'] = $proyecto->vigencia;

            $data_contrato['tipo_cobro'] = $proyecto->tipo_facturacion;

            $data_contrato['clausulas_permanencia'] = $clausulas_permanencia;
            $data_contrato['costos'] = $proyecto->costo;
            $data_contrato['dia_corte_facturacion'] = $proyecto->dia_corte_facturacion;
            $data_contrato['condiciones_plan'] = $proyecto->condiciones_plan;
            $data_contrato['condiciones_servicio'] = $proyecto->condiciones_servicio;

            $array_costos = $proyecto->costo->toArray();

            $mas_iva = "";

            if(!empty($array_costos)){

                if($array_costos[array_search(
                    'Reconexión', 
                    array_column($array_costos, 'concepto')
                    )]["iva"] == "SI"){
        
                    $mas_iva = " más IVA.";
        
                }
                

                $data_contrato['reconexion'] = number_format(
                                                $array_costos[array_search(
                                                    'Reconexión', 
                                                    array_column($array_costos, 'concepto')
                                                )]["valor"],
                                                0,',','.'
                                            ) . $mas_iva;
            }



            $ruta = null;

            $pdf = null;

            switch ($request->documento) {
                case 'contrato':
                    $pdf = $this->contrato_generar('S', $data_contrato, $ruta);
                    break;
                case 'acta':
                    $pdf = $this->declaracion_generar('S', $data_contrato, $ruta);
                    break;            
                default:
                    # code...
                    break;
            }       
            
            return base64_encode($pdf);
        }else{
            abort(403);
        }

    }

    private function contrato_generar($destino, $data, $ruta){

        $contrato = null;

        switch ($data['proyecto']) {
            case 6:
                $contrato = $this->lp015($destino, $data, $ruta);
                break;
            case 8:
                $contrato = $this->lp018($destino, $data, $ruta);
                break;
            
            default:
                $contrato = $this->amigored($destino, $data, $ruta);
                break;
        }        

        return $contrato;
    }

    private function declaracion_generar($destino, $data, $ruta){

        $declaracion = null;

        switch ($data['proyecto']) {
            case 6:
                $declaracion = $this->declaracion_lp15($destino, $data, $ruta);
                break;
            case 8:
                $declaracion = $this->declaracion_lp18($destino, $data, $ruta);
                break;
            default:
                $declaracion = $this->declaracion_findeter($destino, $data, $ruta);
                break;
        }
        
        return $declaracion;
    }
}
