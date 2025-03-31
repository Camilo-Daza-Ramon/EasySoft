function consultar_cliente(documento){
  var parametros = {
        cedula : documento,
        '_token' : $('input:hidden[name=_token]').val()
    };

  $.post('/clientes/ajax', parametros).done(function(data){
    if (Object.keys(data).length > 0) {
        $('#panel-cliente').show(1000);
        $('#link-mantenimiento').attr('disabled', false);

        cliente_id = data.id;
        cedula.text(data.cedula);
        nombre.text(data.nombre);
        correo_cliente.text(data.correo);
        direccion.text(data.direccion);
        telefono.text(data.telefono);
        proyecto.text(data.proyecto);
        estado.text(data.estado);
        total_deuda.text(data.total_deuda);

        $('#departamento option[value='+data.departamento_id+']').prop('selected', true);
        buscarmunicipios(data.municipio_id);

        departamento = data.departamento_id;
        municipio = data.municipio_id;

        link_cliente.attr('href', '/clientes/'+data.id);

        alerta_ticket.empty();

        console.log(data.mantenimiento);

        if (data.ticket === null) {

          if (data.mantenimiento === null) {
            ticket.val('');
            $('input[name=mantenimiento]').val('')
            alerta_ticket.hide(1000);
          }else{
            alerta_ticket.append('<h4><i class="icon fa fa-warning"></i> Atención!</h4><p>El cliente tiene un mantenimiento abierto <a href="/mantenimientos/correctivos/'+data.mantenimiento['MantId']+'" target="_black"><b>#'+ data.mantenimiento['NumeroDeTicket'] + '</b></a></p>');
            alerta_ticket.show(1000);
            mantenimiento.val(data.mantenimiento['MantId']);
            btn_solicitud.hide();
          }
        }else{
          alerta_ticket.append('<h4><i class="icon fa fa-warning"></i> Atención!</h4><p>El cliente ya tiene un ticket abierto <a href="/tickets/'+data.ticket+'" target="_black"><b>#'+ data.ticket + '</b></a></p>');
          ticket.val(data.ticket);
          alerta_ticket.show(1000);
          btn_solicitud.hide();
        }
      }else{
        $('#panel-cliente').hide(1000);
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error('Cliente no existe!');
      }
    });
}

function limpiar(){
  cedula.text('');
  nombre.text('');
  direccion.text('');
  telefono.text('');
  proyecto.text('');
  estado.text('');
  total_deuda.text('');
  cliente_id = null;
  link_cliente.attr('href', '#');
}

$('#departamento').on('change', function(){
  departamento = $(this).val();
  buscarmunicipios(null);
});

$('#municipio').on('change', function(){
  municipio = $(this).val();
});
