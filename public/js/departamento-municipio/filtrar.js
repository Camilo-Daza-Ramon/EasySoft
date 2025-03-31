$('#proyecto').on('change', function(){            
    buscar_departamentos($('#departamento').val());            
});


$('#departamento').on('change', function(){  
    buscar_municipio($('#municipio').val());            
});

function buscar_municipio(municipio){

    var parameters = {
        departamento_id : $('#departamento').val(),
        proyecto_id : $('#proyecto').val(),
        '_token' : $('input:hidden[name=_token]').val()
    };
    

    $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){

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

        $('#departamento').empty();                
        $('#departamento').append('<option value="">Elija un departamento</option>');
        $.each(data, function(index, departamentosObj){

            if (departamento != null) {
                if (departamentosObj.DeptId == departamento) {

                    $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
                }else{
                    $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
                }
            }else{
                $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '" >' + departamentosObj.NombreDelDepartamento + '</option>');
            }                    
        });
    }).fail(function(e){
        alert('error');
    });
}
	    
            