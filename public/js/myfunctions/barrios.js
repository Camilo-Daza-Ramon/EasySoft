const buscar_barrios = (municipio, barrio) => {  
    
    var parameters = {
        municipio : municipio,
        campana_id : campana_id,
        '_token' : $('input:hidden[name=_token]').val()
    };

    $.post('/barrios/ajax', parameters).done(function(data){

        $('#barrio').empty();
        $('#barrio').append('<option value="">Elija un barrio</option>');
        $.each(data, function(index, barriosObj){

            if (barrio != null) {
                if (barriosObj.Barrio == barrio) {

                    $('select[name=barrio]').append('<option value="' + barriosObj.Barrio + '" selected>' + barriosObj.Barrio + '</option>');
                }else{
                    $('select[name=barrio]').append('<option value="' + barriosObj.Barrio + '">' + barriosObj.Barrio + '</option>');
                }
            }else{
                $('select[name=barrio]').append('<option value="' + barriosObj.Barrio + '">' + barriosObj.Barrio + '</option>');
            }
        });
    });
}