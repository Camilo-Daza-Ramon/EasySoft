@extends('adminlte::layouts.app')

@section('htmlheader_title')
Usuarios
@endsection

@section('contentheader_title')
<h1> <i class="fa fa-users"> </i> Usuarios </h1>
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <!-- Default box -->
            <div class="box box-info">
                <div class="box-header with-border bg-blue">
                    <form id="formBuscarUsuarios" class="navbar-form navbar-left" action="{{route('usuarios.index')}}" role="search" method="GET">
                        <div class="form-group">
                            <input type="text" id="palabra" class="form-control" name="palabra" placeholder="Buscar" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}">
                        </div>
                        <select class="form-control" name="rol" id="rol">
                            <option value="">Elija un rol</option>
                            @foreach($roles as $rol)
                            <option value="{{$rol->id}}" {{(isset($_GET['rol'])) ? (($_GET['rol'] == $rol->id) ? 'selected' : '') : ''}}>{{$rol->display_name}}</option>
                            @endforeach
                        </select>
                        <select class="form-control" name="estado" id="estado">
                            <option value="">Elija un estado</option>
                            <option {{ (isset($_GET['estado']) && $_GET['estado'] == 'ACTIVO') ? 'selected' : '' }} value="ACTIVO">ACTIVO</option>
                            <option {{ (isset($_GET['estado']) && $_GET['estado'] == 'INACTIVO') ? 'selected' : '' }} value="INACTIVO">INACTIVO</option>
                        </select>
                        <input type="hidden" name="busquedaHecha" id="busquedaHecha">

                        <button type="submit" class="btn btn-primary">Buscar</button>
                        <button onclick="limpiarFiltros()" type="button" class="btn btn-primary">Limpiar filtros</button>

                    </form>

                    <div class="box-tools pull-right">

                        <div class="btn-group">
                            <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <span id="icon-opciones" class="fa fa-gears"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                @permission('usuarios-crear')
                                <li>
                                    <a style="cursor: pointer;" data-toggle="modal" data-target="#modal-default">
                                        <i class="fa fa-plus"></i> Agregar
                                    </a>
                                </li>
                                @endpermission

                                @permission('usuarios-exportar')
                                <li>
                                    <a href="#" id="exportar">
                                        <i class="fa fa-file-excel-o"></i> Exportar
                                    </a>
                                </li>
                                @endpermission



                            </ul>
                        </div>
                    </div>

                </div>

                <div class="box-body table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>

                        @foreach($usuarios as $usuario)
                        <tr>

                            <td><a href="{{route('usuarios.show', $usuario->id)}}">{{$usuario->name}}</a></td>
                            <td>{{$usuario->email}}</td>
                            <td>
                                @if(!empty($usuario->roles->get(0)))
                                {{$usuario->roles->get(0)->display_name}}
                                @endif
                            </td>
                            <td>
                                @if($usuario->estado == 'ACTIVO')
                                <span class="label label-primary">{{$usuario->estado}}</span>
                                @else
                                <span class="label label-danger">{{$usuario->estado}}</span>
                                @endif

                            </td>
                            <td>
                                @permission('usuarios-editar')
                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-primary btn-xs" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endpermission


                                @permission('usuarios-eliminar')
                                <form style="display: inline-block;" action="{{route('usuarios.destroy', $usuario->id)}}" method="post">
                                    <input type="hidden" name="_method" value="delete">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro INACTIVAR al usuario?');" title="inactivar">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                                @endpermission
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$usuarios->currentPage()}} de {{$usuarios->lastPage()}}. Total registros {{$usuarios->total()}}</span>
                    <!-- paginacion aquí -->
                    {!! $usuarios->appends(Request::only(['palabra', 'rol', 'estado']))->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>



@permission('usuarios-crear')
<!--MODAL-->
@include('adminlte::usuarios.add')
<!-- /.modal -->
@endpermission

@section('mis_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.js"></script>
<script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>

<script>
    $('.js-example-basic-multiple').select2({
        placeholder: "Elija proyectos",
    });
    $(".select2-container").attr('style', "width:100%");

    function limpiarFiltros() {
        $('#rol').val('');
        $('#estado').val('');
        $('#palabra').val('');
        window.location.href = '{{ route("usuarios.index") }}'
    }

    $('#exportar').on('click', function() {
        var parametros = {
            palabra: "{!! (isset($_GET['palabra'])? $_GET['palabra']:'') !!}",
            rol: "{!! (isset($_GET['rol'])? $_GET['rol']:'') !!}",
            estado: "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
            '_token': $('input:hidden[name=_token]').val()
        }

        exportarConAjax('/usuarios/exportar', parametros);

    });

    
</script>

@endsection
@endsection