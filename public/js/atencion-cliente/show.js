function traer_atencion(id){

    $('#productos_txt').empty();
    $('#estado_txt').empty();

    $.get('/atencion-clientes/'+id, null).done(function(data){
      $('#nombre_txt').text(data.nombre);
      $('#cedula_txt').text(data.cedula);
      $('#municipio_txt').text(data.municipio);
      $('#departamento_txt').text(data.departamento);
      $('#motivo_txt').text(data.motivo);
      $('#categoria_txt').text(data.categoria);
      $('#medio_txt').text(data.medio);
      $('#agente_txt').text(data.agente);
      $('#fecha_atencion_txt').text(data.fecha);
      $('#estado_txt').text(data.estado);

      $('#solicitud_txt').text(data.solicitud_id).attr('href', '/solicitudes/'+data.solicitud_id);
      $('#cun_txt').text(data.cun).attr('href', '/pqr/'+data.pqr_id);
      $('#ticket_txt').text(data.ticket_id).attr('href', '/tickets/'+data.ticket_id);
      $('#mantenimiento_txt').text(data.mantenimiento_id).attr('href', '/mantenimientos/'+data.mantenimiento_id);

      $('#descripcion_txt').text(data.descripcion);
      $('#solucion_txt').text(data.solucion);




      $('#showAtencion').modal('show');

      }).fail(function(e){
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(e.statusText);
    });
  }
