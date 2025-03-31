@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-plus"></i> Atender - Atencion al Cliente </h1>

@endsection



@section('main-content')
<div class="container-fluid spark-screen">
	<div class="alert alert-warning" style="display:none;" id="alerta-ticket">

	</div>

	<div class="row">
		<div class="col-md-7">
			<div class="box box-info">
				<form id="form-atender" action="{{route('atencion-clientes.update', $atencion->id)}}" method="post">
					<input type="hidden" name="cliente_id" id="cliente_id" value="">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title"> Registro de Información</h3>
					</div>
					<div class="box-body">
						<input type="hidden" name="_method" value="PUT">
						@include('adminlte::atencion-clientes.partials.form')
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
              <button type="button" class="btn btn-app bg-purple" id="btn-pqr" data-toggle="modal" data-target="#addPqr"> <i class="fa fa-bullhorn"></i> PQRS </button>
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

@include('adminlte::atencion-clientes.partials.add-solicitud')
@include('adminlte::pqr.partials.add')


  @section('mis_scripts')
    <script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios2.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/atencion-cliente/variables.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/atencion-cliente/motivos.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/atencion-cliente/cliente.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/atencion-cliente/solicitud.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/pqr/add.js')}}"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        buscarmunicipios(<?php echo $municipio_id; ?>);
      	traer_motivos($('#categorias option:selected').val());
      	consultar_cliente($('input[name=cedula_atencion]').val());
        $("input[name=celular_contacto]").inputmask({"mask": "(999) 999-9999"});
        municipio = "{!!$municipio_id!!}";
      });

    	$('input[name=cedula_titular]').blur(function() {
    		limpiar();
    		consultar_cliente($(this).val());
    	});

      	$('#addPqr').on('show.bs.modal', function (event) {
			$('input[name=identificacion]').val($('input[name=cedula_titular]').val());
			$('input[name=nombre_pqr]').val($('#nombre').val());
			$('input[name=correo_pqr]').val($('input[name=correo]').val());
			$('textarea[name=hechos]').val($('textarea[name=descripcion]').val());
		});

      $('#form-atender').submit(function( event ) {

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
						'cedula_atencion': $('input[name=cedula_atencion]').val(),
            'cedula_titular' : $('input[name=cedula_titular]').val(),
		        'nombre': $('input[name=nombre]').val(),
		        'cliente_id' : cliente_id,
						'motivo' : $('#motivo option:selected').val(),
						'municipio' : municipio,
						'descripcion' : $('textarea[name=descripcion]').val(),
						'solucion' : $('textarea[name=solucion]').val(),
						'estado' : $('#estado').val(),

						'jornada' : jornada,
						'celular' : celular,
						'fecha_limite' : fecha_limite,
            'correo' : correo,

						'ticket' : ticket.val(),
						'mantenimiento' : mantenimiento.val(),
						'pqr' : cun.val(),
            '_method' : 'PUT',
						'_token' : $('input:hidden[name=_token]').val()
					};

					$.post(url, parametros).done(function(data){
						if(data[0] == 'success'){
							toastr.options.positionClass = 'toast-bottom-right';
							toastr.success(data[1]);
              window.location.href = "/atencion-clientes";
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
