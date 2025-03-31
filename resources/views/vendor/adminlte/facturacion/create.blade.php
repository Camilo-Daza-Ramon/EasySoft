@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-file-text-o"></i>  Generar facturación masiva</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
        	<div class="col-md-12">
        		<div class="box box-primary" id="panel-generar">
		            <div class="box-header with-border bg-blue">
		              Parametros
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
		            	<form action="{{route('facturacion.store')}}" method="POST">
				            {{csrf_field()}}

		            		<div class="row">
				            	<div class="form-group{{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-4">
				            		<label>*Proyecto</label>
					            	<select class="form-control" name="proyecto" id="proyecto" required>
			                            <option value="">Elija un proyecto</option>
			                            @foreach($proyectos as $proyecto)
			                                <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
			                            @endforeach
			                        </select>
			                    </div>

			                    <div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
			                    	<label>Departamento</label>
			                        <select class="form-control" name="departamento" id="departamento">
			                            <option value="">Elija un departamento</option>
			                        </select>
			                    </div>

			                    <div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
			                    	<label>Municipio</label>
			                        <select class="form-control" name="municipio" id="municipio">
			                            <option value="">Elija un municipio</option>
			                        </select>
			                    </div>	                    

			                    <div class="form-group{{ $errors->has('periodo') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
				            		<label>*Perido a Facturar</label>
				            		<input type="month" name="periodo" class="form-control" required>
				            	</div>

			                    <div class="form-group{{ $errors->has('clasificacion') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
									<label>Clasificación</label>
									<select class="form-control" name="clasificacion" id="clasificacion">
										<option value="">Elija una Opción</option>
										<option value="CASMOT">CASMOT</option>
										<option value="DIALNET">DIALNET</option>
									</select>
								</div>

								<div class="form-group{{ $errors->has('fecha_limite_pago') ? ' has-error' : '' }} col-md-4 col-md-4 col-sm-6 col-xs-6">
				            		<label>*Fecha límite de pago:</label>
				            		<input type="date" name="fecha_limite_pago" class="form-control" value="{{date('Y-m').'-25'}}" min="{{date('Y-m-d')}}" required>
				            	</div>
				            </div>

				            <div class="row">
				            	<div class="form-group{{ $errors->has('cedulas_facturar') ? ' has-error' : '' }} col-md-12">
				            		<label>Cedulas a facturar</label>
				            		<textarea class="form-control" name="cedulas_facturar" placeholder="Cedulas ceparadas por coma"></textarea>
				            	</div>

				            	<div class="form-group{{ $errors->has('cedulas_no_facturar') ? ' has-error' : '' }} col-md-12">
				            		<label>Cedulas para no facturar</label>
				            		<textarea class="form-control" name="cedulas_no_facturar" placeholder="Cedulas ceparadas por coma"></textarea>
				            	</div>

				            	<div class="form-group col-md-12">
				            		<h3 class="text-center" >Cedulas sin servicio</h3>
				            		<textarea class="form-control"  rows="10" name="nada" placeholder="No hay cedulas sin servicio" readonly>@foreach($cedulas_sin_servicio_B as $cedula_sin_servicio){{$cedula_sin_servicio->Identificacion."\n"}}@endforeach</textarea>
				            	</div>
	            			</div>
            				@if(count($cedulas_sin_servicio_B)==0)
            					@if(date('d') > 6 )
									<button type="button" id="generar" class="btn btn-primary btn-block margin-bottom"> <i id="load" class="fa fa-spinner"></i> Generar</button>
            						
            					@else
            						<div class="alert alert-warning alert-dismissible">
						                <h4><i class="icon fa fa-warning"></i> Alerta!</h4>
						                No es posible generar la facturación antes del 6 de cada mes porque el proseso de suspensión masiva se ejecuta el día 6 para todos los morosos. 
						            </div>																
								@endif
            				@endif
		            	</form>
		            </div>
		        </div>		        
        	</div>        	
        </div>


    </div>
    @section('mis_scripts')
	    <script type="text/javascript">
	    	$('#proyecto').on('change', function(){            
	            buscar_departamentos($('#departamento').val());            
	        });


	        $('#departamento').on('change', function(){            
	            buscar_municipio($('#municipio').val());            
	        });

	        function buscar_municipio(municipio){

	            var parameters = {
	                departamento_id : $('#departamento').val(),
	                proyecto_id : $('#proyecto').val(),
	                '_token' : $('input:hidden[name=_token]').val()
	            };


	            $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){

	                $('#municipio').empty();
	                $('#municipio').append('<option value="">Elija un municipio</option>');
	                $.each(data, function(index, municipiosObj){

	                    if (municipio != null) {
	                        if (municipiosObj.MunicipioId == municipio) {

	                            $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '" selected>' + municipiosObj.NombreMunicipio + '</option>');
	                        }else{
	                            $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
	                        }
	                    }else{
	                        $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
	                    }                                     
	                });
	            }).fail(function(e){
	                alert('error');
	            });
	        }

	        function buscar_departamentos(departamento){
	            var parameters = {
	                proyecto_id : $('#proyecto').val(),
	                '_token' : $('input:hidden[name=_token]').val()
	            };

	            $.post('/estudios-demanda/ajax-departamentos', parameters).done(function(data){

	                $('#departamento').empty();                
	                $('#departamento').append('<option value="">Elija un departamento</option>');
	                $.each(data, function(index, departamentosObj){

	                    if (departamento != null) {
	                        if (departamentosObj.DeptId == departamento) {

	                            $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '" selected>' + departamentosObj.NombreDelDepartamento + '</option>');
	                        }else{
	                            $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
	                        }
	                    }else{
	                        $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
	                    }                    
	                });
	            }).fail(function(e){
	                alert('error');
	            });
	        }
	    </script>
		<script type="text/javascript">

	        $("#generar").on('click', function(){

	        	

	        	$('#panel-generar').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
	        	$('#load').addClass('fa-refresh fa-spin');
	        	$('#generar').attr('disabled', true);		         

	            var parametros = {
	            	'proyecto' : $('#proyecto').val(),
	            	'departamento' : $('#departamento').val(),
	            	'municipio' : $('#municipio').val(),
	            	'periodo' : $('input[name=periodo]').val(),
	            	'clasificacion' : $('select[name=clasificacion]').val(),
	            	'cedulas_facturar' : $('textarea[name=cedulas_facturar]').val(),
	            	'cedulas_no_facturar' : $('textarea[name=cedulas_no_facturar]').val(),
					'fecha_limite_pago' : $('input[name=fecha_limite_pago]').val(),
	                '_token' : $('input:hidden[name=_token]').val()
	                };


	            $.post( "{{route('facturacion.store')}}",parametros, function(data) {

	            	toastr.options.positionClass = 'toast-bottom-right';

	            	switch (data['codigo']){

	            		case 'success':		            		
		                	toastr.success(data['mensaje']);
		                	temporizador(3, '/facturacion/' + data['periodo']);
		                	break;

	                	case 'warning':
		                	toastr.warning(data['mensaje']);
		                	$('.overlay').remove();
		                	$('#load').removeClass('fa-refresh fa-spin');
		                	$('#generar').attr('disabled', false);
		                	break;

	                	case 'error':
		                	toastr.error(data['mensaje']);
		                	$('.overlay').remove();
		                	$('#load').removeClass('fa-refresh fa-spin');
		                	$('#generar').attr('disabled', false);
		                	break;
		            }
	            })
	            .fail(function(data) {

	                //var datos = $.parseJSON(data);
	                toastr.options.positionClass = 'toast-bottom-right';

	                toastr.error("Error al crear la factura.");

	                //console.log(data.responseJSON);
	                
	                $.each(data.responseJSON, function(index, errObj){

	                    var array = index.split('.');

	                    if (array.lenght == 0) {
	                        toastr.error(index + ' ' + errObj);
	                        $('#'+index).addClass('is-invalid');
	                    }else{
	                        toastr.error(index + ' ' + errObj);
	                        $('#'+array[1]).addClass('is-invalid');
	                    }                   
	                    
	                });

	                $('.overlay').remove();
	                $('#load').removeClass('fa-refresh fa-spin');
	                $('#generar').attr('disabled', false);
	            });
		                
	        });

		    function temporizador(tiempo, url){

		        var segundero = setInterval(function(){

		        if(tiempo > 0){
		            $(".temporizador").text(tiempo + ' Segundos');
		            tiempo = tiempo - 1;

		        }else{
		            $(".temporizador").text(tiempo + ' Segundos');
		            clearTimeout(segundero);

		            window.location.href = url;
		        }
		    	}, 1000);
			}
		</script>
	@endsection
@endsection