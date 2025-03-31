const canvas = document.querySelector("canvas");

const signaturePad = new SignaturePad(canvas, {
    // It's Necessary to use an opaque color when saving image as JPEG
    // this option can be omitted if only saving as PNG or SVG
    backgroundColor: 'rgb(255, 255, 255)'
})

signaturePad.fromDataURL("/img/fondo_firma.jpg", { ratio: 1, width: 500, height: 250});

const limpiar = () => {
    signaturePad.clear();
    signaturePad.fromDataURL("/img/fondo_firma.jpg", { ratio: 1, width: 500, height: 250});
}

$('#guardarFirma').on('click', function(){
    var tipo_firma = $('#addFirma').find('canvas').attr('data-tipo');

    if(tipo_firma == 'usuario'){
        firma_usuario = signaturePad.toDataURL();
    }else if(tipo_firma == 'cliente'){
        firma = signaturePad.toDataURL();
    }
    
    $('#addFirma').modal('hide');
});






