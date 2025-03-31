@extends('adminlte::layouts.app')


@section('contentheader_title')
    <h1><i class="fa fa-user-plus"></i>  Crear Usuario</h1>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
            	<!-- Default box -->
                <div class="box box-info">
                    <div class="box-header bg-blue">
                    </div>
                    <div class="box-body">
                    	<div class="row">
                    		<div class="col-md-6">
                    			<form action="{{ route('entrust-gui::users.store') }}" method="post" role="form">
								    @include('entrust-gui::users.partials.form')
								    <button type="submit" id="create" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span>  crear</button>
								    <a class="btn btn-labeled btn-danger" href="{{ route('entrust-gui::users.index') }}"><span class="btn-label"><i class="fa fa-chevron-left"></i></span>  cancelar</a>
								</form>
                    		</div>
                    	</div>
						
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