@extends('adminlte::layouts.app')

@section('contentheader_title')
	<h1> <i class="fa fa-plus"></i> Registrar Atencion al Cliente</h1>
@endsection

@section('other-notifications')
	<div class="alert alert-warning" style="display:none;" id="alerta-ticket">
	</div>
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
			<div class="col-md-7">
				<div class="box box-info">
					<form id="form-atencion" action="{{route('atencion-clientes.store')}}" method="post">
						<div class="box-header with-border bg-blue">
							<h3 class="box-title"> Registro de Información</h3>
						</div>
						<div class="box-body">
							{{csrf_field()}}
							<div class="row">
								<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-7">
									<label>*Nombre de quien llama:</label>
									<input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre Completo" value="{{old('nombre')}}" autocomplete="off" required>
								</div>
								<div id="form-group-cedula" class="form-group{{ $errors->has('cedula') ? ' has-error' : '' }} col-md-5">
									<label>*Documento Titular Servicio:</label>
									<input type="number" id="cedula" name="cedula" class="form-control" placeholder="Documento" value="{{old('cedula')}}" min="0" max="9999999999" autocomplete="off" required>
								</div>
								<div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-md-6">
									<label>*Departamentos:</label>
									<select name="departamento" id="departamento" class="form-control" required>
										<option value="">Elija un departamento</option>
										@foreach($departamentos as $departamento)
											<option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-md-6">
									<label>*Municipio:</label>
									<select name="municipio" id="municipio" class="form-control" required></select>
								</div>
							</div>

							<div class="row">
								<div class="form-group{{ $errors->has('categorias') ? ' has-error' : '' }} col-md-6">
									<label>*Categorias Atención: </label>
									<select name="categorias" id="categorias" class="form-control" required>
										<option value="">Elija una categoria</option>
										@foreach($categorias as $categoria)
										<option value="{{$categoria->categoria}}">{{$categoria->categoria}}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group{{ $errors->has('motivo') ? ' has-error' : '' }} col-md-6">
									<label>*Motivo Atención: </label>
									<select name="motivo" id="motivo" class="form-control" required></select>
								</div>

								<div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
									<label>*Descripción Solicitud: </label>
									<textarea class="form-control" name="descripcion" required>{{old('descripcion')}}</textarea>
								</div>

								<div class="form-group{{ $errors->has('solucion') ? ' has-error' : '' }} col-md-12">
									<label>*Solución: </label>
									<textarea class="form-control" name="solucion" id="solucion_atencion" required>{{old('solucion')}}</textarea>
								</div>

								<div id="panel-pqr-ticket">
									<div class="form-group{{ $errors->has('cun') ? ' has-error' : '' }} col-md-4">
										<label># CUN</label>
										<input type="text" name="cun" class="form-control" placeholder="CUN" value="{{old('cun')}}" autocomplete="off" >
									</div>

									<div class="form-group{{ $errors->has('ticket') ? ' has-error' : '' }} col-md-4">
										<label># Ticket:</label>
										<input type="text" name="ticket" class="form-control" placeholder="Ticket" value="{{old('ticket')}}" autocomplete="off" >
									</div>

									<div class="form-group{{ $errors->has('mantenimiento') ? ' has-error' : '' }} col-md-4">
										<label># Mantenimiento:</label>
										<input type="text" name="mantenimiento" class="form-control" placeholder="Mantenimiento" value="{{old('mantenimiento')}}" autocomplete="off" >
									</div>
								</div>
								<div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-4">
									<label>*Estado: </label>
									<select name="estado" id="estado" class="form-control" required>
										<option value="">Elija una Opcion</option>
										<option value="ATENDIDO">ATENDIDO</option>
										<option value="ABANDONO">ABANDONO</option>
									</select>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">
							<button type="submit" id="btn_enviar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Guardar</button>
						</div>
					</form>
				</div>
			</div>

			<div class="col-md-5">
				<div class="box box-primary" id="panel-cliente" style="display:none;">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title"><i class="fa fa-user"></i> Datos Cliente</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-condensed">
							<tbody>
								<tr>
									<th>Identificacion</th>
									<td id="text-cedula"></td>
								</tr>
								<tr>
									<th>Nombre</th>
									<td id="text-nombre"></td>
								</tr>
								<tr>
									<th>Correo</th>
									<td id="text-correo"></td>
								</tr>
								<tr>
									<th>Direccion</th>
									<td id="text-direccion"></td>
								</tr>
								<tr>
									<th>Telefono</th>
									<td id="text-telefono"></td>
								</tr>
								<tr>
									<th>Proyecto</th>
									<td id="text-proyecto"></td>
								</tr>
								<tr>
									<th>Estado</th>
									<td id="text-estado"></td>
								</tr>
								<tr>
									<th>Total Deuda</th>
									<td id="text-total-deuda"></td>
								</tr>
							</tbody>
						</table>
						<a id="link-cliente" class="btn btn-default btn-block" target="_blank"> <i class="fa fa-eye"></i> Ver Cliente</a>
					</div>
				</div>

				<div class="box box-info" id="panel-otras-acciones">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title"> <i class="fa fa-external-link-square"></i> Otras Acciones</h3>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-3">
								<button type="button" class="btn btn-app bg-purple" id="btn-pqr" data-toggle="modal" data-target="#addPqr"> <i class="fa fa-bullhorn"></i> PQRS </button>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-app bg-olive" id="link-mantenimiento" disabled> <i class="fa fa-wrench"></i> MANTENIMIENTO </button>
							</div>

							<div class="col-md-3">
								<button type="button" id="btn-solicitud" class="btn btn-app bg-blue" data-toggle="modal" data-target="#addSolicitud" disabled> <i class="fa fa-calendar-plus-o"></i> SOLICITUD </button>
							</div>

						</div>
					</div>
				</div>


				<div class="box box-info" id="panel-solicitud" style="display:none;">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title"> <i class="fa fa-calendar"></i> Solicitud</h3>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12 text-center">
								<h3>
									<span id="txt-limite"></span>
								</h3>
								<h4>
									<i class="fa fa-calendar-check-o"></i> Fecha limite de respuesta
								</h4>
								<h4>
									<i class="fa fa-phone"></i> <b>Contacto:</b> <span id="txt-celular-contacto"></span>
								</h4>
								<h4>
									<i class="fa fa-envelope-o"></i> <b>Correo:</b> <span id="txt-correo-contacto"></span>
								</h4>
								<h4>
									<i class="fa fa-sun-o"></i> <b>Jornada:</b> <span id="txt-jornada"></span>
								</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@include('adminlte::soporte-tecnico.tickets.partials.add')
	@include('adminlte::atencion-clientes.partials.add-solicitud')
	@include('adminlte::pqr.partials.add')

	@section('mis_scripts')
		<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
		<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
		<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios2.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/tickets/add.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/atencion-cliente/variables.js')}}"></script>
		<script type="text/javascript">
			const ultimo_dia = "{!!(intval(date('d')) > 25 )? date('Y-m-t', strtotime(date('Y-m-d'). ' + 1 month')) : date('Y-m-t')!!}";
		</script>	
    	<script type="text/javascript" src="{{asset('js/pqr/add.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/atencion-cliente/motivos.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/atencion-cliente/cliente.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/atencion-cliente/solicitud.js')}}"></script>

		<script type="text/javascript">
			const checkbox = document.getElementById('crear_pqr');
			const checkbox_visita = document.getElementById('agendar_visita');
			var correo = document.getElementById("text-correo");
			var correo_solicitud = document.getElementById("txt-correo-contacto");

			
			$(function () {
				$("input[name=celular_contacto]").inputmask({"mask": "(999) 999-9999"});
			});
			
			$('#addPqr').on('show.bs.modal', function (event) {

				$('input[name=identificacion]').val($('#cedula').val());
				$('input[name=nombre_pqr]').val($('#nombre').val());
				$('input[name=correo_pqr]').val(correo.textContent);
				$('textarea[name=hechos]').val($('textarea[name=descripcion]').val());
				$('textarea[name=solucion]').val($('#solucion_atencion').val());

			});

			$('#link-mantenimiento').on('click',function (event) {
				const hora = document.getElementById("hora");
				hora.value = new Date().toLocaleTimeString();

				if($('textarea[name=descripcion]').val() != ''){
						$('#hechos_pqr').val($('textarea[name=descripcion]').val());
					}

				if($('textarea[name=solucion]').val() != ''){
					$('#solucion').val($('textarea[name=solucion]').val());
				}	

				if($('textarea[name=solucion]').val() != ''){
					$('#descripcion_ticket').val($('textarea[name=solucion]').val());
				}

				if($('#nombre').val() == ''){					
					checkbox.disabled = true;
				}else{
					checkbox.disabled = false;
				}
				
			})

			checkbox_visita.addEventListener('change', function(){
				if(checkbox.checked){
					checkbox.checked = false;
					$('#datos_pqr').hide();	 

					if($('textarea[name=solucion]').val() != ''){
						$('#hechos_ticket').val($('textarea[name=solucion]').val());
					}

					$('#descripcion_ticket_').show();
					$('#descripcion_pqr').hide();
					$('#descripcion_ticket_').attr('required',true);
					$('#descripcion_pqr').attr('required',false);

						
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.warning('No puede crear PQR y agendar visita al mismo tiempo');
					
				}

				

			});
 
			checkbox.addEventListener('change', function() {

				if (checkbox.checked) {
					
					$('#descripcion_ticket_').hide();
					$('#descripcion_ticket_').attr('required',false);
					$('#descripcion_ticket').attr('required',false);


					$('#descripcion_pqr').show();
					$('#descripcion_pqr').attr('required',true);


					if(checkbox_visita.checked){
						checkbox_visita.checked = false ;
						
						if($('textarea[name=solucion]').val() != ''){
							$('#descripcion_ticket').val($('textarea[name=solucion]').val());
						}					

						toastr.options.positionClass = 'toast-bottom-right';
						toastr.warning('No puede crear PQR y agendar visita al mismo tiempo');
					}

					if($('textarea[name=descripcion]').val() != ''){
						$('#hechos_pqr').val($('textarea[name=descripcion]').val());
					}

					if($('textarea[name=solucion]').val() != ''){
						$('#solucion').val($('textarea[name=solucion]').val());
					}	
						 
					$('#nombre_pqr').val($('#nombre').val());					
					$('#municipio_pqr').val($('#municipio').val());
					$('#departamento_pqr').val($('#departamento').val());
					$('#hechos_ticket').val($('textarea[name=descripcion]').val());
					$('#solucion').val($('textarea[name=solucion]').val());
					$('#datos_pqr').show();

					if($('#descripcion_ticket').val()!=''){
						console.log($('#descripcion_ticket').val());
						$('#solucion').val($('#descripcion_ticket').val());	
					}
				
									
				} else {
                    $('#datos_pqr').hide();
					$('#descripcion_ticket_').show();
					$('#descripcion_pqr').hide();
					
					if($('textarea[name=solucion]').val() != ''){
						$('#hechos_ticket').val($('textarea[name=solucion]').val());
					}

				}
			});
 

			$('input[name=cedula]').blur(function() {

				limpiar();
				$('#municipio').empty();
				$('#departamento option:eq(0)').prop('selected', true);

				consultar_cliente($(this).val());
			});

			$("#form_ticket").on("submit",function(e){ 

				if($('#solucion').val() != ''){
					$('#descripcion_ticket').val($('#solucion').val());
				}

				document.getElementById("hora").value = "";

				toastr.options.positionClass = 'toast-bottom-right';

				event.preventDefault();
				var f = $(this);
				var formData = new FormData(this)

				for(var i = 0; i < pruebas.length; i++){
					for ( var key in pruebas[i]){
						formData.append('pruebas['+i+']['+key+']', pruebas[i][key]);
					}
				}

				formData.append('cliente_id', cliente_id);
				

				$('#crear_ticket').attr('disabled',true);
				$('#icon-guardar').removeClass('fa-floppy-o');
				$('#icon-guardar').addClass('fa-refresh fa-spin');


				$.ajax({
					url: "/tickets",
					type: "post",
					dataType: "json",
					data: formData,
					cache: false,
					contentType: false,
					processData: false
				}).done(function(res){

					if(res['tipo_mensaje'] == 'success'){                        
						toastr.success(res['mensaje']);

						ticket.val(res.data.ticket);
						$('input[name=cun]').val(res.data.pqr);

						if (checkbox.checked) { 
							$('textarea[name=descripcion]').val($('#hechos_pqr').val());
							$('textarea[name=solucion]').val($('#solucion').val());	
						}else{
							$('textarea[name=solucion]').val($('#descripcion_ticket').val());
						}

						$('#form-ticket').empty();
						$('#footer_ticket').hide();

						if(res.data.ticket != null){
							$('#form-ticket').append('<h2>Se creó el ticket <b>#'+res.data.ticket+'</b><br></h2>');
						}

						if(res.data.pqr != null){
							$('#form-ticket').append('<h2>Se creó la pqr  <b>#'+res.data.pqr+'</b></h2>');
						}

					}else{

						$('#crear_ticket').attr('disabled',false);
						$('#icon-guardar').removeClass('fa-refresh fa-spin');
						$('#icon-guardar').addClass('fa-floppy-o');

						toastr.error(res['mensaje']); 
					}

				}).fail(function( jqXHR, textStatus, errorThrown ) {					
					$('#crear_ticket').attr('disabled',false);
					$('#icon-guardar').removeClass('fa-refresh fa-spin');
					$('#icon-guardar').addClass('fa-floppy-o');

					toastr.error(errorThrown);

					if(jqXHR.status == 422){

						var objeto = JSON.parse(jqXHR.responseText);

						$.each(objeto, function(index, respuestaObj){			                   
							var padre = $('[name="' + index+'"]').parent();
							padre.removeClass('has-success').addClass('has-error');
							padre.find('.help-block').text(respuestaObj)
						});

						toastr.options.positionClass = 'toast-bottom-right';
						toastr.error("Corrija los campos");
					}else{
						toastr.error(errorThrown);
					}                      
				});				
			});


			$('#form-atencion').submit(function( event ) {

				var url = $(this).attr('action');

				event.preventDefault();
				btn_enviar.attr("disabled", true);
				btn_enviar.find('i').removeClass('fa-floppy-o');
				btn_enviar.find('i').addClass('fa-refresh fa-spin');

				if (jornada != null && celular != null && fecha_limite != null) {

				}else if(ticket.val().length > 0 || mantenimiento.val().length > 0 || cun.val().length > 0){

				}else{
					btn_enviar.attr("disabled", false);
					btn_enviar.find('i').removeClass('fa-refresh fa-spin');
					btn_enviar.find('i').addClass('fa-floppy-o');
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.error('Debe generar algun tipo de soporte de la atención!');
					return null;
				}

				var parametros = {
					'cedula': $('#cedula').val(),
					'nombre': $('input[name=nombre]').val(),
					'cliente_id' : cliente_id,
					'motivo' : $('#motivo option:selected').val(),
					'municipio' : municipio,
					'descripcion' : $('textarea[name=descripcion]').val(),
					'solucion' : $('textarea[name=solucion]').val(),
					'estado' : $('#estado').val(),

					'jornada' : jornada,
					'celular' : celular,
					'correo' : correo_solicitud.textContent,
					'fecha_limite' : fecha_limite,

					'ticket' : ticket.val(),
					'mantenimiento' : mantenimiento.val(),
					'pqr' : cun.val(),
					'_token' : $('input:hidden[name=_token]').val()
				};

				$.post(url, parametros)
				.done(function(data){
					if(data[0] == 'success'){
						toastr.options.positionClass = 'toast-bottom-right';
						toastr.success(data[1]);
						location.reload();
					}else{
						toastr.options.positionClass = 'toast-bottom-right';
						toastr.error(data[1]);
						btn_enviar.attr("disabled", false);
						btn_enviar.find('i').removeClass('fa-refresh fa-spin');
						btn_enviar.find('i').addClass('fa-floppy-o');
					}
				}).fail(function(e){
					console.log(e);
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.error(e.statusText);

					btn_enviar.attr("disabled", false);
					btn_enviar.find('i').removeClass('fa-refresh fa-spin');
					btn_enviar.find('i').addClass('fa-floppy-o');
				});
			});
		</script>
	@endsection
@endsection
