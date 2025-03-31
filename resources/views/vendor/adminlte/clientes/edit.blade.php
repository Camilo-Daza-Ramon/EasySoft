@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-edit"></i> Editar Cliente - {{$cliente->NombreBeneficiario}}</h1>
    
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">				
				<div class="box-header with-border bg-blue">
					<h3 class="box-title">Editar Cliente</h3>
				</div>
				<div class="box-body">
					<form id="form-cliente" action="{{route('clientes.update', $cliente->ClienteId)}}" method="post">
						<input type="hidden" name="_method" value="PUT">
						{{csrf_field()}}
						<!--- Datos proyecto --->
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4><i class="fa fa-tag"></i>  Proyecto</h4>
							</div>
							<div class="panel-body">						

								<div class="row">									
									<div class="form-group{{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-3 text-center">
										<label>Proyecto</label>
										<select class="form-control" name="proyecto" id="proyecto" required>
											<option value="">Elija una Opción</option>
											@foreach($proyectos as $dato)
												@if($dato->ProyectoID == $cliente->ProyectoId)
													<option value="{{$dato->ProyectoID}}" selected>{{$dato->NumeroDeProyecto}}</option>
												@else
													<option value="{{$dato->ProyectoID}}">{{$dato->NumeroDeProyecto}}</option>
												@endif
											
											@endforeach
										</select>
									</div>
									<div class="form-group{{ $errors->has('tipo_beneficiario') ? ' has-error' : '' }} col-md-3 text-center" id="panel-tipo-beneficiario">
										<label>Tipo de Beneficiario</label>
										<select class="form-control" name="tipo_beneficiario" id="tipo_beneficiario">
											<option value="">Elija una Opción</option>
											@foreach($tipo_beneficiario as $tipo)
												<option value="{{$tipo->nombre}}" {{($cliente->tipo_beneficiario == $tipo->nombre)? 'selected' : ''}}>{{$tipo->nombre}}</option>
											@endforeach											
										</select>
									</div>

									<div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-md-3 col-sm-6">
										<label>*Departamento: </label>
										<select class="form-control" name="departamento" id="departamento" required>
											<option value="">Elija una opción</option>
											@foreach($departamentos as $departamento)
						                		@if(isset($cliente->municipio))
							                		@if($departamento->DeptId == $cliente->municipio->DeptId)
							                			<option value="{{$departamento->DeptId}}" selected>{{$departamento->NombreDelDepartamento}}</option>
							                		@else
							                			<option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
							                		@endif
							                	@else
							                		<option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
							                	@endif
						                	@endforeach
										</select>           			
									</div>

									<div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-md-3 col-sm-6">
										<label>*Municipio: </label> 
										<select class="form-control" name="municipio" id="municipio" required>
										</select>
									</div>

									<div class="form-group{{ $errors->has('estrato') ? ' has-error' : '' }} col-md-3 col-sm-6">
					            		<label>*Estrato:</label>
					            		<select name="estrato" class="form-control" id="estrato" required>
	                                        <option value="">Elija una opción</option>
	                                        @foreach($estratos as $estrato)
												<option value="{{$estrato}}" {{($cliente->Estrato == $estrato)? 'selected' : ''}}>{{$estrato}}</option>
											@endforeach	                                        
	                                    </select>
	                                </div>

									<div class="form-group{{ $errors->has('clasificacion') ? ' has-error' : '' }} col-md-3 text-center">
										<label>Clasificación</label>
										<select class="form-control" name="clasificacion" id="clasificacion">
											<option value="">Elija una Opción</option>
											@foreach($clasificacion as $tipo)
												<option value="{{$tipo}}" {{($cliente->Clasificacion == $tipo)? 'selected' : ''}}>{{$tipo}}</option>
											@endforeach											
										</select>
									</div>									
								</div>
							</div>
						</div>

						<!--- Datos personales --->
						<div class="panel panel-info" id="datos-personales">
							<div class="panel-heading">
								<h4><i class="fa fa-user"></i>  Datos Personales</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="form-group{{ $errors->has('tipo_documento') ? ' has-error' : '' }} col-md-3">
										<label>Tipo de documento: </label>
										<input type="text" class="form-control" value="{{$cliente->TipoDeDocumento}}" disabled>										
									</div>
									<div id="form-group-documento" class="form-group{{ $errors->has('documento') ? ' has-error' : '' }} col-md-3">
										<label>Documento:</label>
										<input type="number" class="form-control" placeholder="Documento" value="{{$cliente->Identificacion}}" disabled>
									</div>
									<div class="form-group{{ $errors->has('lugar_expedicion') ? ' has-error' : '' }} col-md-3">
										<label>*Ciudad de Expedición:</label>
										<input type="text" name="lugar_expedicion" class="form-control" placeholder="Ciudad de Espedición" value="{{$cliente->ExpedidaEn}}" autocomplete="off" required>
									</div>

									<div class="form-group{{ $errors->has('fecha_nacimiento') ? ' has-error' : '' }} col-md-3">
										<label>*Fecha de Nacimiento: </label>
	                                    <input type="date" class="form-control" name="fecha_nacimiento" placeholder="Fecha de nacimiento" value="{{$cliente->fecha_nacimiento}}" required>
	                                </div>
																
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label>Nombres:</label>
										<input type="text" name="nombres" class="form-control" placeholder="Nombres" value="{{$cliente->NombreBeneficiario}}">
									</div> 

									<div class="form-group col-md-6">
										<label>Apellidos:</label>
										<input type="text" name="apellidos" class="form-control" placeholder="Apellidos" value="{{$cliente->Apellidos}}">
									</div>

									<div class="form-group{{ $errors->has('lugar_nacimiento') ? ' has-error' : '' }} col-md-3">
										<label>*Lugar de Nacimiento: </label>
	                                    <input type="text" class="form-control" name="lugar_nacimiento" placeholder="Lugar de nacimiento" value="{{$cliente->lugar_nacimiento}}" autocomplete="off" required>
	                                </div>

									<div class="form-group{{ $errors->has('genero') ? ' has-error' : '' }} col-md-3">
	                                 	<label>*Género: </label>
	                                    <select name="genero" id="genero" class="form-control" required>
	                                    	<option value="">Elija una Opcion</option>
	                                    	@foreach($genero as $dato)
	                                    		@if($cliente->genero == $dato['sigla'])
	                                    			<option value="{{$dato['sigla']}}" selected>{{$dato['valor']}}</option>
	                                    		@else
	                                    			<option value="{{$dato['sigla']}}">{{$dato['valor']}}</option>
	                                    		@endif
	                                    	@endforeach                                        
	                                    </select>
	                                </div>

									<div class="form-group{{ $errors->has('sexo') ? ' has-error' : '' }} col-md-3">
										<label>*Sexo:  </label>
										<select class="form-control" name="sexo" id="sexo" required>
											<option value="">Elija un opcion</option>
											@foreach($sexo as $dato)
												@if($dato == $cliente->sexo)
													<option value="{{$dato}}" selected>{{$dato}}</option>
												@else
													<option value="{{$dato}}">{{$dato}}</option>
												@endif
											@endforeach	
										</select>
									</div>

									<div class="form-group{{ $errors->has('orientacion_sexual') ? ' has-error' : '' }} col-md-3">
					            		<label>*Orientación Sexual:</label>
										<select name="orientacion_sexual" id="orientacion_sexual" class="form-control" required>
											<option value="">Elija un opcion</option>
											@foreach($orientacion_sexual as $dato)
												@if($dato == $cliente->orientacion_sexual)
													<option value="{{$dato}}" selected>{{$dato}}</option>
												@else
													<option value="{{$dato}}">{{$dato}}</option>
												@endif
											@endforeach	
										</select>
	                                </div>
								</div>
								<div class="row">

	                                <div id="form-group-email" class="form-group{{ $errors->has('CorreoElectronico') ? ' has-error' : '' }} col-md-6">
										<label>*Correo:</label>
										<input type="email" name="CorreoElectronico" class="form-control" placeholder="Correo" value="{{$cliente->CorreoElectronico}}" autocomplete="off" required>
									</div>
									
									<div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }} col-md-3">
					            		<label>Teléfono:</label>
	                                     <input type="number" class="form-control" name="telefono" placeholder="Telefono" value="{{$cliente->TelefonoDeContactoFijo}}" autocomplete="off"> 
					            	</div>

					            	<div class="form-group{{ $errors->has('celular') ? ' has-error' : '' }} col-md-3">
										<label>*Celular:</label>
										<input type="number" name="celular" class="form-control" placeholder="Celular" value="{{str_replace(' ', '', $cliente->TelefonoDeContactoMovil)}}" autocomplete="off">
									</div>

									<div class="form-group{{ $errors->has('etnia') ? ' has-error' : '' }} col-md-3">
										<label>*Étnia: </label>
										<select name="etnia" id="etnia" class="form-control" required>
											<option value="">Elija una opción</option>
											@foreach($etnia as $dato)
												@if($dato == $cliente->pertenencia_etnica)
													<option value="{{$dato}}" selected>{{$dato}}</option>
												@else
													<option value="{{$dato}}">{{$dato}}</option>
												@endif
											@endforeach	
										</select>
									</div>									

	                                <div class="form-group{{ $errors->has('nivel_estudios') ? ' has-error' : '' }} col-md-3">
										<label>*Nivel de Estudios</label>
										<select class="form-control" name="nivel_estudios" id="nivel_estudios" required>
											<option value="">Nivel de estudios</option>

											@foreach($nivel_estudios as $dato)
												@if($dato == $cliente->nivel_estudios)
													<option value="{{$dato}}" selected>{{$dato}}</option>
												@else
													<option value="{{$dato}}">{{$dato}}</option>
												@endif
											@endforeach
										</select>
									</div>

									<div class="form-group{{ $errors->has('discapacidad') ? ' has-error' : '' }} col-md-3">
					            		<label>*Discapacidad:</label>
										<select name="discapacidad" id="discapacidad" class="form-control" required>
											<option value="">Elija una opcion</option>
											@foreach($discapacidad as $dato)
												@if($dato == $cliente->discapacidad)
													<option value="{{$dato}}" selected>{{$dato}}</option>
												@else
													<option value="{{$dato}}">{{$dato}}</option>
												@endif
											@endforeach
										</select>
	                                </div>

									@role(['admin', 'aux-desarrollo'])
									<div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-3">
										<label>Estado:</label>										
										<select name="estado" class="form-control">
											<option value="">Elija una opción</option>
											@foreach($estados as $estado)
												<option value="{{$estado}}" {!!($estado == $cliente->Status) ? 'selected':''!!}>{{$estado}}</option>
											@endforeach
										</select>										
									</div>
									@endrole
								</div>							
							</div>
						</div>

						<!--- Direccion --->
						<div class="panel panel-info" id="panel-direccion">
							<div class="panel-heading">
								<h4> <i class="fa fa-map-marker"></i>  Lugar de Recidencia</h4>
							</div>
							<div class="panel-body">

								<div class="row">
									<div class="col-md-6">
										<label>Dirección: </label>
										<div class="row" >										
											<!-- Grupo 1-->
											<div class="form-group col-md-5 col-sm-12 col-xs-12" >
												<select id="d1" class="form-control" >
													<option value=""></option>
													<option value="AUTOP">Autopista</option>
													<option value="AV">Avenida</option>
													<option value="CL">Calle</option>
													<option value="KR">Carrera</option>
													<option value="CT">Carretera</option>
													<option value="DG">Diagonal</option>
													<option value="KM">Kilómetro</option>
													<option value="TV">Transversal</option>
												</select>
											</div>

											<!-- Grupo 2-->
											<div class="form-group col-md-7 col-sm-12 col-xs-12"  >
												<div class="input-group">
													<input type="text" id="d2" class="form-control" autocomplete="off">
													<span class="input-group-addon" style="border: 0px;">#</span>
												<input type="text" id="d3" class="form-control" autocomplete="off">
													<span class="input-group-addon" style="border: 0px;">-</span>
													<input type="text" id="d4" class="form-control"  autocomplete="off">
												</div>
											</div>

											<!-- Grupo 3-->
											<div class="form-group col-md-5 col-sm-7 col-xs-7" >
												<select id="d5" class="form-control">
													<option value=""></option>
													<option value="BL">Bloque</option>
													<option value="CLJ">Callejón</option>
													<option value="CN">Camino</option>
													<option value="CAS">Caserio</option>
													<option value="ED">Edificio</option>
													<option value="ET">Etapa</option>
													<option value="FCA">Finca</option>
													<option value="HC">Hacienda</option>
													<option value="LT">Lote</option>
													<option value="PRJ">Paraje</option>
													<option value="PA">Parcela</option>
													<option value="PD">Predio</option>
													<option value="SEC">Sector</option>
													<option value="TZ">Terraza</option>
													<option value="TO">Torre</option>
													<option value="VRD">Vereda</option>
												</select>
											</div>
											<div class="form-group col-md-7 col-sm-5 col-xs-5" >
												<input type="text" id="d6" class="form-control" autocomplete="off">
											</div>

											<!-- Grupo 4-->
											<div class="form-group col-md-5 col-sm-9 col-xs-9" >
												<select id="d7" class="form-control">
													<option value=""></option>
													<option value="APTO">Apartamento</option>
													<option value="CA">Casa</option>
													<option value="CS">Consultorio</option>
													<option value="LC">Local</option>
													<option value="OF">Oficina</option>
													<option value="PI">Piso</option>
												</select>
											</div>

											<div class="form-group col-md-3 col-sm-3 col-xs-3" >
												<input type="text" id="d8" class="form-control" autocomplete="off">
											</div>

											<!-- Grupo 5-->
											<div class="form-group col-md-5 col-sm-9 col-xs-9" >
												<select id="d9" class="form-control">
													<option value=""></option>
													<option value="MZ">Manzana</option>
													<option value="SMZ">Super Manzana</option>
													<option value="CONJ">Conjunto</option>
													<option value="IN">Interior</option>
												</select>
											</div>

											<div class="form-group col-md-3 col-sm-3 col-xs-3" >
												<input type="text" id="d10" class="form-control" autocomplete="off">
											</div>

										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="form-group col-md-12 col-sm-6">
												<label>*Direccion Real:</label>
												<input type="text" class="form-control" name="direccion" placeholder="Direccion" autocomplete="off" value="{{$cliente->DireccionDeCorrespondencia}}">
											</div>

											<div class="form-group col-md-12 col-sm-6">
												<label>*Direccion Recibo:</label>
												<input type="text" class="form-control" name="direccion_recibo" placeholder="Direccion Recibo" value="{{$cliente->direccion_recibo}}"  autocomplete="off">
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>Barrio:</label>
												<input type="barrio" class="form-control" name="barrio" placeholder="Barrio" value="{{$cliente->Barrio}}" autocomplete="off">
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>Urbanización:</label>
												<input type="urbanizacion" class="form-control" name="urbanizacion" placeholder="Urbanización" value="{{$cliente->NombreEdificio_o_Conjunto}}" autocomplete="off">
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>*Zona:</label>
												<select name="zona" id="zona" class="form-control" required>
													<option value="">Elija una opción</option>
													@foreach($zonas as $zona)
														<option value="{{$zona}}" {{($cliente->zona == $zona)? 'selected' : ''}}>{{$zona}}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>Localidad:</label>
												<select name="localidad" id="localidad" class="form-control" required>
													<option value="">Elija una opción</option>
													@foreach($localidades as $localidad)
														<option value="{{$localidad}}" {{($cliente->localidad == $localidad)? 'selected' : ''}}>{{$localidad}}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group col-xs-12 col-md-6 col-sm-6">
												<label>*Coordenadas</label>
												<input type="text" name="coordenadas" placeholder="Latitud,Longitud" class="form-control" value="{{$cliente->Latitud}}, {{$cliente->Longitud}}" required autocomplete="off">
											</div>
											

											<div class="form-group col-md-6 col-sm-6">
												<label>*Tipo de Vivienda:</label>
												<select name="tipo_vivienda" id="tipo_vivienda" class="form-control" required>
													<option value="">Elija una opción</option>												
													@foreach($tipo_vivienda as $tipo)
														<option value="{{$tipo}}" {{($cliente->RelacionConElPredio == $tipo)? 'selected' : ''}}>{{$tipo}}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
								</div>
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
	<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios3.js')}}"></script>
	<script type="text/javascript">

		$(document).ready(function() {
		    /*validarEmail();*/
		    buscar_departamentos({{$cliente->municipio->DeptId}});
            buscar_municipio({{$cliente->municipio_id}});
		});
	</script>

	<script type="text/javascript">

		/*$('input[name=CorreoElectronico]').blur(function(){
			validarEmail();
		});*/

		function validarEmail(){
			if ($('input[name=CorreoElectronico]').val().length > 0) {
				var parametros = {
					'cedula' : $('input[name=CorreoElectronico]').val(),
					'validar' : 'CorreoElectronico',
					'_token' : $('input:hidden[name=_token]').val()
				}

				$.post('/clientes/ajaxvalidar', parametros).done(function(data){
					if (data > 1) {
						toastr.options.positionClass = 'toast-bottom-right';
  						toastr.error("El Correo ya existe");
  						$('#form-group-email').removeClass('has-success').addClass('has-error');
  						$('input[name=CorreoElectronico]').focus().select();
  						
					}else{
						toastr.options.positionClass = 'toast-bottom-right';
  						toastr.success("Correo Validado.");
  						$('#form-group-email').removeClass('has-error').addClass('has-success');
  						
					}
				});
			}
		}


	</script>

	<script type="text/javascript">
	    $('#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10').focusout(function() {
	      	var numero = '';
	      	if ($('#d3').val().length > 0) {
	      		numero = ' # ' + $('#d3').val() + ' - ' + $('#d4').val();
	      	}

	      	$('input[name=direccion]').val($('#d1').val() + ' ' + $('#d2').val() + numero + ' ' + $('#d5').val() + ' ' + $('#d6').val()  + ' ' + $('#d7').val()  + ' ' + $('#d8').val()  + ' ' + $('#d9').val()  + ' ' + $('#d10').val());
	    });
	</script>

	@endsection
@endsection