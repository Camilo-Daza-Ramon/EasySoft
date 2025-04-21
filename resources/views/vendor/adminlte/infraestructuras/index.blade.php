@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-desktop"></i>  Infraestructuras</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('infraestructuras.index')}}" role="search" method="GET">
                            
                                <div class="btn-group pull-right">

                                    <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">                                    
                                        <span id="icon-opciones" class="fa fa-gears"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    
                                    <ul class="dropdown-menu" role="menu">
                                    
                                        @permission('infraestructura-crear')
                                            <li><a href="{{route('infraestructuras.create')}}"><i class="fa fa-plus"></i> Agregar</a></li>
                                        @endpermission 
                                        
                                    </ul>
                                </div>
                            

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            <input type="number" class="form-control" name="identificacion" placeholder="Número identificacion" value="{{(isset($_GET['identificacion'])? $_GET['identificacion']:'')}}" autocomplete="off">
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
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach
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
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Categoría</th>
                                    <th scope="col">Tipo de Categoría</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Estado</th>
                                    <th>Acciones</th>
                                </tr>

                                @if($infraestructuras->count() > 0)
                                    @foreach($infraestructuras as $infra)
                                    <tr>
                                        <th>
                                            @permission('infraestructura-ver')
                                                <a href="{{route('infraestructuras.show', $infra->id)}}">{{$infra->id}}</a>
                                            @else
                                                {{$infra->id}}
                                            @endpermission
                                        </th>

                                        <td>{{mb_convert_case($infra->nombre, MB_CASE_TITLE, "UTF-8")}}</td>
                                        <td>{{$infra->categoria}}</td>
                                        <td>{{$infra->tipo_categoria}}</td>
                                        <td>{{$infra->municipio->NombreMunicipio}}</td>
                                        <td>{{$infra->municipio->departamento->NombreDelDepartamento}}</td>
                                        <td>{{$infra->estado}}</td>

                                        <td>
                                            @permission('infraestructura-editar')
                                                <a href="{{route('infraestructuras.edit', $infra->id)}}" class="btn btn-primary btn-xs"> <i class="fa fa-edit"></i></a>
                                            @endpermission    
                                            
                                            @permission('infraestructura-eliminar')
                                                <form action="{{route('infraestructuras.destroy', $infra->id)}}" method="post" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="delete"> 
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <button type="submit" onclick="return confirm('Estas seguro Eliminar esta Infraestructura?');" title="Eliminar" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            @endpermission
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">NO HAY REGISTROS</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$infraestructuras->currentPage()}} de {{$infraestructuras->lastPage()}}. Total registros {{$infraestructuras->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $infraestructuras->appends(Request::only(['identificacion','proyecto', 'departamento', 'municipio', 'estado']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>




    @section('mis_scripts')
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        <script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>

        @permission('infraestructuras-exportar')
        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    identificacion : "{!! (isset($_GET['identificacion'])? $_GET['identificacion']:'') !!}",
                    proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                    departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                    estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                exportarConAjax('/infraestructuras/exportar', parametros);

            });
        </script>
        @endpermission
    @endsection
@endsection