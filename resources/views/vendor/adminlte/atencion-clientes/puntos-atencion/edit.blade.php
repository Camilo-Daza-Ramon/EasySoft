@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-map-marker"></i>  Puntos de Atención - {{$punto_atencion->id}}</h1>
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
                        <form id="form-cliente" action="{{route('puntos-atencion.update', $punto_atencion->id)}}" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            {{csrf_field()}}        

                            <div class="row">
                                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-6">
                                  <label>Nombre</label>
                                  <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{$punto_atencion->nombre}}" required />
                                </div>
                                <div class="form-group{{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-6">
                                  <label>Proyecto</label>
                                  <select class="form-control" name="proyecto" id="proyecto" required>
                                    <option value="">Elija un proyecto</option>
                                    @foreach($proyectos as $proyecto)
                                        @if($proyecto->ProyectoID == $punto_atencion->proyecto_id)
                                            <option value="{{$proyecto->ProyectoID}}" selected>{{$proyecto->NumeroDeProyecto}}</option>
                                        @else
                                            <option value="{{$proyecto->ProyectoID}}">{{$proyecto->NumeroDeProyecto}}</option>
                                        @endif
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <div class="row">            
                                <div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-md-6">
                                  <label>Departamento</label>
                                  <select class="form-control" name="departamento" id="departamento" required>
                                    <option value="">Elija un departamento</option>
                                  </select>
                                </div>
                                <div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-md-6">
                                  <label>Municipio</label>
                                  <select class="form-control" name="municipio" id="municipio" required>
                                    <option value="">Elija un municipio</option>
                                  </select>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }} col-md-6">
                                  <label>Dirección</label>
                                  <input type="text" class="form-control" name="direccion" value="{{$punto_atencion->direccion}}" required>
                                </div>
                                <div class="form-group{{ $errors->has('barrio') ? ' has-error' : '' }} col-md-6">
                                  <label>Barrio</label>
                                  <input type="text" class="form-control" name="barrio" value="{{$punto_atencion->barrio}}" required>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group{{ $errors->has('latitud') ? ' has-error' : '' }} col-md-4">
                                  <label>Latitud</label>
                                  <input type="text" class="form-control" name="latitud" value="{{$punto_atencion->latitud}}" required>
                                </div>
                                <div class="form-group{{ $errors->has('longitud') ? ' has-error' : '' }} col-md-4">
                                  <label>Longitud</label>
                                  <input type="text" class="form-control" name="longitud" value="{{$punto_atencion->longitud}}" required>
                                </div>
                                <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-4">
                                  <label>Estado</label>
                                  <select class="form-control" name="estado" required>
                                    <option value="">Elija una opción</option>
                                    @foreach($estados as $estado)
                                        @if($estado == $punto_atencion->estado)
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
    @section('mis_scripts')
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({!!$punto_atencion->municipio->DeptId!!});

                buscar_municipio({!!$punto_atencion->municipio_id!!});
            });
        </script>
    @endsection
@endsection