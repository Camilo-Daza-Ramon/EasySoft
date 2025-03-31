@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-users"></i>  Clientes Metas</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('metas-clientes.index')}}" role="search" method="GET">
                              <div class="form-group">
                                <input type="text" class="form-control" name="palabra" placeholder="Buscar" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}" autocomplete="off">

                                <select class="form-control" name="proyecto" id="proyecto" onchange="listar_metas(this);">
                                    <option value="">Elija un proyecto</option>
                                    @foreach($proyectos as $proyecto)
                                        <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                    @endforeach
                                </select>

                              </div>
                              <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                        </form>
                        
                        <div class="box-tools pull-right">                            
                            <div class="btn-group">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('metas-clientes-crear')
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#metasClientesAdd" data-toggle="modal">
                                            <i class="fa fa-plus"></i>  Asignar
                                        </a>
                                    </li>
                                    @endpermission                                    
                                </ul>
                            </div>                            
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>                                    
                                    <th scope="col">Documento</th> 
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Meta</th>                      
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                @if($metas_clientes->count() > 0)
                                    @foreach($metas_clientes as $dato)
                                    <tr>
                                        <th><a href="{{route('clientes.show', $dato->ClienteId)}}">{{$dato->cliente->Identificacion}}</a></th>
                                        <td>{{mb_convert_case($dato->cliente->NombreBeneficiario . ' ' . $dato->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
                                        <td>{{$dato->cliente->municipio->NombreMunicipio}} - {{$dato->cliente->municipio->departamento->NombreDelDepartamento}}</td>
                                        <td>{{$dato->meta->proyecto->NumeroDeProyecto}}</td>
                                        <td>{{$dato->meta->nombre}}</td>
                                        <td>{{$dato->cliente->Status}}</td>
                                        <td>
                                        @permission('metas-clientes-eliminar')
                                            <form action="{{route('metas-clientes.destroy', $dato->id)}}" method="post" style="display:inline-block;">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                    <i class="fa fa-trash-o"></i>   
                                                </button>
                                            </form>
                                        @endpermission
                                                    
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted"> <h4>NO HAY REGISTROS</h4></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$metas_clientes->currentPage()}} de {{$metas_clientes->lastPage()}}. Total registros {{$metas_clientes->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $metas_clientes->appends(Request::only(['proyecto']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('metas-clientes-crear')
        @include('adminlte::clientes.metas.create')
    @endpermission 

    @section('mis_scripts')
    <script src="/js/metas/listar.js"></script>
    @endsection     
@endsection