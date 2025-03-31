$('#editClausula').on('show.bs.modal', function (event) {
	var a = $(event.relatedTarget) // Button that triggered the modal
    id = a.data('id');

    var modal = $(this);

    $("#form_editar_clausula").attr('action', '');
    modal.find('select[name=numero_mes]').attr('selectedIndex', 0);
	modal.find('#valor').val('');
	

	$.get('/proyectos-clausulas/'+id+'/edit').done(function(data){

		$("#form_editar_clausula").attr('action',"/proyectos-clausulas/" + id);
		modal.find('select[name=numero_mes] option[value="'+data.clausula["numero_mes"]+'"]').prop("selected", true);	
		modal.find('#valor').val(data.clausula['valor']);		        

	}).fail(function(e){
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(e.statusText);       
    });
});