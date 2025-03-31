@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-internet-explorer"></i> Reporte de ONTS Fallidas</h1>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header bg-blue with-border">

                    <form class="navbar-form navbar-left" action="{{route('red.reporte.onts')}}" role="search" method="GET">
                        <div class="row">

                            <div class="form-group">
                                <input type="text" id="palabra" class="form-control" name="palabra" placeholder="Buscar por Cedula o Serial" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}">
                            </div>

                            <button style="margin: 0 5px;" type="submit" class="btn btn-primary">Buscar</button>
                        </div>

                    </form>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ONT Serial</th>
                                <th>Cedula Cliente</th>
                                <th>Mensaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportes as $rep)
                            <tr>
                                <td>
                                    {{$rep->id}}
                                </td>

                                <td>
                                    {{$rep->ONT_Serial}}
                                </td>

                                <td>
                                    <a href="{{ route('clientes.show', ['id' => $rep->ClienteId]) }}" target="_blank">
                                        {{$rep->Identificacion}}
                                    </a>
                                </td>

                                <td>
                                    {{$rep->mensaje}}
                                </td>

                                <td>
                                    <div style="display: flex; justify-content:start;">

                                        @permission('reporte-onts-eliminar')
                                        <form action="{{ route('red.reporte.onts.destroy', $rep->id) }}" method="post">
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
                    <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$reportes->currentPage()}} de {{$reportes->lastPage()}}. Total registros {{$reportes->total()}}</span>
                    <!-- paginacion aquí -->
                    {!! $reportes->appends(Request::only(['palabra']))->links() !!}

                </div>
                <div class="box-footer clearfix"></div>
            </div>

        </div>
    </div>
</div>


@section('mis_scripts')



@endsection
@endsection