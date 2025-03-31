@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1> <i class="fa fa-edit"></i> Editar Contrato - {{$contrato->referencia}}</h1>

@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border bg-blue">
                    <h3 class="box-title">Detalles</h3>
                </div>
                <form action="{{route('clientes.contratos.update', [$contrato->ClienteId, $contrato->id])}}"
                    method="post">

                    <div class="box-body">
                        <input type="hidden" name="_method" value="PUT">
                        {{csrf_field()}}

                        <div class="row">

                            <div class="form-group col-md-3 {{ $errors->has('referencia') ? ' has-error' : '' }}">
                                <label class="control-label">*Referencia:</label>
                                <input type="text" class="form-control" name="referencia"
                                    value="{{ (!empty($contrato->referencia)) ? $contrato->referencia : $contrato->id}}"
                                    required>
                            </div>

                            <div class="form-group col-md-3 {{ $errors->has('tipo_cobro') ? ' has-error' : '' }}">
                                <label class="control-label">*Tipo de Cobro:</label>
                                <select class="form-control" name="tipo_cobro" required>
                                    <option value="">Elija una Opción</option>
                                    @foreach($tipos_cobro as $dato)
                                    @if($dato == $contrato->tipo_cobro)
                                    <option value="{{$dato}}" selected>{{$dato}}</option>
                                    @else
                                    <option value="{{$dato}}">{{$dato}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3 {{ $errors->has('vigencia') ? ' has-error' : '' }}">
                                <label class="control-label">*Vigencia en meses:</label>
                                <input type="number" class="form-control" name="vigencia" value="{{$contrato->vigencia_meses}}" required>
                            </div>

							<div class="form-group col-md-3 {{ $errors->has('estado') ? ' has-error' : '' }}">
                                <label class="control-label">*Estado contrato:</label>
                                <select class="form-control" name="estado" id="estado" required>
                                    <option value="">Elija una Opción</option>
                                    @foreach($estados as $dato)
                                    @if($dato == $contrato->estado)
                                    <option value="{{$dato}}" selected>{{$dato}}</option>
                                    @else
                                    <option value="{{$dato}}">{{$dato}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3 {{ $errors->has('fecha_inicio') ? ' has-error' : '' }}">
                                <label class="control-label">*Fecha de Inicio:</label>
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{$contrato->fecha_inicio}}" required>
                            </div>

                            <div class="form-group col-md-3 {{ $errors->has('fecha_instalacion') ? ' has-error' : '' }}">
                                <label class="control-label">*Fecha Instalacion:</label>
                                <input type="date" class="form-control" name="fecha_instalacion" id="fecha_instalacion" value="{{$contrato->fecha_instalacion}}" required>
                            </div>

							<div class="form-group col-md-3 {{ $errors->has('fecha_operacion') ? ' has-error' : '' }}">
                                <label class="control-label">Fecha Operación:</label>
                                <input type="date" class="form-control" name="fecha_operacion" id="fecha_operacion" min="{{$contrato->fecha_instalacion}}" value="{{$contrato->fecha_operacion}}">
                            </div>

                            <div class="form-group col-md-3 {{ $errors->has('fecha_final') ? ' has-error' : '' }}">
                                <label class="control-label text-red">Fecha de Finalizacion:</label>
                                <input type="date" class="form-control" name="fecha_final" value="{{$contrato->fecha_final}}">
                            </div>

                            <div class="form-group col-md-4">
                                <label class="control-label">Vendedor:</label>
                                <p>{{$contrato->vendedor->name}}</p>
                            </div>

                            

                            <div class="form-group col-md-12{{ $errors->has('observacion') ? ' has-error' : '' }}">
                                <label class="control-label">Observacion:</label>
                                <textarea class="form-control" name="observacion">{{$contrato->observacion}}</textarea>
                            </div>

                            <div class="form-group col-md-6 {{ $errors->has('status') ? ' has-error' : '' }} ">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="status">
                                            Aplicar estado al cliente.
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-md-6 {{ $errors->has('clausula') ? ' has-error' : '' }} ">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="clausula" {{($contrato->clausula_permanencia)? 'checked': ''}}>
                                            Marque si el contrato tiene clausula de permanencia.
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right" onclick="return validar();"><i
                                class="fa fa-floppy-o"></i> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('mis_scripts')
<script type="text/javascript">
function validar() {
    var contrato = $('#fecha_inicio').val();
    var instalacion = $('#fecha_instalacion').val();
    var estado = $('#estado').val();

    if (estado != 'ANULADO') {

        if (estado != 'PENDIENTE') {

            if (instalacion < contrato) {
                alert('la fecha de instalacion no puede ser menor que la del contrato.');
                return false;
            } else {
                return true;
            }
        } else {
            $('#fecha_instalacion').removeAttr('required');
        }
    } else {
        $('#fecha_instalacion').removeAttr('required');
    }


}
</script>
@endsection
@endsection