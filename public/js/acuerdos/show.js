
function traer_acuerdo(id){
	$('#cuotas_txt').empty();
	$('#estado_cliente_txt').empty();
	$('#estado_acuerdo_txt').empty();

	

	$.get('/acuerdos/'+id, null).done(function(data){
		$('#nombre_cliente_txt').text(data.datos['nombre_cliente']+data.datos['apellidos_cliente']);
		$('#identificacion_txt').text(data.datos['identificacion']);
		$('#contacto_txt').text(data.datos['contacto']);
        $('#correo_txt').text(data.datos['correo']);
		$('#proyecto_txt').text(data.datos['proyecto']);
		if(data.datos['estado_cliente'] == 'ACTIVO'){
			$('#estado_cliente_txt').append('<span class="text-success"><i class="fa fa-check"></i>'+data.datos['estado_cliente']+'</span>');
		}
		else{ 
			$('#estado_cliente_txt').append('<span class="text-dark">'+data.datos['estado_cliente']+'</span>');
		}
		$('#tarifa_txt').text(formato_dinero(data.datos['tarifa']));
        $('#deuda_txt').text(formato_dinero(data.datos['deuda']));
		$('#tipo_descuento_txt').text(data.datos['tipo_descuento']);
		$('#valor_perdonar_txt').text(formato_dinero(data.datos['valor_perdonado']));
		if(data.datos['tipo_descuento'] == 'porcentual'){
			$('#descuento_txt').text(data.datos['descuento']+'%');
		}else{
			$('#descuento_txt').text(formato_dinero(data.datos['descuento']));
		}
		if(data.datos['estado_acuerdo'] == 'ACTIVO'){
			$('#estado_acuerdo_txt').append('<span class="text-success"><i class="fa fa-check"></i>'+data.datos['estado_acuerdo']+'</span>');
		}
		else{ 
			$('#estado_acuerdo_txt').append('<span class="text-dark">'+data.datos['estado_acuerdo']+'</span>');
		}
		$('#descripcion_acuerdo_txt').text(data.datos['descripcion']);				

		if(data.cuotas !== 0){
			$.each(data.cuotas, function(index,objcuota){
				$('#cuotas').parent().show();				
				$('#cuotas_txt').append('<tr><td class="text-uppercase bg-gray" >'+objcuota.fecha_pago+'</td>  <td>'+objcuota.cuota+'</td><td>'+formato_dinero(objcuota.valor_pagar)+'</td> <td>'+objcuota.estado+'</td></tr>')					
			});
		}else{ 
			$('#cuotas').parent().hide();
		}
	    
	    $('#showCuotas').modal('show');
	  }).fail(function(e){
	  	toastr.options.positionClass = 'toast-bottom-right';
	    toastr.error(e.statusText);	      
	});
}