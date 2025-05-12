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

							<tr>
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
						<select name="pregunta_firma_tecnico" class="form-control" >
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

	<div class="row">		
		<div class="col-md-12">
			<div class="box box-info">				
				<div class="box-header with-border bg-blue">
					<h3 class="box-title">Datos de la instalación</h3>
				</div>
				
				@if ($cliente->proyecto->NumeroDeProyecto === 'PROYECTO GUAJIRA')
					<div class="box-body">
						<div class="row col-md-12">
							<div class="form-group col-md-3">
								<label for="TipoConexion">Conexión</label>
								<select class="form-control" id="tipoConexion" name="tipo_conexion">
									<option value="">Elija una conexión</option>
									<option value="INALAMBRICO">Conexión Inalámbrica</option>
									<option value="ONT">Fibra</option>
									<option value="CABLEADO">Conexión Cableada</option>
								</select>
							</div>
				
							<div class="form-group col-md-3" id="contenedorEstructura" style="display: none;">
								<label for="Estructura">Estructura</label>
								<select class="form-control" id="estructura" name="estructura">
									<option value="">Elija una estructura</option>
									<option value="NODO_PRIMARIO">Nodo Primario</option>
									<option value="NODO_SECUNDARIO">Nodo Secundario</option>
									<option value="PAC_CC">PAC-CC</option>
									<option value="HOGAR">Hogar</option>
								</select>
							</div>
							{{-- FORMULARIO CABLEADO --}}
							<div class="form-group col-md-3" id="formulario-cableado" style="display: none; width:100%; margin-left:20px;">
								@include('adminlte::instalaciones.partials.material.formCableado')
							</div>

							{{-- FORMULARIO ONTS --}}
							<div class="form-group col-md-3" id="formulario-ONT" style="display: none; width:100%; margin-left:20px;">
								@include('adminlte::instalaciones.partials.material.formONTS')
							</div>

							{{-- FORMULARIOS DE CADA ESTRUCTURA  --}}
							<div class="form-group col-md-3" id="formulario-nodo-primario" style="display: none; width:100%; margin-left:20px;">
								@include('adminlte::instalaciones.partials.material.formInalNODOPRIMARIO')
							</div>
							<div class="form-group col-md-3" id="formulario-nodo-secundario" style="display: none; width:100%; margin-left:20px;">
								@include('adminlte::instalaciones.partials.material.formInalNODOSECUNDARIO')
							</div>
							<div class="form-group col-md-3" id="formulario-PAC-CC" style="display: none; width:100%; margin-left:20px;">
								@include('adminlte::instalaciones.partials.material.formInaPAC-CC')
							</div>
							<div class="form-group col-md-3" id="formulario-HOGAR" style="display: none; width:100%; margin-left:20px;">
								@include('adminlte::instalaciones.partials.material.formInalHOGAR')
							</div>
						</div>
					</div>
				@else
					@include('adminlte::instalaciones.partials.material.formONTS')
				@endif
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
					$('#firmaSubir').find('input[name="firma"]').attr('', false);
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

		$('input[name="serial_ont"]').on("focusout", function(){

			if($(this).val() != '' && $(this).val().length > 8) {		

				var parametros = {
					serial : $(this).val(),
					'_token' : $('input:hidden[name=_token]').val()             
				};

				$('input[name="serial_ont"]').parent().find('.help-block').empty();

				$.post("/inventarios/ajax",parametros, function(data){
					$('#ont-resultado').empty();

					if (data.resultado == true) {
						$('input[name="serial_ont"]').parent().addClass('has-success');
						$('input[name="serial_ont"]').parent().removeClass('has-error');						
						$('input[name="serial_ont"]').attr('readonly',true);
						$('#contenido_formulario').show();
						$('#btnAgregar').attr('disabled',false);
						
					}else{
						$('input[name="serial_ont"]').parent().addClass('has-error');
						$('input[name="serial_ont"]').parent().find('.help-block').append("<strong>"+data.resultado+"</strong>");

						
						
						toastr.options.positionClass = 'toast-bottom-right';
						toastr.warning(data.resultado);
					}
				});
			}

		});

		let fibra_desde = $('input[name="fibra_drop_desde"]');
		let fibra_hasta = $('input[name="fibra_drop_hasta"]');

		const total_fibra = () => {
			if((fibra_desde.val().length > 0 && fibra_desde.val() != '') && (fibra_hasta.val().length > 0 && fibra_hasta.val() != '')){
				const total = (fibra_desde.val() - fibra_hasta.val());

				if(total > 0){
					$('.total_fibra').text(total + ' mts').addClass('text-success').removeClass('text-danger');
				}else{
					$('.total_fibra').text(total + ' mts').addClass('text-danger').removeClass('text-success');
				}
				
			}else{
				$('.total_fibra').text(0 + ' mts');
			}
		}

		$('#form-instalacion').on('submit', function(event) {
			event.preventDefault();

			var f = $(this);
			var formData = new FormData(this);
			
			if ($('select[name="pregunta_firma"]').val() == 'FIRMAR') {
				formData.append('firma', firma);
			}

			if ($('select[name="pregunta_firma_tecnico"]').val() == 'FIRMAR') {
				formData.append('firma_tecnico', firma_tecnico);
			}else if ($('select[name="pregunta_firma_tecnico"]').val() == 'SUBIR FIRMA'){
				formData.append('firma_tecnico', $('input[name="firma_tecnico"]')[0].files[0]);
			}

			$('#result').removeClass('overlay').empty();			
			$('#result').addClass('overlay').append('<i class="fa fa-refresh fa-spin"></i>');


			$.ajax({
				url: "/instalaciones",
				type: "post",
				dataType: "json",
				data: formData,
				cache: false,
				contentType: false,
				processData: false
			})
			.done(function(res){

				if(res['resultado'] == 'success'){                        
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.success(res['mensaje']);

					setTimeout(() => {
						location.replace("/instalaciones/instalar");
					}, "3000");


				}else{

					$('#result').removeClass('overlay').empty();

					toastr.options.positionClass = 'toast-bottom-right';
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

					toastr.options.positionClass = 'toast-bottom-right';
					toastr.error("Corrija los campos");
				}else{
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.error(errorThrown);
				}                      
			});
		});
	</script>

	<script> //Este Script manejara la funcionabilidad de los formularios para la Guajira de todos los
		document.getElementById("tipoConexion").addEventListener("change", function () {
			const tipoConexion = this.value; // Capturar el valor seleccionado

			// Referencias a todos los formularios
			const contenedorEstructura = document.getElementById("contenedorEstructura");
			const contenedorFormCableado = document.getElementById("formulario-cableado");
			const contenedorFormONT = document.getElementById("formulario-ONT");
			const contenedorNodoPrimario = document.getElementById("formulario-nodo-primario");
			const contenedorNodoSecundario = document.getElementById("formulario-nodo-secundario");
			const contenedorPacCC = document.getElementById("formulario-PAC-CC");
			const contenedorHogar = document.getElementById("formulario-HOGAR");

			// Ocultar todos los formularios por defecto
			contenedorEstructura.style.display = "none";
			contenedorFormCableado.style.display = "none";
			contenedorFormONT.style.display = "none";
			contenedorNodoPrimario.style.display = "none";
			contenedorNodoSecundario.style.display = "none";
			contenedorPacCC.style.display = "none";
			contenedorHogar.style.display = "none";

			// Lógica para mostrar el formulario correspondiente
			if (tipoConexion === "INALAMBRICO") {
				contenedorEstructura.style.display = "block"; // Mostrar estructura
			} else if (tipoConexion === "CABLEADO") {
				contenedorFormCableado.style.display = "block"; // Mostrar formulario cableado
			} else if (tipoConexion === "ONT") {
				contenedorFormONT.style.display = "block"; // Mostrar formulario ONTs
			} else {
				// Si no hay selección válida, todos permanecen ocultos
			}
		});

		// Detectar cambios en el campo "Estructura"
		document.getElementById("estructura").addEventListener("change", function () {
			const tipoConexion = document.getElementById("tipoConexion").value; // Captura el tipo de conexión
			const estructura = this.value; // Captura el valor de estructura seleccionado

			// Referencias a los formularios específicos
			const contenedorNodoPrimario = document.getElementById("formulario-nodo-primario");
			const contenedorNodoSecundario = document.getElementById("formulario-nodo-secundario");
			const contenedorPacCC = document.getElementById("formulario-PAC-CC");
			const contenedorHogar = document.getElementById("formulario-HOGAR");

			// Ocultar todos los formularios de estructura por defecto
			contenedorNodoPrimario.style.display = "none";
			contenedorNodoSecundario.style.display = "none";
			contenedorPacCC.style.display = "none";
			contenedorHogar.style.display = "none";

			// Solo mostrar los formularios de estructura si el tipo de conexión es inalámbrico
			if (tipoConexion === "INALAMBRICO") {
				if (estructura === "NODO_PRIMARIO") {
					contenedorNodoPrimario.style.display = "block"; // Mostrar Nodo Primario
				} else if (estructura === "NODO_SECUNDARIO") {
					contenedorNodoSecundario.style.display = "block"; // Mostrar Nodo Secundario
				} else if (estructura === "PAC_CC") {
					contenedorPacCC.style.display = "block"; // Mostrar PAC CC
				} else if (estructura === "HOGAR") {
					contenedorHogar.style.display = "block"; // Mostrar Hogar
				}
			}
		});


		// GUARDAR LA INFORMACION DE LOS CAMPOS CONEXION Y ESTRUCTURA EN LA BD

	</script>
	@endsection
@endsection