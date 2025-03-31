<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


use App\User;
use Storage;


class PerfilController extends Controller
{


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id != Auth::user()->id){
            abort(403);
        }else{
            $usuario = User::findOrFail($id);
            return view('adminlte::perfil.show', compact('usuario'));
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
        if($id != Auth::user()->id){
            abort(403);
        }else{
            $usuario = User::find($id);
            $usuario->celular = $request->celular;

            $otracontrasena = false;

            if (!empty($request->password)) {
                $this->validate($request,[                
                    'password' => 'required|min:6|confirmed',
                ]);
                $otracontrasena = true;
                $usuario->password = bcrypt($request->password);
            }

            //Declaramos una ruta
            $directory = 'usuarios/'. $usuario->id;

            if (!empty($request->avatar)) {
                # code...
                $this->validate($request,[
                    'avatar'=> 'required|mimes:jpeg,png,jpg',    
                ]);

                $size = $request->file('avatar')->getClientsize();

                if ($size > 25000) {
                    return redirect('perfil')->with('warning','La imagen es muy grande. debe ser de 160x160px, con tamaño menor a 15kb.');                    
                }

                $file = $request->file('avatar');

                //Si no existe el directorio, lo creamos
                if (!file_exists($directory)) {
                    //Creamos el directorio
                    Storage::makeDirectory($directory);
                }

                //Asignamos el nombre al archivo
                $nombre = 'avatar';

                //Obtenemos el tipo de archivo que se esta subiendo
                $extension = strtolower($request->file('avatar')->getClientOriginalExtension());

                //Eliminamos el archivo existente para reemplazarlo por el nuevo
                if (Storage::exists($usuario->avatar)){
                        Storage::delete($usuario->avatar);
                }            

                //Indicamos que queremos guardar un nuevo archivo en el directorio publico
                Storage::disk('public')->put($directory.'/' . $nombre.'.'.$extension, \File::get($file));

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $imagenavatar = $directory.'/'.$nombre.'.'.$extension;

                $usuario->avatar = $imagenavatar;            
            }


            if (!empty($request->firma)) {
                # code...
                $this->validate($request,[
                    'firma'=> 'required|mimes:jpeg,png,jpg',    
                ]);


                //Asignamos el nombre al archivo
                $nombre = 'firma';

                $file = $request->firma;
                $extension = strtolower($file->getClientOriginalExtension()); 

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $ruta = $directory.'/'.$nombre.'.'.$extension;

                if (!file_exists($directory)) {
                    //Creamos el directorio
                    Storage::disk('public')->makeDirectory($directory);
                }

                Storage::disk('public')->put($ruta, \File::get($file));

                $existe = Storage::disk('public')->exists($ruta);

                if ($existe){
                    $usuario->firma = $ruta;
                }            
            }

            if ($usuario->save()) {            

                if ($otracontrasena) {
                    # code...
                    Auth::logout();
                    return redirect()->route('login');
                }else{
                    return redirect()->route('perfil.show', $id)->with('success','Datos actualizados');
                }

            }else{
                return redirect()->route('perfil.show', $id)->with('warning','No se pudo actualizar');
            }

        }
    }

}
