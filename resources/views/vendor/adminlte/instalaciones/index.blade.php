@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-hdd-o"></i>  Instalaciones</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('instalaciones.index')}}" role="search" method="GET">
                            
                                <div class="btn-group pull-right">

                                    <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">                                    
                                        <span id="icon-opciones" class="fa fa-gears"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    
                                    <ul class="dropdown-menu" role="menu">
                                    
                                        @permission('instalaciones-crear')
                                            <li><a href="{{route('instalaciones.instalar')}}"><i class="fa fa-plus"></i> Instalar</a></li>
                                        @endpermission 
                                        
                                        @permission('instalaciones-exportar')
                                            <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                        @endpermission 
                                    </ul>
                                    
                                </div>
                            

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" name="serial" placeholder="Serial" value="{{(isset($_GET['serial'])? $_GET['serial']:'')}}" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="">Elija un proyecto</option>
                                                @foreach($proyectos as $proyecto)

                                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="departamento" id="departamento">
                                                <option value="">Elija un departamento</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select> 
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="estado" id="estado">
                                                <option value="">Elija un estado</option>
                                                @foreach($estados as $estado)
                                                    @if(isset($_GET['estado']))
                                                        @if($_GET['estado'] == $estado)
                                                            <option value="{{$estado}}" selected>{{$estado}}</option>
                                                        @else
                                                            <option value="{{$estado}}">{{$estado}}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{$estado}}">{{$estado}}</option>
                                                    @endif
                                                @endforeach
                                            </select>                                        
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default" style="height: 85px;">
                                        <i class="fa fa-search"></i>  Buscar
                                    </button>
                                </div>
                            </div>
                        </form>                        


                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Proyecto</th>
                                    <th>Estado</th>
                                    @role(['admin', 'administrativo', 'comercial', 'auditor'])
                                    <th>Acciones</th>
                                    @endrole
                                </tr>
                                @foreach($instalaciones as $dato)
                                <tr>
                                    <th>
                                        @if($dato->estado == 'RECHAZADO' && Auth::user()->hasRole('tecnico'))
                                            <a href="{{route('instalaciones.edit', $dato->id)}}">{{$dato->cliente->Identificacion}}</a>
                                        @else
                                            <a href="{{route('instalaciones.show', $dato->id)}}">{{$dato->cliente->Identificacion}}</a>
                                        @endif
                                    </th>

                                    <td>{{mb_convert_case($dato->cliente->NombreBeneficiario . ' ' . $dato->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>                                    
                                    
                                        @if(!empty($dato->cliente->municipio))
                                            <td>{{$dato->cliente->municipio->NombreMunicipio}}</td>
                                            <td>{{$dato->cliente->municipio->departamento->NombreDelDepartamento}}</td>
                                        @else
                                            <td>{{$dato->cliente->ubicacion->municipio->NombreMunicipio}}</td>
                                            <td>{{$dato->cliente->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                        @endif
                                    <td>{{$dato->cliente->proyecto->NumeroDeProyecto}}</td>

                                    <td>
                                        @if($dato->estado == 'APROBADO')
                                          <span class="label label-success">{{$dato->estado}}</span>
                                        @elseif($dato->estado == 'RECHAZADO')
                                          <span class="label label-danger">{{$dato->estado}}</span>
                                        @elseif($dato->estado == 'PENDIENTE')
                                          <span class="label label-warning">{{$dato->estado}}</span>
                                        @endif
                                    </td>

                                    @role(['admin', 'administrativo', 'comercial', 'auditor', 'agente-noc'])
                                    <td>
                                        <a href="{{route('instalacion.pdf', $dato->id)}}" class="btn btn-default btn-xs" title="Formato de Instalacion" target="_blank"> <i class="fa fa-file-pdf-o"></i></a>

                                        @permission('instalacion-edit')
                                            <a href="{{route('instalaciones.edit', $dato->id)}}" class="btn btn-primary btn-xs"  target="_blank"> <i class="fa fa-edit"></i></a>
                                        @endpermission    

                                        @if($dato->estado == 'PENDIENTE')
                                        
                                            @permission('instalacion-eliminar')
                                                <form action="{{route('instalaciones.destroy', $dato->id)}}" method="post" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="delete"> 
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <button type="submit" onclick="return confirm('Estas seguro Eliminar la instalacion?');" title="Eliminar" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            @endpermission
                                        @endif
                                    </td>
                                    @endrole
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$instalaciones->currentPage()}} de {{$instalaciones->lastPage()}}. Total registros {{$instalaciones->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $instalaciones->appends(Request::only(['proyecto', 'departamento', 'municipio', 'estado']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('mis_scripts')
    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
    <script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>


    @role(['admin', 'administrativo', 'comercial', 'agente-noc'])
    <script type="text/javascript">
        $('#exportar').on('click',function(){
            var parametros = {
                cedula : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                serial : "{!! (isset($_GET['serial'])? $_GET['serial']:'') !!}",
                '_token' : $('input:hidden[name=_token]').val() 
            }

            exportarConAjax('/instalaciones/exportar', parametros);

        });
    </script>
    @endrole
    @endsection
@endsection