$('.showDocumento').on('click', function (event) {

    id = $(this).data("id");
    documento = $(this).data("documento");
    toastr.options.positionClass = 'toast-bottom-right';           

    $('.espera').addClass('overlay').show();

    $.ajax({
        type: "POST",
        url: "/contratos-archivos/ajax", // do not hard code your url's
        data: {
            proyecto : id,
            documento : documento,
            '_token' : $('input:hidden[name=_token]').val() 
        },
  
        success: function (data, jqXHR, response) {            
            
            //var bytes = _base64ToArrayBuffer(data.message);
            // saveByteArray("Sample Report", bytes);
            //var getFile = new Blob([bytes], { type: data.type });
            //var fileURL = URL.createObjectURL(getFile);

            $('#'+documento+'_previsualizar').attr('src', 'data:application/pdf;base64,' + data);
            $('.espera').removeClass('overlay').hide();
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Función que se ejecuta si la petición falla

          toastr.error(errorThrown);
          $('.espera').removeClass('overlay').hide();

        }
    });

});


