@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-plus"></i> Crear Novedades</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">    	
        <div class="row">
	        <div class="col-md-4">
	            <div class="box">
	                <div class="box-header bg-purple">
	                    {{csrf_field()}}
	                    <h4 class="pull-left" style="margin-right: 20px;"><i class="fa fa-user"></i> Datos Cliente</h4>       
	                </div>
	                <div class="box-body">
	                	<div class="row" id="panel-buscar">
	                		<div class="col-md-12">
	                			<div class="form-group">
	                				<label>*Identificacion Cliente:</label>
									<div class="input-group input-group-md" style="width: 250px;">
					                  <input type="number" name="documento" id="documento"  class="form-control pull-right" placeholder="Documento" value="{{old('documento')}}" min="0" max="9999999999" autocomplete="off" required>

					                  <div class="input-group-btn">
					                    <button type="button" class="btn btn-default" id="buscar"><i class="fa fa-search"></i></button>
					                  </div>
					                </div>		                            
		                        </div>
		                    </div>
	                	</div>
	                    <div class="row" id="panel-datos" style="display: none">
	                        <div class="col-md-12">
	                            <table class="table table-sm">
				        			<tbody>
				        				<tr>
				        					<th>Identificacion</th>
				        					<td id="txt-cedula">		        						
				        					</td>
				        				</tr>
				        				<tr>
				        					<th>Nombre</th>
				        					<td id="txt-nombre"></td>
				        				</tr>
				        				<tr>
				        					<th>Direccion</th>
				        					<td id="txt-direccion">				        						
				        					</td>
				        				</tr>
				        				<tr>
				        					<th>Proyecto</th>
				        					<td id="txt-proyecto"></td>
				        				</tr>		        				
				        				<tr>
				        					<th>Estado Cliente</th>
				        					<td id="txt-estado">
				        						
				        					</td>
				        				</tr>
				        			</tbody>
				        		</table>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	        </div>
	        <div class="col-md-8">
	            <div class="box">
	                <div class="box-header bg-purple">
	                    <h4 class="pull-left" style="margin-right: 20px;"><i class="fa fa-edit"></i> Datos Novedad</h4>
	                    <div class="box-tools d-flex justify-content-end">
	                        <button type="button" id="modal-button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#addConcepto" disabled><i class="fa fa-plus"></i> Agregar Concepto</button>
	                    </div>
	                </div>

	                <div class="box-body">
	                    <div class="row">
	                        <div class="col-md-12" style="margin-bottom: 10px;">
	                            <table class="table table-sm table-conceptos">                        
	                                <thead>
	                                    <tr>
	                                    	<th style="width: 10px"></th>
	                                        <th>Concepto</th>
							                <th width="100px">Cantidad</th>
							                <th width="120px">Valor Uni.</th>
							                <th width="100px">IVA</th>
							                <th width="100px">Inicio</th>
							                <th width="100px">Fin</th>
							                <th></th>
	                                    </tr>                            
	                                </thead>
	                                <tbody id="conceptos">
	                                    
	                                </tbody>
	                                <tfoot class="text-right">

	                                </tfoot>
	                            </table>
	                        </div>	                        
	                    </div>
	                </div>
	                <div class="box-footer d-flex justify-content-end">
	                    <button class="btn btn-success pull-right" id="confirmar" disabled>
		                    <span role="status" aria-hidden="true"><i class="" id="load"></i> CONFIRMAR</span>  
		                </button>
	                </div>
	            </div>
	            <div class="box" id="panel-novedades-pendientes" style="display:none;">
	                <div class="box-header bg-purple">
	                    <h4 class="pull-left" style="margin-right: 20px;"><i class="fa fa-exclamation-circle"></i> Novedades Pendientes</h4>
	                </div>

	                <div class="box-body">
	                    <div class="row">
	                        <div class="col-md-12" style="margin-bottom: 10px;">
	                            <table class="table table-sm">                        
	                                <thead>
	                                    <tr>
	                                    	<th style="width: 10px"></th>
	                                        <th>Concepto</th>
							                <th width="100px">Fecha Inicio</th>
							                <th width="120px">Fecha Fin</th>
							                <th width="100px">Ticket</th>
	                                    </tr>                            
	                                </thead>
	                                <tbody id="novedades-pendientes">
	                                    
	                                </tbody>
	                                <tfoot class="text-right">

	                                </tfoot>
	                            </table>
	                        </div>	                        
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
    </div>

    @permission('novedades-crear')
        @include('adminlte::facturacion.novedades.partials.add')  
    @endpermission

    @section('mis_scripts')
    	@permission('novedades-crear')
    	<script type="text/javascript">

    		var documento = $('#documento');
    		var cliente_id;
    		var modal_button = $('#modal-button');
    		var boton_confirmar = $('#confirmar');

    		$('#buscar').on('click', function(){
    			limpiar();

    			if (documento.val().length > 0) {

	    			var parametros = {
						cedula : documento.val(),
						'_token' : $('input:hidden[name=_token]').val()
					}

					$.post('/clientes/ajax', parametros).done(function(data){

						if (Object.keys(data).length > 0) {

							$('#panel-buscar').hide()
							$('#panel-datos').show()

							modal_button.attr('disabled', false);

							cliente_id = data.id;

							$('#txt-cedula').text(data.cedula)
							$('#txt-nombre').text(data.nombre)
							$('#txt-direccion').text(data.direccion)
							$('#txt-proyecto').text(data.proyecto)
							$('#txt-estado').text(data.estado)

							if (Object.keys(data.novedades).length > 0) {
								$('#panel-novedades-pendientes').show(1000);

								$.each(data.novedades, function(index, novedadesObj){

									$('#novedades-pendientes').append('<tr><td>'+novedadesObj.id+'</td><td>'+novedadesObj.concepto+'</td><td>'+novedadesObj.fecha_inicio+'</td><td>'+novedadesObj.fecha_fin+'</td><td>'+novedadesObj.ticket_id+'</td></tr>');

									$('select[name=concepto] option[value="'+ novedadesObj.concepto +'"]').attr('disabled', true);
	            					$('select[name=concepto] option[value="'+ novedadesObj.concepto +'"]').addClass('disabled');
								});
							}

						}else{
							cliente_id = null;
							modal_button.attr('disabled', true);
				        	toastr.options.positionClass = 'toast-bottom-right';
				    		toastr.error('Cliente no existe!');
				        }

					}).fail(function(e){
						toastr.options.positionClass = 'toast-bottom-right';
						toastr.error(e.statusText);	
					});
				}else{
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.warning("Debe ingresar una cedula válida.");
				}		
    		});
    	</script>
	    <script type="text/javascript">

			const modal = $('#addConcepto');
			const mes_anterior = "<?php echo date("Y-m",strtotime(date("Y-m-d")."- 1 month")) . '-01T00:00';?>";
	    	var cobrar = 0;
	    	$('select[name=concepto]').on('change',function(){

				if($(this).val().length > 0){
					
					let ahora = new Date();
    				ahora.setMinutes(ahora.getMinutes() - ahora.getTimezoneOffset()); // Ajustar zona horaria local
    				let fechaHoraColombia = ahora.toISOString().slice(0, 16); // Formato 'YYYY-MM-DDTHH:MM'

					cobrar = parseInt($('option:selected',this).attr('data-cobrar'));
					modal.find('select[name="unidad_medida"]').prop('selectedIndex',0);

					if($(this).val() == 'Servicio de Internet'){
						modal.find('input[name="mes"]').attr('required', true).parent().show();
						modal.find('input[name="fecha_inicio"]').val(fechaHoraColombia).attr('readonly', true);
						modal.find('input[name="fecha_fin"]').val(fechaHoraColombia).attr('readonly', true);
					}else{
						modal.find('input[name="mes"]').attr('required', false).val('').parent().hide();
						modal.find('input[name="fecha_inicio"]').val('').attr('readonly', false).attr('min', mes_anterior).attr('max', fechaHoraColombia);
						modal.find('input[name="fecha_fin"]').val('').attr('readonly', false).attr('min', mes_anterior).attr('max', fechaHoraColombia);
					}
	    		
					if (cobrar) {
						modal.find('input[name="cantidad"]').attr('disabled',false).val('');
						modal.find('input[name="valor_unidad"]').attr('disabled',false).val('');
						modal.find('input[name="iva"]').attr('disabled',false).val('');

					}else{
						modal.find('input[name="cantidad"]').attr('disabled',true).val('');
						modal.find('input[name="valor_unidad"]').attr('disabled',true).val('');
						modal.find('input[name="iva"]').attr('disabled',true).val('');
					}

				}
	    		
	    	});
	    </script>


        <script type="text/javascript">
    
            
            var j = 1;
            var conceptos = [];
	        var hoy = Date.now();
            
            function addConcepto(){

				const modal = $('#addConcepto');

            	var concepto;
	            var cantidad = 0;
	            var valor_unidad = 0;
	            var iva = 0;
	            var total = 0;
	            var unidad_medida = $('select[name=unidad_medida] option:selected').val();
	            
	            var fecha_inicio = $('input[name=fecha_inicio]').val();
	            var fecha_fin = $('input[name=fecha_fin]').val();
	            var ticket = $('input[name=tickets]').val();
	            
				mes = $('input[name="mes"]').val();

	            concepto = (mes.length > 0) ? $('select[name=concepto] option:selected').val() + nombre_mes(mes) : $('select[name=concepto] option:selected').val();
				console.log(concepto);
				
	            if ($('select[name=concepto] option:selected').val().length > 0) {

	            	if ($('select[name=unidad_medida] option:selected').val().length > 0) {
		            	if (fecha_inicio.length > 0) {			            	

							if (fecha_fin.length > 0) {
								if (fecha_inicio > fecha_fin) {	            				
									toastr.options.positionClass = 'toast-bottom-right';
									toastr.warning('La fecha de inicio no puede ser mayor que la fecha fin.');
									return null;
								}
							}

							if (cobrar) {
								cantidad = $('input[name=cantidad]').val();
								valor_unidad = $('input[name=valor_unidad]').val();
								iva = $('input[name=iva]').val();

								if (cantidad.length <= 0) {
									toastr.options.positionClass = 'toast-bottom-right';
									toastr.warning('Debe ingresar la cantidad');
									return null;
								}

								if(valor_unidad.length <= 0){
									toastr.options.positionClass = 'toast-bottom-right';
									toastr.warning('Debe ingresar un valor');
									return null;
								}

								if(iva.length <= 0){
									toastr.options.positionClass = 'toast-bottom-right';
									toastr.warning('Debe ingresar el iva');
									return null;
								}
							}


							var item = {};

							item.id = j;
							item.concepto = concepto;

							

							item.cantidad = cantidad;
							item.valor_unidad = valor_unidad;
							item.unidad_medida = unidad_medida;
							item.iva = iva;
							item.valor_iva = (cantidad * valor_unidad) * (iva / 100);
							item.total = ((cantidad * valor_unidad) * (iva / 100)) + (cantidad * valor_unidad);
							item.fecha_inicio = fecha_inicio;
							item.fecha_fin = fecha_fin;
							item.cobrar = cobrar;
							item.ticket = ticket;

							$('#conceptos').append('<tr id="concepto-' + j +'"><td>' + j + '</td><td>' + concepto + '</td><td>' + cantidad + ' ' + unidad_medida +'</td><td>$' + valor_unidad + '</td><td>'+iva+'%</td><td>'+fecha_inicio+'</td><td>'+fecha_fin+'</td><td><a class="btn text-danger" onclick="removeConcepto('+ j +')"><i class="fa fa-remove"></i></a></td></tr>');

							boton_confirmar.attr('disabled', false);


							conceptos.push(item);

							$('select[name=concepto]').children("option:selected").attr('disabled', true);
							$('select[name=concepto]').children("option:selected").addClass('disabled');

							limpiar();
							
							j = j+1;
			            	
			            }else{
			            	toastr.options.positionClass = 'toast-bottom-right';
							toastr.warning("Debe elegir una fecha.");
			            }
			        }else{
		            	toastr.options.positionClass = 'toast-bottom-right';
						toastr.warning("Debe elegir una unidad de medida.");
		            }
	                
	                  
	            }else{
	            	toastr.options.positionClass = 'toast-bottom-right';
					toastr.warning("Debe elegir un concepto.");
	            }
            }

            function removeConcepto(id){

            	var nombre_concepto = $('#concepto-' + id).find('td').eq(1).text();
              if (confirm("Desea Eliminar el concepto " + nombre_concepto)) {


                $('#concepto-' + id).remove();
                //se reasigna la variable sin el array que contiene el id que se esta eliminando.
                
                conceptos = $.grep(conceptos, function(e){
                	return e.id != id;
                });
                console.log(id);

                //Quita el disable de la opcion del producto que se esta eliminado            
	            $('select[name=concepto] option[value="'+ nombre_concepto +'"]').attr('disabled', false);
	            $('select[name=concepto] option[value="'+ nombre_concepto +'"]').removeClass('disabled');

                if (Object.keys(conceptos).length == 0) {
                	boton_confirmar.attr('disabled', true);
                }
                
              }
            }

           
        	function limpiar(){
            	$('select[name=concepto]').prop('selectedIndex',0);
                $('input[name=cantidad]').val('');
                $('input[name=valor_unidad]').val('');
                $('input[name=iva]').val('');
				$('input[name=mes]').val('');
            } 

            boton_confirmar.on('click',function(){

	            $('#load').addClass('fa fa-refresh fa-spin');
	            $(this).attr('disabled', true);

	            $('input').removeClass('is-invalid');

	            if (cliente_id == 0) {
	            	toastr.options.positionClass = 'toast-bottom-right';
	                toastr.error("Debe buscar el cliente.");

	                $('#load').removeClass('fa fa-refresh fa-spin');
		            $(this).attr('disabled', false);
	            }else{
	            	if (conceptos.length > 0){
	            		
		            	var parametros = {
		                    'conceptos' : conceptos,
		                    'cliente_id' : cliente_id,
		                    '_token' : $('input:hidden[name=_token]').val()
		                };

			            $.post( "{{route('novedades.store')}}",parametros, function(data) {

			               toastr.options.positionClass = 'toast-bottom-right';

			               if (data.tipo_mensaje == 'error') {
			               		toastr.error(data.respuesta);
			               }else{
			               		toastr.success(data.respuesta);
			               		location.reload();
			               }
			               

			                

			            })
			            .fail(function(data) {

			                //var datos = $.parseJSON(data);
			                toastr.options.positionClass = 'toast-bottom-right';
			                toastr.error("Error al crear la novedad.");

			                console.log(data.responseJSON);
			                
			                $.each(data.responseJSON, function(index, errObj){

			                    var array = index.split('.');

			                    if (array.lenght == 0) {
			                        toastr.error(index + ' ' + errObj);
			                        $('#'+index).addClass('is-invalid');
			                    }else{
			                        toastr.error(index + ' ' + errObj);
			                        $('#'+array[1]).addClass('is-invalid');
			                    }                   
			                    
			                });

			                $('#load').removeClass('fa fa-refresh fa-spin');
			                $('#confirmar').attr('disabled', false);
			            });
				        
			        }else{
			        	toastr.options.positionClass = 'toast-bottom-right';
	                	toastr.error("Debe agregar conceptos.");

	                	 $('#load').removeClass('fa fa-refresh fa-spin');
		            	$(this).attr('disabled', false);
			        }
	            }

	                       
	        });

			function nombre_mes(fechaString) {
				if(fechaString.length > 0){

					console.log(fechaString);
					let fecha = new Date(fechaString + '-01T00:00:00');
					let formato = new Intl.DateTimeFormat("es-ES", { month: "long", year: "numeric" });
					return ' ' + (formato.format(fecha)).replace(" de "," ").toUpperCase();
				}				
			}

			function formatoMesAno(fechaString) {
				let fecha = new Date(fechaString);
				let formato = new Intl.DateTimeFormat("es-ES", { month: "long", year: "numeric" });
				return formato.format(fecha);
			}
        </script>
        @endpermission
    @endsection
@endsection