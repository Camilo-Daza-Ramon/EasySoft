$('select[name="pregunta_firma"]').on('change', function(){

    switch($(this).val()){
        case 'FIRMAR':
            limpiar();

            $('#addFirma').modal('show');
            $('#addFirma').find('canvas').attr('data-tipo', 'cliente');
            $('#firmaSubir').hide();
            $('#firmaSubir').find('input[name="firma"]').attr('required', false);
            break;
        case 'SUBIR FIRMA':
            $('#firmaSubir').show();
            $('#firmaSubir').find('input[name="firma"]').attr('required', true);
            break;
        default:
            break;
    }
});