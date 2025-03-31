@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-users"></i>  Clientes - Contratos - Crear</h1>
@endsection

@section('mis_styles')

<style>
	#jq-signature-canvas-1{
		background-size: contain;
		background-repeat: no-repeat;
	}
</style>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <h3 class="box-title">Crear Contrato </h3>
                    </div>

					<form id="form-contrato" action="{{route('clientes.contratos.store', $cliente->ClienteId)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="box-body">
							<!--- Datos proyecto --->
							<div class="panel panel-info">
								<div class="panel-heading">
									<h4><i class="fa fa-tag"></i>  Proyecto</h4>
								</div>
								<div class="panel-body">
									<p>Elija el proyecto al que se asociará al cliente</p>

									<div class="row justify-content-center">									
										<div class="form-group{{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-3 col-sm-6 text-center">
											<label>Proyecto</label>
											<select class="form-control" name="proyecto" id="proyecto" required>
												<option value="">Elija una Opción</option>
												@foreach($proyectos as $proyecto)
													<option value="{{$proyecto->id}}" {{($cliente->ProyectoId == $proyecto->id)? 'selected' : ''}}>{{$proyecto->nombre}}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group{{ $errors->has('tipo_beneficiario') ? ' has-error' : '' }} col-md-3 col-sm-6 text-center" id="panel-tipo-beneficiario" style="display: none;">
											<label>Tipo de Beneficiario</label>
											<select class="form-control" name="tipo_beneficiario" id="tipo_beneficiario">
												<option>Elija una Opción</option>											
											</select>
										</div>

										
										<div class="form-group col-md-3 col-sm-6">
											<label>*Departamento: </label>
											<select class="form-control" name="departamento" id="departamento" readonly>
												<option value="{{$cliente->municipio->DeptId}}" selected>{{$cliente->municipio->departamento->NombreDelDepartamento}}</option>
											</select>
										</div>

										<div class="form-group col-md-3 col-sm-6">
											<label>*Municipio: </label> 
											<select class="form-control" name="municipio" id="municipio" readonly>
												<option value="{{$cliente->municipio_id}}" selected>{{$cliente->municipio->NombreMunicipio}}</option>
											</select>
										</div>

										<div class="form-group{{ $errors->has('estrato') ? ' has-error' : '' }} col-md-3 col-sm-6">
											<label>*Estrato:</label>
											<select name="estrato" class="form-control" id="estrato" required>
												<option value="">Elija una opción</option>
												@foreach($estratos as $estrato)
													<option value="{{$estrato}}" {{($estrato == $cliente->Estrato)? 'selected' : ''}}>{{$estrato}}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>

							<!--- Planes y Tarifas --->
							<div class="panel panel-info" >
								<div class="panel-heading">
									<h4> <i class="fa fa-suitcase"></i>  Contrato, Planes y Tarifas</h4>
								</div>
								<div class="panel-body">
									
									<div class="row">
										<div class="col-md-12">
											<p>De acuerdo al proyecto elegido, el contrato tendrá las siguientes caracteristicas.</p>
											<table class="table mi-tabla">
												<tr>
													<th>Fecha de inicio: </th>
													<td colspan="3">
														<input type="date" class="form-control" name="fecha_inicio" min="{{date('Y-m-d')}}" placeholder="Fecha" required>
													</td>											
												</tr>
												<tr>
													<th>Vigencia: </th>
													<td id="vigencia"></td>
													<th>Tipo de Facturación: </th>
													<td id="tipo_facturacion"></td>
												</tr>
												<tr>
													<th>Dia corte facturación: </th>
													<td id="dia_corte"></td>
													<th>% intereses por mora: </th>
													<td id="porcentaje_mora"></td>
												</tr>
												<tr>
													<th>Condiciones del Plan: </th>
													<td colspan="3"><p class="text-justify" id="condiciones_plan"></p></td>
												</tr>
												<tr>
													<th>Condiciones del Servicio: </th>
													<td colspan="3"><p class="text-justify" id="condiciones_servicio"></p></td>
												</tr>
												<tr>
													<th>Otros Costos: </th>
													<td colspan="3" id="otros_costos"></td>
												</tr>
												<tr>
													<th>Clausula de Permanencia: </th>
													<td colspan="3">
														<div id="clausulas">

														</div>
													</td>
												</tr>
											</table>
											<br>
										</div>
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
								</div>
							</div>

							<!--- Datos del usuario--->
							<div class="panel panel-info">
								<div class="panel-heading">
									<h4><i class="fa fa-user"></i>  Datos del Cliente</h4>
								</div>
								<div class="panel-body">
									<div class="row">
										<div id="form-group-email" class="form-group col-md-6 col-sm-6">
											<label>*Correo:</label>
											<input type="email" name="correo" id="correo" class="form-control" placeholder="Correo Electronico" value="{{$cliente->CorreoElectronico}}" onblur="validarEmail(this)" autocomplete="off" required>
										</div>
										<div class="form-group col-md-3 col-sm-6">
											<label>*Celular:</label>
											<input type="text" name="celular" class="form-control" placeholder="Celular" value="{{$cliente->TelefonoDeContactoMovil}}" data-inputmask='"mask": "(999) 999-9999"' data-mask  autocomplete="off" min-length="14">
										</div>
									</div>

									
									<div class="row">
										<div class="form-group col-md-6 col-sm-6">
											<label>*Direccion Real:</label>
											<input type="text" class="form-control" name="direccion" placeholder="Direccion" autocomplete="off" value="{{$cliente->DireccionDeCorrespondencia}}">
										</div>

										<div class="form-group col-md-6 col-sm-6">
											<label>*Direccion Recibo:</label>
											<input type="text" class="form-control" name="direccion_recibo" placeholder="Direccion Recibo" value="{{$cliente->direccion_recibo}}"  autocomplete="off">
										</div>

										<div class="form-group col-md-6 col-sm-6">
											<label>Barrio:</label>
											<input type="barrio" class="form-control" name="barrio" placeholder="Barrio" value="{{$cliente->Barrio}}" autocomplete="off">
										</div>

										<div class="form-group col-xs-12 col-md-6 col-sm-6">
											<label>Coordenadas</label>
											<div class="input-group input-group">
												<input type="text" name="coordenadas" id="coordenadas" placeholder="Latitud,Longitud" class="form-control" required autocomplete="off" value="{{trim($cliente->Latitud)}}, {{trim($cliente->Longitud)}}">
												<span class="input-group-btn">
													<button class="btn btn-info btn-flat" type="button" onclick="getUserPosition()"><i class="fa fa-map-marker"></i> Obtener</button>
												</span>
											</div>
											<span class="help-block"></span>
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
									<div class="row" id="documentacion_proyecto">

									</div>
									<div class="row">

										@if(!in_array("firma", $documentos->toArray()))
											<div class="form-group col-md-6 col-sm-6">
												<label>*Firma Cliente</label>
												<select name="pregunta_firma" class="form-control" required>
													<option value="">Elija una opción</option>
													<option>FIRMAR</option>
													<option>SUBIR FIRMA</option>
												</select>        
												<span class="help-block"></span>
											</div>

										

											<div id="firmaSubir" class="form-group col-md-6 col-sm-6" style="display:none;">
												<label>*Firma Cliente</label>
												<input type="file" class="form-control" name="firma" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
												<span class="help-block"></span>
											</div>
										@endif

										@if(empty(Auth::user()->firma))
											<div class="form-group col-md-6 col-sm-6">
												<label>*Firma Vendedor</label>
												<select name="pregunta_firma_usuario" class="form-control" required>
													<option value="">Elija una opción</option>
													<option>FIRMAR</option>
													<option>SUBIR FIRMA</option>
												</select>        
												<span class="help-block"></span>
											</div>

											<div id="firmaUsuarioSubir" class="form-group col-md-6 col-sm-6" style="display:none;">
												<label>*Firma Vendedor</label>
												<input type="file" class="form-control" name="firma_usuario" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
												<span class="help-block"></span>
											</div>

										@endif
									</div>
								</div>
							</div>

							<!--- Preguntas --->
							<div class="panel panel-info" id="panel-preguntas" style="display:none;">
								<div class="panel-heading">
									<h4> <i class="fa fa-question-circle"></i>  Preguntas</h4>
								</div>
								<div class="panel-body">
									<div class="row" id="preguntas_container">

									</div>
								</div>
							</div>

						</div>
						<div class="box-footer clearfix">
							<button type="submit" id="enviar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Guardar</button>
						</div>
					</form>

					<div id="result"></div>
                </div>
            </div>
        </div>
    </div>

	@include('adminlte::instalaciones.partials.firma.add')

    @section('mis_scripts')

		<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    	<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
		<script src="/js/signature_pad.umd.js"></script>
		<script src="/js/coordenadas.js"></script>
		<script src="/js/firma.js"></script>
		<script src="/js/usuarios/firma.js"></script>

		<script>
			var resultado_email = false;
			var firma = null;
			var firma_usuario = null;
			const cliente_id = {!!$cliente->ClienteId!!};
			toastr.options.positionClass = 'toast-bottom-right';
		</script>

		<script src="/js/clientes/firma.js"></script>
		<script>
			
			toastr.options.positionClass = 'toast-bottom-right';

			var estrato = "{{$cliente->Estrato}}";
			var proyecto = "{{$cliente->ProyectoId}}";
			const archivos = {!!json_encode($documentos)!!};

			const formatoMoneda = (valor) => {
				return valor.toLocaleString('es-CO',{style:'currency', currency:'COP', minimumFractionDigits:0});
			}

			$(document).ready(function() {
				caracteristicas_proyecto(proyecto);
				traer_planes(estrato);
				validarEmail(document.getElementById('correo'));
				$("input[name=celular]").inputmask({"mask": "(999) 999-9999"});
			})

			$("#tipo_beneficiario").on('change', function(){

				if($(this).val().length > 0){
					
					if($(this).val() == 'Estrato 1'){
						traer_planes(1);
						$('#estrato option:eq(2)').prop('selected', true);

					}else if($(this).val() == 'Estrato 2'){
						traer_planes(2);
						$('#estrato option:eq(3)').prop('selected', true);
					}
				}
			});

			$('#proyecto').on('change', function(){

				if($(this).val().length > 0) {

					$('#tipo_beneficiario').empty();                
					$('#clausulas').empty();
					$('#otros_costos').empty();
					$('#lista-planes').empty();
					$('#documentacion_proyecto').empty();

					caracteristicas_proyecto($(this).val());
					traer_planes($('#estrato').val());

				}
			});

			const caracteristicas_proyecto = (proyecto) => {

				if(proyecto != ""){

					var parameters = {
						proyecto_id : proyecto,
						'_token' : $('input:hidden[name=_token]').val()
					};

					$.post('/proyectos/ajax', parameters).done(function(data){						
						$('#vigencia').text(data.proyecto['vigencia'] + ' Meses');
						$('#tipo_facturacion').text(data.proyecto['tipo_facturacion']);
						$('#dia_corte').text('Día ' + data.proyecto['dia_corte_facturacion'] + ' de cada mes.');
						$('#porcentaje_mora').text(data.proyecto['porcentaje_interes_mora']);
						$('#condiciones_plan').text((data.proyecto['condiciones_plan'] != null)? data.proyecto['condiciones_plan'] : 'SIN DEFINIR');
						$('#condiciones_servicio').text((data.proyecto['condiciones_servicio'] != null)? data.proyecto['condiciones_servicio'] : 'SIN DEFINIR');

						if(data.proyecto['clausula'].length > 0){

							let clausulas_list = '';

							var k = 1;

							$.each(data.proyecto['clausula'], function(index, clausulaObj){ 
								if(k == 1){
									clausulas_list += '<tr>';
								}

								clausulas_list += `<td class="text-center"> <b>MES ${clausulaObj.numero_mes}</b> </br> ${formatoMoneda(parseFloat(clausulaObj.valor))}</td>`;

								if(k == 6){
									clausulas_list += '</tr>';
									k = 0;
								}

								k += 1;
							});

							$('#clausulas').append(`
							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="text-center" colspan="6">Valor a pagar si termina el contrato anticipadamente según el mes</th>
									</tr>                 
								</thead>
								<tbody>									
									${clausulas_list}
								</tbody>
							</table>
							`);
						}else{							
							$('#clausulas').text('SIN DEFINIR');
						}

						if(data.proyecto['costo'].length > 0){

							let costos_list = '';

							$.each(data.proyecto['costo'], function(index, costoObj){

								costos_list += `
									<tr>
										<td>${costoObj.concepto}</td>
										<td>${(costoObj.descripcion != null) ? costoObj.descripcion : ''}</td>
										<td>${formatoMoneda(parseFloat(costoObj.valor))} ${(costoObj.iva == 'SI')? ' más IVA': ''}</td>
									</tr>								
								`;
								
							});

							$('#otros_costos').append(`
							<table class="table">
								<thead>
									<tr>
										<th>Concepto</th>
										<th>Descripcion</th>
										<th>Valor</th>             
									</tr>                   
								</thead>
								<tbody>
									${costos_list}
								</tbody>
							</table>
							`);

						}else{							
							$('#otros_costos').text('SIN DEFINIR');
						}

						if(data.documentacion.length > 0){

							$.each(data.documentacion, function(index, documentacionObj){

								if(!archivos.includes(documentacionObj.nombre) || documentacionObj.nombre == 'recibo_publico' || documentacionObj.nombre == 'foto_vivienda'){

									$('#documentacion_proyecto').append(`
										<div class="form-group col-md-6 col-sm-6">
											<label>${(documentacionObj.tipo == 'OBLIGATORIO')? '*' : ''} ${documentacionObj.alias}</label>
											<input type="file" name="archivos[${documentacionObj.nombre}]" class="form-control" ${(documentacionObj.tipo == 'OBLIGATORIO')? 'required' : ''}>
										</div>
									`);	
								}						
							});

						}else{							
							$('#documentacion_proyecto').text('SIN DEFINIR');
						}
					
						if(data.tipos_beneficiarios.length > 0){

							$('#tipo_beneficiario').attr('required', true);
							$('#tipo_beneficiario').parent().show(2000);

							$('#tipo_beneficiario').append('<option value="">Elija un tipo de beneficiario</option>');
							$.each(data.tipos_beneficiarios, function(index, tipoBeneficiarioObj){                   
								$('#tipo_beneficiario').append('<option value="'+tipoBeneficiarioObj.nombre+'">'+tipoBeneficiarioObj.nombre+'</option>');                    
							});
						}else{
							$('#tipo_beneficiario').attr('required', false);
							$('#tipo_beneficiario').parent().fadeOut(2000);
						}

						if(data.preguntas.length > 0){
							$('#preguntas_container').empty();
							$('#panel-preguntas').show();

							$.each(data.preguntas, function(index, pregunta){

								let input = "";

								switch (pregunta.tipo) {
									case 'textarea':
										input = `<textarea name="respuesta[${pregunta.id}]" class="form-control" ${(pregunta.obligatoriedad == '1')? 'required' : ''}></textarea>`;										
										break;

									case 'select':
										var opciones = `<option value="">Elija una opción</option>`;
										var opciones_respuesta = JSON.parse(pregunta.opciones_respuesta);

										$.each(opciones_respuesta, function(index, opcion_respuesta){
											opciones += `<option value="${opcion_respuesta}">${opcion_respuesta}</option>`;
										});

										input = `<select name="respuesta[${pregunta.id}]" class="form-control" ${(pregunta.obligatoriedad == '1')? 'required' : ''}>${opciones}</select>`;										
										break;

									case 'check':

										var opciones_respuesta = JSON.parse(pregunta.opciones_respuesta);

										$.each(opciones_respuesta, function(index, opcion_respuesta){
											input += `<div class="checkbox">
															<label>
																<input type="checkbox" name="respuesta[${pregunta.id}][]" value="${opcion_respuesta}">
																${opcion_respuesta}
															</label>
														</div>`;
										});

										break;
								
									default:
										input = `<input type="${pregunta.tipo}" name="respuesta[${pregunta.id}]" class="form-control" ${(pregunta.obligatoriedad == '1')? 'required' : ''}>`;
										break;
								}								

								$('#preguntas_container').append(`
									<div class="form-group col-md-6 col-sm-6">
										<label>${(pregunta.obligatoriedad == '1')? '*' : ''} ${pregunta.pregunta}</label>
										${input}
									</div>
								`);

							});


						}else{
							$('#panel-preguntas').hide();
							$('#preguntas_container').empty();
						}

					}).fail(function(e){
						toastr.error(e.message);
					});	

				}

			}

			$('#estrato').on('change', function(){
				if($(this).val().length > 0){
					traer_planes($(this).val());
				}
			});

			const traer_planes = (estrato) => {

				const parameters = {
					'estrato' : estrato,
					'proyecto' : $('#proyecto').val(),
					'municipio' : $('#municipio').val(),
					'_token' : $('input:hidden[name=_token]').val()
				}

				$.post("/planes-comerciales/ajax", parameters, function(data){

					$('#lista-planes').empty();

					if($.isEmptyObject(data)){
						$('#lista-planes').append('<tr><td colspan="7" class="text-center">No se encontraron datos.</td></tr>');
					}else{					
				
						$.each(data, function(index, planesObj){
							$('#lista-planes').append(`
							<tr>
								<td>
									<input type="radio" name="plan_internet" value="${planesObj.PlanId}" required>
								</td>
								<td>${planesObj.nombre}</td>
								<td>${planesObj.DescripcionPlan}</td>
								<td>${planesObj.VelocidadInternet} Megas</td>
								<td>${planesObj.TipoDePlan}</td>
								<td>${planesObj.Estrato}</td>
								<td>
									<span class="badge bg-default">${formatoMoneda(parseFloat(planesObj.ValorDelServicio))}</span>
								</td>
							</tr>
							`);
						});
					}
				});
			}

			const validarEmail = (correo) => {
				if (correo.value.length > 0) {
					var parametros = {
						'cedula' : correo.value,
						'cliente_id' : cliente_id,
						'validar' : 'CorreoElectronico',
						'_token' : $('input:hidden[name=_token]').val()
					}

					$.post('/clientes/ajaxvalidar', parametros).done(function(data){
						if (data > 0) {
							toastr.options.positionClass = 'toast-bottom-right';
							toastr.error("El Correo ya existe");
							$('#form-group-email').removeClass('has-success').addClass('has-error');
							correo.focus();
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


			$("#form-contrato").submit(function(e) {
			    e.preventDefault();
			    var f = $(this);
				var formData = new FormData(this);
			    
			    if ($('select[name="pregunta_firma"]').val() == 'FIRMAR') {
					formData.append('firma', firma);
				}

				if ($('select[name="pregunta_firma_usuario"]').val() == 'FIRMAR') {
					formData.append('firma_usuario', firma_usuario);
				}else if ($('select[name="pregunta_firma_usuario"]').val() == 'SUBIR FIRMA'){
					formData.append('firma_usuario', $('input[name="firma_usuario"]')[0].files[0]);
				}
			   

			    if (resultado_email) {

					$('#result').removeClass('overlay').empty();			
					$('#result').addClass('overlay').append('<i class="fa fa-refresh fa-spin"></i>');
		    
			    	
					$.ajax({
						url: f.attr('action'),
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false
					})
					.done(function(res){

						console.log(res);

						if(res['tipo_mensaje'] == 'success'){                        
							toastr.success(res['mensaje']);

							setTimeout(() => {
								location.replace("/clientes/" + cliente_id);
							}, "3000");

						}else{

							$('#result').removeClass('overlay').empty();

							toastr.error(res['mensaje']); 
						}

					}).fail(function( jqXHR, textStatus, errorThrown ) {
						$('#result').removeClass('overlay').empty();						

						if(jqXHR.status == 422){

							var objeto = JSON.parse(jqXHR.responseText);

							$.each(objeto, function(index, respuestaObj){			                   
								var padre = $('[name="' + index+'"]').parent();
								padre.removeClass('has-success').addClass('has-error');
								padre.find('.help-block').text(respuestaObj)
								//padre.append('<span class="text-danger">' + respuestaObj +'</span>');
							});

							toastr.error("Corrija los campos");
						}else{
							
							toastr.error(errorThrown);
						}                      
					});
			    }else{
					toastr.warning("El correo es incorrecto.");
				}
			});
		</script>
    @endsection
@endsection