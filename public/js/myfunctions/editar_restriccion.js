
$('#clienteRestEdit').on('show.bs.modal', function (event) {

    var a = $(event.relatedTarget) // Button that triggered the modal
    var id = a.data('id');
    var url = '/clientes/restricciones/'+id;
    var modal = $(this);

    $.get(url +'/edit'  ,null, function(data){
        modal.find('form').attr('action', url);
        modal.find('textarea[name=observaciones]').val(data.restriccion['observaciones']);   
    });

});

