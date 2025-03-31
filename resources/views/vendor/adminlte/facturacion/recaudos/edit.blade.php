@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-dollar"></i>  Recaudo #{{$recaudo->RecaudoId}} - Editar</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <h2 class="box-title"> Datos Recaudo</h2>
                    </div>
                    <div class="box-body">
                        <form class="form-horizontal" action="{{route('recaudos.update', $recaudo->RecaudoId)}}" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            {{csrf_field()}}

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Recaudo ID</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="recaudo_id" value="{{$recaudo->RecaudoId}}" disabled>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('cedula') ? ' has-error' : '' }}">
                                <label class="col-sm-3 control-label">*Cedula</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="cedula" value="{{$recaudo->cedula}}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nombres</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="nombres" value="{{$recaudo->nombres}}" disabled>
                                </div>
                            </div>   

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Apellidos</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="apellidos" value="{{$recaudo->apellido1}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Valor Pagado</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="valor" value="${{number_format($recaudo->valor,0, ',','.')}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fecha</label>
                                <div class="col-sm-8">
                                    <input type="datetime" class="form-control" name="fecha" value="{{$recaudo->Fecha}}" disabled>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
                            
                        </form>

                    </div>
                </div>                
            </div>
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <h2 class="box-title"> Clientes posibles</h2>

                    </div>
                    <div class="box-body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>CEDULA</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($clientes) > 0)
                                    @foreach($clientes as $cliente)
                                        <tr>
                                            <td>{{$cliente->ClienteId}}</td>
                                            <td>{{$cliente->Identificacion}}</td>
                                            <td>{{mb_convert_case($cliente->NombreBeneficiario . ' ' . $cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
                                        </tr>
                                    @endforeach

                                @else
                                <tr>
                                    <td colspan="3">
                                        <h3 class="text-center">No hay clientes relacionados</h3>
                                    </td>
                                </tr>
                                    

                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('mis_scripts')   
    @endsection
@endsection