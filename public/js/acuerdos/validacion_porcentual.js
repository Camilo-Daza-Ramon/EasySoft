var tipo_descuento = document.getElementById('perdonar_porcentual');

$('#perdonar_porcentual').on('change' , function(){

    if(tipo_descuento.value == 'porcentual'){
        $('#descontado').val('');
        $('#label_perdonarP').show();
        $('#label_perdonarV').hide();
        $('#signo_porcentaje').removeClass("hide");
        $('#signo_pesos').addClass("hide");   
        $('#valor_perdonar').val('');
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.info('Numero en un rango de 1 a 100');
    }else{
        $('#descontado').val('');
        $('#label_perdonarP').hide();
        $('#label_perdonarV').show();
        $('#signo_porcentaje').addClass("hide");
        $('#signo_pesos').removeClass("hide");
        $('#valor_perdonar').val('');
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.info('Ingresar valor monetario');
    }
});

$('#valor_perdonar').on('input', function(){
    valor = $('#valor_perdonar').val();
    if(tipo_descuento.value == 'porcentual'){
        descuentos(valor);    

        if(valor == 100){
            $('#valor_perdonar').val('')
            $('#descontado').val('')
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.warning('No es posible perdonar la deuda completa!');
        }

        if(valor > 100 || valor < 1){
            $('#valor_perdonar').val('')
            $('#descontado').val('')
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.warning('Numero en un rango de 1 a 100');
        } 
    }else{
        $('#descontado').val(valor);
    }
});

function descuentos(valor){
    deuda = $('#deuda').val();
    var descuento = 0;
    if(tipo_descuento.value == 'porcentual'){
        descuento = deuda * valor;
        descuento = descuento / 100;
    }else{
        descuento = valor;
    }
    
    $('#descontado').val(descuento);
}
