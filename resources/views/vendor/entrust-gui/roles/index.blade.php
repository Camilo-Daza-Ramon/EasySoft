@extends('adminlte::layouts.app')


@section('contentheader_title')
<h1><i class="fa fa-sitemap"></i> Roles</h1>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <!-- Default box -->
            <div class="box box-info">
                <div class="box-header bg-blue">
                    <form class="navbar-form navbar-left" action="{{route('roles.buscar')}}" role="search" method="GET">
                        <div class="form-group">
                            <input type="text" id="palabra" class="form-control" name="palabra" placeholder="Buscar" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}">
                        </div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>

                    <div class="box-tools pull-right">

                        <div class="btn-group">
                            <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <span id="icon-opciones" class="fa fa-gears"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                @permission('roles-crear')
                                <li>
                                    <a href="{{ route('entrust-gui::roles.create') }}" class="">
                                        <i class="fa fa-plus"></i>Agregar</a>
                                </li>
                                @endpermission

                                @permission('roles-exportar')
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
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                        @foreach($models as $model)
                        <tr>
                            <td>{{ $model->name }}</th>
                            <td class="col-xs-3">

                                @permission('roles-editar')
                                <a href="{{ route('entrust-gui::roles.edit', $model->id) }}" class="btn btn-primary btn-xs " title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endpermission

                                @permission('roles-eliminar')
                                <form action="{{ route('entrust-gui::roles.destroy', $model->id) }}" method="post" style="display: inline-block">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <button type="submit" class="btn btn-danger btn-xs " onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                                @endpermission
                            </td>
                        </tr>
                        @endforeach
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <center>
                                        {!! $models->appends(Request::only(['palabra']))->links() !!}
                                    </center>
                                </td>
                            </tr>
                        </tfoot>
                    </table>


                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <!-- paginacion aquí -->
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>

@section('mis_scripts')
<script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>

<script>
    $('#exportar').on('click', function() {
        var parametros = {
            palabra: "{!! (isset($_GET['palabra'])? $_GET['palabra']:'') !!}",
            '_token': $('input:hidden[name=_token]').val()
        }

        exportarConAjax('/roles/exportar', parametros);

    });
</script>

@endsection

@endsection