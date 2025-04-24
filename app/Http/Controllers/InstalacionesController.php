<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Custom\ImageTextBlur;
use App\Instalacion;
use App\InstalacionArchivo;
use App\User;
use App\Cliente;
use App\ClienteContrato;
use App\ContratoServicio;
use App\Olt;
use App\Departamento;
use App\Proyecto;
use App\ClienteOntOlt;
use App\ActivoFijo;
use App\Infraestructura;
use Storage;
use Hash;
use Image;
use Excel;
use Zipper;
use PDF;
use DB;

class InstalacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function guardarInstalacion(Request $request) { //FUNCION PARA GUARDAR LOS DATOS DE LA INSTALACION DEL PROYECTO GUAJIRA
        // Validar los datos enviados
        $request->validate([
            'TipoConexion' => 'required|string|max:255',
            'EstructuraInstalacion' => 'nullable|string|max:255',
            // Campos del formulario para instalacion por Cableado
            'RouterSerial' => 'nullable|string|max:255',
            'CableUTP' => 'nullable|integer|min:0',
            'SwitchPuerto' => 'nullable|integer|min:1|max:16',
            // Campos del formulario para instalacion Inhalambrica para los PAC-CC y NODOS
            'Paneles' => 'nullable|integer|min:0|max:2',
            'PotenciaPaneles' => 'nullable|integer|in:580,630',
            'ControladorSolar' => 'nullable|interger|min:0|max:1',
            'AccesPoint'=> 'nullable|integer|min:0|max:1',
            'SwitchPOE' => 'nullable|integer|min:0|max:1',
            'Switch' => 'nullable|integer|min:0|max:2',
            'Bateria' => 'nullable|integer|min:0|max:1',
            'Router' => 'nullable|integer|min:0|max:1',
            'ConversorDCDC' => 'nullable|integer|min:0|max:1',
            'AntenaSectorial' => 'nullable|integer|min:0|max:4',
            'AntenaReceptora' => 'nullable|integer|min:0|max:1',
            'CamaraIP' => 'nullable|integer|min:0|max:2',
            'CerboGX' => 'nullable|integer|min:0|max:1',
            'Inversor' => 'nullable|integer|min:0|max:1',
        ]);
    
        // Guardar los datos en la base de datos
        ClientesInstalaciones::create([
            'TipoConexion' => $request->input('TipoConexion'),
            'EstructuraInstalacion' => $request->input('EstructuraInstalacion'),
            // Guardar campos del formulario para instalacion por Cableado
            'RouterSerial' => $request->input('RouterSerial'),
            'CableUTP' => $request->input('CableUTP'),
            'SwitchPuerto' => $request->input('SwitchPuerto'),
            //Guardar los campor del formulario para instalacion Inhalambrica para los PAC-CC y NODOS
            'Paneles' => $request->input('Paneles'),
            'PotenciaPaneles' => $request->input('PotenciaPaneles'),
            'ControladorSolar' => $request->input('ControladorSolar'),
            'AccesPoint'=> $request->input('AccesPoint'),
            'SwitchPOE' => $request->input('SwitchPOE'),
            'Switch' => $request->input('Switch'),
            'Bateria' => $request->input('Bateria'),
            'Router' => $request->input('Router'),
            'ConversorDCDC' => $request->input('ConversorDCDC'),
            'AntenaSectorial' => $request->input('AntenaSectorial'),
            'AntenaReceptora' => $request->input('AntenaReceptora'),
            'CamaraIP' => $request->input('CamaraIP'),
            'CerboGX' => $request->input('CerboGX'),
            'Inversor' => $request->input('Inversor'),
        ]);
    
        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Datos guardados correctamente.');
    }



    public function index(Request $request)
    {

        if (Auth::user()->can('instalaciones-listar')) {
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
                    ->whereNotNull('ClienteId')
                    ->paginate(15);
            } elseif (Auth::user()->hasRole('auditor')) {
                $instalaciones = Instalacion::Cedula($request->get('documento'))
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Estado('PENDIENTE')
                    ->Serial($request->get('serial'))
                    ->whereNotNull('ClienteId')
                    ->paginate(15);
            } else {
                $instalaciones = Instalacion::Cedula($request->get('documento'))
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Estado($request->get('estado'))
                    ->Serial($request->get('serial'))
                    ->where(function ($query) {
                        if (Auth::user()->proyectos()->count() > 0) {
                            $query->whereHas('cliente', function ($query) {
                                $query->whereIn('Clientes.ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                            });
                        }
                    })
                    ->whereNotNull('ClienteId')
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

            return view('adminlte::instalaciones.index', compact('instalaciones', 'proyectos', 'estados'));
        } else {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function instalar(Request $request)
    {

        if (Auth::user()->can('instalaciones-crear')) {

            $graficar = null;

            $clientes = Cliente::Cedula($request->get('documento'))
                ->Proyecto($request->get('proyecto'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))
                ->where('Status', 'EN INSTALACION');

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();

            foreach ($clientes->get() as $dato) {

                if (!empty($dato->Latitud) && is_numeric($dato->Latitud)) {
                    $graficar[] = array(
                        'id' => $dato->ClienteId,
                        'nombre' => $dato->NombreBeneficiario . ' ' . $dato->Apellidos,
                        'latitud' => $dato->Latitud,
                        'longitud' => $dato->Longitud,
                        'direccion' =>  $dato->DireccionDeCorrespondencia,
                        'barrio' => $dato->Barrio
                    );
                }
            }

            $clientes =  $clientes->paginate(15);

            $graficar = json_encode($graficar);

            return view('adminlte::instalaciones.instalar', [
                'instalaciones' => $clientes,
                'proyectos' => $proyectos,
                'graficar' => $graficar
            ]);
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($cliente)
    {
        if (Auth::user()->can('instalaciones-crear')) {

            $cliente = Cliente::where('Status', 'EN INSTALACION')->find($cliente);

            //dd(array_values($cliente->archivos->toArray()));

            //dd(array_search('foto_vivienda', $cliente->archivos->toArray()));

            if ($cliente->count() > 0) {

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

                $index_key = null;

                foreach ($cliente->archivos as $key => $archivo) {
                    if ($this->buscar_palabra($archivo->nombre, ["vivienda", "fachada", "casa"])) {
                        $index_key = $key;
                    }
                }

                //$index_key = array_search(["vivienda", "fachada", "casa"] ,  array_column($cliente->archivos->toArray(),'nombre'));

                return view(
                    'adminlte::instalaciones.create',
                    compact(
                        'cliente',
                        'estados',
                        'tipos_equipos',
                        'tipos_conexion',
                        'tipos_pelectrica',
                        'estados_otros',
                        'tipos_retenciones',
                        'tipos_correas',
                        'tipos_chazos',
                        'index_key'
                    )
                );
            } else {
                return redirect()->route('instalaciones.index')->with('error', 'El cliente no esta en proceso de instalación.');
            }
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (Auth::user()->can('instalaciones-crear')) {

            $this->validate($request, [
                'serial_ont' => 'required',
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

                'pregunta_firma' => 'required',

                'speedtest' => 'required|mimes:jpg,jpeg,png',
                'ping' => 'required|mimes:jpg,jpeg,png',
                'navegacion' => 'required|mimes:jpg,jpeg,png',
                'youtube' => 'required|mimes:jpg,jpeg,png',
                'mintic' => 'required|mimes:jpg,jpeg,png',
                'instalacion' => 'required|mimes:jpg,jpeg,png',
                'firma' => 'required'
            ]);

            $result = DB::transaction(function () use ($request) {

                $instalaciones_reportadas = [
                    52054688 => '06-12-2024',
                    51603058 => '06-12-2024',
                    1109845160 => '06-12-2024',
                    1105056855 => '06-12-2024',
                    1080933044 => '06-12-2024',
                    1006069516 => '06-12-2024',
                    65788139 => '06-12-2024',
                    65787784 => '06-12-2024',
                    52934545 => '06-12-2024',
                    28853867 => '06-12-2024',
                    79876999 => '06-12-2024',
                    65789130 => '06-12-2024',
                    65790744 => '06-12-2024',
                    28852970 => '06-12-2024',
                    18411075 => '07-12-2024',
                    1012449287 => '07-12-2024',
                    65790023 => '07-12-2024',
                    93478413 => '07-12-2024',
                    5962414 => '07-12-2024',
                    28852765 => '07-12-2024',
                    11300346 => '07-12-2024',
                    93479016 => '07-12-2024',
                    1105059953 => '07-12-2024',
                    1007600976 => '07-12-2024',
                    5963597 => '07-12-2024',
                    1068926853 => '07-12-2024',
                    5869055 => '07-12-2024',
                    28853043 => '07-12-2024',
                    93443192 => '07-12-2024',
                    28648940 => '08-12-2024',
                    7539954 => '08-12-2024',
                    65790121 => '08-12-2024',
                    93444458 => '12-12-2024',
                    65789070 => '12-12-2024',
                    65786934 => '12-12-2024',
                    65586501 => '12-12-2024',
                    1109846491 => '07-12-2024',
                    65736764 => '07-01-2025',
                    1005780815 => '07-01-2025',
                    1110539535 => '07-01-2025',
                    28905095 => '07-01-2025',
                    28904421 => '07-01-2025',
                    65831450 => '07-01-2025',
                    1110462440 => '07-01-2025',
                    1105871219 => '07-01-2025',
                    28931693 => '07-01-2025',
                    28905276 => '07-01-2025',
                    38892752 => '07-01-2025',
                    14170095 => '07-01-2025',
                    28904380 => '07-01-2025',
                    5989795 => '07-01-2025',
                    65732679 => '08-01-2025',
                    28918014 => '08-01-2025',
                    1072592932 => '08-01-2025',
                    28904943 => '08-01-2025',
                    1105871267 => '08-01-2025',
                    1105871104 => '08-01-2025',
                    28904564 => '08-01-2025',
                    2369350 => '08-01-2025',
                    14244259 => '08-01-2025',
                    2284004 => '08-01-2025',
                    1110475531 => '08-01-2025',
                    28904807 => '09-01-2025',
                    1105870582 => '09-01-2025',
                    28905292 => '09-01-2025',
                    28904221 => '09-01-2025',
                    28904570 => '09-01-2025',
                    1075216035 => '09-01-2025',
                    93360048 => '09-01-2025',
                    70079379 => '09-01-2025',
                    38236575 => '09-01-2025',
                    51714016 => '10-01-2025',
                    1006095620 => '14-01-2025',
                    20423810 => '14-01-2025',
                    3057216 => '14-01-2025',
                    41760747 => '14-01-2025',
                    39577846 => '14-01-2025',
                    20647884 => '14-01-2025',
                    52655460 => '14-01-2025',
                    11430907 => '14-01-2025',
                    39575487 => '14-01-2025',
                    20647540 => '13-01-2025',
                    52092371 => '13-01-2025',
                    94501099 => '13-01-2025',
                    1003748656 => '13-01-2025',
                    3056939 => '13-01-2025',
                    5975839 => '13-01-2025',
                    1233907095 => '13-01-2025',
                    19432678 => '13-01-2025',
                    1069832542 => '13-01-2025',
                    39568077 => '13-01-2025',
                    52705099 => '13-01-2025',
                    1069832243 => '13-01-2025'
                ];

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

                $ont = ActivoFijo::where('serial', $request->serial_ont)->first();

                $instalacion = new Instalacion;
                $instalacion->ClienteId = $request->cliente_id;
                $instalacion->serial_ont = $request->serial_ont;

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
                $cliente = Cliente::find($request->cliente_id);

                if (array_key_exists($cliente->Identificacion, $instalaciones_reportadas)) {
                    $instalacion->fecha = date('Y-m-d', strtotime($instalaciones_reportadas[$cliente->Identificacion]));
                } else {
                    $instalacion->fecha = date('Y-m-d');
                }


                $instalacion->estado = 'PENDIENTE';
                $instalacion->user_id = Auth::user()->id;
                $instalacion->activo_fijo_id = $ont->ActivoFijoId;

                if ($instalacion->save()) {

                    //Declaramos una ruta
                    $directory = 'installations/' . $instalacion->id;

                    /*if($request->pregunta_firma == 'FIRMAR'){
                        $request->firma = base64_decode($request->firma);
                    }*/

                    $this->guardar_archivos($instalacion->id, $directory, 'firma', $request->firma, $instalacion->fecha);
                    $this->guardar_archivos($instalacion->id, $directory, 'speedtest', $request->speedtest, $instalacion->fecha, $coordenadas[0], $coordenadas[1]);
                    $this->guardar_archivos($instalacion->id, $directory, 'ping', $request->ping, $instalacion->fecha);
                    $this->guardar_archivos($instalacion->id, $directory, 'navegacion', $request->navegacion, $instalacion->fecha);
                    $this->guardar_archivos($instalacion->id, $directory, 'youtube', $request->youtube, $instalacion->fecha);
                    $this->guardar_archivos($instalacion->id, $directory, 'mintic', $request->mintic, $instalacion->fecha);
                    $this->guardar_archivos($instalacion->id, $directory, 'instalacion', $request->instalacion, $instalacion->fecha);

                    $contrato = ClienteContrato::where([
                        ['estado', 'PENDIENTE'],
                        ['ClienteId', $request->cliente_id]
                    ])->first();

                    if (!empty($contrato)) {
                        $contrato->fecha_instalacion = date('Y-m-d');
                        $contrato->estado = 'VIGENTE';
                        if (!$contrato->save()) {
                            DB::rollBack();
                            Storage::disk('public')->deleteDirectory($directory);
                            return ['error', 'Error al actualizar el estado del contrato'];
                        }
                    } else {
                        $contrato = ClienteContrato::where([
                            ['estado', 'VIGENTE'],
                            ['ClienteId', $request->cliente_id]
                        ])->first();
                    }

                    $servicio = ContratoServicio::where('contrato_id', $contrato->id)->first();
                    $servicio->estado = 'Activo';

                    if (!$servicio->save()) {
                        DB::rollBack();
                        Storage::disk('public')->deleteDirectory($directory);
                        return ['error', 'Error al actualizar el estado del servicio'];
                    }



                    $cliente->Status = 'ACTIVO';
                    $cliente->EstadoDelServicio = 'Activo';

                    if (!$cliente->save()) {
                        DB::rollBack();
                        Storage::disk('public')->deleteDirectory($directory);
                        return ['error', 'Error al actualizar el estado del cliente'];
                    }

                    if (empty($cliente->cliente_ont_olt)) {


                        $olt = Olt::select('id')->where('municipio_id', $cliente->municipio_id)->first();

                        if (!empty($olt)) {

                            $aprovicionar = new ClienteOntOlt;
                            $aprovicionar->ClienteId = $request->cliente_id;
                            $aprovicionar->ActivoFijoId = $ont->ActivoFijoId;
                            $aprovicionar->olt_id = $olt->id;
                            $aprovicionar->user_id = Auth::user()->id;

                            if (!$aprovicionar->save()) {
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ['error', 'No se pudo asociar la ONT con el cliente y la OLT.'];
                            }
                        }

                        $ont->Estado = 'ASIGNADA';

                        if (!$ont->save()) {
                            DB::rollBack();
                            Storage::disk('public')->deleteDirectory($directory);
                            return ['error', 'No se actualizó el estado en el inventario'];
                        }
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
        if (Auth::user()->can('instalaciones-ver')) {

            $instalacion = Instalacion::findOrFail($id);

            if (Auth::user()->proyectos()->count() > 0) {

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if (!in_array($instalacion->cliente->ProyectoId, $array)) {
                    abort(403);
                }
            }

            $motivos_rechazo = array('Documentacion incompleta', 'Firma no corresponde', 'Velocidad de navegacion no cumple', 'Material de instalacion incompleto', 'Foto Speedtest sin velocidad de subida');

            $tecnicos = User::whereHas('roles', function ($q) {
                $q->where('roles.name', '=', 'tecnico');
            })->orderBy('name', 'ASC')->get();
            return view('adminlte::instalaciones.show', compact('instalacion', 'motivos_rechazo', 'tecnicos'));
        } else {
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
        $estados;

        if (Auth::user()->hasRole('tecnico')) {
            $estados = array('RECHAZADO', 'PENDIENTE');
        } else {
            $estados = array('APROBADO', 'RECHAZADO', 'PENDIENTE');
        }

        if (Auth::user()->can('instalacion-edit')) {
            $instalacion = Instalacion::findOrFail($id);
            $evidencias = array(
                array('archivo' => 'speedtest', 'nombre' => 'Test de Velocidad (Speedtest)'),
                array('archivo' => 'instalacion', 'nombre' => 'Evidencia de la Instalacion'),
                array('archivo' => 'mintic', 'nombre' => 'Pagina MINTIC'),
                array('archivo' => 'navegacion', 'nombre' => 'Pagina Navegación Google'),
                array('archivo' => 'ping', 'nombre' => 'Ping'),
                array('archivo' => 'youtube', 'nombre' => 'Streaming de Youtube'),
                array('archivo' => 'firma', 'nombre' => 'Firma Cliente')
            );

            return view('adminlte::instalaciones.edit', compact('instalacion', 'evidencias', 'estados'));
        } else {
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
    public function auditar(Request $request, $id)
    {
        $this->validate(request(), [
            'estado' => 'required'
        ]);

        $instalacion = Instalacion::find($id);

        if ($request->estado == 'APROBADO') {
            $instalacion->fecha_auditado = date('Y-m-d h:i:s');
        } else {
            $instalacion->motivo_rechazo = $request->motivo_rechazo;
            $instalacion->descripcion_rechazo = $request->observaciones;
        }

        $instalacion->estado = $request->estado;
        $instalacion->user_id = $request->tecnico;
        $instalacion->auditor_id = Auth::user()->id;

        if ($instalacion->save()) {
            return redirect()->route('instalaciones.index')->with('success', 'Instalacion Auditada');
        } else {
            return redirect()->route('instalaciones.index')->with('error', 'Error al auditar');
        }
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
        if (Auth::user()->can('instalacion-edit')) {
            $this->validate(request(), [
                'vel_bajada' => 'required',
                'vel_subida' => 'required',
                'servicio_activo' => 'required',
                'cumple_velocidad' => 'required',
                'serial_ont' => 'required',
                'estado' => 'required'
            ]);


            if (Auth::user()->can('instalaciones-inventarios-editar')) {
                $this->validate(request(), [
                    'conector' => 'required',
                    'pigtail' => 'required',
                    'cant_retenciones' => 'required',
                    'cinta_bandit' => 'required',
                    'hebilla' => 'required',
                    'gancho_poste' => 'required',
                    'gancho_pared' => 'required',
                    'cant_correa_amarre' => 'required',
                    'cant_chazo' => 'required',
                    'tornillo' => 'required',
                    'roseta' => 'required',
                    'patch_cord_fibra' => 'required',
                    'patch_cord_utp' => 'required',
                    'fibra_drop_desde' => 'required',
                    'fibra_drop_hasta' => 'required',
                ]);
            }



            $instalacion = Instalacion::find($id);

            if ($request->serial_ont != $instalacion->serial_ont) {
                $instalacion->serial_ont = $request->serial_ont;

                $ont = ActivoFijo::where('serial', $request->serial_ont)->first();
                $ont->Estado = 'ASIGNADA';
                $ont->save();

                $instalacion->activo_fijo_id = $ont->ActivoFijoId;
            }

            $instalacion->serial_ont = $request->serial_ont;
            //$instalacion->port_onu = $request->onu_id;
            //$instalacion->olt = $request->olt;
            $instalacion->velocidad_bajada = $request->vel_bajada;
            $instalacion->velocidad_subida = $request->vel_subida;
            $instalacion->servicio_activo = $request->servicio_activo;
            $instalacion->cumple_velocidad_contratada = $request->cumple_velocidad;
            $instalacion->estado = $request->estado;

            if (Auth::user()->can('instalaciones-inventarios-editar')) {
                $instalacion->conector = $request->conector;
                $instalacion->pigtail = $request->pigtail;
                $instalacion->cant_retenciones = $request->cant_retenciones;
                $instalacion->cinta_bandit = $request->cinta_bandit;
                $instalacion->hebilla = $request->hebilla;
                $instalacion->gancho_poste = $request->gancho_poste;
                $instalacion->gancho_pared = $request->gancho_pared;
                $instalacion->cant_correa_amarre = $request->cant_correa_amarre;
                $instalacion->cant_chazo = $request->cant_chazo;
                $instalacion->tornillo = $request->tornillo;
                $instalacion->roseta = $request->roseta;
                $instalacion->patch_cord_fibra = $request->patch_cord_fibra;
                $instalacion->patch_cord_utp = $request->patch_cord_utp;
                $instalacion->fibra_drop_desde = $request->fibra_drop_desde;
                $instalacion->fibra_drop_hasta = $request->fibra_drop_hasta;
            }

            if ($instalacion->save()) {

                if (Auth::user()->hasRole('tecnico')) {
                    return redirect()->route('instalaciones.index')->with('success', 'Informacion actualizada correctamente');
                } else {
                    return redirect()->route('instalaciones.edit', $id)->with('success', 'Informacion actualizada correctamente');
                }
            } else {
                return redirect()->route('instalaciones.edit', $id)->with('error', 'Error al actualizar la informacion.');
            }
        } else {
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
        if (Auth::user()->can('instalacion-eliminar')) {
            $instalacion = Instalacion::find($id);

            if ($instalacion->estado != 'PENDIENTE') {
                return redirect()->route('instalaciones.index')->with('error', 'No permitido. La instalacion ya fue auditada.');
            } else {

                if ($instalacion->archivo->count() > 0) {

                    foreach ($instalacion->archivo as $archivo) {

                        if (Storage::disk('public')->exists($archivo->archivo)) {
                            Storage::disk('public')->delete($archivo->archivo);
                        }

                        $archivo->delete();
                    }
                }

                if ($instalacion->delete()) {
                    return redirect()->route('instalaciones.index')->with('success', 'Instalación eliminada.');
                } else {
                    return redirect()->route('instalaciones.index')->with('error', 'Error al eliminar la instalación');
                }
            }
        }
    }

    public function vistaImportar()
    {
        return view('adminlte::instalaciones.importar');
    }

    public function importar(Request $request)
    {

        $this->validate($request, [
            'archivo' => 'required|mimes:csv,txt|max:100000',
            'archivo_carpetas' => 'required|mimes:zip'
        ]);

        Zipper::make($request->archivo_carpetas)->extractTo(public_path('storage\\importaciones\\instalaciones\\' . Auth::user()->id));


        $result = DB::transaction(function () use ($request) {


            $total_insersiones = 0;
            $instalaciones_sin_carpeta = array();
            $clientes_vendedor_asignado = array();
            $clientes_sin_auditar = array();
            $instalaciones_existentes = array();
            $user_id = Auth::user()->id;
            $buscar = array("[", "]", "'");
            $reemplazar = array("{", "}", '"');

            $cedula = 0;
            $municipio_id = 0;
            $cliente = "";

            if ($request->hasFile('archivo')) {
                # code...            
                $path = $request->file('archivo');
                $data = Excel::load($path, function ($reader) {})->get();

                if (!empty($data) && $data->count()) {
                    # Recorremos el CVS para identificar que los datos ingresados esten en la codificacion UTF-8


                    foreach ($data as $key => $value) {

                        #se valida el reporte con el fin que no se suban duplicados
                        $directory_zip = 'importaciones\\instalaciones\\' . Auth::user()->id . '\\' . $value->fecha . '\\' . $value->documento;

                        #Retorna la lista de archivos que contiene la carpeta
                        $listado_archivos_comprimidos = Storage::disk('public')->files($directory_zip);

                        $hay_firma = 0;

                        foreach ($listado_archivos_comprimidos as $archivox) {
                            $archivox = explode("/", $archivox);
                            $archivox = explode(".", $archivox[count($archivox) - 1]);

                            if ($archivox[0] == "firma") {
                                $hay_firma = 1;
                            }
                        }

                        if ($hay_firma == 0) {
                            Storage::disk('public')->deleteDirectory($directory_zip);
                            return ['error', 'Error. Instalacion incompleta. Debe volver a tomar la firma del cliente. ' . $value->documento];
                        }


                        $hay_instalacion = Instalacion::select('id')
                            ->join('Clientes', 'instalaciones.ClienteId', 'Clientes.ClienteId')
                            ->where('Clientes.Identificacion', $value->documento)->count();

                        $cliente = Cliente::select('ClienteId', 'Identificacion', 'municipio_id', 'Status')->where('Identificacion', $value->documento)->first();


                        if ((count($cliente)) > 0) {
                            $municipio_id = $cliente->municipio_id;
                        } else {

                            $clientes_sin_auditar[] = $value->documento . " NO EXISTE.";
                            Storage::disk('public')->deleteDirectory($directory_zip);

                            continue;
                        }


                        if ($hay_instalacion > 0) {
                            $instalaciones_existentes[] = $value->documento;
                            Storage::disk('public')->deleteDirectory($directory_zip);
                            $municipio_id = $cliente->municipio_id;
                            continue;
                        }

                        if ($cliente->Status != 'EN INSTALACION') {
                            $clientes_sin_auditar[] = $value->documento;
                            Storage::disk('public')->deleteDirectory($directory_zip);
                            $municipio_id = $cliente->municipio_id;
                            continue;
                        }

                        if (floatval($value->velocidad_bajada) <= 0 || floatval($value->velocidad_subida) <= 0 || empty($value->velocidad_subida) || empty($value->velocidad_bajada)) {
                            DB::rollBack();
                            Storage::disk('public')->deleteDirectory($directory_zip);
                            return ['error', 'Error La instalacion esta incompleta. debe volver hacerla'];
                        } else {

                            $instalacion = new Instalacion;
                            $instalacion->ClienteId = $cliente->ClienteId;
                            $instalacion->serial_ont = $value->serial_ont;
                            $instalacion->tipo_conexion = $value->tipo_conexion;
                            $instalacion->marca_equipo = $value->marca_equipo;
                            $instalacion->serial_equipo = $value->serial_equipo;
                            $instalacion->estado_equipo = $value->estado_equipo;
                            $instalacion->cantidad_equipos_conectados = $value->cantidad_equipos_conectados;
                            $instalacion->tipo_conexion_electrica = $value->tipo_conexion_electrica;
                            $instalacion->tipo_proteccion_electrica = $value->tipo_proteccion_electrica;
                            $instalacion->marca_proteccion_electrica = $value->marca_proteccion_electrica;
                            $instalacion->serial_proteccion_electrica = $value->serial_proteccion_electrica;
                            $instalacion->estado_conexion_electrica = $value->estado_conexion_electrica;
                            $instalacion->velocidad_bajada = $value->velocidad_bajada;
                            $instalacion->velocidad_subida = $value->velocidad_subida;
                            $instalacion->conector = $value->conector;
                            $instalacion->pigtail = $value->pigtail;

                            $retenciones = str_replace($buscar, $reemplazar, $value->retenciones);
                            $retenciones = json_decode($retenciones);
                            $instalacion->cant_retenciones = (!empty($retenciones->cantidad)) ? $retenciones->cantidad : 0;
                            $instalacion->tipo_retenciones = $retenciones->tipo;

                            $instalacion->cinta_bandit = $value->cinta_bandit;
                            $instalacion->hebilla = $value->hebilla;
                            $instalacion->gancho_poste = $value->gancho_poste;
                            $instalacion->gancho_pared = $value->gancho_pared;

                            $correa_amarre = str_replace($buscar, $reemplazar, $value->correa_amarre);
                            $correa_amarre = json_decode($correa_amarre);
                            $instalacion->cant_correa_amarre = $correa_amarre->cantidad;
                            $instalacion->tipo_correa_amarre = $correa_amarre->tipo;

                            $chazo = str_replace($buscar, $reemplazar, $value->chazo);
                            $chazo = json_decode($chazo);
                            $instalacion->cant_chazo = $chazo->cantidad;
                            $instalacion->tipo_chazo = $chazo->tipo;

                            $instalacion->tornillo = $value->tornillo;
                            $instalacion->roseta = $value->roseta;
                            $instalacion->patch_cord_fibra = $value->patch_cord_fibra;
                            $instalacion->patch_cord_utp = $value->patch_cord_utp;
                            $instalacion->fibra_drop_desde = $value->fibra_drop_desde;
                            $instalacion->fibra_drop_hasta = $value->fibra_drop_hasta;
                            $instalacion->caja = $value->caja;
                            $instalacion->puerto = $value->puerto;
                            $instalacion->sp_splitter = $value->sp_splitter;
                            $instalacion->ss_splitter = $value->ss_splitter;
                            $instalacion->tarjeta = $value->tarjeta;
                            $instalacion->modulo = $value->modulo;
                            $instalacion->servicio_activo = $value->servicio_activo;
                            $instalacion->cumple_velocidad_contratada = $value->cumple_velocidad_contratada;
                            $instalacion->latitud = $value->latitud;
                            $instalacion->longitud = $value->longitud;
                            $instalacion->observaciones = $value->observaciones;
                            $instalacion->fecha = $value->fecha;
                            $instalacion->estado = 'PENDIENTE';
                            $instalacion->user_id = $user_id;

                            if (file_exists(public_path('storage\\' . $directory_zip))) {


                                if ($instalacion->save()) {

                                    $id = $instalacion->id;

                                    //Declaramos una ruta
                                    $directory = 'instalaciones/' . $cliente->Identificacion;

                                    if (Storage::disk('public')->exists($directory)) {
                                        //Eliminamos el directorio
                                        Storage::disk('public')->deleteDirectory($directory_zip);
                                    } else {
                                        #Movemos la carpeta del cliente a la ruta final
                                        Storage::disk('public')->move($directory_zip, $directory);

                                        #Retorna la lista de archivos que contiene la carpeta
                                        $files = Storage::disk('public')->files($directory);

                                        #Recorremos el array e insertamos en la base de datos el nombre y ruta de los archivos que contiene la carpeta.
                                        foreach ($files as $foto) {
                                            $array = explode("/", $foto);

                                            $nombre = explode('.', $array[2]);

                                            $archivo = new InstalacionArchivo;
                                            $archivo->nombre = $nombre[0];
                                            $archivo->archivo = $foto;
                                            $archivo->tipo_archivo = $nombre[1];
                                            $archivo->estado = 'EN REVISION';
                                            $archivo->instalacion_id = $id;
                                            if (!$archivo->save()) {
                                                DB::rollBack();
                                                Storage::disk('public')->deleteDirectory($directory);
                                                return ['error', 'Error al guardar los archivos'];
                                            }
                                        }
                                    }


                                    $contrato = ClienteContrato::where([['estado', 'PENDIENTE'], ['ClienteId', $cliente->ClienteId]])->first();

                                    if (!empty($contrato)) {
                                        $contrato->fecha_instalacion = $value->fecha;
                                        $contrato->estado = 'VIGENTE';
                                        if (!$contrato->save()) {
                                            DB::rollBack();
                                            Storage::disk('public')->deleteDirectory($directory);
                                            return ['error', 'Error al actualizar el estado del contrato'];
                                        }
                                    } else {
                                        $contrato = ClienteContrato::where([['estado', 'VIGENTE'], ['ClienteId', $cliente->ClienteId]])->first();
                                    }

                                    $servicio = ContratoServicio::where('contrato_id', $contrato->id)->first();
                                    $servicio->estado = 'Activo';

                                    if (!$servicio->save()) {
                                        DB::rollBack();
                                        Storage::disk('public')->deleteDirectory($directory);
                                        return ['error', 'Error al actualizar el estado del servicio'];
                                    }

                                    $cliente->Status = 'ACTIVO';
                                    $cliente->EstadoDelServicio = 'Activo';

                                    if (!$cliente->save()) {
                                        DB::rollBack();
                                        Storage::disk('public')->deleteDirectory($directory);
                                        return ['error', 'Error al actualizar el estado del cliente'];
                                    }

                                    $total_insersiones =  $total_insersiones + 1;
                                }
                            } else {
                                $instalaciones_sin_carpeta[] = $value->documento;
                            }
                        }
                    }

                    return ['success', 'Se cargaron ' . $total_insersiones . ' registros satisfactoriamente.', $instalaciones_sin_carpeta, $instalaciones_existentes, $clientes_sin_auditar];

                    /*return redirect()->route('instalaciones.importar')->with('success','Se cargaron ' . $total_insersiones . ' registros satisfactoriamente.')->with(['instalaciones_sin_carpeta' => $instalaciones_sin_carpeta, 'instalaciones_existentes' => $instalaciones_existentes, 'sin_auditar' => $clientes_sin_auditar]);*/
                } else {
                    //redireccionamos al index
                    return ['warning', 'No hay archivos'];
                }
            }
        });


        if (!empty($result[2])) {
            return redirect()->route('instalaciones.importar')
                ->with(
                    $result[0],
                    $result[1]
                )
                ->with([
                    'instalaciones_sin_carpeta' => $result[2],
                    'instalaciones_existentes' => $result[3],
                    'sin_auditar' => $result[4]
                ]);
        } else {
            return redirect()->route('instalaciones.importar')->with($result[0], $result[1]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function pdf($id)
    {
        $data = [

            'codigo_dane_municipio' => '',
            'orden_trabajo' => '',
            'fecha_instalacion' => '',
            'departamento' => '',
            'municipio' => '',

            // Datos adicionales de instalación de los usuarios del Proyecto Guajira
            'TipoConexion' => '',
            'EstructuraInstalacion' => '',
            'RouterSerial' => '',
            'CableUTP' => '',
            'SwitchPuerto' => '',
            'Paneles' => '',
            'PotenciaPaneles' => '',
            'ControladorSolar' => '',
            'AccesPoint'=> '',
            'SwitchPOE' => '',
            'Switch' => '',
            'Bateria' => '',
            'Router' => '',
            'ConversorDCDC' => '',
            'AntenaSectorial' => '',
            'AntenaReceptora' => '',
            'CamaraIP' => '',
            'CerboGX' => '',
            'Inversor' => '',

            'nombre_tecnico' => '',
            'cedula_tecnico' => '',
            'celular_tecnico' => '',

            'nombre_cliente' => '',
            'cedula_cliente' => '',
            'celular_cliente' => '',
            'correo' => '',
            'direccion' => '',
            'estrato' => '',
            'coordenadas' => '',
            'tipo_beneficiario' => '',

            'marca_ont' => '',
            'serial_ont' => '',
            'estado_ont' => '',

            'tipo_equipo_cliente_conexion' => '',
            'marca_equipo' => '',
            'serial_equipo' => '',
            'estado_equipo' => '',

            'cantidad_equipos_conectados' => '',

            'tipo_conexion_electrica' => '',

            'tipo_proteccion_electrica' => '',
            'serial_proteccion_electrica' => '',
            'estado_conexion_electrica' => '',

            'servicio_activo' => '',
            'cumple_velocidad_contratada' => '',
            'observaciones' => '',

            'velocidad_bajada' => '',
            'velocidad_subida' => '',
            'ping' => '',
            'speedtest' => '',
            'google' => '',
            'youtube' => '',
            'mintic' => '',
            'instalacion' => '',
            'firma_cliente' => '',
            'firma_instalador' => ''
        ];

        $instalacion = Instalacion::findOrFail($id);

        // Verificar si el ProyectoId es 14
        if ($instalacion->cliente->proyecto->NumeroDeProyecto == 14) {
            // Agregar los datos específicos para ProyectoId == 14
            $data['TipoConexion'] = $instalacion->TipoConexion;
            $data['EstructuraInstalacion'] = $instalacion->EstructuraInstalacion;
            $data['RouterSerial'] = $instalacion->RouterSerial;
            $data['CableUTP'] = $instalacion->CableUTP;
            $data['SwitchPuerto'] = $instalacion->SwitchPuerto;
            $data['Paneles'] = $instalacion->Paneles;
            $data['PotenciaPaneles'] = $instalacion->PotenciaPaneles;
            $data['ControladorSolar'] = $instalacion->ControladorSolar;
            $data['AccesPoint'] = $instalacion->AccesPoint;
            $data['SwitchPOE'] = $instalacion->SwitchPOE;
            $data['Switch'] = $instalacion->Switch;
            $data['Bateria'] = $instalacion->Bateria;
            $data['Router'] = $instalacion->Router;
            $data['ConversorDCDC'] = $instalacion->ConversorDCDC;
            $data['AntenaSectorial'] = $instalacion->AntenaSectorial;
            $data['AntenaReceptora'] = $instalacion->AntenaReceptora;
            $data['CamaraIP'] = $instalacion->CamaraIP;
            $data['CerboGX'] = $instalacion->CerboGX;
            $data['Inversor'] = $instalacion->Inversor;    
        }

        $data['codigo_dane_municipio'] = $instalacion->cliente->municipio->CodigoDane;

        $data['orden_trabajo'] = $instalacion->id;
        $data['fecha_instalacion'] = $instalacion->fecha;
        $data['departamento'] = $instalacion->cliente->municipio->NombreDepartamento;
        $data['municipio'] = $instalacion->cliente->municipio->NombreMunicipio;

        $data['nombre_tecnico'] = $instalacion->tecnico->name;
        $data['cedula_tecnico'] = $instalacion->tecnico->cedula;
        $data['celular_tecnico'] = $instalacion->tecnico->celular;

        $data['nombre_cliente'] = $instalacion->cliente->NombreBeneficiario . ' ' . $instalacion->cliente->Apellidos;
        $data['cedula_cliente'] = $instalacion->cliente->Identificacion;
        $data['celular_cliente'] = $instalacion->cliente->TelefonoDeContactoMovil;
        $data['correo'] = $instalacion->cliente->CorreoElectronico;

        $data['direccion'] = $instalacion->cliente->DireccionDeCorrespondencia;
        $data['estrato'] = $instalacion->cliente->Estrato;
        $data['coordenadas'] = number_format($instalacion->latitud, 5, '.', '') . ' , ' . number_format($instalacion->longitud, 5, '.', '');
        $data['tipo_beneficiario'] = $instalacion->cliente->tipo_beneficiario;

        $data['marca_ont'] = 'HUAWEI';
        $data['serial_ont'] = $instalacion->serial_ont;
        $data['estado_ont'] = 'Funcional';

        $data['tipo_equipo_cliente_conexion'] = $instalacion->tipo_conexion;
        $data['marca_equipo'] = $instalacion->marca_equipo;
        $data['serial_equipo'] = $instalacion->serial_equipo;
        $data['estado_equipo'] = $instalacion->estado_equipo;

        $data['cantidad_equipos_conectados'] = $instalacion->cantidad_equipos_conectados;

        $data['tipo_conexion_electrica'] = $instalacion->tipo_conexion_electrica;
        $data['tipo_proteccion_electrica'] = $instalacion->tipo_proteccion_electrica;
        $data['serial_proteccion_electrica'] = $instalacion->serial_proteccion_electrica;
        $data['estado_conexion_electrica'] = $instalacion->estado_conexion_electrica;

        $data['servicio_activo'] = $instalacion->servicio_activo;
        $data['cumple_velocidad_contratada'] = $instalacion->cumple_velocidad_contratada;
        $data['observaciones'] = $instalacion->observaciones;

        $data['velocidad_bajada'] = number_format($instalacion->velocidad_bajada, 2, '.', '');
        $data['velocidad_subida'] = number_format($instalacion->velocidad_subida, 2, '.', '');

        $data['firma_instalador'] = $instalacion->tecnico->firma;

        foreach ($instalacion->archivo as $archivo) {
            switch ($archivo->nombre) {
                case 'speedtest':
                    $data['speedtest'] = $archivo->archivo;
                    break;
                case 'firma':
                    $data['firma_cliente'] = $archivo->archivo;
                    break;
                case 'instalacion':
                    $data['instalacion'] = $archivo->archivo;
                    break;
                case 'mintic':
                    $data['mintic'] = $archivo->archivo;
                    break;
                case 'navegacion':
                    $data['google'] = $archivo->archivo;
                    break;
                case 'ping':
                    $data['ping'] = $archivo->archivo;
                    break;
                case 'youtube':
                    $data['youtube'] = $archivo->archivo;
                    break;
            }
        }

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.instalacion', compact('data'));
        //$pdf->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        //return $pdf->download('cita.pdf');
        return $pdf->stream('instalacion.pdf');
    }

    public function exportar(Request $request)
    {

        Excel::create('instalaciones', function ($excel) use ($request) {

            $excel->sheet('Instalaciones', function ($sheet) use ($request) {


                $datos = array();

                $instalaciones = Instalacion::Cedula($request->get('documento'))
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Estado($request->get('estado'))
                    ->Serial($request->get('serial'))
                    ->get();

                foreach ($instalaciones as $instalacion) {
                    $datos[] = array(
                        "CEDULA" => $instalacion->cliente->Identificacion,
                        "NOMBRE" => $instalacion->cliente->NombreBeneficiario . ' ' . $instalacion->cliente->Apellidos,
                        "CLASIFICACION" => $instalacion->cliente->Clasificacion,
                        "CELULAR" => $instalacion->cliente->TelefonoDeContactoMovil,
                        "DIRECCION" => $instalacion->cliente->DireccionDeCorrespondencia,
                        "BARRIO" => $instalacion->cliente->Barrio,
                        "URBANIZACION" => $instalacion->NombreEdificio_o_Conjunto,
                        "MUNICIPIO" => $instalacion->cliente->municipio->NombreMunicipio,
                        "DEPARTAMENTO" => $instalacion->cliente->municipio->departamento->NombreDelDepartamento,
                        "REGION" => $instalacion->cliente->municipio->region,
                        "META" => (!empty($instalacion->cliente->meta_cliente)) ? $instalacion->cliente->meta_cliente->meta->nombre : '',
                        "FECHA INSTALACION" => $instalacion->fecha,
                        "TECNICO" => $instalacion->tecnico->name,
                        "MOTIVO RECHAZO" => $instalacion->motivo_rechazo,
                        "DESCRIPCION RECHAZO" => $instalacion->descripcion_rechazo,
                        "ESTADO INSTALACION" => $instalacion->estado,
                        "SERIAL ONT" => $instalacion->serial_ont,
                        "CONECTOR SC/APC" => floatval($instalacion->conector),
                        "CONECTOR PIGTAIL" => floatval($instalacion->pigtail),
                        "RETENCION HERRAJE TENSOR PLASTICO" => floatval($instalacion->cant_retenciones),
                        "CINTA BANDIT" => floatval($instalacion->cinta_bandit),
                        "HEBILLA" => floatval($instalacion->hebilla),
                        "GANCHO POSTE" => floatval($instalacion->gancho_poste),
                        "GANCHO PARED" => floatval($instalacion->gancho_pared),
                        "CORREA AMARRE" => floatval($instalacion->cant_correa_amarre),
                        "CHAZOS" => floatval($instalacion->cant_chazo),
                        "TORNILLOS" => floatval($instalacion->tornillo),
                        "ROSETA" => floatval($instalacion->roseta),
                        "PATCH CORD DE FIBRA" => floatval($instalacion->patch_cord_fibra),
                        "PATCH CORD UTP" => floatval($instalacion->patch_cord_utp),
                        "FIBRA DROP 1 HILO" => ($instalacion->fibra_drop_desde - $instalacion->fibra_drop_hasta),
                        "CAJA" => $instalacion->caja,
                        "PUERTO" => $instalacion->puerto,
                        "SP_SPLITTER" => $instalacion->sp_splitter,
                        "SS_SPLITTER" => $instalacion->ss_splitter,
                        "TARJETA" => $instalacion->tarjeta,
                        "MODULO" => $instalacion->modulo
                    );
                }



                if (count($datos) == 0) {
                    return redirect()->route('instalaciones.index')->with('warning', 'No hay datos para el filtro enviado.');
                }

                $sheet->fromArray($datos, true, 'A1', true);
            });
        })->export('xlsx');
    }


    private function guardar_archivos($instalacion_id, $directory, $nombre, $file, $fecha, $latitud = null, $longitud = null)
    {
        $tamaño = 1500;

        if (!empty($file)) {


            if ($nombre == 'firma') {
                $extension = 'jpg';
                $tamaño = 800;
            } else {
                //Obtenemos el tipo de archivo que se esta subiendo
                $extension = strtolower($file->getClientOriginalExtension());
            }

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

                if ($nombre == 'speedtest') {
                    $this->estampar_coordenadas($ruta, $ruta, $latitud, $longitud, $extension, $fecha);
                }

                $archivo = new InstalacionArchivo;
                $archivo->nombre = $nombre;
                $archivo->archivo = $ruta;
                $archivo->tipo_archivo = $extension;
                $archivo->estado = 'EN REVISION';
                $archivo->instalacion_id = $instalacion_id;

                if (!$archivo->save()) {
                    DB::rollBack();
                    Storage::disk('public')->deleteDirectory($directory);
                    return ['error', 'Error al guardar los archivos'];
                }
            } else {
                DB::rollBack();
                Storage::disk('public')->deleteDirectory($directory);
                return ['error', 'Error al guardar los archivos'];
            }
        }
    }

    private function estampar_coordenadas($archivo, $destino, $latitud, $longitud, $extension, $fecha)
    {

        $path = Storage::disk('public')->path($archivo);
        //imageCreateFromPng

        $im = null;

        if ($extension == "png") {
            $im = imagecreatefrompng($path);
        } else {
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
            $im,
            18,
            0,
            20,
            $height - 7,
            $shadow_color,
            $font,
            $string,
            10
        );

        // place the text onto our image
        $imagenblur->imagettftextblur(
            $im,
            18,
            0,
            20,
            $height - 7,
            $text_color,
            $font,
            $string
        );


        imagejpeg($im, $path);
    }

    private function buscar_palabra($texto, $array_palabras)
    {

        $resultado = false;

        foreach ($array_palabras as $palabra) {
            if (stripos($texto, $palabra) !== false) {
                $resultado = true;
            }
        }

        return $resultado;
    }

 

}
