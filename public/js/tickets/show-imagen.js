$('#modal-attachment').on('show.bs.modal', function (event) {
    var a = $(event.relatedTarget) // Button that triggered the modal
    var recipient = a.data('imagen') // Extract info from data-* attributes
    var modal = $(this)  
    modal.find('#presentacion').html('<img src="'+ recipient +'" id="img-attachment" class="img-responsive" width="100%">');
    
  });
