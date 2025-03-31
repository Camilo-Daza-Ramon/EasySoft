@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-check-square-o"></i> Acuerdos de pago</h1>
@endsection

@section('main-content')
    <div class="row">
        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header bg-blue with-border">
                            <form class="navbar-form navbar-left" action="{{route('acuerdos.index')}}" role="search" method="GET">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="documento" placeholder="Cliente" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">
                                    <select class="form-control" name="estado" >
                                        <option value="">Eligir estado</option>
                                        @foreach ($estados as $estado)
                                            <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{ $estado }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                            </form>
                            @permission('acuerdos-pago-crear')
                                <div class="btn-group pull-right">
                                    <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <span id="icon-opciones" class="fa fa-gears"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a class="float-bottom btn-sm" href="{{route('acuerdos.create')}}">
                                                <i class="fa fa-bullhorn"></i> Crear Acuerdo          
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endpermission 
                        </div>
                        <div class="box-body table-responsive">
                            <table  id="" class="table table-bordered table-striped dataTable">
                                <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Deuda</th>
                                        <th>Cuotas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>									
                                    </tr>
                                    <?php $contar = 0; ?>
                                    @foreach($acuerdos as $acuerdo)
                                        <tr>
                                            <td>{{$contar+=1}}</td>
                                            <td>                             
                                                {{$acuerdo->cliente->Identificacion}}                                               
                                            </td>
                                            <td>                                               
                                                ${{number_format($acuerdo->valor_deuda, 2, ',', ' ')}}                                               
                                            </td>
                                            <td>
                                                {{$acuerdo->total_cuotas}}
                                            </td>
                                            <td>
                                                @if ($acuerdo->estado == 'ACTIVO')
                                                    <span class="label label-info">{{$acuerdo->estado}}</span>                                    
                                                @else
                                                    <span class="label label-success">{{$acuerdo->estado}}</span> 
                                                @endif
                                            </td>
                                            <td>
                                                @permission('acuerdos-pago-ver')
                                                    <button class="btn bt-default btn-xs " onclick="traer_acuerdo({!!$acuerdo->id!!});return false;"><i class="fa fa-eye"></i></button>
                                                @endpermission
                                            </td>									
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer clearfix">
                            <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$acuerdos->currentPage()}} de {{$acuerdos->lastPage()}}. Total registros {{$acuerdos->total()}}</span>
                            <!-- paginacion aquí -->
                            {!! $acuerdos->appends(Request::only(['documento','estado']))->links() !!}
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    @permission('acuerdos-pago-ver')
        @include('adminlte::acuerdos.partials.show-acuerdo')
    @endpermission
    @section('mis_scripts')
        <script type="text/javascript" src="/js/acuerdos/funcion_moneda.js"></script>
        <script type="text/javascript" src="/js/acuerdos/show.js"></script>
    @endsection

@endsection