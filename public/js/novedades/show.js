function traer_novedad(id){    

    $.get('/novedades/'+id, null).done(function(data){

      $('#novedad_id').val(id);
      $('#ticket').val(data.ticket);

      $('#fecha_inicio_txt').val(data.fecha_inicio.replace(' ', 'T'));

      $('#nombre_txt').text(data.nombre);
      $('#municipio_txt').text(data.municipio);
      $('#proyecto_txt').text(data.proyecto);      
      $('#fecha_fin_txt').text(data.fecha_fin);      
      $('#agente_txt').text(data.agente);
      $('#concepto_txt').text(data.concepto);
      $('#estado_txt').text(data.estado);
      $('#cantidad_txt').text(data.cantidad);
      $('#valor_txt').text(data.valor);
      $('#iva_txt').text(data.solucion);
        
      $('#cerrarNovedad').modal('show');

      }).fail(function(e){
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(e.statusText);       
    });
  }