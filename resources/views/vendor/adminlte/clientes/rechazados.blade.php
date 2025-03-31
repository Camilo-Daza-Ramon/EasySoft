@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-graduation-cap"></i>  Clientes - Rechazados</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" acction="{{route('clientes.index')}}" role="search" method="GET">
                              <div class="form-group">
                                <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}">
                              </div>
                              <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>                                    
                                    <th scope="col">Documento</th> 
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>                        
                                    <th>Estado</th>
                                </tr>
                                @foreach($clientes as $dato)
                                <tr>
                                    <td><a href="{{route('clientes.show', $dato->ClienteId)}}">{{$dato->Identificacion}}</a></td>

                                    <td>{{$dato->NombreBeneficiario}} {{$dato->Apellidos}}</td>
                                    
                                    
                                        @if(!empty($dato->ubicacion))
                                            <td>{{$dato->ubicacion->municipio->NombreMunicipio}}</td>
                                            <td>{{$dato->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                        @else
                                            <td>{{$dato->municipio->NombreMunicipio}}</td>
                                            <td>{{$dato->municipio->departamento->NombreDelDepartamento}}</td>
                                        @endif


                                    <td>{{$dato->Status}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$clientes->currentPage()}} de {{$clientes->lastPage()}}. Total registros {{$clientes->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $clientes->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection