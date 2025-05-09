@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1> <i class="fa fa-edit"></i> Agregar Instalacion</h1>
@endsection

@section('mis_styles')

<style>
	#jq-signature-canvas-1 {
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
					<h3 class="box-title"><i class="fa fa-user"></i> Datos Infraestructura</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive">
					<table class="table">
						<tbody>
							<tr>
								<th>Nombre</th>
								<td>
									@permission('infraestructura-ver')
									<a href="{{route('infraestructuras.show', $infra->id)}}">{{$infra->nombre}}</a>
									@else
									{{$infra->id}}
									@endpermission
								</td>
							</tr>
							<tr>
								<th>Categoría</th>
								<td>{{$infra->categoria}}</td>
							</tr>
							<tr>
								<th>Tipo de Categoría</th>
								<td>{{$infra->tipo_categoria}}</td>
							</tr>

							<tr>
								<th>Direccion</th>
								<td>
									<a href="{{'https://maps.google.com/?q='.trim($infra->latitud).','.trim($infra->longitud)}}">
										{{$infra->direccion}} - {{$infra->municipio->departamento->NombreDelDepartamento}}
									</a>
								</td>
							</tr>


							<tr>
								<th>Proyectos</th>
								<td>
									@foreach($infra->proyectos as $proyecto)
									{{$proyecto->NumeroDeProyecto}} |
									@endforeach
								</td>
							</tr>
							<tr>
								<th>Estado</th>
								<td>{{$infra->estado}}</td>
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



	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border bg-blue">
					<h3 class="box-title">Datos de la instalación</h3>
				</div>
				<form id="form-instalacion-infra" action="{{route('instalaciones.store.infra', $infra->id)}}" method="post">
					{{csrf_field()}}
					<div class="box-body">

						<div class="row">
							<div class="form-group{{ $errors->has('codigo') ? ' has-error' : '' }} col-md-4  col-xs-12">
								<label>*Código del Insumo</label>
								<select id="codigo" class="form-control" name="codigo">
									<option value="">Elija un insumo</option>
									@foreach($insumos as $insumo)
									<option value="{{$insumo->InsumoId}}">{{$insumo->Codigo}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group{{ $errors->has('serial') ? ' has-error' : '' }} col-md-4 col-xs-12">
								<label>*Serial</label>
								<input type="text" class="form-control" name="serial" required>
							</div>

							<div class="form-group{{ $errors->has('coordenadas') ? ' has-error' : '' }} col-xs-12 col-md-4">
								<label>*Coordenadas</label>
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
							@include('adminlte::instalaciones.partials.infra.form')

							@include('adminlte::instalaciones.partials.material.form')

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
										<input type="number" name="caja" class="form-control" placeholder="Cant." value="" min="0" required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('puerto') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Puerto</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="puerto" class="form-control" placeholder="Cant." value="" min="0" required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('sp_splitter') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*SP Spliter</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="sp_splitter" class="form-control" placeholder="Cant." value="" min="0" required>
									</div>
								</div>


								<div class="form-group{{ $errors->has('ss_splitter') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*SS Spliter</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="ss_splitter" class="form-control" placeholder="Cant." value="" min="0" required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('tarjeta') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Tarjeta</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="tarjeta" class="form-control" placeholder="Cant." value="" min="0" required>
									</div>
								</div>

								<div class="form-group{{ $errors->has('modulo') ? ' has-error' : '' }} col-md-3">
									<label class="control-label col-xs-7 col-md-12">*Modulo</label>
									<div class="col-xs-5 col-md-12 mb-2">
										<input type="number" name="modulo" class="form-control" placeholder="Cant." value="" min="0" required>
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
						<button type="submit" id="btnAgregar" class="btn btn-success pull-right" disabled><i class="fa fa-floppy-o"></i> Agregar</button>
					</div>

				</form>

				<div id="result"></div>
			</div>
		</div>
	</div>
</div>

@include('adminlte::partials.modal_show_archivos')

@include('adminlte::instalaciones.partials.firma.add')

@section('mis_scripts')

<script type="text/javascript">
	const form = $('#form-instalacion-infra');
	form.find('select[name=codigo]').on("change", function() {
		form.find('input[name="serial"]').val("");
		form.find('input[name="serial"]').parent().removeClass('has-error');
		form.find('input[name="serial"]').parent().removeClass('has-success');
		form.find('input[name="serial"]').attr('readonly', false);
		form.find('#btnAgregar').attr('disabled', true);
		$('#contenido_formulario').hide();

	})
	form.find('input[name="serial"]').on("focusout", function() {

		const codigoInsumo = form.find('select[name=codigo]').val();
		const serial = $(this).val();

		if (codigoInsumo != '') {

			var parametros = {
				serial: serial,
				codigo_insumo: codigoInsumo,
				'_token': $('input:hidden[name=_token]').val()
			};

			$.post("/inventarios/validar/ajax", parametros, function(data) {

				if (data.resultado == true) {
					form.find('input[name="serial"]').parent().addClass('has-success');
					form.find('input[name="serial"]').parent().removeClass('has-error');
					form.find('input[name="serial"]').attr('readonly', true);
					$('#contenido_formulario').show();
					form.find('#btnAgregar').attr('disabled', false);

				} else {
					form.find('input[name="serial"]').parent().addClass('has-error');
					form.find('input[name="serial"]').parent().find('.help-block').append("<strong>" + data.resultado + "</strong>");

					toastr.options.positionClass = 'toast-bottom-right';
					toastr.warning(data.resultado);
				}
			});
		}
	});
</script>

<script type="text/javascript" src="/js/myfunctions/show-archivo.js"></script>



<script src="/js/signature_pad.umd.js"></script>
<script src="/js/coordenadas.js"></script>
<script src="/js/firma.js"></script>

<script type="text/javascript" src="/js/instalaciones/firma-tecnico.js"></script>

<script type="text/javascript">
	var firma = null;
	var firma_tecnico = null;

	$(document).ready(function() {
		//$('.js-signature').jqSignature({width: '550', height:'300', background: 'rgb(255 255 255 / 0%)'});
		//$('.js-signature').jqSignature({width: '550', height:'300', background: ' url("/img/fondo_firma.jpg")'});	
	});

	$('select[name="pregunta_firma"]').on('change', function() {

		switch ($(this).val()) {
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

	$('#limpiarFirma').on('click', function() {
		limpiar;
	});

	$('#guardarFirma').on('click', function() {
		var tipo_firma = $('#addFirma').find('canvas').attr('data-tipo');

		if (tipo_firma == 'tecnico') {
			firma_tecnico = signaturePad.toDataURL();
		} else if (tipo_firma == 'cliente') {
			firma = signaturePad.toDataURL();
		}

		$('#addFirma').modal('hide');
	});

	$('input[name="serial"]').on("focusout", function() {

		if ($(this).val() != '' && $(this).val().length > 8) {

			var parametros = {
				serial: $(this).val(),
				'_token': $('input:hidden[name=_token]').val()
			};

			$('input[name="serial"]').parent().find('.help-block').empty();

			$.post("/inventarios/ajax", parametros, function(data) {
				$('#ont-resultado').empty();

				if (data.resultado == true) {
					$('input[name="serial"]').parent().addClass('has-success');
					$('input[name="serial"]').parent().removeClass('has-error');
					$('input[name="serial"]').attr('readonly', true);
					$('#contenido_formulario').show();
					$('#btnAgregar').attr('disabled', false);

				} else {
					$('input[name="serial"]').parent().addClass('has-error');
					$('input[name="serial"]').parent().find('.help-block').append("<strong>" + data.resultado + "</strong>");



					toastr.options.positionClass = 'toast-bottom-right';
					toastr.warning(data.resultado);
				}
			});
		}

	});

	let fibra_desde = $('input[name="fibra_drop_desde"]');
	let fibra_hasta = $('input[name="fibra_drop_hasta"]');

	const total_fibra = () => {
		if ((fibra_desde.val().length > 0 && fibra_desde.val() != '') && (fibra_hasta.val().length > 0 && fibra_hasta.val() != '')) {
			const total = (fibra_desde.val() - fibra_hasta.val());

			if (total > 0) {
				$('.total_fibra').text(total + ' mts').addClass('text-success').removeClass('text-danger');
			} else {
				$('.total_fibra').text(total + ' mts').addClass('text-danger').removeClass('text-success');
			}

		} else {
			$('.total_fibra').text(0 + ' mts');
		}
	}

	$('#form-instalacion-infra').on('submit', function(event) {
		event.preventDefault();

		var f = $(this);
		var formData = new FormData(this);

		if ($('select[name="pregunta_firma_tecnico"]').val() == 'FIRMAR') {
			formData.append('firma_tecnico', firma_tecnico);
		} else if ($('select[name="pregunta_firma_tecnico"]').val() == 'SUBIR FIRMA') {
			formData.append('firma_tecnico', $('input[name="firma_tecnico"]')[0].files[0]);
		}

		$('#result').removeClass('overlay').empty();
		$('#result').addClass('overlay').append('<i class="fa fa-refresh fa-spin"></i>');
		
		$.ajax({
				url: "/instalaciones/store/infraestructura/"+"{{$infra->id}}",
				type: "post",
				dataType: "json",
				data: formData,
				cache: false,
				contentType: false,
				processData: false
			})
			.done(function(res) {

				if (res['resultado'] == 'success') {
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.success(res['mensaje']);

					setTimeout(() => {
						location.replace("/instalaciones/instalar/infraestructura");
					}, "3000");


				} else {

					$('#result').removeClass('overlay').empty();

					toastr.options.positionClass = 'toast-bottom-right';
					toastr.error(res['mensaje']);

				}
			}).fail(function(jqXHR, textStatus, errorThrown) {
				$('#result').removeClass('overlay').empty();

				if (jqXHR.status == 422) {

					var objeto = JSON.parse(jqXHR.responseText);

					$.each(objeto, function(index, respuestaObj) {
						var padre = $('[name="' + index + '"]').parent();
						padre.removeClass('has-success').addClass('has-error');
						padre.find('.help-block').text(respuestaObj)
						//padre.append('<span class="text-danger">' + respuestaObj +'</span>');
					});

					toastr.options.positionClass = 'toast-bottom-right';
					toastr.error("Corrija los campos");
				} else {
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.error(errorThrown);
				}
			});
	});
</script>


@endsection
@endsection