function buscarmunicipios(municipio){
    

    var parametros = {
        departamento_id : $('#departamento').val(),
        proyecto_id : $('#proyecto').val(),
        '_token' : $('input:hidden[name=_token]').val()             
    };

    $.post('/estudios-demanda/ajax-municipios', parametros).done(function(data){

        $('#municipio').empty();
        $('#municipio').append('<option value="">Elija un municipio</option>');
        $.each(data, function(index, municipiosObj){
           if (municipio != null) {
                if (municipiosObj.MunicipioId == municipio) {

                    $('select[name=municipio]').append('<option value="' + municipiosObj.MunicipioId + '" selected>' + municipiosObj.NombreMunicipio + '</option>');
                }else{
                $('select[name=municipio]').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
                }
            }else{
                $('select[name=municipio]').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
            }
        });
    });
}