$('select[name="pregunta_firma_tecnico"]').on('change', function(){

    switch($(this).val()){
        case 'FIRMAR':
            limpiar;
            $('#addFirma').modal('show');
            $('#addFirma').find('canvas').attr('data-tipo', 'tecnico');
            $('#firmaTecnicoSubir').hide();
            $('#firmaTecnicoSubir').find('input[name="firma_tecnico"]').attr('required', false);
            break;
        case 'SUBIR FIRMA':
            $('#firmaTecnicoSubir').show();
            $('#firmaTecnicoSubir').find('input[name="firma_tecnico"]').attr('required', true);
            break;
        default:
            break;
    }
});