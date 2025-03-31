$('#editMeta').on('show.bs.modal', function (event) {
	var a = $(event.relatedTarget) // Button that triggered the modal
    id = a.data('id');

    var modal = $(this);

    $("#form_editar_meta").attr('action', '');
	modal.find('#nombre').val('');
	modal.find("#fecha_inicio").val('');
	modal.find("#fecha_fin").val('');
	modal.find("#descripcion").val('');
	modal.find("#total_accesos").val('');
	modal.find("#fecha_aprobacion_interventoria").val('');
	modal.find("#fecha_aprobacion_supervision").val('');
	modal.find('select[name=estado]').attr('selectedIndex', 0);
	

	$.get('/metas/'+id+'/edit').done(function(data){

		$("#form_editar_meta").attr('action',"/metas/" + id);
		
		modal.find('#nombre').val(data.meta['nombre']);
		modal.find('#fecha_inicio').val(data.meta['fecha_inicio']);
		modal.find('#fecha_fin').val(data.meta['fecha_fin']);
		modal.find("#descripcion").val(data.meta['descripcion']);
		modal.find('#total_accesos').val(data.meta['total_accesos']);
		modal.find('#fecha_aprobacion_interventoria').val(data.meta['fecha_aprobacion_interventoria']);
		modal.find('#fecha_aprobacion_supervision').val(data.meta['fecha_aprobacion_supervision']); 				
		modal.find('select[name=estado] option[value="'+data.meta["estado"]+'"]').prop("selected", true);
				        

	}).fail(function(e){
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(e.statusText);       
    });
});