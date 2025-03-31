  

var j = 1;
var pruebas = [];
var btn_crear_ticket = $('#crear_ticket');
var link_mantenimiento = $('#link-mantenimiento');
var hora_apertura;


function addPrueba(){

  var currentdate = new Date();
  var hora_text = new Date().toLocaleTimeString();

  var prueba = $('select[name=prueba] option:selected');
  var hora = $('input[name=hora]');
  var observacion = $('#observacion');

  if (prueba.val().length > 0) {
    if (hora.val().length > 0) {
      if (observacion.val().length > 0) {
        var item = {};
        item.id = prueba.val();
        item.prueba = prueba.val();
        item.hora = hora.val();
        item.observacion = observacion.val();

        $('#pruebas').append('<tr id="prueba-' + prueba.val() +'"><td>' + j + '</td><td>' + prueba.text() + '</td><td>' + hora.val() + '</td><td>' + observacion.val() + '</td><td><a class="btn text-danger" onclick="removePrueba('+ prueba.val() +')"><i class="fa fa-remove"></i></a></td></tr>');

        pruebas.push(item);

        $('select[name=prueba]').prop('selectedIndex',0);


        if(item.id == 25){
          checkbox_visita.disabled = false;
          checkbox_visita.checked = true;
          checkbox.disabled = true;
          hora.val(hora_text);
          observacion.val('');
          j = j+1;
          prueba.attr('disabled', true); 
          prueba.addClass('disabled');

          if(checkbox.checked = true){
            checkbox.checked = false;
            $('#datos_pqr').hide();
            $('#identificacion_pqr').attr('required',false);
            $('#celular_pqr').attr('required',false);
            $('#nombre_pqr').attr('required',false);
            $('#correo_pqr').attr('required',false);
          }
        }

        prueba.attr('disabled', true); 
        prueba.addClass('disabled');

        //prueba.val('');
        hora.val(hora_text);
        observacion.val('');
        j = j+1;

        btn_crear_ticket.attr('disabled', false);
      }else{
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning('Debe agregar una observacion.');
      }
    }else{
      toastr.options.positionClass = 'toast-bottom-right';
      toastr.warning('Debe agregar una hora válida.');
    }
  }else{
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.warning('Debe seleccionar una prueba.');
  }
}

function removePrueba(id){
  if (confirm("Desea Eliminar el prueba " + $('#prueba-' + id).find('td').eq(1).text())) {
    
    $('#prueba-' + id).remove();

    if(id == 25){
      checkbox_visita.checked = false;
      checkbox_visita.disabled = true;
      checkbox.disabled = false;     
    }
    //se reasigna la variable sin el array que contiene el id que se esta eliminando.
    
    pruebas = $.grep(pruebas, function(e){
      return e.id != id; 
    });

    $('select[name=prueba] option[value="'+ id +'"]').attr('disabled', false);
    $('select[name=prueba] option[value="'+ id +'"]').removeClass('disabled');

    if (Object.keys(pruebas).length == 0) {
      btn_crear_ticket.attr('disabled', true);
    }

  }
}

link_mantenimiento.on('click', function(){
  $('#addTicket').modal('show'); 
});