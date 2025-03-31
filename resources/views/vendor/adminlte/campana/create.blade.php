@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-file-text-o"></i>  Crear Campaña</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="panel-generar">

                    <div class="box-header with-border bg-blue">
                        <h3 class="box-title">FIltro para la campaña </h3>
                    </div>
                
                    <div class="box-body">
                        
                        <form action="{{route('campanas.store')}}" id="crearCampaña" method="POST">
                            {{csrf_field()}} 
                            <div  class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-pencil-square-o"></i> Datos De Campaña</h4>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">                                                   

                                        <div class="form-group {{ $errors->has('nombre_campana') ? ' has-error' : '' }} col-md-6">
                                            <label>*Titulo de campaña</label>
                                            <input type="text" name="nombre_campana" placeholder="Nombre Campaña" class="form-control first-mayus" value="{{ old('nombre_campana') }}" required>
                                        </div>
                
                                        <div class="form-group {{ $errors->has('fecha_inicio') ? ' has-error' : '' }} col-md-6 ">
                                            <label>*Fecha inicio de campaña</label>
                                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="form-control" required>
                                        </div>
 
                                        <div class="form-group  col-md-12">
                                            <label>Cedulas especificas</label>
                                            <textarea name="cedulas_especificas" placeholder="Separadas por una coma ," id="cedulas_especificas"  rows="3" class="form-control">{{ old('cedulas_especificas') }}</textarea>
                                        </div>
                                    
                                    </div>
                                    <div class="col-md-12">                                                   
                                        <div class="form-group {{ $errors->has('tipo_campana') ? ' has-error' : '' }} col-md-4 ">
                                            <label>*Tipo Campaña</label>
                                            <select class="form-control"  name="tipo_campana" id="tipo_campana" required>
                                                <option value="">Elija una campaña</option>
                                                @foreach($tipo_campañas as $tipo)
                                                    <option value="{{$tipo}}" {{ (old('tipo_campana') == $tipo)? 'selected' : '' }}>{{$tipo}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div hidden class="form-group {{ $errors->has('periodo') ? ' has-error' : '' }} col-md-4">
                                            <label>*Perido Facturación</label>
                                            <select  name="periodo" class="form-control" id="periodo_factura" required>
                                                <option value="">Elija un periodo</option>
                                                @foreach($ultimos_periodos as $periodo)
                                                    <option value="{{$periodo}}" {{ (old('periodo') == $periodo)? 'selected' : '' }} >{{$periodo}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group {{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-4">
                                            <label>Proyecto</label>
                                            <select class="form-control" name="proyecto" onchange="ajax_consulta()" id="proyecto">
                                                <option value="">Elija un proyecto</option>                      
                                                
                                            </select>
                                        </div>   
                                        <div class="form-group {{ $errors->has('departamento') ? ' has-error' : '' }} col-md-4">
                                            <label>Departamento</label>
                                            <select class="form-control" name="departamento" onchange="ajax_consulta()" id="departamento">
                                                <option value="">Elija un departamento</option>
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group {{ $errors->has('municipio') ? ' has-error' : '' }} col-md-4"> 
                                            <label>Municipio</label>
                                            <select class="form-control" value="{{ old('municipio') }}" onchange="ajax_consulta()" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select>
                                        </div>
                                        <div hidden class="form-group col-md-4">
                                            <label>*Estado del Cliente</label>
                                            <select class="form-control" name="estado_cliente" onchange="ajax_consulta()" id="estado_cliente" required>
                                                <option value="">Elija un estado</option>
                                                @foreach($estados_cliente as $estado)
                                                    <option value="{{$estado}}" {{ (old('estado_cliente') == $estado)? 'selected' : '' }} >{{$estado}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <div class="checkbox mt-3">
                                                <label>
                                                    <input type="checkbox" name="restricciones" value="1">
                                                    <span class="ml-1"> Sin restricciones ni Solicitudes</span>
                                                </label>
                                            </div>
                                        </div>
                                                           
                                    </div>
                                </div>
                            </div>
                            <div  class="panel panel-info" id="informacion_mostrar" hidden>
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-file-text-o"></i> Información a mostrar</h4>
                                </div>
                                <div class="panel-body" >                        
                                    <div class="col-md-12 ">
                                        <div class="lista-nombres ">
                                            <ul id="campos_visualizar" name="campos_visualizar">
                                              
                                            </ul>
                                        </div>                                       
                                    </div>
                                </div>
                            </div>
                        
                            <div  class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-file-text-o"></i> Información a registrar</h4>
                                </div>
                                <div class="panel-body"> 
                                    <div class="col-md-12">  

                                        <table class="table table-striped" id="informcaion_registrar">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Tipo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td >
                                                        <input id="nombre_campo" class="form-control first-mayus" name="nombres[]" value="estado" placeholder="Nombre Campo" readonly required/>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" style="margin-top: 23px;" name="tipo[]"  readonly required>
                                                            <option value="SELECCION_CON_UNICA_RESPUESTA" selected>SELECCION CON UNICA RESPUESTA</option>
                                                        </select><br> 
                                                        <div id="opciones">
                                                            <div>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" style="margin-top: 10px;" name="estado[]" placeholder="opcion" type="text" value="PENDIENTE" readonly required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" style="margin-top: 10px;" name="estado[]" placeholder="opcion" type="text" value="CONTESTA" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button id="eliminar_option" name="eliminar_option" type="button" class="btn btn-danger btn-xs" style="margin-top: 10px;"><i class="fa fa-minus-circle"></i></button>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" style="margin-top: 10px;" name="estado[]" placeholder="opcion" type="text" value="NO CONTESTA" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button id="eliminar_option" name="eliminar_option" type="button" class="btn btn-danger btn-xs" style="margin-top: 10px;"><i class="fa fa-minus-circle"></i></button>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" style="margin-top: 10px;" name="estado[]" placeholder="opcion" type="text" value="EQUIVOCADO" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button id="eliminar_option" name="eliminar_option" type="button" class="btn btn-danger btn-xs" style="margin-top: 10px;"><i class="fa fa-minus-circle"></i></button>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" style="margin-top: 10px;" name="estado[]" placeholder="opcion" type="text" value="VOLVER A LLAMAR" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button id="eliminar_option" name="eliminar_option" type="button" class="btn btn-danger btn-xs" style="margin-top: 10px;"><i class="fa fa-minus-circle"></i></button>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="col-md-10">
                                                                    <input class="form-control" style="margin-top: 10px;" name="estado[]" placeholder="opcion" type="text" value="NO LLAMAR" required>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button id="eliminar_option" name="eliminar_option" type="button" class="btn btn-danger btn-xs" style="margin-top: 10px;"><i class="fa fa-minus-circle"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    
                                                    <td width="30px">                                                        
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-xs" onclick="agregar_opcion(this)"><i class="fa fa-plus"></i></button>
                                                    </td>
                                                </tr>

                                                <tr class="fila-par">
                                                    <td >
                                                        <input id="nombre_campo" class="form-control first-mayus" required name="nombres[]" onblur="validar_iguales(this)" value="{{ old('nombres[]') }}" placeholder="Nombre Campo" />
                                                    </td>
                                                    <td>
                                                        <select class="form-control" style="margin-top: 23px;" name="tipo[]" onchange="validar_seleccion(this)"  required>
                                                            <option value="">Elija un tipo</option>
                                                            @foreach($tipos_campos as $tipo_campo)
                                                                <option value="{{$tipo_campo}}">{{str_replace('_', ' ', $tipo_campo)}}</option>
                                                            @endforeach
                                                        </select><br> 
                                                        <div id="opciones">
                                                        </div>                                                             
                                                    </td>
                                                    
                                                    <td width="30px">
                                                        <input type="button" class="btn btn-danger btn-sm eliminar" value="Quitar" />
                                                    </td>
                                                    <td hidden>
                                                        <button  type="button" class="btn btn-info btn-xs" onclick="agregar_opcion(this)"><i class="fa fa-plus"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                
                                        <div class="btn-der text-center">
                                            <button id="adicional"  type="button" class="btn btn-success btn-sm"> Agregar Más... </button>
                                        </div>
                                        <br>
                                    </div>
                                </div>                                                 
                                <br>
                            </div>
                            <div  class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-file-text-o"></i> Información para acuerdo de pago</h4>
                                </div>
                                <div class="panel-body"> 
                                    <div class="col-md-12">
                                        <div class="form-group {{ $errors->has('valor_perdonar') ? ' has-error' : '' }} col-md-3">
                                            <label >Tipo de descuento</label>
                                            <select class="form-control"  name="perdonar_porcentual" id="perdonar_porcentual">
                                                <option value="">Seleccionar</option>
                                                <option value="porcentual" {{ (old('porcentual') == 'porcentual')? 'selected' : '' }} >Porcentual</option>
                                                <option value="monetario" {{ (old('monetario') == 'monetario')? 'selected' : '' }} >Monetario</option>
                                            </select>
                                        </div>
                                        <div class="form-group {{ $errors->has('valor_perdonar') ? ' has-error' : '' }} col-md-4">
                                            <label id="label_perdonarP" hidden>Porcentaje Perdonar</label>
                                            <label id="label_perdonarV">Valor Perdonar</label>
                                            <div class="input-group">
                                                <span id="signo_pesos" class="input-group-addon">$</span>
                                                <input class="form-control" type="text" name="valor_perdonar" id="valor_perdonar" value="{{ old('valor_perdonar') }}">
                                                <span id="signo_porcentaje"  class="input-group-addon hide">%</span>
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('cuotas_max_acuerdo') ? ' has-error' : '' }} col-md-4">
                                            <label>Cuotas MAX.</label>
                                            <input type="text" name="cuotas_max_acuerdo" placeholder="Cuotas" class="form-control first-mayus" value="{{ old('cuotas_max_acuerdo') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-der text-right">
                                <button type="submit" disabled id="crear_campaña" onclick="ajax_consulta(event)"  class="btn btn-primary"> <i id="load" class="fa fa-spinner"></i>Crear</button>							           
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    @section('mis_scripts')
        <script>
            const tipos_campos = {!! json_encode($tipos_campos) !!};
            toastr.options.positionClass = 'toast-bottom-right';
        </script>
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        <script type="text/javascript" src="/js/campaña/campana_create.js"></script>
        <script type="text/javascript" src="/js/campaña/validacion_create.js"></script>
        <script type="text/javascript" src="/js/campaña/consulta_proyectos.js"></script>
        <script type="text/javascript" src="/js/acuerdos/validacion_porcentual.js"></script>   
        <script>
            
            $(document).ready(function(){
                tipo_campana($('#tipo_campana'));
                buscar_departamentos($('#proyecto'));
            });

            const validar_cedulas = (e) => {

                let resultado = false;

                const texto = document.getElementById('cedulas_especificas').value;

                if(texto != ""){

                    const comas = /,/;
                    const saltos_de_linea = /[\n]/;

                    if(saltos_de_linea.test(texto)){
                        e.preventDefault();
                        toastr.warning('Las cedulas no deben contener saltos de linea.');
                    }else if(!comas.test(texto)){
                        e.preventDefault();
                        toastr.warning('Las cedulas deben estar separadas por coma ","');
                    }else{

                        const todosSonNumeros = texto.split(',')
                        .map(elemento => elemento.trim())
                        .every(elemento => !isNaN(elemento) && elemento !== '');

                        if(todosSonNumeros){
                            resultado = true;
                        }else{
                            e.preventDefault();
                            toastr.warning('Se ha detectado letras entre las cedulas');
                        }

                        
                    }

                }else{
                    resultado = true;
                }

                return resultado;

            }               

            const campos_cliente = {!!$campos_cliente!!};
            const campos_facturacion = {!!$campos_facturacion!!};
            const proyectos = {!! $proyectos !!}

        </script>
    @endsection
@endsection   

