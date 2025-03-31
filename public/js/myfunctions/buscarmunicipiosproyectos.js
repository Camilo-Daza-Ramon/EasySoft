$('#proyecto').on('change', function(){
    if ($(this).val() == 7 || $(this).val() == 8 || $(this).val() == 6) {
        $('#tipo_beneficiario').attr('required', 'required');
        $('#panel-tipo-beneficiario').show(2000);
        //$('#panel-detalles-contrato').fadeOut(2000);
        $('#referencia').removeAttr('required', 'required');
        $('#contrato').removeAttr('required', 'required');
        $('#vigencia').removeAttr('required', 'required');
        $('#tipo_cobro').removeAttr('required', 'required');
        $('#estado').removeAttr('required', 'required');

    }else{
        $('#tipo_beneficiario').removeAttr('required');
        $('#panel-tipo-beneficiario').fadeOut(2000);
        $('#fotos-opcionales').empty();

        //$('#panel-detalles-contrato').show(2000);
        $('#referencia').attr('required', 'required');
        $('#contrato').attr('required', 'required');
        $('#vigencia').attr('required', 'required');
        $('#tipo_cobro').attr('required', 'required');
        $('#estado').attr('required', 'required');
        
    }

    $('#proyecto_municipio_id').empty();
    $('#departamento').empty();
    $('#lista-planes').empty();

    var parameters = {
        proyecto_id : $(this).val(),
        '_token' : $('input:hidden[name=_token]').val()
    };

    $.post('/estudios-demanda/ajax-departamentos', parameters).done(function(data){

        $('#departamento').empty();                
        $('#departamento').append('<option value="">Elija un departamento</option>');
        $.each(data, function(index, departamentosObj){                   
            $('#departamento').append('<option value="'+departamentosObj.DeptId+'">'+departamentosObj.NombreDelDepartamento+'</option>');                    
        });
    }).fail(function(e){
        alert('error');
    });
});