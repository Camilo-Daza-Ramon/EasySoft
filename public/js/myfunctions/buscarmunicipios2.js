function buscarmunicipios(municipio_id, selectDepartamento = null, selectMunicipio = null){
    

    var parametros = {
        departamento_id : selectDepartamento !== null && selectDepartamento[0].value !== null  
            ?  selectDepartamento[0].value : $('#departamento').val(),
        '_token' : $('input:hidden[name=_token]').val()             
    };
    

    $.post('/municipios/ajax', parametros).done(function(data){
        const municipio = selectMunicipio ?? $('#municipio');
        
        municipio[0].innerHTML = '';
        
        municipio.append('<option value="">Elija un municipio</option>');
        $.each(data, function(index, municipiosObj){
            
           if (municipio != null) {
                if (municipiosObj.id == municipio_id) {
                    
                    municipio.append('<option value="' + municipiosObj.id + '" selected>' + municipiosObj.nombre + '</option>');
                }else{
                    municipio.append('<option value="' + municipiosObj.id + '">' + municipiosObj.nombre + '</option>');
                }
                
            }else{
                municipio.append('<option value="' + municipiosObj.id + '">' + municipiosObj.nombre + '</option>');
            }
        });
    });
}

