@extends('adminlte::layouts.app')



@section('contentheader_title')
	<h1> <i class="fa fa-plus"></i> Registrar Atencion al Cliente 2</h1>
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
				<form action="{{route('atencion-clientes.store')}}" method="post">
					<input type="hidden" name="cliente_id" id="cliente_id" value="">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title"> Registro de Información</h3>
					</div>
					<div class="box-body">		          	
						{{csrf_field()}}

						<div class="row">
							<div id="form-group-medio_atencion" class="form-group{{ $errors->has('medio_atencion') ? ' has-error' : '' }} col-md-6">
								<label>*Medio de Atencion:</label>
								<select class="form-control" name="medio_atencion" required>
									<option value="">Elija una opción</option>
									@foreach($medios_atencion as $medio_atencion)
										<option value="{{$medio_atencion}}">{{$medio_atencion}}</option>
									@endforeach
								</select>
							</div>
							<div id="form-group-tipo_llamada" class="form-group{{ $errors->has('tipo_llamada') ? ' has-error' : '' }} col-md-6">
								<label>*Tipo Llamada:</label>
								<select class="form-control" name="tipo_llamada" required>
									<option value="">Elija una opción</option>									
									<option value="ENTRANTE">ENTRANTE</option>
									<option value="SALIENTE">SALIENTE</option>
									<option value="NA">NA</option>
								</select>
							</div>
							<div id="form-group-cedula" class="form-group{{ $errors->has('cedula') ? ' has-error' : '' }} col-md-4">
								<label>*Documento:</label>
								<input type="number" name="cedula" class="form-control" placeholder="Documento" value="{{old('cedula')}}" min="0" max="9999999999" autocomplete="off" required>
							</div>

							<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-8">
								<label>*Nombre Completo:</label>
								<input type="text" name="nombre" class="form-control" placeholder="Nombre Completo" value="{{old('nombre')}}" autocomplete="off" required>
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

                            <div class="form-group{{ $errors->has('codigo') ? ' has-error' : '' }} col-md-4">
								<label># CUN - TICKET:</label>
								<input type="text" name="codigo" class="form-control" placeholder="CUN - TICKET" value="{{old('codigo')}}" autocomplete="off" >
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
						<button type="submit" id="enviar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Guardar</button>

						
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

			<div class="box box-info" id="panel-otras-acciones" style="display:none;">				
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
							<button type="button" class="btn btn-app bg-olive" id="link-mantenimiento"> <i class="fa fa-wrench"></i> MANTENIMIENTO </button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	@include('adminlte::soporte-tecnico.tickets.partials.add')


@section('mis_scripts')

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
    var codigo = $('input[name=codigo]');

	$('#categorias').on('change', function(){
		traer_motivos($(this).val());
	});

	$('#categorias').on('blur', function(){
		traer_motivos($(this).val());
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
	        	$('select[name=motivo]').append('<option value="' + categoriasObj.id + '">' + categoriasObj.motivo + '</option>');
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
	        	$('#panel-otras-acciones').show(1000);

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
	        		codigo.val('');
	        		alerta_ticket.hide(1000);
	        		link_mantenimiento.attr('disabled', false);
		        	}else{
		        		alerta_ticket.append('<h4><i class="icon fa fa-warning"></i> Atención!</h4><p>El cliente tiene un mantenimiento abierto <a href="/mantenimientos/'+data.mantenimiento['MantId']+'" target="_black"><b>#'+ data.mantenimiento['NumeroDeTicket'] + '</b></a></p>');
		        		codigo.val(data.mantenimiento['NumeroDeTicket']);
		        		alerta_ticket.show(1000);
		        		link_mantenimiento.attr('disabled', true);
		        	}
	        	}else{
	        		alerta_ticket.append('<h4><i class="icon fa fa-warning"></i> Atención!</h4><p>El cliente ya tiene un ticket abierto <a href="/tickets/'+data.ticket['TicketId']+'" target="_black"><b>#'+ data.ticket['TicketId'] + '</b></a></p>');
	        		codigo.val(data.ticket['TicketId']);
	        		alerta_ticket.show(1000);
	        		//link_mantenimiento.removeAttr('href');
	        		link_mantenimiento.attr('disabled', true);
	        	}

	        	

	        }else{
	        	$('#panel-cliente').hide(1000);
	        	$('#panel-otras-acciones').hide(1000);	        	
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
		      codigo.val(data.ticket);

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


</script>
@endsection
@endsection