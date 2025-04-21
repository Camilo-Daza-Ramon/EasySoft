@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-internet-explorer"></i> Gestion de Red</h1>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header bg-blue with-border">
                    <form id="formBuscarPlataformas" class="navbar-form navbar-left" action="{{route('gestion.index')}}" role="search" method="GET">
                        <div class="row">
                            <div class="form-group" style="margin: 0 5px;">
                                @auth
                                    @if(!in_array(auth()->user()->id, [274, 275]))
                                        <select class=" form-control" name="proyecto" id="proyecto">
                                            <option value="">Elija un proyecto</option>
                                            @foreach($proyectos as $proyecto)
                                            <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>
                                                {{$proyecto->NumeroDeProyecto}}
                                            </option>
                                            @endforeach
                                        </select>
                                    @endif
                                @endauth

                            </div>
                            <div class="form-group" style="margin: 0 5px;">

                                <select class="form-control" name="departamento" id="departamento">
                                    <option value="">Elija un departamento</option>
                                    @foreach($departamentos as $departamento)
                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="margin: 0 5px;">
                                <select class="form-control" name="municipio" id="municipio">
                                    <option value="">Elija un municipio</option>
                                </select>
                            </div>
                            <button style="margin: 0 5px;" type="submit" class="btn btn-primary">Buscar</button>
                            <button onclick="limpiarFiltros()" type="button" class="btn btn-primary">Limpiar filtros</button>
                        </div>

                    </form>
                    @permission('gestion-red-crear')
                    <div class="box-tools pull-right">
                        <a href="{{route('gestion.create')}}" class="btn btn-default float-bottom btn-sm">
                            <i class="fa fa-plus"></i> Agregar
                        </a>
                    </div>
                    @endpermission
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plataforma</th>
                                <th>Usuario</th>
                                <th>Contraseña</th>
                                <th>Instrucciones</th>
                                <th>Municipios</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plataformas as $dato)
                            <tr>
                                <td><a target="_blank" href="{{ $dato->link }}">{{$dato->nombre}}</a></td>
                                <td>
                                    {{$dato->acceso->usuario}}
                                </td>
                                <td>
                                    @php
                                        try {
                                            $password = Illuminate\Support\Facades\Crypt::decrypt($dato->acceso->contrasena);
                                        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                            // Si hay error, manejarlo y asignar un valor predeterminado
                                            $password = 'Error al descifrar';
                                            logger('Error al descifrar contraseña del acceso ID: ' . $dato->id . ' - ' . $e->getMessage());
                                        }
                                    @endphp
                                <input id="input-acceso-password-{{$dato->id}}" style="width: 100px;" disabled value="{{ $password }}" type="password">
                                <button onclick="copyName('{{$dato->id}}')" style="padding: 2px 10px;" class="btn btn-primary">
                                        <i id="icon-copy-password-{{$dato->id}}" class="fa fa-copy"></i>
                                    </button>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('gestion.view.instruccion', [$dato->id, $dato->instruccion->nombre]) }}">Manual de Usuario</a>
                                </td>
                                <td>
                                    @foreach($dato->municipios as $mun)
                                    <p>{{$mun->NombreMunicipio}}</p>
                                    @endforeach
                                </td>
                                <td>
                                    <div style="display: flex; justify-content:start;">

                                        @permission('gestion-red-editar')
                                        <a style="margin: 0  5px;" href="{{ route('gestion.edit', ['id' => $dato->id]) }}" class="btn btn-xs btn-primary" data-id="{!! $dato->id !!}"><i class="fa fa-edit"></i></a>
                                        @endpermission

                                        @permission('gestion-red-eliminar')
                                        <form action="{{ route('gestion.destroy', $dato->id) }}" method="post">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </form>
                                        @endpermission
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$plataformas->currentPage()}} de {{$plataformas->lastPage()}}. Total registros {{$plataformas->total()}}</span>
                    <!-- paginacion aquí -->
                    {!! $plataformas->links() !!}

                </div>
                <div class="box-footer clearfix"></div>
            </div>

        </div>
    </div>
</div>


@section('mis_scripts')

<script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        buscar_departamentos("{{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}}");
        buscar_municipio("{{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}}");
    });
</script>

<script type="text/javascript">

    function limpiarFiltros() {
        $('#proyecto').val('');
        $('#departamento').val('');
        $('#municipio').val('');
        window.location.href = '{{ route("gestion.index") }}'
    }

    async function copyName(id) {
        const input = $('#input-acceso-password-' + id);
        const icon = $('#icon-copy-password-' + id);
        await navigator.clipboard.writeText(input.val());
        icon.removeClass('fa-copy');
        icon.addClass('fa-check');
        setTimeout(() => {
            icon.removeClass('fa-check');;
            icon.addClass('fa-copy');
        }, "2000");

    }
</script>

@endsection
@endsection