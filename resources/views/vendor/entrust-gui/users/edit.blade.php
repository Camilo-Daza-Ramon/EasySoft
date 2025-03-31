@extends('adminlte::layouts.app')

@section('contentheader_title')
  <h1> <i class="fa fa-users">  </i> Editar Usuarios</h1>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
	    <div class="row">
	      <div class="col-md-12">
	        <!-- Default box -->
	        <div class="box box-info">
	        	<div class="box-header with-border bg-blue">
	        		<h4> <i class="fa fa-users">  </i> Editar Usuario</h4>
	        	</div>

	        	<div class="box-body">
	        		<form action="{{route('perfil.update', $user->id)}}" method="post" role="form" enctype="multipart/form-data">
						<div class="row">
							<input type="hidden" name="_method" value="put">
							@include('entrust-gui::users.partials.form')
						</div>
	        			<button type="submit" id="save" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-check"></i></span>Actualizar</button>
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

