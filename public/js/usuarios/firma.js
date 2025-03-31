$('select[name="pregunta_firma_usuario"]').on('change', function(){

    switch($(this).val()){
        case 'FIRMAR':
            limpiar();
            $('#addFirma').modal('show');
            $('#addFirma').find('canvas').attr('data-tipo', 'usuario');
            $('#firmaUsuarioSubir').hide();
            $('#firmaUsuarioSubir').find('input[name="firma_usuario"]').attr('required', false);
            break;
        case 'SUBIR FIRMA':
            $('#firmaUsuarioSubir').show();
            $('#firmaUsuarioSubir').find('input[name="firma_usuario"]').attr('required', true);
            break;
        default:
            break;
    }
});