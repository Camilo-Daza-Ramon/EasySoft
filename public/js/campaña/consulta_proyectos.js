$('#periodo_factura').on('change',function(){
    consultar_proyectos($(this).val()); 
});

function consultar_proyectos(periodo){
    var parametros = {
        periodo_f : periodo,
        '_token' : $('input:hidden[name=_token]').val()
    };
    $.post('ajax_proyectos', parametros).done(function(data){
        $('#proyecto').empty();
        $('#departamento').empty(); 
        $('#municipio').empty();                 
        $('#proyecto').append('<option value="">Elija un proyecto</option>');
        $.each(data, function(index, proyectosObj){
            $('select[name=proyecto]').append('<option value="' +proyectosObj.ProyectoId + '">' + proyectosObj.NumeroDeProyecto + '</option>');                                                             
        });
    }).fail(function(e){
        alert('error');
    });
}