<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\InstalacionArchivo;
use App\Custom\ImageTextBlur;
use Storage;
use Image;

class InstalacionesArchivosController extends Controller
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
    public function store(Request $request, $instalacion)
    {
        if (Auth::user()->can('instalacion-archivo-crear')) {
            $this->validate(request(),[
                'archivo' => 'required|mimes:pdf,jpg,jpeg,png|max:5000',
                'nombre' => 'required',
                'documento' => 'required'
            ]);

            //validar si existen dos registros con el mismo nombre
            $validar = InstalacionArchivo::where([['instalacion_id', $instalacion], ['nombre',$request->nombre]])->count();

            if ($validar > 0) {
                return redirect()->route('instalaciones.edit', $instalacion)->with('warning', 'Ya existe una archivo con el mismo nombre');
            }

            $tamaño = 1500;
            $nombre = $request->nombre;
            //Declaramos una ruta
            $directory = 'installations/'.$instalacion;
            $file = $request->archivo;

            //Si no existe el directorio, lo creamos
            if (!file_exists($directory)) {
                //Creamos el directorio
                Storage::makeDirectory('public/'.$directory);
            }

            if (!empty($file)) {

                if ($nombre == 'firma') {
                    $extension = 'jpg';
                    $tamaño = 1382;
                }else{
                    //Obtenemos el tipo de archivo que se esta subiendo
                    $extension = strtolower($file->getClientOriginalExtension());
                }            

                if ($extension != 'pdf') {
                   $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save('foto.jpg',90);
               }
               

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $documento = $directory.'/'.$nombre.'.'.$extension;

                //Indicamos que queremos guardar un nuevo archivo en el disco local
                //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));

                if ($extension != 'pdf') {
                    Storage::disk('public')->put($documento, $file);  
                }else{
                    Storage::disk('public')->put($documento, \File::get($file));
                }

                $existe = Storage::disk('public')->exists($documento);

                if ($existe) {

                    $archivo = new InstalacionArchivo;
                    $archivo->nombre = $nombre;
                    $archivo->archivo = $documento;
                    $archivo->tipo_archivo = $extension;
                    $archivo->estado = 'EN REVISION';
                    $archivo->instalacion_id = $instalacion;            

                    if ($archivo->save()) {

                        if ($archivo->nombre == 'speedtest') {
                            $caracter = '\\';
                            $carpeta_archivos = 'D:'.$caracter.'Awebsites'.$caracter.'ConstruyendoWebSite'.$caracter.'easy'.$caracter.'public'.$caracter.'storage'.$caracter;

                            $this->estampar_coordenadas($archivo->archivo, $archivo->archivo, number_format($archivo->instalacion->latitud,5,'.',''), number_format($archivo->instalacion->longitud,5,'.',''),$extension,$archivo->instalacion->fecha);
                        }

                        return redirect()->route('instalaciones.edit', $instalacion)->with('success', 'Archivo subido satisfactoriamente!');
                    }else{
                        return redirect()->route('instalaciones.edit', $instalacion)->with('error', 'Error al guardar el archivo!');
                    }
                }else{
                    return redirect()->route('instalaciones.edit', $instalacion)->with('error', 'El archivo no fue subido.');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $instalacion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($instalacion, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $instalacion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $instalacion, $id)
    {              

        if ($request->ajax()) {
            $archivo = InstalacionArchivo::find($id);
            $archivo->estado = $request->estado;        

            if ($archivo->save()) {
                return response()->json(['result' => true]);
            }
        }else{

            if (Auth::user()->hasRole('tecnico')) {

                $this->validate(request(),[
                    'archivo' => 'required|mimes:jpg,jpeg,png|max:5000'
                ]);
            }
            
            $archivo = InstalacionArchivo::find($id);

            $file = $request->archivo;


            if (!empty($file)) {

                //Obtenemos el tipo de archivo que se esta subiendo
               $extension = strtolower($file->getClientOriginalExtension());

               
                $file = Image::make($request->archivo)->resize(1500,null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save('foto.jpg',90);
                

                //Si el archivo ya existe lo eliminamos para reemplazarlo por el nuevo
                if (Storage::exists($archivo->archivo)){
                    Storage::delete($archivo->archivo);
                }

                //Declaramos una ruta
                $directory = 'instalaciones/'.$archivo->instalacion->cliente->Identificacion;           

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $documento = $directory.'/'.$archivo->nombre.'.'.$extension;            

                //Indicamos que queremos guardar un nuevo archivo en el disco local
                //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));

                Storage::disk('public')->put($documento, $file);

                $archivo->archivo = $documento;
                $archivo->tipo_archivo = $extension;
            }

            $archivo->estado = $request->estado;
            $resultado = $archivo->save();

            if ($archivo->nombre == 'speedtest') {
                $caracter = '\\';
                $carpeta_archivos = 'D:'.$caracter.'Awebsites'.$caracter.'ConstruyendoWebSite'.$caracter.'easy'.$caracter.'public'.$caracter.'storage'.$caracter;

                $this->estampar_coordenadas($carpeta_archivos . $archivo->archivo, $carpeta_archivos . $archivo->archivo, number_format($archivo->instalacion->latitud,5,'.',''), number_format($archivo->instalacion->longitud,5,'.',''),$archivo->instalacion->fecha);
            }

            if ($resultado) {
                return redirect()->route('instalaciones.edit', $instalacion)->with('success', 'Archivo subido satisfactoriamente!');
            }else{
                return redirect()->route('instalaciones.edit', $instalacion)->with('error', 'No se pudo subir');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $instalacion
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($instalacion,$id)
    {

        if (Auth::user()->can('instalacion-archivo-eliminar')) {
            $archivo = InstalacionArchivo::findOrFail($id);

            //validar si existen dos registros con el mismo nombre
            $validar = InstalacionArchivo::where([['instalacion_id', $archivo->instalacion_id], ['nombre',$archivo->nombre], ['tipo_archivo', $archivo->tipo_archivo]])->count();

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

                return redirect()->route('instalaciones.edit', $instalacion)->with('success','Archivo eliminado con exíto!');
            }else{
                return redirect()->route('instalaciones.edit', $instalacion)->with('error','No se pudo eliminar el archivo.');
            }
        }else{
            abort(403);
        }
    }  


    private function estampar_coordenadas($archivo, $destino, $latitud, $longitud, $extension, $fecha){

        $path = Storage::disk('public')->path($archivo);
        //imageCreateFromPng

        $im = null;

        if($extension == "png"){
            $im = imagecreatefrompng($path);
        }else{
            $im = imagecreatefromjpeg($path);
        } 

        $font             = "C:\Windows\Fonts\arial.ttf";
        $width            = imagesx($im);
        $height           = imagesy($im) - 40;
        $string = "Fecha: $fecha \nLatitud: $latitud, Longitud: $longitud";

        // set our image's colors
        $text_color       = imagecolorallocate($im, 255, 255, 255);
        $shadow_color     = imagecolorallocate($im, 0x00, 0x00, 0x00);

        $imagenblur = new ImageTextBlur;



        // place the shadow onto our image
        $imagenblur->imagettftextblur(
            $im, 18, 0, 20, $height - 7,
            $shadow_color,
            $font,
            $string,
            10
        );

        // place the text onto our image
        $imagenblur->imagettftextblur(
            $im, 18, 0, 20, $height - 7,
            $text_color,
            $font,
            $string
        );


        imagejpeg($im, $path);
    }
}
