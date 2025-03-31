
$('#proyecto').on('change', function(){

    if($(this).val().length > 0){
        $('#departamento').empty();     
        buscar_departamentos($('#departamento').val());            
    }
});


$('#departamento').on('change', function(){ 
    if($(this).val().length > 0){        
        buscar_municipio($('#municipio').val());            
    }
});

function buscar_municipio(municipio){
 
    var parameters = {
        departamento_id : $('#departamento').val(),
        proyecto_id : $('#proyecto').val(),
        '_token' : $('input:hidden[name=_token]').val()
    };

    $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){
        $(document).trigger('cargandoMunicipios');

        $('#municipio').empty();

        $('#municipio').append('<option value="">Elija un municipio</option>');
        $.each(data, function(index, municipiosObj){
            
            if (municipio != null) {
                if (municipiosObj.MunicipioId == municipio) {

                    $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '" selected>' + municipiosObj.NombreMunicipio + '</option>');
                }else{
                    $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
                }
            }else{
                $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
            }                                     
        });
        $(document).trigger('municipiosCargados');
    }).fail(function(e){
        alert('error');
    });
}

function buscar_departamentos(departamento){
    var parameters = {
        proyecto_id : $('#proyecto').val(),
        '_token' : $('input:hidden[name=_token]').val()
    };

    $.post('/estudios-demanda/ajax-departamentos', parameters).done(function(data){
        $(document).trigger('cargandoMunicipios');
        
        $('#municipio').empty();
        
        
        $('#departamento').append('<option value="">Elija un departamento</option>');
        $('#municipio').append('<option value="">Elija un municipio</option>');

        $.each(data, function(index, departamentosObj){

            if (departamento != null) {
                if (departamentosObj.DeptId == departamento) {

                    $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '" selected>' + departamentosObj.NombreDelDepartamento + '</option>');
                }else{
                    $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
                }
            }else{
                $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
            }                    
        });
        $(document).trigger('municipiosCargados');

    }).fail(function(e){
        alert('error');
    });
}