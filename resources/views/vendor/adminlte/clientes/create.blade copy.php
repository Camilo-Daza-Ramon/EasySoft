@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> Crear Cliente </h1>
    
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<form id="form-cliente" action="{{route('clientes.store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title">Crear Cliente</h3>
					</div>
					<div class="box-body">		          	
						{{csrf_field()}}

						<!--- Datos proyecto --->
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4><i class="fa fa-tag"></i>  Proyecto</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-4"></div>
									<div class="form-group{{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-4 text-center">
										<label>Proyecto</label>
										<select class="form-control" name="proyecto" id="proyecto" required>
											<option value="">Elija una Opción</option>
											@foreach($proyectos as $dato)
											<option value="{{$dato->ProyectoID}}" data-vigencia="{{$dato->vigencia}}" data-tipo_facturacion="{{$dato->tipo_facturacion}}">{{$dato->NumeroDeProyecto}}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group{{ $errors->has('tipo_beneficiario') ? ' has-error' : '' }} col-md-4 text-center" id="panel-tipo-beneficiario" style="display: none;">
										<label>Tipo de Beneficiario</label>
										<select class="form-control" name="tipo_beneficiario" id="tipo_beneficiario">
											<option>Elija una Opción</option>
											<option value="Estrato 1">Estrato 1</option>
											<option value="Estrato 2">Estrato 2</option>
											<option value="SISBEN IV">SISBEN IV</option>
											<option value="Ley 1699 de 2013">Ley 1699 de 2013</option>
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
										<label>*Tipo de documento: </label>
										<select name="tipo_documento" id="tipo_documento" class="form-control" required>
											<option value="">Elija un valor</option>
											<option value="C.C">Cédula de Ciudadanía</option>
											<option value="C.E">Cédula de Extranjería</option>
											<option value="P.P">Pasaporte</option>
											<option value="R.C">Registro Civil</option>
											<option value="T.I">Tarjeta de Identidad</option>
											<option value="NIT">Número de Identificación Tributaria</option>
										</select>
									</div>
									<div id="form-group-documento" class="form-group{{ $errors->has('documento') ? ' has-error' : '' }} col-md-3">
										<label>*Documento:</label>
										<input type="number" name="documento" class="form-control" placeholder="Documento" value="{{old('documento')}}" min="0" max="9999999999" autocomplete="off" required>
									</div>
									<div class="form-group{{ $errors->has('lugar_expedicion') ? ' has-error' : '' }} col-md-3">
										<label>*Ciudad de Expedición:</label>
										<input type="text" name="lugar_expedicion" class="form-control" placeholder="Ciudad de Espedición" value="{{old('lugar_expedicion')}}" autocomplete="off" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }} col-md-5">
										<label>*Nombres:</label>
										<input type="text" name="nombres" class="form-control" placeholder="Nombres" value="{{old('nombres')}}" autocomplete="off" required>
									</div> 

									<div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }} col-md-5">
										<label>*Apellidos:</label>
										<input type="text" name="apellidos" class="form-control" placeholder="Apellidos" value="{{old('apellidos')}}" autocomplete="off" required>
									</div>

									<div class="form-group{{ $errors->has('genero') ? ' has-error' : '' }} col-md-2">
	                                 	<label>*Género: </label>
	                                    <select name="genero" id="genero" class="form-control" required>
	                                    	<option value="">Elija una Opcion</option>
	                                        <option value="M">Masculino</option>
	                                        <option value="F">Femenino</option>
	                                        <option value="T">Transgénero</option>
	                                    </select>
	                                </div>
								</div>
								<div class="row">
									<div class="form-group{{ $errors->has('fecha_nacimiento') ? ' has-error' : '' }} col-md-3">
										<label>*Fecha de Nacimiento: </label>
	                                    <input type="date" class="form-control" name="fecha_nacimiento" placeholder="Fecha de nacimiento" value="{{old('fecha_nacimiento')}}" required>
	                                </div>

	                                <div class="form-group{{ $errors->has('lugar_nacimiento') ? ' has-error' : '' }} col-md-3">
										<label>*Lugar de Nacimiento: </label>
	                                    <input type="text" class="form-control" name="lugar_nacimiento" placeholder="Lugar de nacimiento" value="{{old('lugar_nacimiento')}}" autocomplete="off" required>
	                                </div>

	                                <div id="form-group-email" class="form-group{{ $errors->has('CorreoElectronico') ? ' has-error' : '' }} col-md-6">
										<label>*Correo:</label>
										<input type="email" name="CorreoElectronico" class="form-control" placeholder="Correo" value="{{old('CorreoElectronico')}}" autocomplete="off" required>
									</div>                         
									
								</div>
								<div class="row">
									<div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }} col-md-3">
					            		<label>Teléfono:</label>
	                                     <input type="number" class="form-control" name="telefono" placeholder="Telefono" value="{{old('telefono')}}" autocomplete="off"> 
					            	</div>

					            	<div class="form-group{{ $errors->has('celular') ? ' has-error' : '' }} col-md-3">
										<label>*Celular:</label>
										<input type="number" name="celular" class="form-control" placeholder="Celular" value="{{old('celular')}}" autocomplete="off">
									</div>

									<div class="form-group{{ $errors->has('etnia') ? ' has-error' : '' }} col-md-6">
										<label>*Étnia: </label>
										<select name="etnia" id="etnia" class="form-control" required>
											<option value="">Elija una opción</option>
											<option value="Mulato">Mulato</option>
											<option value="Indigena">Indigena</option>
											<option value="Negra">Negra</option>
											<option value="Afro">Afro</option>
											<option value="Palenquera">Palenquera</option>
											<option value="Raizal">Raizal</option>
											<option value="Gitanos - Rom">Gitanos - Rom</option>
											<option value="Mestiza">Mestiza</option>
											<option value="Sin Informacion">Sin Información</option>
										</select>
									</div>																
								</div>
								<div class="row">
									<div class="form-group{{ $errors->has('sexo') ? ' has-error' : '' }} col-md-2">
										<label>*Sexo:  </label>
										<select class="form-control" name="sexo" id="sexo" required>
											<option value="">Elija un opcion</option>
											<option value="Hembra">Hembra</option>
											<option value="Macho">Macho</option>
											<option value="Intersexual">Intersexual</option>
											<option value="Sin informacion">Sin información</option>
										</select>
									</div>

									<div class="form-group{{ $errors->has('orientacion_sexual') ? ' has-error' : '' }} col-md-3">
					            		<label>*Orientación Sexual:</label>
										<select name="orientacion_sexual" id="orientacion_sexual" class="form-control" required>
											<option value="">Elija un opcion</option>
											<option value="Heterosexual">Heterosexual</option>
											<option value="Homosexual">Homosexual</option>
											<option value="Bisexual">Bisexual</option>
											<option value="Sin informacion">Sin información</option>
										</select>
	                                </div>

	                                <div class="form-group{{ $errors->has('nivel_estudios') ? ' has-error' : '' }} col-md-4">
										<label>Nivel de Estudios</label>
										<select class="form-control" name="nivel_estudios" id="nivel_estudios" required>
											<option value="">Nivel de estudios</option>
											<option value="Preescolar">Preescolar</option>
											<option value="Basica">Básica</option>
											<option value="Media">Media</option>
											<option value="Superior pregrado">Superior pregrado</option>
											<option value="Superior posgrado">Superior posgrado</option>
											<option value="Sin informacion">Sin información</option>
										</select>
									</div>

									<div class="form-group{{ $errors->has('discapacidad') ? ' has-error' : '' }} col-md-3">
					            		<label>*Discapacidad:</label>
										<select name="discapacidad" id="discapacidad" class="form-control" required>
											<option value="">Elija una opcion</option>
											<option value="Visual">Visual</option>
											<option value="Auditiva">Auditiva</option>
											<option value="Fisica">Fisica</option>
											<option value="Cognitiva-Intelectual">Cognitiva-Intelectual</option>
											<option value="Psicosocial">Psicosocial</option>
											<option value="Multiple">Multiple</option>
											<option value="Sin discapacidad">Sin discapacidad</option>
										</select>
	                                </div>
								</div>							
							</div>
						</div>

						<!--- Direccion --->
						<div class="panel panel-info" id="panel-direccion">
							<div class="panel-heading">
								<h4> <i class="fa fa-map-marker"></i>  Lugar de Recidencia</h4>
							</div>
							<div class="panel-body">
								<label>Dirección: </label>
								<div class="row" >
									<!-- Grupo 1-->
			            			<div class="form-group col-md-2 col-sm-12 col-xs-12" >
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
				            		<div class="form-group col-md-3 col-sm-12 col-xs-12"  >
					            		<div class="input-group">
					            			<input type="text" id="d2" class="form-control" autocomplete="off">
							                <span class="input-group-addon" style="border: 0px;">#</span>
							               <input type="text" id="d3" class="form-control" autocomplete="off">
							                <span class="input-group-addon" style="border: 0px;">-</span>
							                <input type="text" id="d4" class="form-control"  autocomplete="off">
							            </div>
							        </div>

				            		<!-- Grupo 3-->
				            		<div class="form-group col-md-2 col-sm-7 col-xs-7" >
					            		<select id="d5" class="form-control">
					            			<option value=""></option>
					            			<option value="BL">Bloque</option>
					            			<option value="CAS">Caserio</option>
					            			<option value="ED">Edificio</option>
					            			<option value="ET">Etapa</option>
					            			<option value="LT">Lote</option>
					            			<option value="TZ">Terraza</option>
					            			<option value="TO">Torre</option>
					            		</select>
					            	</div>
					            	<div class="form-group col-md-2 col-sm-5 col-xs-5" >
				            			<input type="text" id="d6" class="form-control" autocomplete="off">
				            		</div>

				            		<!-- Grupo 4-->
				            		<div class="form-group col-md-2 col-sm-9 col-xs-9" >
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

				            		<div class="form-group col-md-1 col-sm-3 col-xs-3" >
				            			<input type="text" id="d8" class="form-control" autocomplete="off">
				            		</div>

				            		<!-- Grupo 5-->
				            		<div class="form-group col-md-2 col-sm-9 col-xs-9" >
					            		<select id="d9" class="form-control">
					            			<option value=""></option>
					            			<option value="MZ">Manzana</option>
					            			<option value="SMZ">Super Manzana</option>
					            			<option value="CONJ">Conjunto</option>
					            			<option value="IN">Interior</option>
					            		</select>
					            	</div>

					            	<div class="form-group col-md-1 col-sm-3 col-xs-3" >
				            			<input type="text" id="d10" class="form-control" autocomplete="off">
				            		</div>

				            	</div>
						        <div class="row">
					            	<div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }} col-md-4">
					            		<label>*Direccion Real:</label>
					            		<input type="text" class="form-control" name="direccion" placeholder="Direccion" value="{{old('direccion')}}" autocomplete="off" readonly>
					            	</div>

					            	<div class="form-group{{ $errors->has('direccion_recibo') ? ' has-error' : '' }} col-md-4">
					            		<label>*Direccion Recibo:</label>
					            		<input type="text" class="form-control" name="direccion_recibo" placeholder="Direccion Recibo" value="{{old('direccion_recibo')}}" autocomplete="off">
					            	</div>

					            	<div class="form-group{{ $errors->has('coordenadas') ? ' has-error' : '' }} col-md-2">
					            		<label>Coordenadas:</label>	
					            		<input type="text" class="form-control" id="coordenadas" name="coordenadas" value="{{old('coordenadas')}}" placeholder="Latitud,Longitud" autocomplete="off">
					            	</div>

					            	<div class="form-group{{ $errors->has('estrato') ? ' has-error' : '' }} col-md-2">
					            		<label>*Estrato:</label>
					            		<select name="estrato" class="form-control" id="estrato" required>
	                                        <option value="">Elija una opción</option>
	                                        <option value="0">0</option>
	                                        <option value="1">1</option>
	                                        <option value="2">2</option>
	                                        <option value="3">3</option>
	                                        <option value="4">4</option>
	                                        <option value="5">5</option>
	                                        <option value="6">6</option>
	                                    </select>
	                                </div>

					            	<div class="form-group{{ $errors->has('barrio') ? ' has-error' : '' }} col-md-3">
					            		<label>Barrio:</label>
					            		<input type="barrio" class="form-control" name="barrio" placeholder="Barrio" value="{{old('barrio')}}" autocomplete="off">
					            	</div>

					            	<div class="form-group{{ $errors->has('urbanizacion') ? ' has-error' : '' }} col-md-3">
					            		<label>Urbanización:</label>
					            		<input type="urbanizacion" class="form-control" name="urbanizacion" placeholder="Urbanización" value="{{old('urbanizacion')}}" autocomplete="off">
					            	</div>

					            	

	                                <div class="form-group{{ $errors->has('tipo_vivienda') ? ' has-error' : '' }} col-md-2">
					            		<label>*Tipo de Vivienda:</label>
					            		<select name="tipo_vivienda" id="tipo_vivienda" class="form-control" required>
	                                        <option value="">Elija una opción</option>
	                                        <option value="Arrendada">Arrendada</option>
	                                        <option value="Familiar">Familiar</option>
	                                        <option value="Propia">Propia</option>
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="row">
	                                <div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-lg-4">
					            		<label>*Departamento: </label>
						                <select class="form-control" name="departamento" id="departamento" required>
						                	<option value="">Elija una opción</option>
						                </select>           			
					            	</div>
					            	<div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-lg-4">
					            		<label>*Municipio: </label> 
					            		<select class="form-control" name="municipio" id="municipio" required>
					            		</select>
					            	</div>
								</div>
							</div>
						</div>

						<!--- Planes y Tarifas --->
						<div class="panel panel-info" >
							<div class="panel-heading">
								<h4> <i class="fa fa-suitcase"></i>  Planes y Tarifas</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-4">
										<button type="button" id="empresarial" class="btn btn-lg btn-flat btn-block bg-purple"><i class="fa fa-building-o"></i> Empresariales</button>
									</div>
									<div class="col-md-4">
										<button type="button" id="general" class="btn btn-lg btn-flat btn-block bg-blue"><i class="fa fa-user"></i> General</button>
									</div>
									<div class="col-md-4">
										<button type="button" id="tarifa_social" class="btn btn-lg btn-flat btn-block bg-olive"><i class="fa fa-bank"></i> Tarifa Social</button>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 table-responsive">
										<table class="table table-hover">
											<thead>
												<tr>
								                  <th style="width: 10px">#</th>
								                  <th>Plan</th>
								                  <th>Descripción</th>
								                  <th>Cantidad</th>
								                  <th>Tipo</th>
								                  <th>Estrato</th>
								                  <th style="width: 40px">Valor</th>
								                </tr>
											</thead>
							                <tbody id="lista-planes">
							                	<tr>
								                	<td colspan="7" class="text-center">
								                		SIN DATOS
								                	</td>								                	
								                </tr>
								            </tbody>
								        </table>
									</div>
								</div>

								<div class="row" id="panel-detalles-contrato">
									<div class="form-group{{ $errors->has('referencia') ? ' has-error' : '' }} col-md-2 col-sm-6 col-xs-6">
										<label>*# Contrato</label>
										<input class="form-control" type="number" name="referencia" id="referencia" placeholder="Número de Contrato">
									</div>									

									<div class="form-group{{ $errors->has('vigencia') ? ' has-error' : '' }} col-md-2 col-sm-6 col-xs-6">
										<label>*Vigencia en meses</label>
										<input class="form-control" type="number" name="vigencia" id="vigencia" placeholder="Vigencia en meses">
									</div>

									<div class="form-group{{ $errors->has('fecha_contrato') ? ' has-error' : '' }} col-md-3 col-sm-6 col-xs-6">
										<label>*Fecha Contrato</label>
										<input class="form-control" type="date" name="fecha_contrato" id="fecha_contrato" placeholder="Vigencia en meses">
									</div>

									<div class="form-group{{ $errors->has('tipo_cobro') ? ' has-error' : '' }} col-md-2 col-sm-6 col-xs-6">
										<label>*Tipo de Cobro</label>
										<select class="form-control" name="tipo_cobro" id="tipo_cobro">
											<option value="">Elija una opcion</option>
											<option value="ANTICIPADO">Anticipado</option>
											<option value="VENCIDO">Vencido</option>
										</select>										
									</div>

									<div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-2 col-sm-6 col-xs-6">
										<label>*Estado</label>
										<select class="form-control" name="estado" id="estado">
											<option value="">Elija un estado</option>
											<option value="VIGENTE">VIGENTE</option>
											<option value="SUSPENDIDO">SUSPENDIDO</option>
											<option value="FINALIZADO">FINALIZADO</option>
											<option value="ANULADO">ANULADO</option>
											<option value="PENDIENTE">PENDIENTE</option>
										</select>										
									</div>									

									<div class="form-group{{ $errors->has('clausula') ? ' has-error' : '' }} col-md-6 col-xs-12" col-sm-12>										
										<div class="form-group">
						                  <div class="checkbox">
						                    <label>
						                      <input type="checkbox" name="clausula">
						                      Marque si el contrato tiene clausula de permanencia.
						                    </label>
						                  </div>
						              	</div>
									</div>
								</div>
							</div>
						</div>

						<!--- Fotos --->
						<div class="panel panel-info" id="panel-fotos">
							<div class="panel-heading">
								<h4> <i class="fa fa-folder-open-o"></i>  Documentación Requerida</h4>
							</div>
							<div class="panel-body">
								<div class="row">									

									<div class="form-group{{ $errors->has('documento_identidad_1') ? ' has-error' : '' }} col-md-6">
										<label>*Copia documento de identidad (cara 1)</label>
										<input type="file" name="documento_identidad_1" class="form-control" required>
									</div>

									<div class="form-group{{ $errors->has('documento_identidad_2') ? ' has-error' : '' }} col-md-6">
										<label>Copia documento de identidad (cara 2)</label>
										<input type="file" name="documento_identidad_2" class="form-control">
									</div>

									<div class="form-group{{ $errors->has('recibo_publico') ? ' has-error' : '' }} col-md-6">
										<label>*Copia de un servicio público de agua o energía</label>
										<input type="file" name="recibo_publico" class="form-control" required>
									</div>

									<div class="form-group{{ $errors->has('firma') ? ' has-error' : '' }} col-md-6">
										<label>Firma</label>
										<input type="file" name="firma" id="firma" class="form-control">
									</div>
									<div id="fotos-opcionales">
										
									</div>							
								</div>
							</div>
						</div>

			            <div id="progreso" class="progress-group" style="display: none;">
		                    <span class="progress-text">Subiendo Información</span>
		                    <span class="progress-number"><b id="consecutivo">0</b>%</span>

		                    <div class="progress progress-xxs active">
		                      <div class="progress-bar progress-bar-success progress-bar-striped" id="barra" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
		                    </div>
		                </div>


					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
						<button type="submit" id="enviar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Crear</button>

						
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	@section('mis_scripts')
		<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipiosproyectos.js')}}"></script>
		<script type="text/javascript">
			var resultado_cedula, resultado_email;
			$('#departamento').on('change', function(){
			    var parameters = {
			        departamento_id : $(this).val(),
			        proyecto_id : $('#proyecto').val(),
			        '_token' : $('input:hidden[name=_token]').val()
			    };

			    $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){

			        $('#municipio').empty();
			        $('#municipio').append('<option value="">Elija un municipio</option>');
			        $.each(data, function(index, municipiosObj){
			            $('#municipio').append('<option value="'+municipiosObj.MunicipioId+'">'+municipiosObj.NombreMunicipio+'</option>');                    
			        });
			    }).fail(function(e){
			        alert('error');
			    });
			});

			$("#tipo_beneficiario").change(function() {				
				
				$('#fotos-opcionales').empty();
				$('#lista-planes').empty();

				$('#fotos-opcionales').append('<div class="form-group col-md-6"><label>Copia de la declaración del suscriptor sobre su condición de nuevo usuario</label><input type="file" name="acta_nuevo_usuario" class="form-control"></div>');

				$('#fotos-opcionales').append('<div class="form-group col-md-6"><label>*Foto de la fachada del predio del beneficiario</label><input type="file" name="foto_vivienda" class="form-control" required></div>');

				$('#fotos-opcionales').append('<div class="form-group col-md-6"><label>Constancia de la autoridad territorial.</label><input type="file" name="constancia_territorial" class="form-control" ></div>');
        
				if($(this).val() == 'Estrato 1'){
					traer_planes(1,'TARIFA SOCIAL');
					$('#estrato option:eq(2)').prop('selected', true);

				}else if($(this).val() == 'Estrato 2'){
					traer_planes(2,'TARIFA SOCIAL');
					$('#estrato option:eq(3)').prop('selected', true);

				}else if($(this).val() == 'SISBEN IV'){

					$('#fotos-opcionales').append('<div class="form-group col-md-6"> <label id="texto-sisben">*Copia de Acta o Soporte de inscripción al SISBEN IV</label><input type="file" name="acta_sisben" class="form-control" required></div>');
			    	
			    }else if($(this).val() == 'Ley 1699 de 2013'){

			    	$('#fotos-opcionales').append('<div class="form-group col-md-6"><label id="texto-mindefensa">* Copia de carné y/o constancia que expide el Ministerio de Defensa.</label><input type="file" name="carnet_mindefensa" class="form-control" required></div>');			    	
			    	
			    }
			});
		

			$('#empresarial').on('click',function(){

				traer_planes($('#estrato').val(), 'EMPRESARIAL');
			});

			$('#general').on('click',function(){

				traer_planes($('#estrato').val(), 'GENERAL');
			});

			$('#tarifa_social').on('click',function(){

				traer_planes($('#estrato').val(), 'TARIFA SOCIAL');
			});

			function traer_planes(estrato, tipo_plan){

			    var parameters = {
					'estrato' : estrato,
					'proyecto' : $('#proyecto').val(),
					'tipo_plan': tipo_plan,
					'_token' : $('input:hidden[name=_token]').val()
				}

				$.post("/planes-comerciales/ajax", parameters, function(data){

					$('#lista-planes').empty();

					if($.isEmptyObject(data)){
						$('#lista-planes').append('<tr><td colspan="7" class="text-center">No se encontraron datos.</td></tr>');
					}else{					
			       
				        $.each(data, function(index, planesObj){
				        	$('#lista-planes').append('<tr><td><input type="radio" name="plan_internet" value="'+ planesObj.PlanId +'" required></td><td>'+ planesObj.nombre +'</td><td>'+ planesObj.DescripcionPlan +'</td><td>'+  planesObj.VelocidadInternet +' Megas</td><td>'+ planesObj.TipoDePlan +'</td><td>'+ planesObj.Estrato +'</td><td><span class="badge bg-default">$'+ Number(planesObj.ValorDelServicio).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,') +'</span></td></tr>');
				        });
				    }
				});
			}
			

			$('input[name=documento]').blur(function(){
				validarCedula();
			});

			$('input[name=CorreoElectronico]').blur(function(){
				validarEmail();
			});

			function validarCedula(){

				if ($('input[name=documento]').val().length > 0) {
					var parametros = {
						'cedula' : $('input[name=documento]').val(),
						'validar' : 'Identificacion',
						'_token' : $('input:hidden[name=_token]').val()
					}

					$.post('/clientes/ajaxvalidar', parametros).done(function(data){
						if (data > 0) {
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.error("El documento ya existe");
	  						$('#form-group-documento').removeClass('has-success').addClass('has-error');
	  						$('input[name=documento]').focus().select();
	  						resultado_cedula =  false;
						}else{
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.success("Documento Validado.");
	  						$('#form-group-documento').removeClass('has-error').addClass('has-success');
	  						resultado_cedula = true;
						}
					});
				}

				return resultado_cedula;
			}

			function validarEmail(){
				if ($('input[name=CorreoElectronico]').val().length > 0) {
					var parametros = {
						'cedula' : $('input[name=CorreoElectronico]').val(),
						'validar' : 'CorreoElectronico',
						'_token' : $('input:hidden[name=_token]').val()
					}

					$.post('/clientes/ajaxvalidar', parametros).done(function(data){
						if (data > 0) {
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.error("El Correo ya existe");
	  						$('#form-group-email').removeClass('has-success').addClass('has-error');
	  						$('input[name=CorreoElectronico]').focus().select();
	  						resultado_email = false;
						}else{
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.success("Correo Validado.");
	  						$('#form-group-email').removeClass('has-error').addClass('has-success');
	  						resultado_email = true;
						}
					});
				}

				return resultado_email;
			}
		</script>

		<script type="text/javascript">
		    $('#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10,input[name=barrio],input[name=urbanizacion]').focusout(function() {
		      	var numero = '';
		      	if ($('#d3').val().length > 0) {
		      		numero = ' # ' + $('#d3').val() + ' - ' + $('#d4').val();
		      	}

		      	$('input[name=direccion]').val($('#d1').val() + ' ' + $('#d2').val() + numero + ' ' + $('#d5').val() + ' ' + $('#d6').val()  + ' ' + $('#d7').val()  + ' ' + $('#d8').val()  + ' ' + $('#d9').val()  + ' ' + $('#d10').val());
		    });
		</script>
		
		

		<script type="text/javascript">

			$("#form-cliente").submit(function(e) {
			    e.preventDefault();
			    var f = $(this);
			    
			    var formData = new FormData(document.getElementById("form-cliente"));

			    
			    /*if ($('#proyecto').val() == 7 || $('#proyecto').val() == 8 || $('#proyecto').val() == 6) {
			    	if (signaturePad.isEmpty()) {
				    	toastr.options.positionClass = 'toast-bottom-right';
		  				toastr.error("Debe ingresar una firma");
		  				return false;
				    }else{
				    	var firma = signaturePad.toDataURL();
				    	formData.append("firma", firma);
				    }
			    }*/			    
			    
			    //formData.append(f.attr("name"), $(this)[0].files[0]);
			    var url = $(this).attr('action');

			    if (resultado_cedula && resultado_email) {
			    	$('#enviar i').removeClass('fa fa-floppy-o').addClass('fa fa-refresh fa-spin');
			    	$('#progreso').show(2000);
			    	$('#enviar').attr('disabled', true);
			        $.ajax({
			            url: url,
			            type: "POST",
			            dataType: "json",
			            data: formData,
			            cache: false,
			            contentType: false,
			            processData: false,
			            xhr: function(){
					        // get the native XmlHttpRequest object
					        var xhr = $.ajaxSettings.xhr() ;
					        // set the onprogress event handler
					        xhr.upload.onprogress = function(evt){
					        	$('#barra')
		                        .css('width', evt.loaded/evt.total*100 +'%')
		                        .attr('aria-valuenow', evt.loaded)
		                        .text(evt.loaded/evt.total*100  + '%');

					        	$('#consecutivo').text((evt.loaded/evt.total*100).toFixed(2)) } ;
					        // set the onload event handler
					        xhr.upload.onload = function(){ console.log('DONE!') } ;
					        // return the customized object
					        return xhr ;
					    }
			        })
			        .done(function(res){						

						if(res['tipo_mensaje'] == 'success'){

							//$('#enviar i').removeClass('fa fa-refresh fa-spin').addClass('fa fa-floppy-o');

							toastr.options.positionClass = 'toast-bottom-right';
							toastr.success(res['respuesta']);
              				window.location.href = "/clientes/create";
						}else{

							$('#progreso').hide(2000);
			        		$('#barra')
								.css('width', '0%')
								.attr('aria-valuenow', 0)
								.text('0%');

			        		$('#consecutivo').text(0);

							$('#enviar i').removeClass('fa fa-refresh fa-spin').addClass('fa fa-floppy-o');
							$('#enviar').attr('disabled', false);
							
							toastr.options.positionClass = 'toast-bottom-right';
			    			toastr.error(res['respuesta']);

						}			            
			        })
			        .fail( function(xhr, textStatus, errorThrown ) {
			        	$('#progreso').hide(2000);
			        	$('#barra')
	                        .css('width', '0%')
	                        .attr('aria-valuenow', 0)
	                        .text('0%');

			        	$('#consecutivo').text(0);

			        	$('#enviar i').removeClass('fa fa-refresh fa-spin').addClass('fa fa-floppy-o');
			        	$('#enviar').attr('disabled', false);
			            var objeto = JSON.parse(xhr.responseText);
			            if (xhr.status == 422) {                            

			                $.each(objeto, function(index, respuestaObj){			                   
			                    var padre = $('#' + index).parent();
			                    padre.removeClass('has-success').addClass('has-error');
			                    padre.append('<span class="text-danger">' + respuestaObj +'</span>');
			                });

			                toastr.options.positionClass = 'toast-bottom-right';
			                toastr.error("Corrija los campos");
			            }
			        });
			    }
			});
		</script>
    @endsection
@endsection