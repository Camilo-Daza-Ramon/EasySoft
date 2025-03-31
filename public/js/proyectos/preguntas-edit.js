$('#editPregunta').on('show.bs.modal', function (event) {

    const a = $(event.relatedTarget) // Button that triggered the modal
    const id = a.data('id');
    const proyecto_id = a.data('proyecto-id');
    var url = '/proyectos/'+proyecto_id+'/preguntas/'+id;
    var modal = $(this);

    $.get(url+'/edit',null, function(data){
        modal.find('form').attr('action', url);
        modal.find('input[name=pregunta]').val(data.pregunta['pregunta']);
        modal.find('select[name=tipo] option[value='+data.pregunta.tipo+']').prop("selected", true);
        modal.find('select[name=estado] option[value='+data.pregunta.estado+']').prop("selected", true);
        modal.find('input[name=obligatorio]').prop("checked", (data.pregunta.obligatoriedad == 1)? true : false);

        var respuestas = JSON.parse(data.pregunta.opciones_respuesta);        

        modal.find('select[name="respuestas[]"]').empty().select2({
            placeholder: "Ingrese las respuestas separadas por coma ','",
            tags: true,
            tokenSeparators: [','],
            data:respuestas
        });

        modal.find('select[name="respuestas[]"]').val(respuestas);
		modal.find('select[name="respuestas[]"]').trigger('change');


    });
});