$('#editTipoBeneficiario').on('show.bs.modal', function (event) {

    const a = $(event.relatedTarget) // Button that triggered the modal
    const id = a.data('id');
    const proyecto_id = a.data('proyecto-id');
    var url = '/proyectos/'+proyecto_id+'/tipos-beneficiarios/'+id;
    var modal = $(this);

    $.get(url+'/edit',null, function(data){
        modal.find('form').attr('action', url);
        modal.find('input[name=nombre]').val(data.tipo_beneficiario['nombre']);
        modal.find('textarea[name=descripcion]').val(data.tipo_beneficiario['descripcion']);
        modal.find('select[name=estado] option[value='+data.tipo_beneficiario.estado+']').prop("selected", true);
    });
});