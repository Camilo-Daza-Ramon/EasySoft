function traer_nota(id){
	$('#productos_txt').empty();
	$('#estado_txt').empty();

	$.get('/notas/'+id, null).done(function(data){
		$('#tipo_nota_txt').text(data.datos['tipo_nota']);
		$('#concepto_txt').text(data.datos['tipo_concepto']);
		$('#tipo_operacion_txt').text(data.datos['tipo_operacion']);
		$('#tipo_negociacion_txt').text(data.datos['tipo_negociacion']);
		$('#tipo_medio_pago_txt').text(data.datos['tipo_medio_pago']);
		$('#total_txt').text('$'+parseFloat(data.datos['valor_total'] - ((data.datos['descuento'] / 100) * data.datos['valor_total'])).toFixed(2));
		$('#fecha_txt').text(data.datos['fecha_expedision']);
		$('#dian_txt').text(data.datos['numero_nota_dian']);
		$('#descuento_txt').text(data.datos['descuento']);
		$('#motivo_txt').text(data.datos['motivo_descuento']);

		if(data.datos['reportada']){
			$('#estado_txt').append('<span class="text-success"><i class="fa fa-check"></i> REPORTADA</span>');
		}
		else{
			$('#estado_txt').append('<span class="text-danger">SIN REPORTAR</span>');
		}

		var i = 1;
		$.each(data.productos, function(index,objProducto){
			$('#productos_txt').append('<tr> <td>'+i+'</td> <td>'+objProducto.concepto+'</td> <td>'+objProducto.cantidad+'</td> <td>$'+parseFloat(objProducto.valor_unidad).toFixed(2)+'</td> <td>'+objProducto.iva+'%</td> <td class="text-right">$'+parseFloat(objProducto.valor_total).toFixed(2)+'</td> </tr>')
			i+=1;
		});


	    
	    $('#showNota').modal('show');
	  }).fail(function(e){
	  	toastr.options.positionClass = 'toast-bottom-right';
	    toastr.error(e.statusText);	      
	});
}