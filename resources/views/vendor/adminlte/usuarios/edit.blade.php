@extends('adminlte::layouts.app')

@section('contentheader_title')
  Usuarios - Editar
@endsection

@section('main-content')

    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">                
                    <div class="box-header with-border">
                        <h1 class="box-title">Usuarios - Editar</h1>
                    </div>
                    <div class="box-tools"></div>
                    <form class="" enctype="multipart/form-data" action="{{route('usuarios.update', $usuario->id)}}" method="post">
                        <input name="_method" type="hidden" value="PATCH">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @include('adminlte::usuarios.partials.form')
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Guardar</button>  
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('mis_scripts')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.js"></script>
		<script>
            (function() {
            $('.js-example-basic-multiple').select2();
            })();
		</script>
	@endsection
@endsection

