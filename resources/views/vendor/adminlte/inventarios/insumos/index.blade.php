@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-file-o"></i>  Inventarios - Insumos</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('inventarios.insumos.index')}}" role="search" method="GET">
                            <div class="form-group">
                                <input type="text" class="form-control" name="palabra" placeholder="Buscar" value="{{(isset($_GET['palabra'])) ? $_GET['palabra'] : ''}}">
                              

                                <select class="form-control" name="proyecto" id="proyecto">
                                    <option value="">Elija un proyecto</option>
                                    @foreach($proyectos as $proyecto)

                                        <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="departamento" id="departamento">
                                    <option value="">Elija un departamento</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="municipio" id="municipio">
                                    <option value="">Elija un municipio</option>
                                </select> 

                                <select class="form-control" name="estado" id="estado">
                                    <option value="">Elija un estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{$estado}}</option>
                                    @endforeach
                                </select>

                            </div>
                              
                              <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                        </form>

                        <div class="btn-group pull-right">
                            <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <span id="icon-opciones" class="fa fa-gears"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                @permission('inventarios-crear')
                                    <li><a href="#" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i>  Agregar </a></li>
                                @endpermission
                                @permission('inventarios-exportar')
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                @endpermission
                            </ul>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped dataTable">                    
                            <tbody>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Unidad</th>
                                    @role(['admin'])
                                        <th scope="col">Acciones</th>
                                    @endrole
                                </tr>
                                @foreach($insumos as $dato)
                                <tr>
                                    <td>{{$dato->InsumoId}}</td>
                                    <td><a href="{{route('inventarios.insumos.show', $dato->InsumoId)}}">{{$dato->Codigo}}</a></td>
                                    <td>{{$dato->InsumoTipo}}</td>                                    
                                    <td>{{$dato->Descripcion}}</td>
                                    <td>{{$dato->activo_fijo_count}}</td>
                                    <td>{{$dato->UnidadCompra}}</td>
                                    @role(['admin'])
                                        <td>
                                            <form action="{{route('inventarios.insumos.delete', $dato->InsumoId)}}" method="post">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                <a href="{{route('inventarios.insumos.edit', $dato->InsumoId)}}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endrole
                                </tr>

                                @endforeach
                            </tbody>
                       </table>                
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$insumos->currentPage()}} de {{$insumos->lastPage()}}. Total registros {{$insumos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $insumos->links() !!}
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    @section('mis_scripts')

    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>


    
    <script type="text/javascript">
        $(document).ready(function(){
            buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
            buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
        });
    </script>

    @permission('inventarios-exportar')
    <script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>

    <script type="text/javascript">
        $('#exportar').on('click',function(){
            var parametros = {
                // palabra : "{!! (isset($_GET['palabra'])? $_GET['palabra']:'') !!}",
                // proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                // municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                // departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                // estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                '_token' : $('input:hidden[name=_token]').val() 
            }
            exportarConAjax('/inventarios/exportar', parametros);


            
        });
    </script>
    @endpermission
    
    @endsection     
@endsection