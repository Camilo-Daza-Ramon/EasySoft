@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-home"></i>  Bodegas</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('bodegas.index')}}" role="search" method="GET">
                            <div class="form-group">
                                <input type="text" class="form-control" name="palabra" placeholder="Palabra" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}" autocomplete="off">
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
                                    @permission('puntos-atencion-crear')
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#addPunto">
                                            <i class="fa fa-plus"></i>  Agregar
                                        </a>
                                    </li>
                                    @endpermission                                    
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th style="width:10px;">ID</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                @foreach($bodegas as $punto_atencion)
                                <tr>
                                    <td>{{$punto_atencion->id}}</td>
                                    <td>
                                        <a href="{{route('puntos-atencion.show', $punto_atencion->id)}}">
                                            {{$punto_atencion->nombre}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$punto_atencion->proyecto->NumeroDeProyecto}}
                                    </td>
                                    <td>
                                        {{$punto_atencion->municipio->departamento->NombreDelDepartamento}}                                        
                                    </td>
                                    <td>
                                        {{$punto_atencion->municipio->NombreMunicipio}}
                                    </td> 
                                    <td>{{$punto_atencion->estado}}</td>
                                    <td>

                                        @permission('puntos-atencion-actualizar')
                                        <a href="{{route('puntos-atencion.edit', $punto_atencion->id)}}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endpermission

                                        @permission('puntos-atencion-eliminar')
                                        <form action="{{route('puntos-atencion.destroy', $punto_atencion->id)}}" method="post" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="delete">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        @endpermission
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$bodegas->currentPage()}} de {{$bodegas->lastPage()}}. Total registros {{$bodegas->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $bodegas->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('puntos-atencion-crear')
        @include('adminlte::atencion-clientes.puntos-atencion.partials.add')
    @endpermission

    @section('mis_scripts')
    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
    @endsection
    
@endsection