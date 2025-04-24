@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-edit"></i> Agregar Instalacion</h1>    
@endsection

@section('mis_styles')

<style>
	#jq-signature-canvas-1{
		/*background-image: url('/img/fondo_firma.jpg');*/
		background-size: contain;
		background-repeat: no-repeat;
	}
</style>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">

	<div class="row">
		<div class="col-md-6 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border bg-blue">
					<h3 class="box-title"><i class="fa fa-user"></i> Datos Cliente</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<th>Identificacion</th>
								<td>{{$cliente->TipoDeDocumento}} {{$cliente->Identificacion}}</td>
							</tr>
							<tr>
								<th>Nombre</th>
								<td>{{$cliente->NombreBeneficiario}} {{$cliente->Apellidos}}</td>
							</tr>
							<tr>
								<th>Celular</th>
								<td>{{$cliente->TelefonoDeContactoMovil}}</td>
							</tr>
							<tr>
								<th>Direccion</th>
								<td>							
									<a href="{{'https://maps.google.com/?q='.trim($cliente->Latitud).','.trim($cliente->Longitud)}}">
										{{$cliente->DireccionDeCorrespondencia}} - {{$cliente->municipio->departamento->NombreDelDepartamento}}
									</a>
								</td>
							</tr>

							<tr>
								<th>Foto Casa/Fachada</th>
								<td>
									@if(!empty($index_key))
										<button class="btn btn-default btn-block"data-toggle="modal" data-target="#modal-attachment" data-tipo="{{$cliente->archivos[$index_key]['tipo_archivo']}}" data-archivo="{{Storage::url($cliente->archivos[$index_key]['archivo'])}}"> <i class="fa fa-eye"></i> Ver foto </button>
									@else
										no se encontró foto
									@endif
								</td>
							</tr>

							<tr id="numeroProyecto" data-proyecto="{{$cliente->proyecto->NumeroDeProyecto}}">
								<th>Proyecto</th>
								<td>{{$cliente->proyecto->NumeroDeProyecto}}</td>
							</tr>		        				
							<tr>
								<th>Estado</th>
								<td>{{$cliente->Status}}</td>
							</tr>
							<tr>
								<th>Fecha Venta</th>
								<td>{{$cliente->Fecha}}</td>
							</tr>							
						</tbody>
					</table>
				</div>
			</div>
		</div>


		@if(empty(Auth::user()->firma))
		<div class="col-md-6 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border bg-blue">
					<h3 class="box-title"><i class="fa fa-edit"></i> Firma Tecnico</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive">
					<p>Esta firma solo tendrá que realizarla una unica vez. apartir de ahí el sistema ya identifica la firma guardada y se asigna automaticamente al resto de instalaciones que vaya a realizar.</p>
					<div class="form-group{{ $errors->has('pregunta_firma_tecnico') ? ' has-error' : '' }} col-md-6">
						<label>*Firma</label>
						<select name="pregunta_firma_tecnico" class="form-control" required>
							<option value="">Elija una opción</option>
							<option>FIRMAR</option>
							<option>SUBIR FIRMA</option>
						</select>        
						<span class="help-block"></span>
					</div>

					<div id="firmaTecnicoSubir" class="form-group{{ $errors->has('firma_tecnico') ? ' has-error' : '' }} col-md-6" style="display:none;">
						<label>*Firma</label>
						<input type="file" class="form-control" name="firma_tecnico" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
						<span class="help-block"></span>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>


{{--  Datos para la instalación --}}

	<div class="row">		
		<div class="col-md-12">
			<div class="box box-info">				
				<div class="box-header with-border bg-blue">
					<h3 class="box-title">Datos de la instalación</h3>
				</div>
				<form id="form-instalacion" action="{{route('instalaciones.store')}}" method="post">
					{{csrf_field()}}
					<input type="hidden" name="cliente_id" value="{{$cliente->ClienteId}}">

			{{--  Condicional dependiendo del tipo de proyecto --}}

					@if($cliente->proyecto->ProyectoId != 14)
					<div class="row col-md-12">
						<div class="form-group col-md-3">
							<label for="TipoConexion">Conexión</label>
							<select class="form-control" name="TipoConexion" id="tipoConexion" required>
								<option value="">Elija una conexión</option>
								<option value="RADIO">Conexión Inalámbrica</option>
								<option value="ONT">Fibra</option>
								<option value="CABLEADO">Conexión Cableada</option>
							</select>
						</div>
						<div class="form-group{{ $errors->has('coordenadas') ? ' has-error' : '' }} col-xs-12 col-md-4">
							<label>Coordenadas</label>
							<div class="input-group input-group">
								<input type="text" name="coordenadas" id="coordenadas" placeholder="Coordenadas" class="form-control" autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-info btn-flat" type="button" onclick="getUserPosition()"><i class="fa fa-map-marker"></i> Obtener</button>
								</span>
							</div>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-md-3" id="estructura-field" style="display: none;" >
							<Label for="estructura-instalacion">Estructura de Instalación</Label>
							<select class="form-control" name="EstructuraInstalacion" id="estructura-instalacion">
								<option value="">Elija una estructura</option>
								<option value="NODO-SECUNDARIO">NODO SECUNDARIO</option>
								<option value="PAC-CC">PAC / CC</option>
								<option value="HOGARES">HOGARES</option>
							</select>
						</div>

					</div>

					<div id="form-cableado"  class="visible" style="display: none;">
						<div class="form-group col-xs-12 col-md-4">
							<label  for="RouterSerial">Router (Serial)</label>
							<input type="text" class="form-control" name="RouterSerial" placeholder="Serial Router"value=""  maxlength="20"  autocomplete="off">
							<span class="help-block"></span>
						</div>
						<div class="form-group col-xs-12 col-md-4">
							<label  for="RouterMarca">Marca del Router</label>
							<input type="text" class="form-control" name="RouterMarca" placeholder="Marca del Router"value=""  maxlength="20"  autocomplete="off">
							<span class="help-block"></span>
						</div>
						<div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6">
							<label>Estado del Router</label>
							<select class="form-control" name="estado_equipo_pe" required>
								<option value="">Elija una opcion</option>
								@foreach($estados_otros as $estado_equipo)
									<option>{{$estado_equipo}}</option>
								@endforeach
							</select>
							<span class="help-block"></span>
						</div> 
						
						<div class="form-group col-md-3">
							<label for="CableUTP" class="control-label col-xs-7 col-md-12">Cable UTP (Mtrs)</label>
							<div class="col-xs-5 col-md-12 mb-2">
								<input type="number" name="CableUTP" class="form-control" placeholder="Cant." value="" min="0">
							</div>
						</div>
						<div class="form-group col-md-3">
							<label for="SwitchPuerto" class="control-label col-xs-7 col-md-12">Switch (Puertos)</label>
							<div class="col-xs-5 col-md-12 mb-2">
								<input type="number" name="SwitchPuerto" class="form-control" placeholder="Número del Puerto" value="" min="1" max="16">
							</div>
						</div>
						<div class="form-group col-md-12">
							@include('adminlte::instalaciones.partials.formGuajira')
							@include('adminlte::instalaciones.partials.evidencia.form')
							@include('adminlte::partials.modal_show_archivos')
							@include('adminlte::instalaciones.partials.firma.add')
						</div>

					</div>
					<div class="box-body" id="formularioCableado" style="display: none;">

						<div class="row" >
							<div class="form-group{{ $errors->has('serial_ont') ? ' has-error' : '' }} col-xs-12 col-md-4">
								<label>Serial ONT</label>
								<input type="text" class="form-control" name="serial_ont" placeholder="Serial ONT"value=""  maxlength="20"  autocomplete="off">
								<span class="help-block"></span>
							</div>

{{-- 							<div class="form-group{{ $errors->has('coordenadas') ? ' has-error' : '' }} col-xs-12 col-md-4">
								<label>Coordenadas</label>
								<div class="input-group input-group">
									<input type="text" name="coordenadas" id="coordenadas" placeholder="Coordenadas" class="form-control" autocomplete="off">
									<span class="input-group-btn">
										<button class="btn btn-info btn-flat" type="button" onclick="getUserPosition()"><i class="fa fa-map-marker"></i> Obtener</button>
									</span>
								</div>
								<span class="help-block"></span>
							</div> --}}
							<hr width="90%">
						</div>

						<div id="contenido_formulario" style="display:none;">
							@include('adminlte::instalaciones.partials.formGuajira')
						
							@include('adminlte::instalaciones.partials.material.form')

							@include('adminlte::instalaciones.partials.evidencia.form')

							<div class="row  bg-blue">
								<div class="col-md-12 text-center">
									<h5>DATOS DE CONEXION FÍSICA</h5>
								</div>    
							</div>
							<br>

							<div class="row">
							
								<div class="form-group{{ $errors->has('caja') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Caja</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="caja" class="form-control" placeholder="Cant." value="" min="0" >
									</div>
								</div>

								<div class="form-group{{ $errors->has('puerto') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Puerto</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="puerto" class="form-control" placeholder="Cant." value="" min="0">
									</div>
								</div>

								<div class="form-group{{ $errors->has('sp_splitter') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*SP Spliter</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="sp_splitter" class="form-control" placeholder="Cant." value="" min="0">
									</div>
								</div>


								<div class="form-group{{ $errors->has('ss_splitter') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*SS Spliter</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="ss_splitter" class="form-control" placeholder="Cant." value="" min="0">
									</div>
								</div>

								<div class="form-group{{ $errors->has('tarjeta') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Tarjeta</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="tarjeta" class="form-control" placeholder="Cant." value="" min="0">
									</div>
								</div>

								<div class="form-group{{ $errors->has('modulo') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Modulo</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="modulo" class="form-control" placeholder="Cant." value="" min="0">
									</div>
								</div>
								<hr width="90%">
							</div>

						</div>
					</div>
					<div class="form-group col-md-12" id="formulario-inal-nodo" style="display: none;">
						@include('adminlte::instalaciones.partials.material.formInalNODO')
					</div>
					<div class="form-group col-md-12" id="formulario-inal-PAC-CC" style="display: none;">
						@include('adminlte::instalaciones.partials.material.formInaPAC-CC')
					</div>
					<div class="form-group col-md-12" id="formulario-inal-HOGAR" style="display: none;">
						@include('adminlte::instalaciones.partials.material.formInalHOGAR')
					</div>
					<div class="box-footer">
						<button type="submit" id ="btnAgregar" class="btn btn-success pull-right" disabled><i class="fa fa-floppy-o"></i>  Agregar</button>
					</div>
					@else
					<div class="box-body" id="formularioCableado">

						<div class="row" >
							<div class="form-group{{ $errors->has('serial_ont') ? ' has-error' : '' }} col-xs-12 col-md-4">
								<label>Serial ONT</label>
								<input type="text" class="form-control" name="serial_ont" placeholder="Serial ONT"value=""  maxlength="20" required autocomplete="off">
								<span class="help-block"></span>
							</div>

							<div class="form-group{{ $errors->has('coordenadas') ? ' has-error' : '' }} col-xs-12 col-md-4">
								<label>Coordenadas</label>
								<div class="input-group input-group">
									<input type="text" name="coordenadas" id="coordenadas" placeholder="Coordenadas" class="form-control" required autocomplete="off">
									<span class="input-group-btn">
										<button class="btn btn-info btn-flat" type="button" onclick="getUserPosition()"><i class="fa fa-map-marker"></i> Obtener</button>
									</span>
								</div>
								<span class="help-block"></span>
							</div>
							<hr width="90%">
						</div>

						<div id="contenido_formulario" style="display:none;">
							@include('adminlte::instalaciones.partials.form')
						
							@include('adminlte::instalaciones.partials.material.form')

							@include('adminlte::instalaciones.partials.evidencia.form')

							<div class="row  bg-blue">
								<div class="col-md-12 text-center">
									<h5>DATOS DE CONEXION FÍSICA</h5>
								</div>    
							</div>
							<br>

							<div class="row">
							
								<div class="form-group{{ $errors->has('caja') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Caja</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="caja" class="form-control" placeholder="Cant." value="" min="0"  required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('puerto') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Puerto</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="puerto" class="form-control" placeholder="Cant." value="" min="0"  required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('sp_splitter') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*SP Spliter</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="sp_splitter" class="form-control" placeholder="Cant." value="" min="0"  required>
									</div>
								</div>


								<div class="form-group{{ $errors->has('ss_splitter') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*SS Spliter</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="ss_splitter" class="form-control" placeholder="Cant." value="" min="0"  required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('tarjeta') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Tarjeta</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="tarjeta" class="form-control" placeholder="Cant." value="" min="0"  required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('modulo') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Modulo</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="modulo" class="form-control" placeholder="Cant." value="" min="0"  required>
									</div>
								</div>

							

								<hr width="90%">

								<div class="form-group{{ $errors->has('observaciones') ? ' has-error' : '' }} col-md-12">
									<label class="control-label">*Observaciones</label>
									<textarea type="number" name="observaciones" class="form-control"></textarea>								
								</div>

							</div>

						</div>
					</div>	
					<div class="box-footer">
						<button type="submit" id ="btnAgregar" class="btn btn-success pull-right" disabled><i class="fa fa-floppy-o"></i>  Agregar</button>
					</div>
					@endif
				</form>

				<div id="result"></div>
			</div>			
		</div>
	</div>
</div>

	@include('adminlte::partials.modal_show_archivos')

	@include('adminlte::instalaciones.partials.firma.add')

	@section('mis_scripts')
	<script type="text/javascript" src="/js/myfunctions/show-archivo.js"></script>
	


	<script src="/js/signature_pad.umd.js"></script>
	<script src="/js/coordenadas.js"></script>
	<script src="/js/firma.js"></script>

	<script type="text/javascript" src="/js/instalaciones/firma-tecnico.js"></script>

	<script type="text/javascript">

		var firma = null;
		var firma_tecnico = null;

		$(document).ready(function(){			
			//$('.js-signature').jqSignature({width: '550', height:'300', background: 'rgb(255 255 255 / 0%)'});
			//$('.js-signature').jqSignature({width: '550', height:'300', background: ' url("/img/fondo_firma.jpg")'});	
		});

		$('select[name="pregunta_firma"]').on('change', function(){

			switch($(this).val()){
				case 'FIRMAR':
					limpiar;

					$('#addFirma').modal('show');
					$('#addFirma').find('canvas').attr('data-tipo', 'cliente');
					$('#firmaSubir').hide();
					$('#firmaSubir').find('input[name="firma"]').attr('required', false);
					break;
				case 'SUBIR FIRMA':
					$('#firmaSubir').show();
					$('#firmaSubir').find('input[name="firma"]').attr('required', true);
					break;
				default:
					break;
			}
		});

		$('#limpiarFirma').on('click', function(){
			limpiar;
		});

		$('#guardarFirma').on('click', function(){
			var tipo_firma = $('#addFirma').find('canvas').attr('data-tipo');

			if(tipo_firma == 'tecnico'){
				firma_tecnico = signaturePad.toDataURL();
			}else if(tipo_firma == 'cliente'){
				firma = signaturePad.toDataURL();
			}
			
			$('#addFirma').modal('hide');
		});

		// Script para habilitar el botón "Agregar" dependiendo del tipo de conexión y la validación del Serial ONT 
		// (FALTA ACOMODAR QUE EL PROYECTO ==14)
		$(document).ready(function() {
			var proyectoId = $('#numeroProyecto').data('proyecto'); // Obtener ProyectoId desde el HTML
			var tipoConexion = $('#tipoConexion'); // Seleccionar el campo tipoConexion
			var serialONTInput = $('input[name="serial_ont"]');
			var btnAgregar = $('#btnAgregar');

			// Mostrar valores iniciales en consola al cargar la página
			console.log("ProyectoId (inicial):", proyectoId);
			console.log("tipoConexion (inicial):", tipoConexion.val());

			// Mantener botón deshabilitado inicialmente
			btnAgregar.prop('disabled', true);

			// Actualizar el estado del botón y mostrar en consola al cambiar tipoConexion
			tipoConexion.on('change', function() {
				var conexionSeleccionada = $(this).val();
				console.log("tipoConexion (seleccionada):", conexionSeleccionada);

				// Si ProyectoId == 14 y tipoConexion no es ONT, habilitar el botón directamente
				if (proyectoId != 14 && conexionSeleccionada !== "ONT") {
					console.log("Habilitando botón: ProyectoId == 14 y tipoConexion != ONT");
					btnAgregar.prop('disabled', false); // Habilitar el botón
				} else {
					console.log("Deshabilitando botón: Condiciones no cumplidas");
					btnAgregar.prop('disabled', true); // Mantener deshabilitado hasta validar Serial ONT
				}
			});

			// Validación del Serial ONT (solo para conexiones ONT en el ProyectoId 14 y otros proyectos)
			serialONTInput.on("focusout", function() {
				var conexionSeleccionada = tipoConexion.val(); // Obtener tipoConexion seleccionado
				console.log("Serial ONT ingresado:", $(this).val());
				console.log("tipoConexion (validación):", conexionSeleccionada);

				// Validar solo si tipoConexion == ONT y ProyectoId == 14
				if (proyectoId != 14 && conexionSeleccionada === "ONT") {
					if ($(this).val() != '' && $(this).val().length > 8) {        
						var parametros = {
							serial: $(this).val(),
							'_token': $('input:hidden[name=_token]').val()
						};

						serialONTInput.parent().find('.help-block').empty();

						$.post("/inventarios/ajax", parametros, function(data) {
							console.log("Respuesta AJAX:", data);
							$('#ont-resultado').empty();

							if (data.resultado === true) {
								serialONTInput.parent().addClass('has-success');
								serialONTInput.parent().removeClass('has-error');
								serialONTInput.attr('readonly', true);
								$('#contenido_formulario').show();
								btnAgregar.prop('disabled', false); // Habilitar el botón
							} else {
								serialONTInput.parent().addClass('has-error');
								serialONTInput.parent().find('.help-block').append("<strong>" + data.resultado + "</strong>");
								toastr.options.positionClass = 'toast-bottom-right';
								toastr.warning(data.resultado);
							}
						});
					}
				}
			});

			// Forzar una verificación inicial en caso de que tipoConexion ya tenga valor
			tipoConexion.trigger('change');
		});
		
		/*Script para ocultar el forumario de las ONTS cuando se selecciona para radio enlace del proyecto Guajira*/
					document.addEventListener('DOMContentLoaded', function () {
						var selectConexion = document.getElementById('tipoConexion');
						var formularioCableado = document.getElementById('formularioCableado');
						var camposRequeridos = formularioCableado.querySelectorAll('[required]');
						
						// Verificar si el formulario existe (solo si ProyectoId es 14)
						if (formularioCableado) {
							formularioCableado.style.display = "none";
							camposRequeridos.forEach(function (campo) {
								campo.removeAttribute('required');
							});

								selectConexion.addEventListener('change', function () {
									if (this.value === "ONT") {
										formularioCableado.style.display = "block";
										camposRequeridos.forEach(function (campo) {
											campo.setAttribute('required', 'true');
										});
									} else {
										formularioCableado.style.display = "none";
										camposRequeridos.forEach(function (campo) {
											campo.removeAttribute('required');
										});
									}
								});
							}
						});

						// Script para mostrar el formulario de instalación para CONEXION INALAMBRICA 
						// Se utiliza el evento 'DOMContentLoaded' para asegurarse de que el DOM esté completamente cargado antes de ejecutar el script
							document.addEventListener('DOMContentLoaded', function () { // Esperar a que el DOM esté listo
							document.getElementById('tipoConexion').addEventListener('change', function () {
								var estructuraField = document.getElementById('estructura-field');
								var selectedValue = this.value; // Obtiene la opción seleccionada de la conexion

								if (selectedValue == 'RADIO') { // Verifica si la CONEXION seleccionada es igual inhalambrica
									estructuraField.style.display = 'block'; // Cambia directamente a display: block
								} else {
									estructuraField.style.display = 'none'; // Oculta el campo "Estructura"
								}
							});
						});
						// Script para mostrar el formulario de instalación de NODO SECUNDARIO

						document.addEventListener('DOMContentLoaded', function () {
							var selectEstructura = document.getElementById('estructura-instalacion'); 
							var selectConexion = document.getElementById('tipoConexion'); 
							var formularioNodo = document.getElementById('formulario-inal-nodo'); 

							function verificarVisibilidad() {
								var estructuraSeleccionada = selectEstructura.value;
								var conexionSeleccionada = selectConexion.value;

								// Mostrar solo si "NODO-SECUNDARIO" está en estructura y "RADIO" en conexión
								if (estructuraSeleccionada === "NODO-SECUNDARIO" && conexionSeleccionada === "RADIO") {
									formularioNodo.style.display = "block";
								} else {
									formularioNodo.style.display = "none";
								}
							}

							// Verificar cuando cambie la estructura o la conexión
							selectEstructura.addEventListener('change', verificarVisibilidad);
							selectConexion.addEventListener('change', verificarVisibilidad);

							// Verificación inicial al cargar la página
							verificarVisibilidad();
						});

						// Script para mostrar el formulario de instalación de PAC y CC
						document.addEventListener('DOMContentLoaded', function () {
							var selectEstructura = document.getElementById('estructura-instalacion'); 
							var selectConexion = document.getElementById('tipoConexion'); 
							var formularioCableado = document.getElementById('formulario-inal-PAC-CC'); 

							function verificarVisibilidad() {
								var estructuraSeleccionada = selectEstructura.value;
								var conexionSeleccionada = selectConexion.value;

								// Mostrar solo si "PAC-CC" está en estructura y "RADIO" en conexión
								if (estructuraSeleccionada === "PAC-CC" && conexionSeleccionada === "RADIO") {
									formularioCableado.style.display = "block";
								} else {
									formularioCableado.style.display = "none";
								}
							}

							// Verificar cuando cambie la estructura o la conexión
							selectEstructura.addEventListener('change', verificarVisibilidad);
							selectConexion.addEventListener('change', verificarVisibilidad);

							// Verificación inicial al cargar la página
							verificarVisibilidad();
						});

						document.addEventListener('DOMContentLoaded', function () { 
							var selectEstructura = document.getElementById('estructura-instalacion'); 
							var selectConexion = document.getElementById('tipoConexion'); 
							var formularioHogar = document.getElementById('formulario-inal-HOGAR'); 

							function verificarVisibilidad() {
								var estructuraSeleccionada = selectEstructura.value;
								var conexionSeleccionada = selectConexion.value;

								if (estructuraSeleccionada === "HOGARES" && conexionSeleccionada === "RADIO") {
									formularioHogar.style.display = "block"; // Mostrar el formulario
								} else {
									formularioHogar.style.display = "none"; // Ocultarlo si no cumple ambas condiciones
								}
							}

							// Verificar cuando cambie la estructura o la conexión
							selectEstructura.addEventListener('change', verificarVisibilidad);
							selectConexion.addEventListener('change', verificarVisibilidad);

							// Verificar al cargar la página
							verificarVisibilidad();
						});

						// Script para mostrar el formulario de instalación para CONEXION CABLEADA

						document.addEventListener('DOMContentLoaded', function () { 
							var selectEstructura = document.getElementById('tipoConexion'); 
							var formularioCableado = document.getElementById('form-cableado'); 

							selectEstructura.addEventListener('change', function () {
								var selectedValue = this.value;

								if (selectedValue === "CABLEADO") {
									formularioCableado.style.display = "block"; // Mostrar el formulario
								} else {
									formularioCableado.style.display = "none"; // Ocultarlo si no es HOGAR
								}
							});

							// Verificación inicial al cargar la página
							if (selectEstructura.value !== "CABLEADO") {
								formularioCableado.style.display = "none";
							}
						});		
	</script>
	

	@endsection
@endsection