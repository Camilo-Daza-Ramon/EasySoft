<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Custom\ImageTextBlur;
use App\Cliente;
use App\ArchivoCliente;
use Storage;
use Image;


class ArchivosClientesController extends Controller
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('clientes-archivos-crear')) {
            $this->validate(request(),[
                'archivo' => 'required|mimes:jpg,jpeg,png|max:5000'
            ]);

            $tamaño = 1500;
            $nombre = $request->nombre;
            //Declaramos una ruta
            $directory = 'clientes/'.$request->documento;
            $file = $request->archivo;

            //Si no existe el directorio, lo creamos
            if (!file_exists($directory)) {
                //Creamos el directorio
                Storage::makeDirectory('public/'.$directory);
            }

            if (!empty($file)) {

                if ($nombre == 'firma') {
                    $extension = 'jpg';
                    $tamaño = 1300;
                }else{
                    //Obtenemos el tipo de archivo que se esta subiendo
                    $extension = strtolower($file->getClientOriginalExtension());
                }

                $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($extension)->__toString();

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $ruta = $directory.'/'.$nombre.'.'.$extension;

                //Indicamos que queremos guardar un nuevo archivo en el disco local
                //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));

            
                Storage::disk('public')->put($ruta, $file);

                $existe = Storage::disk('public')->exists($ruta);

                if ($existe) {

                    $cliente = Cliente::findOrFail($request->cliente_id);     

                    $archivo = new ArchivoCliente;
                    $archivo->nombre = $nombre;
                    $archivo->archivo = $ruta;
                    $archivo->tipo_archivo = $extension;

                    if ($cliente->Status == 'RECHAZADO' || $cliente->Status == 'PENDIENTE') {
                        $archivo->estado = 'EN REVISION';
                    }else{
                        $archivo->estado = 'APROBADO';
                    }                
                    
                    $archivo->ClienteId = $request->cliente_id;            

                    if ($archivo->save()) {

                        if ($archivo->nombre == 'foto_vivienda') {                       
                            $this->estampar_coordenadas($ruta, $ruta, $cliente->Latitud, $cliente->Longitud);
                        }

                        return redirect()->route('clientes.show', $request->cliente_id)->with('success', 'Archivo subido satisfactoriamente!');
                    }else{
                        return redirect()->route('clientes.show', $request->cliente_id)->with('error', 'Error al actualizar estado del cliente!');
                    }
                }else{
                    return redirect()->route('clientes.show', $request->cliente_id)->with('error', 'El archivo no fue subido.');
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

        if (Auth::user()->can('clientes-archivos-editar')) {
            //dd($request->file('archivo'));

            if (Auth::user()->hasRole('vendedor')) {

                $this->validate(request(),[
                    'archivo' => 'required|mimes:jpg,jpeg,png|max:5000'
                ]);
            }
            
            $archivo = ArchivoCliente::find($id);

            $resultado = [];

            $tamaño = 1500;
            $nombre = $archivo->nombre;
            //Declaramos una ruta
            $directory = 'clientes/'.$archivo->cliente->Identificacion;
            $file = $request->archivo;

            //Si no existe el directorio, lo creamos
            if (!file_exists($directory)) {
                //Creamos el directorio
                Storage::makeDirectory('public/'.$directory);
            }

            if (!empty($file)) {

                if ($nombre == 'firma') {
                    $extension = 'jpg';
                    $tamaño = 1300;
                }else{
                    //Obtenemos el tipo de archivo que se esta subiendo
                    $extension = strtolower($file->getClientOriginalExtension());
                }

                $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($extension)->__toString();

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $ruta = $directory.'/'.$nombre.'.'.$extension;


                //Si el archivo ya existe lo eliminamos para reemplazarlo por el nuevo
                if (Storage::disk('public')->exists($archivo->archivo)){
                    Storage::disk('public')->delete($archivo->archivo);
                }

            
                Storage::disk('public')->put($ruta, $file);

                $existe = Storage::disk('public')->exists($ruta);

                if ($existe) {
                    $archivo->archivo = $ruta;
                    $archivo->tipo_archivo = $extension;
                    $archivo->estado = 'EN REVISION';         

                    if ($archivo->nombre == 'foto_vivienda') {                       
                        $this->estampar_coordenadas($ruta, $ruta, $cliente->Latitud, $cliente->Longitud);
                    }

                    $resultado[1] = 'Archivo actualizado satisfactoriamente!';

                    //return redirect()->route('clientes.show', $request->cliente_id)->with('success', 'Archivo actualizado satisfactoriamente!');
                    
                }else{
                    //return redirect()->route('clientes.show', $request->cliente_id)->with('error', 'El archivo no fue subido.');
                    $resultado[0] = 'error';
                    $resultado[1] = 'El archivo no fue subido.';
                }
                
            }else{
                $archivo->estado = $request->estado;
                $resultado[1] = 'Estado actualizado.';          
            }


            if ($archivo->save()) {
                $resultado[0] = 'success';            
            }else{
                //return redirect()->route('clientes.show', $request->cliente_id)->with('error', 'Error al actualizar el archivo!');
                $resultado[0] = 'error';
                $resultado[1] = 'Error al actualizar el archivo!';
            }

            if ($request->ajax()) {
                return response()->json(['result' => $resultado[0], 'mensaje' => $resultado[1]]);
            }else{
                return redirect()->route('clientes.show', $request->cliente_id)->with($resultado[0], $resultado[1]);
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
    public function destroy($id)
    {

        if (Auth::user()->can('clientes-archivos-eliminar')) {
            $archivo = ArchivoCliente::findOrFail($id);

            $cliente_id = $archivo->ClienteId;

            //validar si existen dos registros con el mismo nombre
            $validar = ArchivoCliente::where([['ClienteId', $archivo->ClienteId], ['nombre',$archivo->nombre], ['tipo_archivo', $archivo->tipo_archivo]])->count();

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

                return redirect()->route('clientes.show', $cliente_id)->with('success','Archivo eliminado con exíto!');
            }else{
                return redirect()->route('clientes.show', $cliente_id)->with('error','No se pudo eliminar el archivo.');
            }
        }else{
            abort(403);
        }
    }

    private function estampar_coordenadas($archivo, $destino, $latitud, $longitud){

        $path = Storage::disk('public')->path($archivo);
       
        $im = imagecreatefromjpeg($path);
        $fecha = date('Y-m-d');

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
