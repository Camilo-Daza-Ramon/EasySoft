@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-building-o  "></i> Campañas</h1>
@endsection

@section('main-content')
    <div class="row">
        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header bg-blue with-border">
                            <form class="navbar-form navbar-left" action="{{route('campanas.index')}}" role="search" method="GET">
                                <div class="form-group">
                                <input type="text" class="form-control" name="nombre" placeholder="Buscar" value="{{(isset($_GET['nombre'])? $_GET['nombre']:'')}}" autocomplete="off">

                                <select class="form-control" name="tipo" id="tipo">
                                    <option value="">Elija un tipo</option>
                                    @foreach($tipo_campañas as $tipo)
                                        <option value="{{$tipo}}" {{(isset($_GET['tipo'])) ? (($_GET['tipo'] == $tipo) ? 'selected' : '') : ''}}>{{$tipo}}</option>
                                    @endforeach
                                </select>                                                 

                                <input type="month" name="mes"  class="form-control" value="{{(isset($_GET['mes'])? $_GET['mes']:'')}}">
                                
                                <select class="form-control" name="estado" id="estado">
                                    <option value="">Elija un estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{$estado}}</option>
                                    @endforeach
                                </select>

                                </div>
                                <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                            </form>
                            @permission('campañas-crear')
                                <div class="btn-group pull-right">
                                    <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <span id="icon-opciones" class="fa fa-gears"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a class="float-bottom btn-sm" href="{{route('campanas.create')}}">
                                                <i class="fa fa-bullhorn"></i> Crear Campaña          
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
                                        <th>Nombre</th>
                                        <th>Fecha inicio</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>									
                                    </tr>
                                    <?php $contar = 0; ?>
                                    @foreach($campañas as $campaña)
                                        <tr>
                                            <td>{{$contar+=1}}</td>
                                            <td>
                                                @if ($campaña->estado == 'EN EJECUCION' or auth()->user()->can('campañas-ejecucion'))
                                                    <a href="{{ route('campanas.show', $campaña->id) }}">{{ $campaña->nombre }}</a>                                                                                                    
                                                @else
                                                    {{ $campaña->nombre }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($campaña->estado == 'FINALIZADA')
                                                    {{$campaña->fecha_inicio}} - {{$campaña->fecha_finalizacion}}
                                                @else
                                                    {{$campaña->fecha_inicio}}
                                                @endif
                                            </td>
                                            <td>{{$campaña->tipo}}</td>
                                            <td>
                                                @if ($campaña->estado == 'EN EJECUCION')
                                                    <span class="label label-success">{{$campaña->estado}}</span>
                                                @elseif ($campaña->estado == 'FINALIZADA')
                                                    <span class="label label-warning">{{$campaña->estado}}</span>
                                                @else
                                                    <span class="label label-info">{{$campaña->estado}}</span> 
                                                @endif
                                            </td>
                                            <td>
                                                @permission('campañas-editar')
 
                                                    @if ($campaña->estado == 'POR EJECUTAR')
                                                        <form style="display: inline-block;" action="{{route('campanas.estado', $campaña->id)}}" method="post">
                                                            <input type="hidden" name="_method" value="put">
                                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                            <input type="hidden" name="accion" value="true">


                                                            <button type="submit"  class="btn btn-success btn-block btn-xs"> Ejecutar</button>							           
                                                        
                                                        </form>
                                                    @endif
                                                
                                                    @if($campaña->estado == 'EN EJECUCION')
                                                        <form style="display: inline-block;" action="{{route('campanas.estado', $campaña->id)}}" method="post">
                                                            <input type="hidden" name="_method" value="put">
                                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                            <input type="hidden" name="accion" value="false">
                                                            <button type="submit"  class="btn btn-warning btn-block btn-xs"> Finalizar</button>							           
                                                        
                                                        </form>	
                                                    @endif                           

                                                    @if($campaña->estado != 'FINALIZADA')                                                                              
                                                        <a href="{{route('campanas.edit', $campaña->id)}}" class="btn btn-primary btn-xs"> <i class="fa fa-edit"></i> </a>
                                                    @endif                                                  
                                                @endpermission 
                                                @permission('campañas-eliminar')
                                                    <form action="{{route('campanas.delete', $campaña->id)}}" method="post" style="display: inline-block;">
                                                        <input type="hidden" name="_method" value="delete"> 
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                        <button type="submit" onclick="return confirm('Estas seguro Eliminar la campaña?');" title="Eliminar" class="btn btn-danger btn-xs">
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
                            <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$campañas->currentPage()}} de {{$campañas->lastPage()}}. Total registros {{$campañas->total()}}</span>
                            <!-- paginacion aquí -->
                            {!! $campañas->appends(Request::only(['nombre','mes','tipo','estado']))->links() !!}
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>
@endsection