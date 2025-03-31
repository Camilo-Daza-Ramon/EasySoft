$('#editPlanComercial').on('show.bs.modal', function (event) {
	var a = $(event.relatedTarget) // Button that triggered the modal
    id = a.data('id');

    var modal = $(this)

    $("#form-editar_plan").attr('action', '');
	modal.find('#nombre').val('');
	modal.find("#descripcion").val('');
	modal.find("#velocidad_descarga").val('');
	modal.find("#valor").val('');
	modal.find('select[name=estrato]').attr('selectedIndex', 0);
	modal.find('select[name=proyecto]').attr('selectedIndex', 0);
	modal.find('select[name=estado]').attr('selectedIndex', 0);
	modal.find('select[name=tipo]').attr('selectedIndex', 0);

	$.get('/planes/'+id+'/edit').done(function(data){

		$("#form-editar_plan").attr('action',"/planes/" + id);
		
		modal.find('#nombre').val(data.plan_comercial['nombre']);
		modal.find("#descripcion").val(data.plan_comercial['DescripcionPlan']);    				
		modal.find("#velocidad_descarga").val(data.plan_comercial['VelocidadInternet']);
		modal.find("#valor").val(parseFloat(data.plan_comercial['ValorDelServicio']).toFixed(0));

		modal.find('select[name=estrato] option[value='+data.plan_comercial["Estrato"]+']').prop("selected", true);
		modal.find('select[name=proyecto] option[value='+data.plan_comercial["ProyectoId"]+']').prop("selected", true);
		modal.find('select[name=estado] option[value="'+data.plan_comercial["Status"]+'"]').prop("selected", true);
		modal.find('select[name=tipo] option[value="'+data.plan_comercial["TipoDePlan"]+'"]').prop("selected", true);			        

		var municipios_array = [];
		$.each(data.municipios, function(index, municipiosObj){
			municipios_array.push(municipiosObj.id);			
		});

		$('.js-example-basic-multiple').val(municipios_array);
		$('.js-example-basic-multiple').trigger('change');

	}).fail(function(e){
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(e.statusText);       
    });
});