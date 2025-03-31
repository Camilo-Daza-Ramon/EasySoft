$('#editCosto').on('show.bs.modal', function (event) {
	var a = $(event.relatedTarget) // Button that triggered the modal
    id = a.data('id');

    var modal = $(this);

    $("#form_editar_costo").attr('action', '');
    modal.find('select[name=concepto]').attr('disabled', true);
    modal.find('select[name=concepto]').attr('selectedIndex', 0);
    modal.find('select[name=iva]').attr('selectedIndex', 0);
	modal.find('#valor').val('');
	modal.find("#descripcion").val('');
	

	$.get('/proyectos-costos/'+id+'/edit').done(function(data){

		$("#form_editar_costo").attr('action',"/proyectos-costos/" + id);
		modal.find('select[name=concepto] option[value="'+data.costo["concepto"]+'"]').prop("selected", true);
		modal.find('select[name=iva] option[value="'+data.costo["iva"]+'"]').prop("selected", true);		
		modal.find('#valor').val(data.costo['valor']);
		modal.find('#descripcion').val(data.costo['descripcion']);				        

	}).fail(function(e){
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(e.statusText);       
    });
});

$('#addCosto').on('show.bs.modal', function (event) {
	var modal = $(this);
	modal.find('select[name=concepto]').attr('disabled', false);
});