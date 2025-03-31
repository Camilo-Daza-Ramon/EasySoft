function traer_respuesta(id){
	$('#respuestas_txt').empty();
	$('#estado1_txt').empty();
	$('#observaciones_txt').empty();
	$('#solicitud_vista').empty();

	$.get('/respuestas/'+id, null).done(function(data){
		$('#nombre_campaña_txt').text(data.datos['nombre_campaña']);
		$('#tipo_campaña_txt').text(data.datos['tipo_campaña']);
		$('#nombre_cliete_txt').text(data.datos['nombre_cliete']);
		$('#correo_cliente_txt').text(data.datos['correo_cliente']);
        $('#apellido_cliete_txt').text(data.datos['apellido_cliente']);
		$('#documento_txt').text(data.datos['documento']);		
		
		$('#estado1_txt').append('<span class="text-dark">'+data.datos['estado']+'</span>');

		if(data.datos['solicitud'] != null){			
			$('#solicitud_vista').append('<a href="/solicitudes/'+data.datos['solicitud']+'" target="_blank"><i class="fa fa-eye"></i> Ver</a>');
		}else{
			$('#solicitud_vista').text('SIN INFORMACION');
		}

		if(data.respuestas !== 0){
			var i = 0;
			$('#respuestas_txt').empty();
			$.each(data.respuestas, function(index,objRespuesta){
				const campo = objRespuesta.campo.nombre;
				let respuesta = null;

				$('#respuestas').parent().show();

				if(objRespuesta.campo.tipo == 'ARCHIVO'){
					
					respuesta = `<ul class="mailbox-attachments clearfix">
									<li>
										<a href="/storage/${objRespuesta.respuesta}" target="_blanck">
											<span class="mailbox-attachment-icon has-img"><img src="/storage/${objRespuesta.respuesta}" alt="Attachment"></span>
										</a>
									</li>  
								</ul>`;

				}else{
					respuesta = objRespuesta.respuesta;
				}
				
				if(campo != 'Motivo_atencion' && campo != 'Categoria_atencion' ){
					i+=1;
					$('#respuestas_txt').append(`
					<tr>
						<td class="text-uppercase bg-gray" >${campo}</td>  
						<td>${respuesta}</td>
						<td>${data.usuario}</td> 
						<td>${objRespuesta.created_at}</td>
					</tr>`);
				}
					
			});

			if( i == 0){
				$('#respuestas').parent().hide();
			}
		}else{
			$('#respuestas').parent().hide();
		}

		if(data.observaciones !== 0){
			var i = 1;
			$.each(data.observaciones, function(index,observacion	){
				const usuario = data.observacion_usuario[index];
				$('#observaciones').parent().show();			
				$('#observaciones_txt').append('<tr> <td>'+i+'</td> <td>'+observacion.observacion+'</td><td>'+usuario+'</td> <td>'+observacion.created_at+'</td></tr>')
				i+=1;
			});
		}else{
			$('#observaciones').parent().hide();
		}	    
	    $('#showRespuesta').modal('show');

	}).fail(function(e){
	  	toastr.options.positionClass = 'toast-bottom-right';
	    toastr.error(e.statusText);	      
	});
}