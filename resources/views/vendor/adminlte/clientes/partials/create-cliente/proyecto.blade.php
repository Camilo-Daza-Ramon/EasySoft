@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> Crear Cliente </h1>
    
@endsection

@section('header-scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<form action="{{route('clientes.store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title">Crear Cliente</h3>
					</div>
					<div class="box-body">		          	
						{{csrf_field()}}

						<!--- Datos personales --->
						<div class="panel panel-info" id="panel-tipo-beneficiario">
							<div class="panel-heading">
								<h4><i class="fa fa-tag"></i>  Tipo de Beneficiario</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-4"></div>
									<div class="form-group{{ $errors->has('tipo_beneficiario') ? ' has-error' : '' }} col-md-4 text-center">
										<label>Tipo de Beneficiario</label>
										<select class="form-control" name="tipo_beneficiario" id="tipo_beneficiario" required>
											<option>Elija una Opción</option>
											<option value="Estrato 1">Estrato 1</option>
											<option value="Estrato 2">Estrato 2</option>
											<option value="SISBEN IV">SISBEN IV</option>
											<option value="Ley 1699 de 2013">Ley 1699 de 2013</option>
										</select>
									</div>
									<div class="col-md-4"></div>
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
										<select name="tipo_documento" class="form-control" required>
											<option value="">Elija un valor</option>
											<option value="C.C">Cédula de Ciudadanía</option>
											<option value="C.E">Cédula de Extranjería</option>
											<option value="P.P">Pasaporte</option>
											<option value="R.C">Registro Civil</option>
											<option value="T.I">Tarjeta de Identidad</option>
											<option value="NIT">Número de Identificación Tributaria</option>
										</select>
									</div>
									<div class="form-group{{ $errors->has('documento') ? ' has-error' : '' }} col-md-3">
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
	                                    <select name="genero" class="form-control" required>
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

	                                <div class="form-group{{ $errors->has('etnia') ? ' has-error' : '' }} col-md-6">
										<label>*Étnia: </label>
										<select name="etnia" class="form-control" required>
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
									<div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }} col-md-3">
					            		<label>Teléfono:</label>
	                                     <input type="number" class="form-control" name="telefono" id="telefono" placeholder="Telefono" value="{{old('telefono')}}" autocomplete="off"> 
					            	</div>

					            	<div class="form-group{{ $errors->has('celular') ? ' has-error' : '' }} col-md-3">
										<label>*Celular:</label>
										<input type="number" name="celular" class="form-control" placeholder="Celular" value="{{old('celular')}}" autocomplete="off">
									</div>

									<div class="form-group{{ $errors->has('CorreoElectronico') ? ' has-error' : '' }} col-md-6">
										<label>*Correo:</label>
										<input type="email" name="CorreoElectronico" class="form-control" placeholder="Correo" value="{{old('CorreoElectronico')}}" autocomplete="off" required>
									</div>								
								</div>

								<div class="row">
									<div class="form-group{{ $errors->has('sexo') ? ' has-error' : '' }} col-md-2">
										<label>*Sexo:  </label>
										<select class="form-control" name="sexo" required>
											<option value="">Elija un opcion</option>
											<option value="Hembra">Hembra</option>
											<option value="Macho">Macho</option>
											<option value="Intersexual">Intersexual</option>
										</select>
									</div>

									<div class="form-group{{ $errors->has('orientacion_sexual') ? ' has-error' : '' }} col-md-3">
					            		<label>*Orientación Sexual:</label>
										<select name="orientacion_sexual" class="form-control" required>
											<option value="">Elija un opcion</option>
											<option value="Heterosexual">Heterosexual</option>
											<option value="Homosexual">Homosexual</option>
											<option value="Bisexual">Bisexual</option>
										</select>
	                                </div>

	                                <div class="form-group{{ $errors->has('nivel_estudios') ? ' has-error' : '' }} col-md-4">
										<label>Nivel de Estudios</label>
										<select class="form-control" name="nivel_estudios" required>
											<option value="">Nivel de estudios</option>
											<option value="NINGUNA">Ninguna</option>
											<option value="Educación Básica Primaria">Educación Básica Primaria</option>
											<option value="Educación Básica Secundaria">Educación Básica Secundaria</option>
											<option value="Bachillerato / Educación Media">Bachillerato / Educación Media</option>
											<option value="Universidad / Carrera técnica">Universidad / Carrera técnica</option>
											<option value="Universidad / Carrera tecnológica">Universidad / Carrera tecnológica</option>
											<option value="Universidad / Carrera Profesional">Universidad / Carrera Profesional</option>
											<option value="Postgrado / Especialización">Postgrado / Especialización</option>
											<option value="Postgrado / Maestría">Postgrado / Maestría</option>
											<option value="Postgrado / Doctorado">Postgrado / Doctorado</option>
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
					            		<label>*Direccion:</label>
					            		<input type="text" class="form-control" name="direccion" placeholder="Direccion" value="{{old('direccion')}}" autocomplete="off" readonly>
					            	</div>

					            	<div class="form-group{{ $errors->has('barrio') ? ' has-error' : '' }} col-md-4">
					            		<label>Barrio:</label>
					            		<input type="barrio" class="form-control" name="barrio" placeholder="Barrio" value="{{old('barrio')}}" autocomplete="off">
					            	</div>

					            	<div class="form-group{{ $errors->has('urbanizacion') ? ' has-error' : '' }} col-md-4">
					            		<label>Urbanización:</label>
					            		<input type="urbanizacion" class="form-control" name="urbanizacion" placeholder="Urbanización" value="{{old('urbanizacion')}}" autocomplete="off">
					            	</div>					            	

					            	<div class="form-group{{ $errors->has('coordenadas') ? ' has-error' : '' }} col-md-4">
					            		<label>Coordenadas:</label>					            		

					            		<div class="input-group input-group">
							                <input type="text" class="form-control" id="coordenadas" name="coordenadas" value="{{old('coordenadas')}}" autocomplete="off" readonly>
							                    <span class="input-group-btn">
							                      <button type="button" class="btn btn-info btn-flat" onclick="getUserPosition();"> <i class="fa fa-crosshairs"></i> Obtener</button>
							                    </span>
							              </div>
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

	                                <div class="form-group{{ $errors->has('tipo_vivienda') ? ' has-error' : '' }} col-md-2">
					            		<label>*Tipo de Vivienda:</label>
					            		<select name="tipo_vivienda" class="form-control" required>
	                                        <option value="">Elija una opción</option>
	                                        <option value="Arrendada">Arrendada</option>
	                                        <option value="Familiar">Familiar</option>
	                                        <option value="Propia">Propia</option>
	                                    </select>
	                                </div>

	                                <div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-lg-4">
					            		<label>*Departamento: </label>
						                <select class="form-control" name="departamento" id="departamento" required>
						                	<option value="">Elija una opción</option>
						                	@foreach($departamentos as $departamento)
						                		<option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
						                	@endforeach
						                    
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

									<div class="col-lg-6 col-sm-12" id="panel-planes" style="display: none;">
										<ul class="products-list product-list-in-box" id="lista-planes">
											
							            </ul>
							        </div>

									<div class="form-group{{ $errors->has('vigencia') ? ' has-error' : '' }} col-md-4">
										<label>*Vigencia</label>
										<select class="form-control" name="vigencia" required>
											<option value="">Elija un valor</option>
											<option value="12">36 Meses</option>
										</select>										
									</div>

									<div class="form-group{{ $errors->has('tipo_cobro') ? ' has-error' : '' }} col-md-4">
										<label>*Tipo de Cobro</label>
										<select class="form-control" name="tipo_cobro" id="tipo_cobro" required>
											<option value="">Elija un valor</option>
											<option value="Anticipado">Anticipado</option>
										</select>										
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

									<div class="form-group{{ $errors->has('acta_nuevo_usuario') ? ' has-error' : '' }} col-md-6">
										<label>*Copia de la declaración del suscriptor sobre su condición de nuevo usuario</label>
										<input type="file" name="acta_nuevo_usuario" class="form-control" required>
									</div>

									<div class="form-group{{ $errors->has('recibo_publico') ? ' has-error' : '' }} col-md-6">
										<label>*Copia de un servicio público de agua o energía</label>
										<input type="file" name="recibo_publico" class="form-control" required>
									</div>

									<div class="form-group{{ $errors->has('foto_vivienda') ? ' has-error' : '' }} col-md-6">
										<label>*Foto de la fachada del predio del beneficiario</label>
										<input type="file" name="foto_vivienda" class="form-control" required>
									</div>

									<!--Solo si en el recibo no se identifica el estrato-->
									<div class="form-group{{ $errors->has('constancia_territorial') ? ' has-error' : '' }} col-md-6">
										<label>Constancia de la autoridad territorial.</label>
										<input type="file" name="constancia_territorial" class="form-control" >
									</div>

									<div id="fotos-opcionales">
										
									</div>
								</div>
							</div>
						</div>

						<!--- Firma --->
						<div class="panel panel-info" id="panel-firma">
							<div class="panel-heading">
								<h4> <i class="fa  fa-pencil"></i>  Firmas</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="form-group col-md-12">
										<label>*Firma del Contrato</label>
										<div id="signature-pad" class="m-signature-pad">
											<div class="m-signature-pad--body">
												<canvas style="border: 2px dashed #ccc"></canvas>
											</div>
											<div class="m-signature-pad--footer">
												<button type="button" class="btn btn-sm btn-secondary" data-action="clear"><i class="fa fa-eraser"></i> Limpiar</button>
												<button type="button" class="btn btn-sm btn-primary" data-action="save">Save</button>
											</div>
										</div>
									</div>									
								</div>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
						<button type="submit" id="enviar" class="btn btn-success pull-right" disabled><i class="fa fa-floppy-o"></i>  Crear</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	@section('mis_scripts')
		<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios.js')}}"></script>

		<script> 	

		    $(function () {
		        var wrapper = document.getElementById("signature-pad"),
		        clearButton = wrapper.querySelector("[data-action=clear]"),
		        saveButton = wrapper.querySelector("[data-action=save]"),
		        canvas = wrapper.querySelector("canvas"),
		        signaturePad;

		        // Adjust canvas coordinate space taking into account pixel ratio,
		        // to make it look crisp on mobile devices.
		        // This also causes canvas to be cleared.
		        window.resizeCanvas = function () {
		            var ratio =  window.devicePixelRatio || 1;
		            canvas.width = canvas.offsetWidth * ratio;
		            canvas.height = canvas.offsetHeight * ratio;
		            canvas.getContext("2d").scale(ratio, ratio);
		        }

		        resizeCanvas();
		    });
		</script>

		<script type="text/javascript">
			$("#departamento").change(function() {
			    buscarmunicipios($(this).val());
			});

			var estrato = '1, 2';

			$("#tipo_beneficiario").change(function() {
				$('#panel-planes').hide(2000);
				$('#lista-planes').empty();
				$('#fotos-opcionales').empty();

				/*$('input:file[name=carnet_mindefensa]').removeAttr('required');
				$('input:file[name=acta_sisben]').removeAttr('required');

				if ($('#texto-sisben').text().substring(0,1) == '*') {
					$('#texto-sisben').text($('#texto-sisben').text().substring(1));								
				}

				if ($('#texto-mindefensa').text().substring(0,1) == '*') {
					$('#texto-mindefensa').text($('#texto-mindefensa').text().substring(1));				
				}*/

				if($(this).val() == 'Estrato 1'){
					estrato = 1;
					traer_planes(estrato);

				}else if($(this).val() == 'Estrato 2'){
					estrato = 2;
					traer_planes(estrato);

				}else if($(this).val() == 'SISBEN IV'){

					$('#fotos-opcionales').append('<div class="form-group col-md-6"> <label id="texto-sisben">*Copia de Acta o Soporte de inscripción al SISBEN IV</label><input type="file" name="acta_sisben" class="form-control" required></div>');

			    	//$('#texto-sisben').text('*' + $('#texto-sisben').text());
			    	//$('input:file[name=acta_sisben]').attr('required', 'true');
			    	
			    }else if($(this).val() == 'Ley 1699 de 2013'){

			    	$('#fotos-opcionales').append('<div class="form-group col-md-6"><label id="texto-mindefensa">* Copia de carné y/o constancia que expide el Ministerio de Defensa.</label><input type="file" name="carnet_mindefensa" class="form-control" required></div>');
			    	
			    	//$('#texto-mindefensa').text('*' + $('#texto-mindefensa').text());
			    	//$('input:file[name=carnet_mindefensa]').attr('required', 'true');
			    }

			    //$('#panel-tipo-beneficiario').hide(2000);

			    //$('#datos-personales').show(4000);
			});

			$("#estrato").change(function() {
				if ($("#tipo_beneficiario").val() == 'SISBEN IV' || $("#tipo_beneficiario").val() == 'Ley 1699 de 2013'){
					if($(this).val() > 2){
						estrato = 2;
						traer_planes(estrato);				
					}else{
						traer_planes($(this).val());
					}
				}
				
			});


			/*$("#discapacidad").change(function() {
			    $('#panel-direccion').show(4000);
			});

			$("#municipio").change(function() {
			    $('#panel-planes').show(4000);
			});

			$("#tipo_cobro").change(function() {
			    $('#panel-fotos').show(4000);
			});*/

			function traer_planes(estrato){

			    var parameters = {
					'estrato' : estrato,
					'_token' : $('input:hidden[name=_token]').val(),
				}

				$.post("/planes-comerciales/ajax", parameters, function(data){

					$('#lista-planes').empty();
					$('#panel-planes').show(4000);
			       
			        $.each(data, function(index, planesObj){
			            
			            $('#lista-planes').append('<li class="item"><div class="product-img"><input type="radio" name="plan_internet" value="'+ planesObj.PlanId +'" required></div><div class="product-info"><a href="javascript:void(0)" class="product-title">'+ planesObj.nombre +'<span class="label label-info pull-right">$'+ Number(planesObj.ValorDelServicio).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,') +'</span></a><span class="product-description">'+ planesObj.DescripcionPlan +'</span></div></li>');
			        });
				});
			}
		</script>

		<script src="/js/signature_pad.umd.js"></script>
		<script src="/js/app1.js"></script>

		<script type="text/javascript">
		      $('#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10,input[name=barrio],input[name=urbanizacion]').focusout(function() {
		      	var numero = '';
		      	if ($('#d3').val().length > 0) {
		      		numero = ' # ' + $('#d3').val() + ' - ' + $('#d4').val();
		      	}

		      	$('input[name=direccion]').val($('#d1').val() + ' ' + $('#d2').val() + numero + ' ' + $('#d5').val() + ' ' + $('#d6').val()  + ' ' + $('#d7').val()  + ' ' + $('#d8').val()  + ' ' + $('#d9').val()  + ' ' + $('#d10').val());
		      });

		      $("#departamento").change(function() {
		        buscarmunicipios($('#departamento').val());
		      });
		</script>

	  	<script type="text/javascript">
	    	function getUserPosition() {
	    		if (navigator.geolocation) {
		          	navigator.geolocation.getCurrentPosition(onSuccessGeolocating, onErrorGeolocating,{
		          		enableHighAccuracy: true,
		          		maximumAge:         5000,
		          		timeout:            10000
		          	});
		         }else {
	                // Browser doesn't support Geolocation
	                handleLocationError(false, infoWindow, map.getCenter());
	            }
	        }

	        function onSuccessGeolocating(position){
	            latitud = position.coords.latitude;
	            longitud = position.coords.longitude;
	            $('#coordenadas').val(latitud + ','+ longitud);

	            marcarmapa(latitud,longitud);
	        }

	        function onErrorGeolocating(error){
	          switch(error.code)
	          {
	            case error.PERMISSION_DENIED:
	              alert('ERROR: No se permitió o no se tienen suficientes privilegios para acceder al servicio de geolocalización.');
	                  $('#permisos').modal('show');
	            break;

	            case error.POSITION_UNAVAILABLE:
	              alert("ERROR: El dispositivo no pudo determinar correctamente su ubicación.");
	            break;

	            case error.TIMEOUT:
	              alert("ERROR: El intento de geolocalización tomó mas tiempo del permitido.");
	            break;

	            default:
	              alert("ERROR: Problema desconocido.");
	            break;
	          }
	        }
	    </script>
    @endsection
@endsection