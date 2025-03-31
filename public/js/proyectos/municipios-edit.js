$('#editMunicipio').on('show.bs.modal', function (event) {
	var a = $(event.relatedTarget) // Button that triggered the modal
    id = a.data('id');
    let meta_id = a.data('meta-id');


    var modal = $(this);

    $("#form_editar_municipio").attr('action', '');

	modal.find("#total_accesos").val('');
	modal.find('select[name=departamento]').attr('selectedIndex', 0);
	modal.find('select[name=municipio]').empty();
	modal.find('select[name=meta]').attr('selectedIndex', 0);
	

	$.get('/proyectos-municipios/'+id+'/edit/'+meta_id).done(function(data){

		$("#form_editar_municipio").attr('action',"/proyectos-municipios/" + id);
		
		
		modal.find('#total_accesos').val(data.municipios['total_accesos']);
		modal.find('select[name=departamento] option[value="'+data.municipios["DeptId"]+'"]').prop("selected", true);
		buscar_municipio(modal,data.municipios.DeptId, data.municipios.municipio_id, proyecto);

		modal.find('input[type=hidden][name="meta_id"]').val(data.municipios.pmm_id);

		if(data.municipios.meta_id && data.municipios.meta_id.length !== undefined){
			modal.find('select[name=meta] option[value="'+data.municipios["meta_id"]+'"]').prop("selected", true);
		}else{
			modal.find('select[name=meta]').attr('selectedIndex', 0);
		}     

    }).fail(function(e){
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(e.statusText);       
    });
});