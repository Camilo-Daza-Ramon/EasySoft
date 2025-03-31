@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-cubes"></i>  Proyectos</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('proyectos.index')}}" role="search" method="GET">
                              <div class="form-group">
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre proyecto" value="{{(isset($_GET['nombre'])? $_GET['nombre']:'')}}" autocomplete="off">

                                <input type="text" class="form-control" name="contrato" placeholder="Número contrato" value="{{(isset($_GET['contrato'])? $_GET['contrato']:'')}}" autocomplete="off">

                                <select class="form-control" name="estado">
                                    <option value="">Elija un estado</option>
                                    <option value="A">ACTIVO</option>
                                    <option value="I">INACTIVO</option>                                    
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
                                    @permission('proyectos-crear')
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#addProyecto">
                                            <i class="fa fa-plus"></i>  Agregar
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
                                    <td>ID</td>
                                    <th scope="col">Nombre</th> 
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Contrato</th>
                                    <th scope="col">Total Municipios</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" style="width: 10%;">Acciones</th>
                                </tr>
                                @foreach($proyectos as $dato)
                                <tr>
                                    <td>{{$dato->ProyectoID}}</td>
                                    <td><a href="{{route('proyectos.show', $dato->ProyectoID)}}">{{$dato->NumeroDeProyecto}}</a></td>
                                    <td>{{$dato->DescripcionProyecto}}</td>
                                    <td>{{$dato->NumeroDeContrato}}</td>
                                    <td>
                                        {{count($dato->municipio)}}
                                    </td>
                                    <td>{{$dato->Status}}</td>
                                    <td>
                                        @permission('proyectos-estadisticas')
                                            <a href="{{route('proyectos.estadisticas', $dato->ProyectoID)}}" class="btn btn-default btn-xs"> <i class="fa fa-pie-chart"></i> </a>
                                        @endpermission

                                        @permission('proyectos-editar')
                                            <a href="{{route('proyectos.edit', $dato->ProyectoID)}}" class="btn btn-primary btn-xs"> <i class="fa fa-edit"></i> </a>
                                        @endpermission

                                        @permission('proyectos-eliminar')
                                            <form action="{{route('proyectos.destroy', $dato->ProyectoID)}}" method="post" style="display:inline-block;">
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
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$proyectos->currentPage()}} de {{$proyectos->lastPage()}}. Total registros {{$proyectos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $proyectos->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('proyectos-crear')
        @include('adminlte::proyectos.partials.add')
    @endpermission
    
@endsection