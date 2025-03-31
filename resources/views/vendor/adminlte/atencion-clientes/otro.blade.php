@extends('adminlte::layouts.app')



@section('contentheader_title')
	<h1> <i class="fa fa-plus"></i> Registrar Atencion al Cliente ---- JONATHAN </h1>
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
				<form id="form-atencion" action="{{route('auditorias.clientes.store')}}" method="post">
					<input type="hidden" name="cliente_id" id="cliente_id" value="">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title"> Registro de Información</h3>
					</div>
					<div class="box-body">		          	
						{{csrf_field()}}

						<div class="row">
							<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-7">
								<label>*Nombre de quien llama:</label>
								<input type="text" name="nombre" class="form-control" placeholder="Nombre Completo" value="{{old('nombre')}}" autocomplete="off" required>
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
								<select name="municipio" id="municipio" class="form-control" required>
									
								</select>
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
								<select name="motivo" id="motivo" class="form-control" required>
																		
								</select>
							</div>

							<div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
                             	<label>*Descripción Solicitud: </label>
                                <textarea class="form-control" name="descripcion" required>{{old('descripcion')}}</textarea>
                            </div>

                            <div class="form-group{{ $errors->has('solucion') ? ' has-error' : '' }} col-md-12">
                             	<label>*Solución: </label>
                                <textarea class="form-control" name="solucion" required>{{old('solucion')}}</textarea>
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
							<a class="btn btn-app bg-purple" id="link-pqr" target="_blank">
				                <i class="fa fa-bullhorn"></i> PQRS
				            </a>				            
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


@section('mis_scripts')

	<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
	<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

	<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios2.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/tickets/add.js')}}"></script>
	<script type="text/javascript">

		var cedula = $('#text-cedula');
	    var nombre = $('#text-nombre');
	    var direccion = $('#text-direccion');
	    var telefono = $('#text-telefono');
	    var proyecto = $('#text-proyecto');
	    var estado = $('#text-estado');
	    var total_deuda = $('#text-total-deuda');
	    var link_cliente = $('#link-cliente');
	    var link_pqr = $('#link-pqr');
	    
	    var cliente_id = $('#cliente_id');
	    var departamento = $('#departamento');
	    var municipio = $('#municipio');
	    var alerta_ticket = $('#alerta-ticket');

	    var ticket = $('input[name=ticket]');
	    var mantenimiento = $('input[name=mantenimiento]');
	    var cun = $('input[name=cun]');

	    var jornada = null;
	    var fecha_limite = null;
	    var celular = null;

	    var btn_solicitud = $('#btn-solicitud');
		var btn_add_solicitud = $('#btn-add-solicitud');
		const fecha_hoy = $('input[name=fecha_limite]').val();
		const ultimo_dia = "{!!(intval(date('d')) > 25 )? date('Y-m-t', strtotime(date('Y-m-d'). ' + 1 month')) : date('Y-m-t')!!}";

		var btn_enviar = $('#btn_enviar');

	    $(function () {
			$("input[name=celular_contacto]").inputmask({"mask": "(999) 999-9999"});
		});
		

		$('#motivo').on('change', function(){
			
			var solicitud = parseInt($('#motivo option:selected').attr('data-solicitud'));

			if (solicitud) {
				btn_solicitud.attr('disabled',false);
			}else{
				btn_solicitud.attr('disabled',true);
			}

		});

		$('#addSolicitud').on('show.bs.modal', function (event) {  

		  var motivo = $('#motivo option:selected');
		  var solicitud = parseInt(motivo.attr('data-solicitud'));
		  var condicional = parseInt(motivo.attr('data-condicional'));
		  var limite = parseInt(motivo.attr('data-limite'));
		  var fecha_limite = $('input[name=fecha_limite]');

		  if (solicitud) {
		  	if (condicional) {
		  		fecha_limite.val(ultimo_dia);
		  	}else{
		  		var modal = $(this);

		  		var date = new Date(fecha_hoy);
    			date.setDate(date.getDate() + limite + 1);
		  		fecha_limite.val(date.toLocaleDateString('en-CA'))
		  	}
		  }		  
		});

		btn_add_solicitud.on('click',function(){

			jornada = $('select[name=jornada]').val();
			fecha_limite = $('input[name=fecha_limite]').val();
			celular = $('input[name=celular_contacto]').val();

			if (jornada.length > 0 && fecha_limite.length > 0 && (celular.replace(/_/g,'')).length == 14) {

				ticket.val('');
				mantenimiento.val('');
				cun.val('');

				$(this).attr('disabled',true);

				$('#panel-pqr-ticket').hide();

				$('#categorias').attr('disabled',true);
				$('#motivo').attr('disabled',true);
				
				$('#txt-jornada').text(jornada);
				$('#txt-limite').text(fecha_limite);
				$('#txt-celular-contacto').text(celular);

				$('#panel-otras-acciones').hide(1000);
				$('#link-mantenimiento').attr('disabled', true);
				$('#panel-solicitud').show(1000);

				$('#addSolicitud').modal('toggle');
			}else{
				toastr.options.positionClass = 'toast-bottom-right';
		    	toastr.warning('Compruebe que todos los campos esten diligenciados correctamente!');
			}
		});

		$('#categorias').on('change', function(){
			traer_motivos($(this).val());
			btn_solicitud.attr('disabled',true);
		});

		$('#categorias').on('blur', function(){
			traer_motivos($(this).val());
			btn_solicitud.attr('disabled',true);
		});

		function traer_motivos(categoria){
			var parametros = {
		        categoria : categoria,	        
		        '_token' : $('input:hidden[name=_token]').val()             
		    };

		    $.post('/motivos-atencion/ajax', parametros).done(function(data){
		        $('#motivo').empty();
		        $('#motivo').append('<option value="">Elija un motivo</option>');
		        $.each(data, function(index, categoriasObj){
		        	$('select[name=motivo]').append('<option value="' + categoriasObj.id + '" data-solicitud="' + categoriasObj.solicitud + '" data-limite="' + categoriasObj.tiempo_limite + '" data-condicional="' + categoriasObj.condicional + '">' + categoriasObj.motivo + '</option>');
		        });
		    });	
		}

		$('input[name=cedula]').blur(function() {

			limpiar();
			$('#municipio').empty();
			$('#departamento option:eq(0)').prop('selected', true);

			var parametros = {
		        cedula : $(this).val(),	        
		        '_token' : $('input:hidden[name=_token]').val()             
		    };

		    $.post('/clientes/ajax', parametros).done(function(data){
		        $('#motivo').empty();

		        if (Object.keys(data).length > 0) {
		        	$('#panel-cliente').show(1000);
		        	$('#link-mantenimiento').attr('disabled', false);

		        	cliente_id.val(data.id);
		        	cedula.text(data.cedula);
		        	nombre.text(data.nombre);
		        	direccion.text(data.direccion);
		        	telefono.text(data.telefono);
		        	proyecto.text(data.proyecto);
		        	estado.text(data.estado);
		        	total_deuda.text(data.total_deuda);
		        	$('#departamento option[value='+data.departamento_id+']').prop('selected', true);
		        	buscarmunicipios(data.municipio_id);

		        	link_cliente.attr('href', '/clientes/'+data.id);
		        	link_pqr.attr('href', 'http://190.0.11.107/ATencionClientes/ClientesPQR/Add-ClientesPQR.aspx?Clienteid='+data.id);

		        	alerta_ticket.empty();

		        	console.log(data.mantenimiento);

		        	if (data.ticket === null) {

		        		if (data.mantenimiento === null) {
		        		ticket.val('');
		        		$('input[name=mantenimiento]').val('')
		        		alerta_ticket.hide(1000);
		        		link_mantenimiento.attr('disabled', false);
			        	}else{
			        		alerta_ticket.append('<h4><i class="icon fa fa-warning"></i> Atención!</h4><p>El cliente tiene un mantenimiento abierto <a href="/mantenimientos/'+data.mantenimiento['MantId']+'" target="_black"><b>#'+ data.mantenimiento['NumeroDeTicket'] + '</b></a></p>');
			        		alerta_ticket.show(1000);
			        		link_mantenimiento.attr('disabled', true);
			        		mantenimiento.val(data.mantenimiento['MantId']);
			        	}
		        	}else{
		        		alerta_ticket.append('<h4><i class="icon fa fa-warning"></i> Atención!</h4><p>El cliente ya tiene un ticket abierto <a href="/tickets/'+data.ticket['TicketId']+'" target="_black"><b>#'+ data.ticket['TicketId'] + '</b></a></p>');
		        		ticket.val(data.ticket['TicketId']);
		        		alerta_ticket.show(1000);
		        		//link_mantenimiento.removeAttr('href');
		        		link_mantenimiento.attr('disabled', true);
		        	}

		        	

		        }else{
		        	$('#panel-cliente').hide(1000);        	
		        	toastr.options.positionClass = 'toast-bottom-right';
		    		toastr.error('Cliente no existe!');
		        }
		        
		    });
			
		});

		departamento.on('change', function(){
			buscarmunicipios(null);
		});

		function limpiar(){
			cedula.text('');
	    	nombre.text('');
	    	direccion.text('');
	    	proyecto.text('');
	    	estado.text('');
	    	total_deuda.text('');
	    	cliente_id.val('');
	    	link_cliente.attr('href', '#');
	    	link_pqr.attr('href', '#');
	    	//link_mantenimiento.attr('href', '#');
		}

		btn_crear_ticket.on('click', function(){
			toastr.options.positionClass = 'toast-bottom-right';

			var parametros = {
				'cliente_id' : cliente_id.val(),
				'pruebas' : pruebas,
				'canal_atencion' : $('#canal_atencion option:selected').val(),
				'tipo_falla' : $('#tipo_falla option:selected').val(),
				'prioridad' : $('#prioridad').val(),
				'descripcion' : $('#descripcion').val(),
				'hora_apertura' : hora_apertura,
				'escalar_mantenimiento' : $('input[name="escalar_mantenimiento"]').is(':checked'),
				'_token' : $('input:hidden[name=_token]').val()
			};

			btn_crear_ticket.attr('disabled',true);
		    $('#icon-guardar').removeClass('fa-floppy-o');
		    $('#icon-guardar').addClass('fa-refresh fa-spin');

			$.post('/tickets', parametros).done(function(data){

				console.log(data);

			    if(data.tipo_mensaje == 'error'){
			      toastr.error(data.respuesta);

			      btn_crear_ticket.attr('disabled',false);
			      $('#icon-guardar').removeClass('fa-refresh fa-spin');
			      $('#icon-guardar').addClass('fa-floppy-o');
			    }else{
			      toastr.success(data.respuesta);
			      ticket.val(data.ticket);
			      btn_solicitud.attr('disabled',true);

			      $('#form-ticket').empty();

			      $('#form-ticket').append('<h2>Se creó el ticket <b>#'+data.ticket+'</b></h2>');

			    }


			}).fail(function(e){
			    btn_crear_ticket.attr('disabled',false);
			    $('#icon-guardar').removeClass('fa-refresh fa-spin');
			    $('#icon-guardar').addClass('fa-floppy-o');
			    
			    toastr.error(e.statusText);
			});
		});


		//btn_enviar.on('click',function(){
		$('#form-atencion').submit(function( event ) {

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
		        'cliente_id' : cliente_id.val(),
				'motivo' : $('#motivo option:selected').val(),
				'municipio' : municipio.val(),
				'descripcion' : $('textarea[name=descripcion]').val(),
				'solucion' : $('textarea[name=solucion]').val(),
				'estado' : $('#estado').val(),

				'jornada' : jornada,
				'celular' : celular,
				'fecha_limite' : fecha_limite,

				'ticket' : ticket.val(),
				'mantenimiento' : mantenimiento.val(),
				'pqr' : cun.val(),			
		        '_token' : $('input:hidden[name=_token]').val()             
		    };

		    $.post('/auditorias/clientes', parametros).done(function(data){	

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