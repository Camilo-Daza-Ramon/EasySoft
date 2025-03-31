
$('select[name=departamento]').on('change', function(){
    if($(this).val().length > 0) {
        buscar_municipio($(this).val(), $('#municipio').val());          
    }
});

function buscar_municipio(departamento, municipio){

    var parameters = {
        departamento_id : departamento,
        campana_id : campana_id,
        '_token' : $('input:hidden[name=_token]').val()
    };
    

    $.post('/campanas/ajax-municipios', parameters).done(function(data){

        $('select[name=municipio]').empty();
        $('select[name=municipio]').append('<option value="">Elija un municipio</option>');
        $.each(data, function(index, municipiosObj){

            if (municipio != null) {
                if (municipiosObj.id == municipio) {

                    $('select[name=municipio]').append('<option value="' + municipiosObj.id + '" selected>' + municipiosObj.nombre + '</option>');
                }else{
                    $('select[name=municipio]').append('<option value="' + municipiosObj.id + '">' + municipiosObj.nombre + '</option>');
                }
            }else{
                $('select[name=municipio]').append('<option value="' + municipiosObj.id + '">' + municipiosObj.nombre + '</option>');
            }                                     
        });
    }).fail(function(e){
        alert('error');
    });
} 