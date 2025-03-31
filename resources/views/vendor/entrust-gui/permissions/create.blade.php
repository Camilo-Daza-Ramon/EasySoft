@extends('adminlte::layouts.app')


@section('contentheader_title')
    <h1><i class="fa fa-sitemap"></i>  Permisos</h1>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
            	<!-- Default box -->
                <div class="box box-info">
                    <div class="box-header bg-blue">
                        <h3 class="box-title">Crear Permisos</h3>                        
                    </div>
                    
                    <div class="box-body table-responsive"> 
                    	<form action="{{ route('entrust-gui::permissions.store') }}" method="post" role="form">
                    		@include('entrust-gui::permissions.partials.form')
						    <button type="submit" class="btn btn-labeled btn-primary"><span class="btn-label"></span>Guardar</button>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('mis_scripts')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.js"></script>
		<script>
		(function() {
		  $('select').select2();
		})();
		</script>
    @endsection
@endsection