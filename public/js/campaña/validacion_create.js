setInterval(function() {

    //verificacion textarea cedulas

    
    var Cedulas_especificas = document.getElementById('cedulas_especificas');

    if(Cedulas_especificas.value != ''){

        $('#proyecto').parent().hide();
        $('#departamento').parent().hide();
        $('#municipio').parent().hide();
        $('#cedulas_no_llamar').parent().hide();
    }else{
        
        $('#proyecto').parent().show();
        $('#departamento').parent().show();
        $('#municipio').parent().show();
        $('#cedulas_no_llamar').parent().show();
    }

    //varificacion checkboxs

    var checkboxs = document.querySelectorAll('#campos_visualizar input[type="checkbox"]');
    var isChecked = false;

    for (var i = 0; i < checkboxs.length; i++) {
        if (checkboxs[i].checked) {
            isChecked = true;
            break;
        }
    }

    if (isChecked) {
        $('#crear_campaña').attr('disabled',false);
    } else {
        $('#crear_campaña').attr('disabled',true);
    }

}, 700);


function ajax_consulta(event){

    if (event) {
        event.preventDefault();
    }

    var parametros = {
        tipo_campana : $('#tipo_campana').val(),
        periodo : $('#periodo_factura').val(),
        proyecto : $('#proyecto').val(),
        departamento : $('#departamento').val(),
        municipio : $('#municipio').val(),
        estado : $('#estado_cliente').val(),
        '_token' : $('input:hidden[name=_token]').val()
    };


    $.post('/campanas/ajax-consulta', parametros) 
    .done(function(res){
        if(res != true){            
            toastr.warning('No hay registros con estas indicaciones.'); 
            return false;
        }else{
            if (event) {

                const validacion = validar_cedulas(event);

                if(res == true && validacion){ 
                    var form = document.getElementById("crearCampaña");
                    if (form.reportValidity()) {
                        form.submit();
                    }
                }else{
                    event.preventDefault();
                }
            }

            return true;

        }  
              
    })
    .fail( function(xhr, textStatus, errorThrown ) {
        
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(errorThrown);			                     
    });    

    
}

