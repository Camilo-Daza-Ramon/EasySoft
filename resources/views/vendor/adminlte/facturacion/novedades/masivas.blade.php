@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-plus"></i> Crear Novedades Masivas</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">    	
        <div class="row">
	        <div class="col-md-8">
	            <div class="box">
	                <div class="box-header bg-blue">
	                    
	                    <h4 class="pull-left" style="margin-right: 20px;"><i class="fa fa-user"></i> Datos</h4>       
	                </div>
	                <div class="box-body">
	                	<form action="{{route('novedades.agregar_masivas')}}" method="post">
	                		{{csrf_field()}}
	                		<div class="row">
	                			<div class="form-group col-md-6">
	                				<label>Conceptos</label>
						            <select class="form-control " name="concepto" required="true">
						                <option value="">Elija una opción</option>
						                <option value="Compensación por indisponibilidad">Compensación por indisponibilidad</option>
							            <option value="Ajustes por falta de servicio">Ajustes por falta de servicio</option>
										<option value="otro">Otro</option>
						            </select>
	                			</div>

								<div class="form-group col-md-6" style="display:none">
	                				<label>Otro</label>
	                				<input class="form-control" type="text" name="otro">
	                			</div>

							</div>

							<div class="row">

	                			<div class="form-group col-md-4">
	                				<label>Fecha Inicio</label>
	                				<input class="form-control" type="datetime-local" name="fecha_inicio" required="true">
	                			</div>
	                			<div class="form-group col-md-4">
	                				<label>Fecha Fin</label>
	                				<input class="form-control" type="datetime-local" name="fecha_fin" required="true">
	                			</div>
	                			<div class="form-group col-md-4">
	                				<label>*Forma de aplicación</label>
	                				<select name="forma_aplicacion" class="form-control" required>
	                					<option value="">Elija una opción</option>
	                					<option value="DESCONTAR">DESCONTAR</option>
	                					<option value="COBRAR">COBRAR</option>

	                				</select>
	                			</div>
	                		</div>
	                		<div class="row">
	                			<div class="form-group col-md-3">
	                				<label>Cantidad</label>
	                				<input class="form-control" type="number" name="cantidad" required="true" step="0.001">
	                			</div>
	                			<div class="form-group col-md-3">
	                				<label>Unidad de medida</label>
	                				<select class="form-control" name="unidad_medida" required="true">
						                <option value="">Elija una opción</option>
						                <option value="MINUTOS">MINUTOS</option>
						                <option value="HORAS">HORAS</option>
						                <option value="DIAS">DIAS</option>
						                <option value="MES">MES</option>
						                <option value="UNIDAD">UNIDAD</option>
						            </select>
	                			</div>
	                			<div class="form-group col-md-3">
	                				<label>Valor. Uni.</label>
	                				<input class="form-control" type="number" name="valor_unidad" value="0" required="true" step="0.01">
	                			</div>
	                			<div class="form-group col-md-3">
					              <label>% IVA</label>
					              <input type="number" name="iva" class="form-control " placeholder="% IVA" required>
					            </div>

	                		</div>
	                		<div class="row">
	                			<div class="form-group col-md-12">
	                				<label>Cedulas</label>
	                				<textarea class="form-control" name="cedulas" required="true" placeholder="Separadas por coma ,"></textarea>
	                			</div>
	                			<div class="form-group col-md-4">
					              <label># Ticket</label>
					              <input type="number" name="ticket" class="form-control " placeholder="id ticket" >
					            </div>
					            <div class="form-group col-md-4">
					              <label># mantenimiento</label>
					              <input type="number" name="mantenimiento" class="form-control " placeholder="id mantenimiento" >
					            </div>
	                		</div>
	                		
	                			<button type="submit" class="btn btn-primary"> Agregar</button>
	                	
	                	</form>
	                </div>
	            </div>
	            
	        </div>	        
	    </div>
    </div>

    @section('mis_scripts')

	<script>
		$('select[name=concepto]').on('change', function() {
			if($(this).val().length > 0 ) {

				if($(this).val() == 'otro'){
					$('input[name=otro]').parent().show();
				}else{
					$('input[name=otro]').val('');
					$('input[name=otro]').parent().hide();
				}

			}
		});
	</script>
    	
    @endsection
@endsection