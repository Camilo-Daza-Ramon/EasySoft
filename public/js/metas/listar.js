const listar_metas = (proyecto) => {
    if(proyecto.value !== undefined || proyecto.value > 0){
        var parameters = {        
            proyecto_id : proyecto.value,
            '_token' : $('input:hidden[name=_token]').val()
        };
    
    
        $.post('/metas/ajax', parameters).done(function(data){

            if(Object.keys(data).length === 0){
                toastr.warning("no hay datos");
            }else{
                $('select[name=meta]').empty();
                $('select[name=meta]').append('<option value="">Elija una meta</option>');
                $.each(data.metas, function(index, metaObj){
                    $('select[name=meta]').append('<option value="' + metaObj.id + '">' + metaObj.nombre + '</option>');
                });
            }
    
            
        }).fail(function(e){
            alert('error');
        });
    }
}