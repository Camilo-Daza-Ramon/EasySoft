@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-hdd-o"></i> Infraestructuras por instalar</h1>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header bg-blue with-border">
                    <form action="{{route('instalaciones.instalar.infra')}}" role="search" method="GET">

                        <div class="row">
                            <div class="form-group col-md-3">
                                <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{(isset($_GET['nombre'])? $_GET['nombre']:'')}}" autocomplete="off">
                            </div>

                            <div class="form-group col-md-3">
                                <select class="form-control" name="proyecto" id="proyecto">
                                    <option value="">Elija un proyecto</option>
                                    @foreach($proyectos as $proyecto)

                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <select class="form-control" name="departamento" id="departamento">
                                    <option value="">Elija un departamento</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <select class="form-control" name="municipio" id="municipio">
                                    <option value="">Elija un municipio</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>


                </div>
                <div class="box-body table-responsive">
                    <table id="areas" class="table table-bordered table-striped dataTable">
                        <tbody>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Datos de Ubicación</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Tipo de Categoría</th>
                                <th scope="col">Municipio</th>
                                <th scope="col">Departamento</th>
                                <th scope="col">Estado</th>

                            </tr>
                            @if($instalaciones->count() > 0)
                            @foreach($instalaciones as $infra)
                            <tr>
                                <th>
                                    @permission('instalaciones-infraestructura-crear')
                                    <a href="{{route('instalaciones.create.infra', $infra->id)}}">{{$infra->id}}</a>
                                    @else
                                    {{$infra->id}}
                                    @endpermission
                                </th>
                                <td>{{mb_convert_case($infra->nombre, MB_CASE_TITLE, "UTF-8")}}</td>
                                <td>{{$infra->direccion}}</td>
                                <td>{{isset($infra->datos_ubicacion) ? $infra->datos_ubicacion : '-'}}</td>
                                <td>{{$infra->categoria}}</td>
                                <td>{{$infra->tipo_categoria}}</td>
                                <td>{{$infra->municipio->NombreMunicipio}}</td>
                                <td>{{$infra->municipio->departamento->NombreDelDepartamento}}</td>
                                <td>
                                    <span class="label label-primary">{{$infra->estado}}</span>
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
                    <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$instalaciones->currentPage()}} de {{$instalaciones->lastPage()}}. Total registros {{$instalaciones->total()}}</span>
                    <!-- paginacion aquí -->
                    {!! $instalaciones->appends(Request::only(['nombre', 'departamento', 'municipio']))->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>


@section('mis_scripts')
<script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>


@endsection
@endsection