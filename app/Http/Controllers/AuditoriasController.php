<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\Proyecto;
use App\User;

use App\AtencionCliente;
use App\Mantenimiento;
use App\MantenimientoCliente;
use App\PQR;
use App\Ticket;
use App\Solicitud;
use App\ClienteContrato;
use App\ContratoArchivo;
use App\ProyectoTipoBeneficiario;
use App\ArchivoCliente;
use App\Custom\ActaNoFirma;
use DB;

use App\Custom\PlantillasContratos\AmigoRed;
use App\Custom\Data;
use PDF;
use Carbon\Carbon;
use Storage;

use App\Traits\Contratos;
use App\Traits\DeclaracionesJuramentadas;


use Illuminate\Support\Facades\Mail;
use App\Mail\Contrato;

class AuditoriasController extends Controller
{
    use Contratos, DeclaracionesJuramentadas;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('auditorias-listar')) {
            $clientes = Cliente::with('ubicacion')
            ->Cedula($request->get('documento'))
            ->Proyecto($request->get('proyecto'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            ->where('Status', 'PENDIENTE')
            ->orderBy('updated_at', 'ASC')
            ->paginate(15);

            $proyectos = Proyecto::get();
        
            return view('adminlte::auditorias.clientes.index', compact('clientes', 'proyectos'));

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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('auditorias-crear')) {

            $this->validate(request(),[
                'tipo_documento' => 'required',
                'documento' => 'required',
                'apellidos' => 'required',
                'nombres' => 'required',
                'fecha_nacimiento' => 'required',
                'lugar_nacimiento' => 'required',
                'genero' => 'required',
                'lugar_expedicion' => 'required',
                'direccion_recibo' => 'required',
                'estrato' => 'required',
                'vendedor' => 'required',
                'estado' => 'required',
                'tipo' => 'required'
            ]);

            $validar = array_search("RECHAZADO", $request->evidencias);

            if($validar != false){
                if($request->estado != "RECHAZADO"){
                    return redirect()->route('auditorias.clientes.show', $request->cliente)->with('error', 'El estado de la auditoria debe coincidir con lo auditado.');
                }else{
                    $this->validate(request(),[
                        'motivo_rechazo' => 'required',
                        'observaciones' => 'required',                        
                    ]);
                }
            }

            $id = $request->cliente;

            $result = DB::transaction(function () use($request, $id) {

                if ($request->tipo == 'cliente') {
                    
                    foreach ($request->evidencias as $key => $value) {
                        $archivo = ArchivoCliente::find($key);
                        $archivo->estado = $value;

                        if(!$archivo->save()){
                            DB::rollBack();
                            return ['error', 'Error al actualizar el estado de las evidencias'];
                        }
                    }

                    $cliente = Cliente::find($id);
                    $cliente->TipoDeDocumento = $request->tipo_documento;
                    $cliente->Identificacion = $request->documento;
                    $cliente->Apellidos = mb_convert_case($request->apellidos, MB_CASE_TITLE, "UTF-8");
                    $cliente->NombreBeneficiario = mb_convert_case($request->nombres, MB_CASE_TITLE, "UTF-8");
                    $cliente->fecha_nacimiento = $request->fecha_nacimiento;
                    $cliente->lugar_nacimiento = mb_convert_case($request->lugar_nacimiento, MB_CASE_TITLE, "UTF-8");
                    $cliente->genero = $request->genero;
                    $cliente->ExpedidaEn = mb_convert_case($request->lugar_expedicion, MB_CASE_TITLE, "UTF-8");
                    
                    $cliente->direccion_recibo = $request->direccion_recibo;
                    $cliente->Barrio = $request->barrio;
                    $cliente->Estrato = $request->estrato;
                    $cliente->user_id = $request->vendedor;
                    $cliente->auditor_id = Auth::user()->id;

                    if ($request->estado == 'APROBADO') {
                        $cliente->FechaAprobacion = date('Y-m-d H:i:s');
                        $cliente->Status = 'EN INSTALACION';
                    }else{
                        $cliente->MotivoDeRechazo = $request->motivo_rechazo;
                        $cliente->ComentarioRechazo = $request->observaciones; //str_replace(PHP_EOL, ';', $request->observaciones);
                        $cliente->Status = $request->estado;
                    }

                    if ($cliente->save()) {

                        if ($request->estado == 'APROBADO') {                        
                                
                            $contrato = ClienteContrato::where([
                                ['ClienteId', $id], 
                                ['estado', 'PENDIENTE']]
                            )->first();

                            if(empty($contrato->referencia)){
                                $contrato->referencia = date('Y') . '-' . $contrato->id;
                                $contrato->save();
                            }
    
                            $archivos_contratos = ["contrato"];
    
                            if(!empty($cliente->proyecto->acta_juramentada) && $cliente->proyecto->acta_juramentada == 1){
                                $archivos_contratos[] = "acta_juramentada";
                            }
    
                            //Declaramos una ruta
                            $directory = 'contratos/' . $contrato->id;
                            $extension = 'pdf';
                            $adjuntos = [];
    
                            //Si no existe el directorio, lo creamos
                            if (!file_exists($directory)) {
                                //Creamos el directorio
                                Storage::disk('public')->makeDirectory($directory);
                            }
    
                            $data = new Data;
                            $data_contrato = $data->contrato($contrato->id);
    
                            foreach ($archivos_contratos as $archivo_contrato) {
    
                                $ruta = $directory.'/'.$archivo_contrato.'.'.$extension;
    
                                $adjuntos[] = $ruta;
    
                                if($archivo_contrato == "contrato"){
    
                                    switch ($cliente->ProyectoId) {
                                        case 6:
                                            $this->lp015('F', $data_contrato, Storage::disk('public')->path($ruta));
                                            break;
                                        case 8:
                                            $this->lp018('F', $data_contrato, Storage::disk('public')->path($ruta));
                                            break;                            
                                        default:
                                            $this->amigored('F', $data_contrato, Storage::disk('public')->path($ruta));
                                            break;
                                    }
    
                                }else if($archivo_contrato == "acta_juramentada"){
    
                                    switch ($cliente->ProyectoId) {
                                        case 6:
                                            $this->declaracion_lp15('F', $data_contrato, Storage::disk('public')->path($ruta));
                                            break;
                                        case 8:
                                            $this->declaracion_lp18('F', $data_contrato, Storage::disk('public')->path($ruta));
                                            break;                            
                                        default:
                                            $this->declaracion_findeter('F', $data_contrato, Storage::disk('public')->path($ruta));        
                                            break;
                                    }
                                }
    
                                $existe = Storage::disk('public')->exists($ruta);
    
                                if($existe){
    
                                    $archivo = new ContratoArchivo;
                                    $archivo->nombre = $archivo_contrato;
                                    $archivo->archivo = $ruta;
                                    $archivo->tipo_archivo = $extension;
                                    $archivo->estado = 'APROBADO';
                                    $archivo->contrato_id = $contrato->id;
                
                                    if (!$archivo->save()){
                                        DB::rollBack();
                                        Storage::disk('public')->deleteDirectory($directory);
                                        return ['error', 'Error al guardar el registro del archivo del contrato.'];
                                    }
                                }else{
                                    DB::rollBack();
                                    Storage::disk('public')->deleteDirectory($directory);
                                    return ['error', 'Error al generar el '. $archivo_contrato];
                                }
                            }
    
                            //Mail::to($cliente->CorreoElectronico)->send(new Contrato($data_contrato, $adjuntos));
                            //Mail::to("jcardona@sisteco.com.co")->send(new Contrato($data_contrato, $adjuntos));
                            //$this->contrato_generar('F', $data_contrato, Storage::disk('public')->path($ruta));                                   
    
                        }

                        return ['success', 'Cliente Auditado Correctamente.'];
                    }else{
                        DB::rollBack();
                        return ['error', 'Error al actualizar el cliente'];
                    }
                }
            });

            return redirect()->route('auditorias.clientes.index')->with($result[0], $result[1]);

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
    public function show(Cliente $cliente)
    {
        if (Auth::user()->can('auditorias-ver')) {

            $motivos_rechazo = array('Documentacion Incompleta','Firma no Corresponde','No aplica Subsidio','Direccion no Corresponde', 'Foto fachazada sin identificar');        

            $vendedores = User::whereHas('roles',function($q){                            
                            $q->where('roles.name', '=', 'vendedor');})
                        ->orderBy('name','ASC')->get();

            $genero = array(array('sigla' => 'M' , 'valor' => 'Masculino' ),
                array('sigla' => 'F' , 'valor' => 'Femenino' ),
                array('sigla' => 'T' , 'valor' => 'Transgénero')
                );

            $tipos_documentos = [
                "C.C" => 'Cédula de Ciudadanía',
                "C.E" => 'Cédula de Extranjería',
                "P.P" => 'Pasaporte',
                "R.C" => 'Registro Civil',
                "T.I" => 'Tarjeta de Identidad',
                "NIT" => 'Número de Identificación Tributaria'
            ];

            $estratos = [0,1,2,3,4,5,6];

            $tipo_beneficiario = ProyectoTipoBeneficiario::where('proyecto_id', $cliente->ProyectoId)->get();

            $contratos = ClienteContrato::where([['ClienteId', $cliente->ClienteId], ['estado', 'PENDIENTE']])->get();

            $evidencias = null;
            $cedula = null;

            if($cliente->archivos->count() > 0){
                foreach ($cliente->archivos as $evidencia) {

                    

                    if($this->buscar_palabra($evidencia->nombre, ["cedula", "identificacion", "documento"])){

                        if(isset($evidencias["datos_personales"])){
                            $evidencias["datos_personales"] .= $this->html_evidencias($evidencia->archivo, $evidencia->id);
                        }else{
                            $evidencias["datos_personales"] = $this->html_evidencias($evidencia->archivo, $evidencia->id);
                        }

                        if($this->buscar_palabra($evidencia->nombre, ["1"])){
                            $cedula = [$evidencia->archivo, $evidencia->nombre];
                        }
                        
                    }

                    if($this->buscar_palabra($evidencia->nombre, ["vivienda", "fachada", "casa", "recibo"])){

                        if(isset($evidencias["lugar_residencia"])){
                            $evidencias["lugar_residencia"] .= $this->html_evidencias($evidencia->archivo, $evidencia->id);
                        }else{
                            $evidencias["lugar_residencia"] = $this->html_evidencias($evidencia->archivo, $evidencia->id);
                        }
                        
                    }

                    if($this->buscar_palabra($evidencia->nombre, ["firma"]) && $cliente->SabeFirmar == 1){
                        $evidencias["firma"] = $this->html_evidencias($evidencia->archivo, $evidencia->id, "col-md-6");

                        if(!empty($cedula)){
                            $evidencias["firma"] .= $this->html_evidencias($cedula[0], $cedula[1], "col-md-6", false);
                        }
                        
                    }
                }
            }

            $tiene_acta_no_firma = count($contratos) > 0 
                ? ContratoArchivo::where([['contrato_id', $contratos[0]->id], ['nombre', "constancia_no_firma"]])->exists()
                : false;

            return view('adminlte::auditorias.clientes.show', compact(
                'cliente', 
                'motivos_rechazo', 
                'vendedores', 
                'genero', 
                'tipos_documentos',
                'estratos',
                'tipo_beneficiario',
                'contratos',
                'evidencias',
                'tiene_acta_no_firma'
            ));

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

    private function buscar_palabra($texto, $array_palabras){

        $resultado = false;

        foreach ($array_palabras as $palabra) {
            if(stripos($texto, $palabra) !== false) {
                $resultado = true;
            }
        }

        return $resultado;

    }

    private function html_evidencias($archivo, $id, $clase = null, $auditar = true){
        $formulario = '
            <div class="form-group col-md-4">
                <div class="radio">
                    <label>
                    <input type="radio" name="evidencias['.$id.']" value="APROBADO" required> APROBAR
                    </label>
                </div>
            </div>

            <div class="form-group col-md-4">
                <div class="radio">
                    <label>
                    <input type="radio" name="evidencias['.$id.']" value="RECHAZADO" required> RECHAZAR
                    </label>
                </div>
            </div>
        ';

        if(!$auditar){
            $formulario = null;
        }


        return '
        <div class="zoom '.$clase.'">
            <img class="img-responsive" src="'.Storage::url($archivo).'"   alt="Photo">

            <div class="formulario-imagen navbar-form navbar-left bg-teal">

                <div class="row">
                    <div class="form-group col-md-4">
                        <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-rotate-right"></i> Girar</button>
                    </div>
                    '.$formulario.'
                </div>
            </div>
        </div>
    ';
    }

    public function generarActaNoFirmaPDF($id) {
        $cliente = Cliente::with(['contrato', 'contrato.servicio'])->findOrFail($id);
        $pdf = new ActaNoFirma('P','mm', 'A4', $cliente);

        $pdf->SetMargins(30, 25 , 30); 

        $pdf->AddFont('calibri','','calibri.php');
        $pdf->AddFont('futura-bdcn-bt-bold','','futura-bdcn-bt-bold.php');

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 1);

        $pdf->body();

        return $pdf->Output('', utf8_decode('Acta No firma - ' . $cliente->Identificacion . " " . $cliente->NombreBeneficiario . " " . $cliente->Apellidos . ".pdf"));
    }

}
