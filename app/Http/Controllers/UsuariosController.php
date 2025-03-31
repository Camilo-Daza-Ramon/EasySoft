<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Role;
use App\Cliente;
use App\Proyecto;

use App\Mail\Bienvenida;
use App\Permission;
use Charts;
use Storage;
use Excel;


class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('usuarios-listar')) {
            $usuarios = User::Buscar($request->get('palabra'))
                ->buscarPorRol($request->get('rol'))
                ->buscarPorEstado($request->get('estado'))
                ->paginate(15);
            $roles = Role::orderBy('display_name', 'ASC')->get();
            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->orderBy('NumeroDeProyecto', 'ASC')->get();
            return view('adminlte::usuarios.index', compact('usuarios', 'roles', 'proyectos'));
        } else {
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
        if (Auth::user()->can('usuarios-crear')) {
            $this->validate(request(), [
                'name' => 'sometimes|required|max:255|unique:users',
                'email'    => 'required|email|max:255|unique:users',
            ]);


            $pass = chr(rand(ord('a'), ord('z'))) . rand(1, 1000) . chr(rand(ord('a'), ord('z'))) . chr(rand(ord('a'), ord('z')));

            $usuario = new User;
            $usuario->name = $request->user;
            $usuario->email = $request->email;
            $usuario->password =  bcrypt($request->contrasena);
            $usuario->cedula = $request->cedula;
            $usuario->celular = $request->celular;
            $usuario->estado = $request->estado;

            if ($usuario->save()) {

                $usuario->roles()->attach($request->rol);
                $usuario->proyectos()->attach($request->proyectos);

                if (!empty($request->firma)) {
                    # code...
                    $this->validate($request, [
                        'firma' => 'required|mimes:jpeg,png,jpg',
                    ]);


                    $file = $request->file('firma');

                    //Declaramos una ruta
                    $directory = 'usuarios/' . $usuario->id;

                    //Si no existe el directorio, lo creamos
                    if (!file_exists($directory)) {
                        //Creamos el directorio
                        Storage::makeDirectory($directory);
                    }

                    //Asignamos el nombre al archivo
                    $nombre = 'firma';

                    //Obtenemos el tipo de archivo que se esta subiendo
                    $extension = strtolower($request->file('firma')->getClientOriginalExtension());

                    //Indicamos que queremos guardar un nuevo archivo en el directorio publico
                    Storage::put('public/' . $directory . '/' . $nombre . '.' . $extension, \File::get($file));

                    //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                    $firma = $directory . '/' . $nombre . '.' . $extension;

                    $usuario->firma = $firma;
                    $usuario->save();
                }

                //Mail::to($usuario->email)->send(new Bienvenida($usuario->email, $usuario->name,$usuario->email, $request->contrasena));

                return redirect()->route('usuarios.index')->with('success', 'Registro creado con exito!');
            } else {
                return redirect()->route('usuarios.index')->with('error', 'No se pudo crear.');
            }
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        /*
        $primer_dia = date('Y-m');
            $ultimo_dia = date('Y-m-t',strtotime($primer_dia));

            $usuario = User::findOrFail($id);
            $clientes = Cliente::selectRaw('Status,count(Status) as cantidad')->groupBy('Status')->where([['user_id', $id], ['ProyectoId', $proyecto]])->get();

            $ventas_fecha = Cliente::selectRaw('ClienteId, Fecha')->where([['user_id',$id], ['ProyectoId', $proyecto]])->whereBetween('Fecha', [$primer_dia . '-1', $ultimo_dia])->orderBy('Fecha','ASC')->get();

            $grafica_fecha_clientes = Charts::database($ventas_fecha, 'line', 'highcharts')
                ->title('Total clientes en el mes')
                ->elementLabel("Total")
                ->responsive(true)
                ->groupBy('Fecha');
            return view('adminlte::perfil.show', compact('usuario','clientes', 'grafica_fecha_clientes'));
        */

        if (Auth::user()->can('usuarios-ver')) {
            return view('adminlte::usuarios.show', compact('usuario'));
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        if (Auth::user()->can('usuarios-editar')) {

            $roles = Role::select('name', 'id')->get()->pluck('name', 'id');
            $proyectos = Proyecto::select('NumeroDeProyecto', 'ProyectoID')->get()->pluck('NumeroDeProyecto', 'ProyectoID');

            return view('adminlte::usuarios.edit', compact('usuario', 'roles', 'proyectos'));
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        if (Auth::user()->can('usuarios-editar')) {

            $usuario->name = $request->name;
            $usuario->cedula = $request->cedula;
            $usuario->celular = $request->celular;
            $usuario->estado = $request->estado;

            if (!empty($request->firma)) {
                //Declaramos una ruta
                $directory = 'usuarios/' . $usuario->id;
                $this->validate($request, [
                    'firma' => 'required|mimes:jpeg,png,jpg',
                ]);

                //Asignamos el nombre al archivo
                $nombre = 'firma';

                $file = $request->firma;
                $extension = strtolower($file->getClientOriginalExtension());

                //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                $ruta = $directory . '/' . $nombre . '.' . $extension;

                if (!file_exists($directory)) {
                    //Creamos el directorio
                    Storage::disk('public')->makeDirectory($directory);
                }

                Storage::disk('public')->put($ruta, \File::get($file));

                $existe = Storage::disk('public')->exists($ruta);

                if ($existe) {
                    $usuario->firma = $ruta;
                }
            }

            if (!empty($request->password)) {
                $this->validate($request, [
                    'password' => 'required|min:6|confirmed',
                ]);

                $usuario->password = bcrypt($request->password);
            }

            if ($usuario->save()) {

                $usuario->roles()->sync($request->roles);
                $usuario->proyectos()->sync($request->proyectos);


                return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
            } else {
                return redirect()->route('usuarios.index')->with('error', 'Error al actualizar el usuario.');
            }
        } else {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        //if (Auth::user()->hasRole('admin')) {
        if (Auth::user()->can('usuarios-eliminar')) {

            $usuario->estado = 'INACTIVO';

            if ($usuario->save()) {
                return redirect()->route('usuarios.index')->with('success', 'Registro actualizado con exíto!');
            } else {
                return redirect()->route('usuarios.index')->with('error', 'No fue posible actualizar.');
            }
        } else {
            abort(403);
        }
    }

    public function perfil()
    {
        $usuario = User::findOrFail(Auth::user()->id);
        return view('adminlte::perfil.index', compact('usuario'));
    }

    public function ventas(Request $request)
    {
        $ventas = Cliente::selectRaw("COUNT(ClienteId) as total_ventas, Fecha")
            ->where(function ($query) use ($request) {
                if (!empty($request->mes)) {
                    $query->whereBetween('Fecha', [$request->mes . '-01',  date('Y-m-t', strtotime($request->mes))]);
                }

                if (!empty($request->municipio)) {
                    $query->where('municipio_id', $request->municipio);
                }

                if (Auth::user()->hasRole('vendedor')) {
                    $query->where('user_id', Auth::user()->id);
                }

                if (!empty($request->proyecto)) {
                    $query->where('ProyectoId', $request->proyecto);
                }
            })
            ->groupBy('Fecha')
            ->orderBy('Fecha', 'ASC')
            ->get();

        $label = array();
        $dataset_ventas = array();

        foreach ($ventas as $dato) {
            $label[] = $dato->Fecha;
            $dataset_ventas[] = intval($dato->total_ventas);
        }

        return response()->json(['labels' => $label, 'ventas' => $dataset_ventas]);
    }

    public function buscarRoles(Request $request)
    {
        if (!Auth::user()->can('roles-listar')) {
            abort(403);
            return;
        }
        $palabra = $request->get('palabra');
        if ($palabra != null) {
            $models = Role::where('name', 'like', '%' . request()->get('palabra') . '%')->paginate(5);
            return view('entrust-gui::roles.index', compact('models'));
        }
        return redirect()->route('entrust-gui::roles.index');
    }

    public function buscarPermisos(Request $request)
    {
        if (!Auth::user()->can('permisos-listar')) {
            abort(403);
            return;
        }
        $palabra = $request->get('palabra');
        if ($palabra != null) {
            $models = Permission::where('display_name', 'like', '%' . request()->get('palabra') . '%')->paginate(5);
            return view('entrust-gui::permissions.index', compact('models'));
        }
        return redirect()->route('entrust-gui::permissions.index');
    }


    public function exportar(Request $request)
    {
        if (!Auth::user()->can('usuarios-exportar')) {
            abort(403);
            return;
        }

        $usuarios = User::Buscar($request->get('palabra'))
            ->buscarPorRol($request->get('rol'))
            ->buscarPorEstado($request->get('estado'))
            ->get();
        
        if ($usuarios->count() == 0) {
            return redirect()->back()->with('error', 'No tienes registros para exportar');
        }

        Excel::create('usuarios', function ($excel) use ($usuarios) {

            $excel->sheet('lista-usuarios', function ($sheet) use ($usuarios) {


                $datos = [];
                foreach ($usuarios as $usuario) {

                    $i = 0;
                    $datos[] = array(
                        'NOMBRE' => $usuario->name,
                        'CORREO' => $usuario->email,
                        'ESTADO' => $usuario->estado,
                        'CELULAR' => $usuario->celular,
                        'ROLES' => $usuario->roles->reduce(function ($carry, $item) use  ($usuario, &$i){
                            $carry .= $item->display_name;
                            if (count($usuario->roles) - 1 != $i) {
                                $carry .= ' | ';
                            }
                            $i++;
                            return $carry;
                        })
                    );
                }

                $sheet->fromArray($datos, true, 'A1', true);
            });
        })->export('xlsx');
    }
}
