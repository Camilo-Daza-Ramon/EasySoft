@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-wrench"></i>  Mantenimientos Correctivos</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" action="{{route('correctivos.index')}}" role="search" method="GET">  
                            
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('mantenimientos-masivos-crear')
                                        <li><a href="#" data-toggle="modal" data-target="#mantenimientoAdd"><i class="fa fa-plus"></i> Agregar Masivo</a></li>
                                    @endpermission

                                    @permission('mantenimientos-exportar')
                                        <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>                                    
                                    @endpermission
                                    
                                </ul>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-10 col-sm-11 col-xs-10">
                                    <div class="row">

                                        <div class="form-group col-md-3 col-sm-6">
                                            <input type="text" class="form-control" name="mantenimiento" placeholder="Buscar" value="{{(isset($_GET['mantenimiento'])? $_GET['mantenimiento']:'')}}" autocomplete="off">
                                        </div>

                                        @if(!Auth::user()->hasRole('interventoria'))
                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="tipo" id="tipo">
                                                <option value="">Elija un tipo</option>
                                                @foreach($tipos as $tipo)
                                                    <option value="{{$tipo->TipoDeMantenimiento}}" {{(isset($_GET['tipo'])) ? (($_GET['tipo'] == $tipo->TipoDeMantenimiento) ? 'selected' : '') : ''}}>{{$tipo->Descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif

                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="estado" id="estado">
                                                <option value="">Elija un estado</option>
                                                @foreach($estados as $estado)

                                                    <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{$estado}}</option>
                                                @endforeach
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="">Elija un proyecto</option>
                                                @foreach($proyectos as $proyecto)

                                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="departamento" id="departamento">
                                                <option value="">Elija un departamento</option>
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select> 
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <button type="submit" class="btn btn-default btn-block"> <i class="fa fa-search"></i>  Buscar</button>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>                              
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">#</th> 
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Falla</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Fecha Ini.</th>
                                    <th scope="col">Fecha Fin</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Dias sin Resolver</th>                                    
                                    <th>Acciones</th>
                                </tr>

                                @if($mantenimientos->count() > 0)
                                    @foreach($mantenimientos as $dato)

                                    <?php 
                                        $contador = date_diff(date_create($dato->Fecha), date_create($dato->fecha_cierre_hora_fin));
                                        $total_dias = $contador->format('%a');
                                    ?>
                                    <tr>
                                        <th>
                                            @if($dato->estado == 'CERRADO')
                                                <i class="fa fa-circle text-gray"></i>
                                            @elseif($total_dias >= 15)
                                                <i class="fa fa-circle text-red"></i>
                                            @elseif($total_dias >= 10 && $total_dias < 15)
                                                <i class="fa fa-circle text-yellow"></i>
                                            @elseif($total_dias < 10)
                                                <i class="fa fa-circle text-default"></i>
                                            @endif
                                        </th>
                                        <th>
                                            <a href="{{route('correctivos.show', $dato->MantId)}}">{{$dato->NumeroDeTicket}}</a>
                                        </th>

                                        <td>{{$dato->tipo_mantenimiento->Descripcion}}</td>
                                        <td>
                                            @if(!empty($dato->TipoFalloID))
                                                {{$dato->tipo_fallo->DescipcionFallo}}
                                            @endif
                                        </td>
                                        <td>{{$dato->municipio->NombreMunicipio}}</td>
                                        <td>{{$dato->municipio->departamento->NombreDelDepartamento}}</td>
                                        <td>{{date('Y-m-d', strtotime($dato->Fecha))}}</td>
                                        <td>
                                            @if(!empty($dato->fecha_cierre_hora_fin))
                                            {{date('Y-m-d', strtotime($dato->fecha_cierre_hora_fin))}}
                                            @endif
                                        </td>
                                        
                                        <td>{{$dato->estado}}</td>
                                        <td>{{($contador->invert)? 0 : $contador->format('%a')}} Días sin solución</td>                                        
                                        <td>
                                            @if($dato->estado == 'ASIGNADO')
                                                @permission('mantenimientos-cerrar')
                                                <a href="{{route('correctivos.cerrar_vista', $dato->MantId)}}" class="btn btn-xs bg-purple"><i class="fa fa-calendar-check-o"></i></a>
                                                @endpermission
                                            @endif

                                            @permission('mantenimientos-editar')
                                            <a href="{{route('correctivos.edit', $dato->MantId)}}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                            @endpermission
                                            @permission('mantenimientos-eliminar')
                                            <form action="{{route('correctivos.destroy', $dato->MantId)}}" method="post">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                @permission('mantenimientos-editar')
                                                <a href="{{route('correctivos.edit', $dato->MantId)}}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endpermission

                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                    <i class="fa fa-trash-o"></i>   
                                                </button>
                                            </form>
                                            @endpermission

                                            @if($dato->estado == 'CERRADO')
                                                @permission('mantenimientos-generar-acta')
                                                <a target="_blank" href="{{route('correctivos.acta', ['id' => $dato->MantId])}}" class="btn btn-xs btn-default" title="Generar Acta de Mantenimiento"><i class="fa fa-file-pdf-o"></i></a>
                                                @endpermission
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">NO HAY REGISTROS</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$mantenimientos->currentPage()}} de {{$mantenimientos->lastPage()}}. Total registros {{$mantenimientos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $mantenimientos->appends(Request::only(['proyecto','tipo','departamento','municipio','estado']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('mantenimientos-masivos-crear')
        @include('adminlte::soporte-tecnico.mantenimientos.correctivos.create')
    @endpermission

    @section('mis_scripts')

        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        <script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });
        </script>

        <script type="text/javascript">

            let mi_contenedor = null;

            const proyectos = (proyecto, contenedor) => {
                if($(proyecto).val().length > 0){

                    mi_contenedor = contenedor;
                    $('#'+contenedor).find('#departamento').empty();    

                    find_departamentos($('#'.contenedor).find('#departamento').val());            
                }
            }

            const departamentos = (departamento, contenedor) => {
                if($(departamento).val().length > 0){

                    mi_contenedor = contenedor;
                    find_municipios($('#'+contenedor).find('#municipio').val());  
                }
            }

            function find_municipios(municipio){

                var parameters = {
                    departamento_id : $('#'+mi_contenedor).find('#departamento').val(),
                    proyecto_id : $('#'+mi_contenedor).find('#proyecto').val(),
                    '_token' : $('input:hidden[name=_token]').val()
                };

                $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){
                    $(document).trigger('cargandoMunicipios');

                    $('#'+mi_contenedor).find('#municipio').empty();

                    $('#'+mi_contenedor).find('#municipio').append('<option value="">Elija un municipio</option>');
                    $.each(data, function(index, municipiosObj){
                        
                        if (municipio != null) {
                            if (municipiosObj.MunicipioId == municipio) {

                                $('#'+mi_contenedor).find('#municipio').append('<option value="' + municipiosObj.MunicipioId + '" selected>' + municipiosObj.NombreMunicipio + '</option>');
                            }else{
                                $('#'+mi_contenedor).find('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
                            }
                        }else{
                            $('#'+mi_contenedor).find('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
                        }                                     
                    });
                    $(document).trigger('municipiosCargados');
                }).fail(function(e){
                    alert('error');
                });
            }

            function find_departamentos(departamento){
                var parameters = {
                    proyecto_id : $('#'+mi_contenedor).find('#proyecto').val(),
                    '_token' : $('input:hidden[name=_token]').val()
                };

                $.post('/estudios-demanda/ajax-departamentos', parameters).done(function(data){
                    $(document).trigger('cargandoMunicipios');
                    
                    $('#'+mi_contenedor).find('#municipio').empty();
                    
                    
                    $('#'+mi_contenedor).find('#departamento').append('<option value="">Elija un departamento</option>');
                    $('#'+mi_contenedor).find('#municipio').append('<option value="">Elija un municipio</option>');

                    $.each(data, function(index, departamentosObj){

                        if (departamento != null) {
                            if (departamentosObj.DeptId == departamento) {

                                $('#'+mi_contenedor).find('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '" selected>' + departamentosObj.NombreDelDepartamento + '</option>');
                            }else{
                                $('#'+mi_contenedor).find('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
                            }
                        }else{
                            $('#'+mi_contenedor).find('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
                        }                    
                    });
                    $(document).trigger('municipiosCargados');

                }).fail(function(e){
                    alert('error');
                });
            }

        </script>

        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                        documento : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                        mantenimiento : "{!! (isset($_GET['mantenimiento'])? $_GET['mantenimiento']:'') !!}",
                        proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                        municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                        departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                        estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                        tipo : "{!! (isset($_GET['tipo'])? $_GET['tipo']:'') !!}",
                        '_token' : $('input:hidden[name=_token]').val() 
                    }
                exportarConAjax('/mantenimientos/exportar', parametros);
            });
        </script>
    @endsection
@endsection