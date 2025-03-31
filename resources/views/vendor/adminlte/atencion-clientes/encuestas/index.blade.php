@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-question-circle"></i>  Encuesta Satisfacción</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('encuestas.index')}}" role="search" method="GET">
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
                                    @permission('encuestas-crear')
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#addEncuesta">
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
                                    <th scope="col" style="width:50%">Descripcion</th> 
                                    <th scope="col">Respuesta</th>
                                    <th scope="col">Archivo</th>
                                    <th scope="col">Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                @foreach($encuestas as $encuesta)
                                <tr>
                                    <td>{{$encuesta->id}}</td>
                                    <td>
                                        <a href="{{route('encuestas.show', $encuesta->id)}}">
                                            {{$encuesta->descripcion}}
                                        </a>
                                    </td>
                                    <td>{{$encuesta->respuesta}}</td>
                                    <td>
                                        @if(!empty($encuesta->archivo))
                                            <audio controls class="input-sm">
                                                <source src="{{Storage::url($encuesta->archivo)}}" type="audio/mp4" />
                                            </audio>
                                        @endif
                                    </td>
                                    <td>{{$encuesta->estado}}</td>
                                    <td>
                                        <form action="{{route('encuestas.destroy', $encuesta->id)}}" method="post">
                                            <input type="hidden" name="_method" value="delete">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">

                                            <a href="{{route('encuestas.edit', $encuesta->id)}}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$encuestas->currentPage()}} de {{$encuestas->lastPage()}}. Total registros {{$encuestas->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $encuestas->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('encuestas-crear')
        @include('adminlte::atencion-clientes.encuestas.partials.add')
    @endpermission
    
@endsection