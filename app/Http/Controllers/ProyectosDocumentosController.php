<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Proyecto;
use App\ProyectoDocumento;
use App\DocumentoNombre;
use App\DocumentoCategoria;
use App\Log;
use Storage;
use DB;

class ProyectosDocumentosController extends Controller
{

  public function index(Request $request ,$proyecto){

    $documentos = ProyectoDocumento::with('documento_nombre')
    ->Nombre($request->get('nombre'))
    ->Categoria($request->get('categoria'),$request->get('sub_categoria'))
    ->where('proyecto_id', $proyecto)
    ->orderBy('documento_nombre_id','ASC')
    ->whereNotIn('estado',['HISTORICO'])
    ->get();

    return response()->json(['documentos' => $documentos]);


  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Proyecto $proyecto)
    {
        if (Auth::user()->can('proyectos-documentos-crear')) {
          $documento = new ProyectoDocumento;
          $estados = ['VIGENTE', 'VENCIDO'];
          $nombres_documentos = DocumentoNombre::where('estado','ACTIVO')->orderBy('nombre','ASC')->get();
          $categorias = DocumentoCategoria::whereNull('documento_categoria_id')
          ->where([
            ['estado','ACTIVO'],
            ['nombre','=','PROYECTOS']
          ])
          ->orderBy('nombre','ASC')
          ->get();

          return view('adminlte::proyectos.gestion-documental.create', compact(
            'documento',
            'proyecto',
            'categorias',
            'estados',
            'nombres_documentos'
          ));
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
    public function store(Request $request, $proyecto)
    {
      if (Auth::user()->can('proyectos-documentos-crear')) {

        $this->validate($request, [
          'nombre' => 'required',
          'estado' => 'required',
          'fecha_expedicion' => 'required',
          'documento' => 'required|mimes:jpg,jpeg,png,pdf,docx,xlsx,zip|max:10000',
        ]);

        

        //$validar = ProyectoDocumento::where([['documento_nombre_id',$request->nombre], ['documento_categoria_id',$categoria_subcategoria]])->count();
        $validar = ProyectoDocumento::where(function($query) use($request, $proyecto) {
          if(empty($request->sub_categoria)){
            $query->where([
              ['documento_nombre_id',$request->nombre],
              ['proyecto_id', $proyecto]
            ]);
          }else{
            $query->where([
              ['documento_nombre_id',$request->nombre],
              ['proyecto_id', $proyecto],
              ['documento_categoria_id', $request->sub_categoria]
            ]);
          }
        })->count();


        if ($validar > 0) {
          return redirect()->route('proyectos.show',$proyecto)->with('warning', 'El documento ya existe!');
        }else{
          $result = DB::transaction(function () use($request,$proyecto) {

            $categoria_subcategoria = (empty($request->sub_categoria))? $request->categoria : $request->sub_categoria;


            //Declaramos una ruta
            $directory = 'proyectos/'.$proyecto;

            //Declaramos el documento
            $file = $request->file('documento');
            //Si no existe el directorio, lo creamos
            if (!file_exists($directory)) {
                //Creamos el directorio
                Storage::makeDirectory($directory);
            }

            //Asignamos el nombre al documento
            $nombre = mb_convert_case(str_replace(' ', '_', $request->nombre), MB_CASE_LOWER, "UTF-8");


            //Obtenemos el tipo de documento que se esta subiendo
            $extension = strtolower($request->file('documento')->getClientOriginalExtension());

            //declaramos la ruta del documento
            $ruta_documento_soporte = $directory.'/'.$nombre.'.'.$extension;

            //Indicamos que queremos guardar un nuevo documento en el directorio publico
            //Storage::put('public/' .$ruta_documento_soporte, \File::get($file));
            Storage::put($ruta_documento_soporte, \File::get($file));

            //validamos si el documento se ha guardado correctamente
            //$existe = Storage::disk('public')->exists($ruta_documento_soporte);
            $existe = Storage::exists($ruta_documento_soporte);

            if ($existe) {

              $date2 = new \DateTime(date("Y-m-d"));
              $estado = "VIGENTE";

              $documento = new ProyectoDocumento;
              $documento->documento_nombre_id = $request->nombre;
              $documento->documento_categoria_id = $categoria_subcategoria;
              $documento->fecha_expedicion = $request->fecha_expedicion;
              $documento->fecha_vencimiento = $request->fecha_vencimiento;
              $documento->estado = $request->estado;
              $documento->proyecto_id = $proyecto;
              $documento->ruta = $ruta_documento_soporte;
              $documento->tipo = $extension;
              $documento->contenido_documento = $request->contenido_documento;
              $documento->version = $request->version;
              $documento->user_id = Auth::user()->id;

              if (isset($request->confidencia)) {
                $documento->confidencial = true;
              }else{
                $documento->confidencial = false;
              }

              if (isset($request->versionado)) {
                $documento->versionado = true;
              }else{
                $documento->versionado = false;
              }

              if (!$documento->save()) {
                Storage::delete($ruta_documento_soporte);
                DB::rollBack();
                return ['error', 'Error al guardar la informacion'];
              }else{

                /*-------Codigo de Log de cambios------*/
                $log = new Log;
                $log->tabla = $documento->getTable();
                $log->accion = 'INSERT';
                $log->descripcion = "Proyecto= ".$documento->proyecto->nombre.
                                    "; Nombre= ". $documento->documento_nombre->nombre.
                                    "; Version= ". $documento->version.
                                    "; Categoria= ". $documento->documento_categoria->nombre.
                                    "; Ruta= ". $documento->ruta.
                                    "; Contenido_Documento= ".$documento->contenido_documento.
                                    "; Estado= " . $documento->estado;
                $log->user_id = Auth::user()->id;

                if (!$log->save()){
                  DB::rollBack();
                  return ['error', 'Error al guardar el log'];
                }
                /*-------FIN odigo de Log de cambios------*/

                return ['success', 'Documento subido correctamente!'];
              }
            }else{
                DB::rollBack();
                return ['error', 'Error al subir el documento'];
            }
          });

          return redirect()->route('proyectos.show',$proyecto)->with($result[0], $result[1]);
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
    public function show($proyecto, $id)
    {
      if (Auth::user()->can('proyectos-documentos-ver')) {

        $documento = ProyectoDocumento::where([
            ['proyecto_id', $proyecto],
            ['id', $id]
          ])
          ->first();
        $data = array();

        if (isset($documento->renovacion)) {
          $data[] = $this->llenar_data($documento->renovacion);
        }

        if (!empty($documento->proyecto_documento_id)) {
          $historicos = ProyectoDocumento::where('proyecto_documento_id',$documento->proyecto_documento_id)
          ->orderBy('fecha_expedicion', 'ASC')
          ->get();

          foreach ($historicos as $historico ) {
            $data[] = $this->llenar_data($historico);
          }
        }else{
          $data[] = $this->llenar_data($documento);
        }

        $original = $this->llenar_data($documento);

        return response()->json(array('documento' => $original, 'historico' => $data));
      }else{
          abort(403);
      }
    }


    private function llenar_data($data){
      $item = array();
      $item['nombre'] = $data->documento_nombre->nombre;

      $item['categoria'] = (empty($data->documento_categoria->documento_categoria_id))? $data->documento_categoria->nombre : $data->documento_categoria->categoria->nombre;
      $item['subcategoria'] = (empty($data->documento_categoria->documento_categoria_id))? null : $data->documento_categoria->nombre;

      $item['ruta'] = $data->ruta;
      $item['fecha_expedicion'] = $data->fecha_expedicion;
      $item['fecha_vencimiento'] = $data->fecha_vencimiento;
      $item['tipo'] = $data->tipo;
      $item['estado'] = $data->estado;
      $item['contenido'] = $data->contenido_documento;
      $item['confidencial'] = $data->confidencial;
      $item['versionado'] = $data->versionado;
      $item['version'] = $data->version;

      return $item;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($proyecto,$id)
    {
      if (Auth::user()->can('proyectos-documentos-editar')) {

        $documento = ProyectoDocumento::withCount('renovacion')->findOrFail($id);

        //dd($documento);

        $proyecto = Proyecto::findOrFail($proyecto);
        $estados = ['VIGENTE', 'VENCIDO'];
        $nombres_documentos = DocumentoNombre::
          where('estado','ACTIVO')
          ->orderBy('nombre','ASC')
          ->get();
        $categorias = DocumentoCategoria::
          whereNull('documento_categoria_id')
          ->where([
            ['estado','ACTIVO'],
            ['nombre','=','PROYECTOS']
          ])
          ->orderBy('nombre','ASC')
          ->get();

        return view('adminlte::proyectos.gestion-documental.edit', compact(
          'documento',
          'proyecto',
          'categorias',
          'estados',
          'nombres_documentos'
        ));
      }else{
          abort(403);
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $proyecto,$id)
    {
        if (Auth::user()->can('proyectos-documentos-editar')) {

          $this->validate($request, [
            'nombre' => 'required',
            'estado' => 'required',
            'fecha_expedicion' => 'required',
          ]);

          $result = DB::transaction(function () use($request,$proyecto,$id) {

            $categoria_subcategoria = (empty($request->sub_categoria))? $request->categoria : $request->sub_categoria;

            $documento = ProyectoDocumento::findOrFail($id);
            $id_documento_principal = $documento->proyecto_documento_id;

            $validar = ProyectoDocumento::select('proyectos_documentos.*')
            ->leftJoin('proyectos_documentos as ea2', 'proyectos_documentos.id', '=','ea2.proyecto_documento_id')
            ->where([
              ['proyectos_documentos.documento_nombre_id', $request->nombre],
              ['proyectos_documentos.documento_categoria_id', $categoria_subcategoria],
              ['proyectos_documentos.id', '!=', $id],
              ['proyectos_documentos.proyecto_id', $proyecto]
            ])
            ->where(function ($query) use($id_documento_principal) {
              $query->where('proyectos_documentos.proyecto_documento_id', '!=', $id_documento_principal)
              ->orWhereNull('proyectos_documentos.proyecto_documento_id');
            })
            ->whereNull('ea2.proyecto_documento_id')
            ->count();

            if ($validar > 0) {
              DB::rollBack();
              return ['warning', 'El documento ya existe!'];
            }else{

              $cambio = "";

              foreach ($request->all() as $key => $value) {
                switch ($key) {
                  case '_method':
                    break;
                  case '_token':
                    break;
                  default:
                    if ($documento[$key] != $value) {
                      $cambio .= ";".ucwords($key)."= " . $value;
                    }

                    break;
                }
              }
              /*-------FIN Codigo de Log de cambios------*/

              $estado = $documento->estado;

              if ($documento->estado == 'VENCIDO') {

                $estado = "VIGENTE";

                $this->validate($request, [
                  'fecha_vencimiento' => 'required',
                  'fecha_expedicion' => 'required',
                ]);
              }else{

                if (!empty($request->estado)) {
                  $estado = $request->estado;
                }

              }

              if (isset($documento->renovacion)) {
                if (strtotime($request->fecha_vencimiento) <  strtotime($documento->renovacion->fecha_vencimiento)) {
                  return ['error', 'La fecha de vencimiento no puede ser menor que la del documento original.'];
                }
              }


              $ruta = $documento->ruta;
              $nombre = $documento->documento_nombre_id;
              $nombre_original = $documento->documento_nombre_id;
              $directory = 'proyectos/'.$documento->proyecto_id;




              #cuando cambia el nombre
              if ($documento->documento_nombre_id != $request->nombre) {
                $nombre = $request->nombre;
              }


              $array = explode("/",$documento->ruta);

              $definitivo = $array[(count($array) -1)];

              $definitivo = explode("_", $definitivo);
              $nombre_complementario = null;


              if(count($definitivo) > 1){
                  $nombre_complementario = "_".$definitivo[(count($definitivo) -1)];
              }else{
                  $nombre_complementario = ".".$documento->tipo;
              }

              $ruta = $directory.'/'.$nombre.$nombre_complementario;

              if($documento->ruta != $ruta){
                Storage::move($documento->ruta, $ruta);
              }

              $documento->documento_nombre_id = $request->nombre;
              $documento->documento_categoria_id = $categoria_subcategoria;
              $documento->fecha_expedicion = $request->fecha_expedicion;
              $documento->fecha_vencimiento = $request->fecha_vencimiento;
              $documento->ruta = $ruta;
              $documento->estado = $estado;
              $documento->contenido_documento = $request->contenido_documento;
              $documento->version = $request->version;
              
              //if (!empty($documento->renovacion)) {
              if (empty($documento->renovacion)) {
                $documento->confidencial = (isset($request->confidencial)) ? true : false;
                $documento->versionado = (isset($request->versionado)) ? true : false;
              }


              if($documento->save()){
                $mensaje = "Informacion actualizada satisfactoriamente!";
              }else{
                DB::rollBack();
                return ['error', 'Error al guardar el documento!'];
              }

              /*-------Codigo de Log de cambios------*/
              $log = new Log;
              $log->tabla = $documento->getTable();
              $log->accion = 'UPDATE';
              $log->descripcion = "ProyectoDocumento= " .$nombre_original. " ".$cambio;
              $log->user_id = Auth::user()->id;
              $log->save();
              /*-------FIN odigo de Log de cambios------*/

              return ['success', $mensaje];
            }
          });
          return redirect()->route('proyectos.show',$proyecto)->with($result[0], $result[1]);
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
    public function destroy(Request $request,$proyecto,$id)
    {

      if (Auth::user()->can('proyectos-documentos-eliminar')) {


        $result = DB::transaction(function () use($request,$proyecto,$id) {

          $documento = ProyectoDocumento::findOrFail($id);

          Storage::delete($documento->ruta);
          
          if($documento->delete()){

            if(!empty($documento->renovacion)){
              $documento_actualizar = ProyectoDocumento::
                where('proyecto_documento_id',$documento->proyecto_documento_id)
                ->orWhere('id', $documento->proyecto_documento_id)
                ->orderBy('fecha_expedicion', 'DESC')
                ->first();
              
              $documento_actualizar->estado = 'VIGENTE';
              if(!$documento_actualizar->save()){
                DB::rollBack();
                return ['error', 'Error al actualizar el ultimo documento del historico.'];
              }
            }

            /*-------Codigo de Log de cambios------*/
            $log = new Log;
            $log->tabla = $documento->getTable();
            $log->accion = 'ELIMINADO';
            $log->descripcion = "Proyecto= ".$documento->proyecto->nombre.
                                "; Nombre= ". $documento->documento_nombre->nombre.
                                "; version= ". $documento->version.
                                "; RutaAnterior= ". $documento->ruta.
                                "; Categoria= ". ((empty($documento->documento_categoria->documento_categoria_id))? $documento->documento_categoria->nombre : $documento->documento_categoria->categoria->nombre).
                                "; SubCategoria= ". ((empty($documento->documento_categoria->documento_categoria_id))? null : $documento->documento_categoria->nombre);

            $log->user_id = Auth::user()->id;
            $log->save();
            /*-------FIN Codigo de Log de cambios------*/

            return ['success', 'Documento eliminado correctamente'];

            
          }else{
            DB::rollBack();
            Storage::move($nombre_ruta,$documento->ruta);
            return ['error', 'Error al elimnar el registro de la tabla'];
          }
        });

        return response()->json($result);


        //return redirect()->route('proyectos.show',$proyecto)->with($result[0], $result[1]);

            //$documento->vinculaciones #'No es posible eliminar porque el documento se encuentra relacionado con una o más vinculaciones.');
            //$documento->renovacion #'No es posible eliminar porque el documento es un historico de un documento principal.');
            //$documento->renovaciones#'No es posible eliminar porque el documento tiene un historico.');
      }else{
        abort(403);
      }
    }

    public function versionado(Request $request, $proyecto, $id){
      if (Auth::user()->can('proyectos-documentos-versionado')){

        $this->validate($request, [
          'fecha_expedicion' => 'required',
          'documento' => 'required|mimes:jpg,jpeg,png,pdf,docx,xlsx,zip|max:10000',
        ]);

        $result = DB::transaction(function () use($request,$proyecto,$id) {

          $documento_original = ProyectoDocumento::find($id);

          /*if(empty($request->fecha_vencimiento) && $documento_original->versionado){
            $this->validate($request, [
              'fecha_vencimiento' => 'required',
            ]);
          }*/       

          $nombre = null;
          $documento = null;

          $historico = null;
          $reorganizar = false;

          if(!empty($documento_original->proyecto_documento_id)){
            $historico = $documento_original->renovacion;
          }


          if($documento_original->versionado){            

            $documento = New ProyectoDocumento;

            if(strtotime($request->fecha_expedicion) <= strtotime($documento_original->fecha_expedicion)){

              $reorganizar = true;
              $documento->estado = 'HISTORICO';

              if(strtotime($request->fecha_expedicion) >= strtotime($historico->fecha_expedicion)){
                $historico = $documento_original;
                $reorganizar = false;
                $documento->proyecto_documento_id = $documento_original->proyecto_documento_id;
              }

            }else{
              $documento->proyecto_documento_id = (empty($documento_original->proyecto_documento_id)) ? $id : $documento_original->proyecto_documento_id;
              $documento->estado = 'VIGENTE';

              #Solo cuento sea mayor la fecha de expedición del documento que se esta subiendo se marcará como historico el documento a actualizar. 
              $documento_original->estado = "HISTORICO";
              if (!$documento_original->save()) {
                DB::rollBack();
                return ['error', 'Error al actualizar el estado del documento versionado!'];                  
              }

            }

          }else{
            $documento = ProyectoDocumento::find($id);
            $documento->estado = 'VIGENTE';
          }         
       

          $documento->documento_nombre_id = $documento_original->documento_nombre_id;
          $documento->documento_categoria_id = $documento_original->documento_categoria_id;
          $documento->fecha_expedicion = $request->fecha_expedicion;
          $documento->fecha_vencimiento = $request->fecha_vencimiento;            
          $documento->proyecto_id = $documento_original->proyecto_id;
          $documento->confidencial = $documento_original->confidencial;
          $documento->versionado = $documento_original->versionado;
          $documento->version = $request->version;
          $documento->contenido_documento = (empty($request->contenido_documento))? $documento_original->contenido_documento : $request->contenido_documento;            
          $documento->user_id = Auth::user()->id;

          if($documento->save()){

            //Asignamos el nombre al documento
            $nombre = mb_convert_case(str_replace(' ', '_', $documento->documento_nombre_id), MB_CASE_LOWER, "UTF-8").'_'.$documento->id;

            //Declaramos el documento
            $file = $request->file('documento');

            //Declaramos una ruta
            $directory = 'proyectos/'.$proyecto;

            //Obtenemos el tipo de documento que se esta subiendo
            $extension = strtolower($request->file('documento')->getClientOriginalExtension());

            //declaramos la ruta del documento
            $ruta_documento_soporte = $directory.'/'.$nombre.'.'.$extension;

            //Si no existe el directorio, lo creamos
            if (!file_exists($directory)) {
                //Creamos el directorio
                Storage::makeDirectory($directory);
            }

            if($documento_original->versionado){
              
              if($reorganizar){
                ProyectoDocumento::where('proyecto_documento_id', $historico->id)->update(['proyecto_documento_id' => $documento->id]);
                $historico->proyecto_documento_id = $documento->id;

                if(!$historico->save()){
                  DB::rollBack();
                  return ['error', 'Error al reorganizar el versionado.'];
                }
              }

            }else{
              if(Storage::exists($documento_original->ruta)){
                Storage::delete($documento_original->ruta);
              }              
            }

            //Indicamos que queremos guardar un nuevo documento en el directorio publico
            Storage::put($ruta_documento_soporte, \File::get($file));

            //validamos si el documento se ha guardado correctamente
            $existe = Storage::exists($ruta_documento_soporte);

            if ($existe) {

              $documento->ruta = $ruta_documento_soporte;
              $documento->tipo = $extension;

              if($documento->save()){
                return ['success', 'Documento subido correctamente.'];
                
              }else{
                DB::rollBack();
                Storage::delete($ruta_documento_soporte);
                return ['error', 'Error al guardar el documento.'];
              }

            }else{
              DB::rollBack();
              return ['error', 'Error al guardar la ruta del documento!'];
            }


          }else{
            DB::rollBack();
            Storage::delete($ruta_documento_soporte);
            return ['error', 'Error al guardar la información del documento!'];
          }
          
        });

        return redirect()->route('proyectos.show',$proyecto)->with($result[0], $result[1]);

      }else{
        abort(403);
      }
    }
}
