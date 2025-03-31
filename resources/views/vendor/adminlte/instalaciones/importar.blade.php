@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-upload"></i>  Instalaciones - Importar</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">    	
        <div class="row">        	
            <div class="col-md-9">
            	<div class="box box-primary" id="cuerpo_importar">            		
		            <!-- form start -->		            
		            <div class="box-body">
		            	<h4>Recomendaciones</h4>
	            		<ul>
	            			<li>No se debe alterar el documento que genera la APP</li>
	            			<li>Debe comprimir la carpeta de la fecha que se quiere reportar. por ejemplo 2020-06-11.zip</li>
	            			<li>La plataforma no permitirá subir la información de instalaciones que ya existan en el sistema.</li>
	            			<li>Tenga en cuenta que todas las instalaciones pasan por una AUDITORIA y en éste proceso se aprueban o rechazan las instalaciones que reporte.</li>
	            			<li>Las instalaciones que sean rechazadas apareceran en la seccion de <a href="{{ url('instalaciones') }}">NOVEDADES</a>, dando la oportunidad al tecnico de corregir la información que esté pendiente según el motivo de rechazo.</li>
	            		</ul>
	            		<div class="row">
	            			<div class="col-md-6">
	            				<form role="form" action="{{route('instalaciones.importar')}}" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
					        		{{csrf_field()}}
					                <div class="form-group col-md-12">
					                	<label>*Listado de instalaciones</label>			                	
					                	<input type="file" name="archivo" id="archivo" class="form-control" required>	
						            </div>
						            <div class="form-group col-md-12">
					                	<label>*Carpetas comprimidas</label>			                	
					                	<input type="file" name="archivo_carpetas" class="form-control" required>	
						            </div>
						            <div class="form-group col-md-12">
						            	<button type="submit" class="btn btn-primary pull-right" id="btn_importar"><i class="fa fa-upload"></i> Subir</button>
						            </div>
					            </form>
	            			</div>
	            			<div class="col-md-6">
	            				<video width="100%" controls>
								  <source src="/video/Untitled.mp4" type="video/mp4">
								  
								  Your browser does not support HTML video.
								</video>
	            			</div>            			
	            		</div>
		            </div>		            
		        </div>
		    </div>
		    <div class="col-md-3">
            	<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title"><i class="fa fa-plus"></i> Resultado</h3>
		            </div>
		            <div class="box-body">
		            	@if(Session::has('instalaciones_existentes'))
			            	<label>Instalaciones ya Existen:</label><br>
			            	@foreach(Session::get('instalaciones_existentes') as $cedula)
	        				<p>{{$cedula}}</p>
	        				@endforeach		            	
		            	@endif

		            	@if(Session::has('instalaciones_sin_carpeta'))
			            	<label>No existen carpetas</label><br>
			            	@foreach(Session::get('instalaciones_sin_carpeta') as $cedula)
	        				<p>{{$cedula}}</p>
	        				@endforeach		            	
		            	@endif

		            	@if(Session::has('sin_auditar'))
			            	<label>Clientes sin Auditar</label><br>
			            	@foreach(Session::get('sin_auditar') as $cedula)
	        				<p>{{$cedula}}</p>
	        				@endforeach		            	
		            	@endif
		            </div>
		        </div>
		    </div>
        </div>
    </div>
@section('mis_scripts')
	<script type="text/javascript">
		$('#btn_importar').on('click',function(){
			$('#cuerpo_importar').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
		});
	</script>

@endsection
@endsection