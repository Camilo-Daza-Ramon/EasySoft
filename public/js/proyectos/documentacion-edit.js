$('#editDocumentacion').on('show.bs.modal', function (event) {

    const a = $(event.relatedTarget) // Button that triggered the modal
    const id = a.data('id');
    const proyecto_id = a.data('proyecto-id');
    var url = '/proyectos/'+proyecto_id+'/documentacion/'+id;
    var modal = $(this);

    $.get(url+'/edit',null, function(data){
        modal.find('form').attr('action', url);
        modal.find('input[name=nombre]').val(data.documentacion['nombre']);
        modal.find('input[name=alias]').val(data.documentacion['alias']);
        modal.find('textarea[name=descripcion]').val(data.documentacion['descripcion']);
        modal.find('select[name=tipo] option[value='+data.documentacion.tipo+']').prop("selected", true);
        modal.find('select[name=estado] option[value='+data.documentacion.estado+']').prop("selected", true);

        modal.find('input[name=coordenadas]').prop("checked", (data.documentacion.coordenadas == 1)? true : false);


    });
});

$('input[name="alias"]').on('keyup', function(){

    var modal = $('#addDocumentacion');

    if($(this).val().length > 0){
        modal.find('input[name="nombre"]').val(($(this).val().replace(/ /g, '_')).toLowerCase());
    }

});