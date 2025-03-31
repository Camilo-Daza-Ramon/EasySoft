@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-plus"></i> Añadir Infraestructura</h1>
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border bg-blue">
                <h3 class="box-title">Detalles</h3>
            </div>
            <form id="form-add-infra" action="{{route('infraestructuras.store')}}" method="post">
                {{csrf_field()}}
                <!-- /.box-header -->
                @include('adminlte::infraestructuras.partials.form')

                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('mis_scripts')
<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios3.js')}}"></script>


@endsection
@endsection