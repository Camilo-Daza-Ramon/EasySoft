@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-question-circle"></i>  Encuesta Satisfacción - {{$encuesta->id}}</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-8">
                
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <h3 class="box-title">Editar</h3>
                    </div>
                    <div class="box-body">
                        <form id="form-cliente" action="{{route('encuestas.update', $encuesta->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="PUT">
                            {{csrf_field()}}        

                            <div class="row">

                                <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
                                    <label>Descripción</label>
                                    <textarea class="form-control" name="descripcion" required>{{$encuesta->descripcion}}</textarea>
                                </div>
                                <div class="form-group{{ $errors->has('respuestas') ? ' has-error' : '' }} col-md-12">
                                    <label>Respuestas</label>
                                    <textarea class="form-control" name="respuestas" placeholder="agregar respuestas separadas por coma" required>{{str_replace(array("[","]"), "" , $encuesta->respuesta)}}</textarea>
                                </div>
                                <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }} col-md-6">
                                    <label>Archivo de Audio</label>
                                    <input type="file" class="form-control" name="archivo" accept=".mp3,audio/*">
                                </div>
                                <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-6">
                                    <label>Estado</label>
                                    <select class="form-control" name="estado" required>
                                        <option value="">Elija una opcion</option>
                                        @foreach($estados as $estado)
                                            @if($estado == $encuesta->estado)
                                                <option value="{{$estado}}" selected>{{$estado}}</option>
                                            @else
                                                <option value="{{$estado}}">{{$estado}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection