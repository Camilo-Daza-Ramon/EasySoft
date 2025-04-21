<?php

namespace App\Http\Controllers;

use App\ActivoFijo;
use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infraestructura;
use App\Instalacion;
use App\Insumo;
use App\Proyecto;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstalacionesInfraestructuraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        if (Auth::user()->can('instalaciones-infraestructura-listar')) {
            if (Auth::user()->hasRole('tecnico')) {
                $instalaciones = Instalacion::Cedula($request->get('documento'))
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Serial($request->get('serial'))
                    ->where([
                        ['instalaciones.user_id', Auth::user()->id],
                        ['instalaciones.estado', 'RECHAZADO']
                    ])
                    ->whereNotNull('infraestructura_id')
                    ->paginate(15);
            } elseif (Auth::user()->hasRole('auditor')) {
                $instalaciones = Instalacion::Cedula($request->get('documento'))
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Estado('PENDIENTE')
                    ->Serial($request->get('serial'))
                    ->whereNotNull('infraestructura_id')
                    ->paginate(15);
            } else {
                $instalaciones = Instalacion::Cedula($request->get('documento'))
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Estado($request->get('estado'))
                    ->Serial($request->get('serial'))
                    ->whereNotNull('infraestructura_id')
                    ->where(function ($query) {
                        if (Auth::user()->proyectos()->count() > 0) {
                            $query->whereHas('cliente', function ($query) {
                                $query->whereIn('Clientes.ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                            });
                        }
                    })
                    ->paginate(15);
            }

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
                ->where(function ($query) {
                    if (Auth::user()->proyectos()->count() > 0) {
                        $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                    }
                })
                ->get();

            $estados = array('APROBADO', 'PENDIENTE', 'RECHAZADO');

            return view('adminlte::instalaciones.partials.infra.index', compact('instalaciones', 'proyectos', 'estados'));
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($infra_id)
    {
        if (!Auth::user()->can('instalaciones-infraestructura-crear')) {
            return abort(403);
        }

        $infraestructura = Infraestructura::findOrFail($infra_id);
        $tipos_equipos = ['Computador de Escritorio', 'Computador Portatil', 'Celular - SmartPhone', 'Tablet', 'Tv - SmartTV'];
        $tipos_conexion = ['Planta Electrica', 'Panel Solar', 'Sistema Electrico Local', 'UPS'];
        $tipos_pelectrica = ['Estabilizador', 'UPS', 'Proteccion de Equipo', 'N/A'];
        $estados_otros = ['Conectado', 'No Conectado'];
        $tipos_retenciones = ['Herraje Tensor Plastico', 'Herraje Tensor Metalico'];
        $estados = array('APROBADO', 'RECHAZADO', 'PENDIENTE');
        $tipos_correas = ['10cm', '15cm', '20cm', '30cm', '55cm'];
        $tipos_chazos = ['1/4', '3/8'];

        $materiales = [
            [
                'nombre' => 'Conector SC/APC',
                'tipo' => []
            ],
            [
                'nombre' => 'Conector PigTail SC/APC',
                'tipo' => []
            ],
            [
                'nombre' => 'Cinta Bandit',
                'tipo' => []
            ],
            [
                'nombre' => 'Hebilla',
                'tipo' => []
            ],
            [
                'nombre' => 'Gancho Poste',
                'tipo' => []
            ],
            [
                'nombre' => 'Gancho Pared',
                'tipo' => []
            ],
            [
                'nombre' => 'Tornillo',
                'tipo' => [
                    '1/4'
                ]
            ],
            [
                'nombre' => 'Rosetas',
                'tipo' => []
            ],
            [
                'nombre' => 'Patch Cord',
                'tipo' => [
                    'FIBRA',
                    'UTP'
                ]
            ],
            [
                'nombre' => 'Retenciones',
                'tipo' => [
                    'Herraje Tensor Plastico',
                    'Herraje Tensor Metalico'
                ]
            ],
            [
                'nombre' => 'Correa de Amarre',
                'tipo' => [
                    '10 cm',
                    '15 cm',
                    '20 cm',
                    '30 cm',
                    '55 cm'
                ]
            ],
            [
                'nombre' => 'Chazos',
                'tipo' => [
                    '1/4',
                    '3/8'
                ]
            ],
            [
                'nombre' => 'Fibra Optica Drop',
                'tipo' => [
                    '1 hilo'
                ]
            ]
        ];

        $insumos = Insumo::select(['InsumoId', 'Codigo'])
            ->where('EsActivo', '=', 'Si')->where('InsumoTipo', '=', 'EQUIPO')->whereNotNull('Codigo')->get();

        return view('adminlte::instalaciones.partials.infra.create', [
            'infra' => $infraestructura,
            'tipos_equipos' => $tipos_equipos,
            'tipos_conexion' => $tipos_conexion,
            'tipos_pelectrica' => $tipos_pelectrica,
            'estados_otros' => $estados_otros,
            'tipos_retenciones' => $tipos_retenciones,
            'tipos_correas' => $tipos_correas,
            'tipos_chazos' => $tipos_chazos,
            'estados' => $estados,
            'materiales' => $materiales,
            'insumos' => $insumos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $infra_id)
    {
        if (Auth::user()->can('instalaciones-infraestructura-crear')) {
            $this->validate($request, [
                'serial' => 'required',
                'tipo_equipo' => 'required',
                'tipo_conexion' => 'required',
                'marca_equipo' => 'required',
                'serial_equipo' => 'required',
                'tipo_conexion' => 'required',
                'tipo_proteccion' => 'required',
                'marca_equipo_pe' => 'required',
                'cantidad_equipos' => 'required',
                'coordenadas' => 'required',
                'vel_subida' => 'required',
                'vel_bajada' => 'required',


            ]);

            $result = DB::transaction(function () use ($request, $infra_id) {

                
                if (!empty($request->firma_tecnico)) {

                    $tecnico = User::find(Auth::user()->id);

                    $file = $request->firma_tecnico;
                    $directory = 'usuarios/' . $tecnico->id;
                    $nombre = "firma";
                    $extension = 'jpg';
                    $tamaño = 800;

                    $file = Image::make($file)->resize($tamaño, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode($extension)->__toString();

                    //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                    $ruta = $directory . '/' . $nombre . '.' . $extension;

                    //Indicamos que queremos guardar un nuevo archivo en el disco local
                    //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));            
                    Storage::disk('public')->put($ruta, $file);

                    $existe = Storage::disk('public')->exists($ruta);

                    if ($existe) {

                        $tecnico->firma = $ruta;

                        if (!$tecnico->save()) {
                            DB::rollBack();
                            Storage::disk('public')->deleteDirectory($directory);
                            return ['error', 'Error al guardar la firma del tecnico'];
                        }
                    } else {
                        DB::rollBack();
                        Storage::disk('public')->deleteDirectory($directory);
                        return ['error', 'Error al subir la firma del tecnico'];
                    }
                }

                $ont = ActivoFijo::where('serial', $request->serial)->first();

                $instalacion = new Instalacion;
                $instalacion->infraestructura_id = $infra_id;
                $instalacion->serial_ont = $request->serial;

                $instalacion->tipo_conexion = $request->tipo_equipo;
                $instalacion->marca_equipo = $request->marca_equipo;
                $instalacion->serial_equipo = $request->serial_equipo;
                $instalacion->estado_equipo = $request->estado_equipo;

                $instalacion->tipo_conexion_electrica = $request->tipo_conexion;
                $instalacion->tipo_proteccion_electrica = $request->tipo_proteccion;
                $instalacion->marca_proteccion_electrica = $request->marca_equipo_pe;
                $instalacion->serial_proteccion_electrica = "N/A";
                $instalacion->estado_conexion_electrica = $request->estado_equipo_pe;

                $instalacion->cantidad_equipos_conectados = $request->cantidad_equipos;
                $instalacion->velocidad_bajada = $request->vel_bajada;
                $instalacion->velocidad_subida = $request->vel_subida;


                $instalacion->servicio_activo = $request->servicio_activo;
                $instalacion->cumple_velocidad_contratada = $request->cumple_velocidad;

                $instalacion->conector = $request->conector;
                $instalacion->pigtail = $request->pigtail;

                $instalacion->cinta_bandit = $request->cinta_bandit;
                $instalacion->hebilla = $request->hebilla;

                $instalacion->gancho_poste = $request->gancho_poste;
                $instalacion->gancho_pared = $request->gancho_pared;
                $instalacion->tornillo = $request->tornillo;
                $instalacion->roseta = $request->roseta;
                $instalacion->patch_cord_fibra = $request->patch_cord_fibra;
                $instalacion->patch_cord_utp = $request->patch_cord_utp;

                $instalacion->cant_retenciones = $request->cantidad_retenciones;
                $instalacion->tipo_retenciones = $request->tipo_retencion;

                $instalacion->cant_correa_amarre = $request->cant_correa_amarre;
                $instalacion->tipo_correa_amarre = $request->tipo_correa;

                $instalacion->cant_chazo = $request->cant_chazo;
                $instalacion->tipo_chazo = $request->tipo_chazo;

                $instalacion->fibra_drop_desde = $request->fibra_drop_desde;
                $instalacion->fibra_drop_hasta = $request->fibra_drop_hasta;


                $instalacion->caja = $request->caja;
                $instalacion->puerto = $request->puerto;
                $instalacion->sp_splitter = $request->sp_splitter;
                $instalacion->ss_splitter = $request->ss_splitter;
                $instalacion->tarjeta = $request->tarjeta;
                $instalacion->modulo = $request->modulo;

                $coordenadas = explode(',', $request->coordenadas);

                if (count($coordenadas) < 2) {
                    DB::rollBack();
                    return ['error', 'Las coordenadas estan mal.'];
                }

                $instalacion->latitud = $coordenadas[0];
                $instalacion->longitud = $coordenadas[1];

                $instalacion->observaciones = $request->observaciones;
                $infra = Infraestructura::findOrFail($infra_id);

                $instalacion->fecha = date('Y-m-d');

                $instalacion->estado = 'PENDIENTE';
                $instalacion->user_id = Auth::user()->id;
                $instalacion->activo_fijo_id = $ont->ActivoFijoId;

                if ($instalacion->save()) {
                    

                    $infra->estado = 'ACTIVO';

                    if (!$infra->save()) {
                        DB::rollBack();
                        return ['error', 'Error al actualizar el estado de la infraestructura'];
                    }

                    $ont->Estado = 'ASIGNADA';

                    if (!$ont->save()) {
                        DB::rollBack();
                        return ['error', 'No se actualizó el estado en el inventario'];
                    }
                    


                    return ['success', 'Instalacion agregada correctamente.'];
                } else {
                    DB::rollBack();
                    return ['error', 'Error al crear la instalacion'];
                }
            });

            return response()->json(['resultado' => $result[0], 'mensaje' => $result[1]]);
        } else {
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

    public function instalar(Request $request)
    {
        if (!Auth::user()->can('instalaciones-infraestructura-crear')) {
            return abort(403);
        }

        $clientes = Infraestructura::Nombre($request->get('nombre'))
            ->Proyecto($request->get('proyecto'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            ->where('estado', 'EN INSTALACION')
            ->paginate(15);

        $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
            ->where(function ($query) {
                if (Auth::user()->proyectos()->count() > 0) {
                    $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                }
            });

        return view('adminlte::instalaciones.partials.infra.instalar', [
            'instalaciones' => $clientes,
            'proyectos' => $proyectos,
        ]);
    }
}
