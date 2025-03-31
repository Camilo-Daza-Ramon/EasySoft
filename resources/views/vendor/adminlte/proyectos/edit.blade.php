@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-edit"></i> Editar Proyecto {{$proyecto->NumeroDeProyecto}}</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        
                    </div>
                    <div class="box-body">
                        <form action="{{route('proyectos.update',$proyecto->ProyectoID)}}" method="post">
                            <input type="hidden" name="_method" value="put">
                            @include('adminlte::proyectos.partials.form')
                            <button type="submit" class="btn btn-labeled btn-primary"><span class="btn-label"></span>Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection