$('#form-pqr').submit(function( event ) {
    event.preventDefault();
    btn_crear_pqr.attr("disabled", true);
    btn_crear_pqr.find('i').removeClass('fa-floppy-o');
    btn_crear_pqr.find('i').addClass('fa-refresh fa-spin');

    var parametros = {
      'cliente_id' : cliente_id,
      'canal_atencion' : $('#canal_atencion_pqr option:selected').val(),
      'fecha_estimada_cierre' : $('input[name=fecha_estimada_cierre]').val(),
      'fecha_limite' : $('input[name=fecha_limite_pqr]').val(),
      'tipo_solicitud' : $('#tipo_solicitud option:selected').val(),
      'tipo_evento' : $('#tipo_evento option:selected').val(),
      'prioridad' : $('#prioridad_pqr option:selected').val(),
      'cedula': $('input[name=identificacion]').val(),
      'celular': $('input[name=celular]').val(),
      'nombre': $('input[name=nombre_pqr]').val(),
      'correo': $('input[name=correo_pqr]').val(),
      'hechos' : $('#hechos').val(),  
      'solucion' : $('#solucion_pqr').val(),
      'municipio' : municipio,
      'departamento' : departamento,
      '_token' : $('input:hidden[name=_token]').val()
    };

    $.post('/pqr', parametros).done(function(data){

    if(data[0] == 'success'){
      toastr.options.positionClass = 'toast-bottom-right';
      toastr.success(data[1]);
      cun.val(data[2]);
      modal_pqr.modal('toggle');
      btn_pqr.attr('disabled',true);
    }else{
      toastr.options.positionClass = 'toast-bottom-right';
      toastr.error(data[1]);

      btn_crear_pqr.attr("disabled", false);
      btn_crear_pqr.find('i').removeClass('fa-refresh fa-spin');
      btn_crear_pqr.find('i').addClass('fa-floppy-o');
    }
  }).fail(function(e){
      console.log(e);
      toastr.options.positionClass = 'toast-bottom-right';
      toastr.error(e.statusText);

      btn_crear_pqr.attr("disabled", false);
      btn_crear_pqr.find('i').removeClass('fa-refresh fa-spin');
      btn_crear_pqr.find('i').addClass('fa-floppy-o');
  });
});
