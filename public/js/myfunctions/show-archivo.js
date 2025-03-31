$('#modal-attachment').on('show.bs.modal', function (event) {
    var a = $(event.relatedTarget) // Button that triggered the modal
    var titulo = a.data('nombre');
    var tipo = a.data('tipo');
    var recipient = a.data('archivo') // Extract info from data-* attributes
    var modal = $(this)

    modal.find('#titulo').text(titulo);
    if (tipo == 'pdf') {
        modal.find('#presentacion').html('<iframe src="'+ recipient +'" width="100%" height="600" style="height: 85vh;"></iframe>');

    }else if(tipo == 'jpg' || tipo == 'png' || tipo == 'jepg'){

        modal.find('#presentacion').html('<img src="'+ recipient +'" id="img-attachment" class="img-responsive" width="100%">');
    }else if(tipo == 'mp4'|| tipo == 'mov'){
        modal.find('#presentacion').html('<video width="100%"  controls> <source src="'+recipient+'" type="video/'+tipo+'">\
        </video>')
    }else if(tipo == 'mp3' || tipo == 'ogg' || tipo == 'mpeg'){
        modal.find('#presentacion').html('<audio controls>\
            <source src="'+recipient+'" type="audio/'+tipo+'">\
        </audio>')
    }
});
