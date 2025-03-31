<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EstudioDemanda;
use App\Proyecto;
use App\Departamento;
use App\Municipio;
use App\ArchivoEstudioDemanda;
use Storage;

class EstudiosDemandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estudios_demanda = EstudioDemanda::Buscar($request->get('nombre'))->paginate(15);
        $proyectos = Proyecto::where('status', 'A')->get();
        return view('adminlte::estudios-demanda.index', compact('estudios_demanda', 'proyectos'));
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
        $this->validate(request(),[
            'nombre' => 'required',
            'version' => 'required',
            'proyecto' => 'required',
            'archivos' => 'required',
            'archivos.*' => 'mimes:pdf,xlsx,docx,jpg,jpeg,png|max:10000'
        ]);

        $estudio = new EstudioDemanda;
        $estudio->nombre = $request->nombre;
        $estudio->version = $request->version;
        $estudio->user_id = Auth::user()->id;
        $estudio->proyecto_id = $request->proyecto;
        $estudio->proyecto_municipio_id = $request->proyecto_municipio_id;

        if ($estudio->save()) {

            $i = 1;

            foreach ($request->archivos as $file) {

                $nombre = strtolower(utf8_decode($request->nombre . '-' . $i));

                $directory = 'estudios-demanda/'.$estudio->id;

                //Si no existe el directorio, lo creamos
                if (!Storage::disk('public')->exists($directory)) {
                    //Creamos el directorio
                    Storage::makeDirectory($directory);
                }

                //Obtenemos el tipo de archivo que se esta subiendo
                $extension = strtolower($file->getClientOriginalExtension());

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $documento = $directory.'/'.$nombre.'.'.$extension;

                //Indicamos que queremos guardar un nuevo archivo en el disco local
                //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));
                Storage::disk('public')->put($documento, \File::get($file));                


                $archivo = new ArchivoEstudioDemanda;
                $archivo->nombre = $nombre;
                $archivo->archivo = $documento;
                $archivo->tipo = $extension;
                $archivo->estudio_demanda_id = $estudio->id;

                if ($archivo->save()) {
                    # code...
                    $i += 1;
                }
                    
            }

             return redirect()->route('estudios-demanda.index')->with('success', 'Información Agregada.');
            
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
        $estudio = EstudioDemanda::findOrFail($id);
        return view('adminlte::estudios-demanda.show', compact('estudio'));
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

        $estudio = EstudioDemanda::findOrFail($id);

        foreach ($estudio->archivos as $archivo) {
            if (Storage::disk('public')->exists($archivo->archivo)){
                Storage::disk('public')->delete($archivo->archivo);
            }

            $archivo->delete();
        }

        if ($estudio->delete()) {

            $directory = 'estudios-demanda\\'.$estudio->id;

            //Si no existe el directorio, lo creamos
            if (Storage::disk('public')->exists($directory)) {
                //Creamos el directorio
                Storage::disk('public')->deleteDirectory($directory);
            }

            return redirect()->route('estudios-demanda.index')->with('success','Registro eliminado');
        }else{
            return redirect()->route('estudios-demanda.index')->with('error','No se pudo eliminar.');
        }    
    }

    public function ajax_departamentos(Request $request){

        $departamentos = Departamento::select(
            'Departamentos.DeptId', 
            'Departamentos.NombreDelDepartamento')
        ->join('Municipios', 'Departamentos.DeptId', '=', 'Municipios.DeptId')
        ->join('proyectos_municipios', 'Municipios.MunicipioId', '=', 'proyectos_municipios.municipio_id')
        ->where('proyectos_municipios.proyecto_id',$request->proyecto_id)
        ->groupBy('Departamentos.DeptId', 'Departamentos.NombreDelDepartamento')
        ->get();
        
        return response()->json($departamentos);
    }

  public function ajax_municipios(Request $request){

    $municipios = null;

    if(!empty($request->proyecto_id)){

        $municipios = Municipio::select(
            'proyectos_municipios.id',
            'Municipios.MunicipioId', 
            'Municipios.NombreMunicipio')
        ->join('proyectos_municipios', 'Municipios.MunicipioId', '=', 'proyectos_municipios.municipio_id')
        ->where([
            ['proyectos_municipios.proyecto_id',$request->proyecto_id], 
            ['Municipios.DeptId', $request->departamento_id]
        ])
        ->get();

    }else{

        $municipios = Municipio::select(
            'MunicipioId', 
            'NombreMunicipio')
        ->where('DeptId', $request->departamento_id)
        ->orderBy('NombreMunicipio', 'ASC')
        ->get();
    }

        return response()->json($municipios);
    }
}
