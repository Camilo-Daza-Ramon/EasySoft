$('#categorias').on('change', function(){
	traer_motivos($(this).val());
	console.log(typeof btn_solicitud);
	if (typeof btn_solicitud != 'undefined') {
		btn_solicitud.attr('disabled',true);
	}
});

$('#categorias').on('blur', function(){
	traer_motivos($(this).val());
	console.log(typeof btn_solicitud);
	if (typeof btn_solicitud != 'undefined') {
		btn_solicitud.attr('disabled',true);
	}});

$('#motivo').on('change', function(){
	var solicitud = parseInt($('#motivo option:selected').attr('data-solicitud'));
	console.log(typeof btn_solicitud);

	if (typeof btn_solicitud !== 'undefined') {

		if (solicitud) {
			btn_solicitud.attr('disabled',false);
		}else{
			btn_solicitud.attr('disabled',true); 
		}
	}
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
