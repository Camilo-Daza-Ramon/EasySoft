function buscar_cliente(){	
	var tabla = $('#tabla_clientes');
	var documento = modal.find('#documento');

	modal.find('#tabla_clientes').empty();
	modal.find('input[name=cliente_id]').val("");

	if (documento.val().length > 0) {

		var parametros = {
			cedula : documento.val(),
			'_token' : $('input:hidden[name=_token]').val()
		}

		$.post('/clientes/ajax', parametros).done(function(data){

			if (Object.keys(data).length > 0) {

				modal.find('#btn_guardar').attr('disabled', false);

				tabla.append('<tr><td>'+data.cedula+'</td><td>'+data.nombre+'</td><td>'+data.municipio+'</td><td>'+data.departamento+'</td><td>'+data.estado+'</td></tr>');
				
				modal.find('input[name=cliente_id]').val(data.id);

			}else{
				cliente_id = null;
				modal.find('#btn_guardar').attr('disabled', true);
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
}