
$('#addSolicitud').on('show.bs.modal', function (event) {
  var motivo = $('#motivo option:selected');
  var solicitud = parseInt(motivo.attr('data-solicitud'));
  var condicional = parseInt(motivo.attr('data-condicional'));
  var limite = parseInt(motivo.attr('data-limite'));
  var fecha_limite = $('input[name=fecha_limite]');

  if (solicitud) {
    if (condicional) {
      fecha_limite.val(ultimo_dia);
    }else{
      var modal = $(this);
      var date = new Date(fecha_hoy);
      date.setDate(date.getDate() + limite + 1);
      fecha_limite.val(date.toLocaleDateString('fr-CA'))
    }
  }
});

btn_add_solicitud.on('click',function(){
  
  jornada = $('select[name=jornada]').val();
  fecha_limite = $('input[name=fecha_limite]').val(); 
  celular = $('input[name=celular_contacto]').val(); 
  correo = $('input[name=correo_solicitud]').val();
  
  if (jornada.length > 0 && fecha_limite.length > 0 && (celular.replace(/_/g,'')).length == 14 && correo.length > 0) {
  
    $(this).attr('disabled',true);

    $('#campos_solicitud').hide();
    $('#txt-jornada').text(jornada);
    $('#txt-limite').text(fecha_limite);
    $('#txt-celular-contacto').text(celular);
    $('#txt-correo-contacto').text(correo);

    $('#panel-solicitud').show(1000);

    $('#addSolicitud').modal('toggle');
    $('#observacion_respuesta').attr('readonly',true);

    var fecha_limit_solicitud = document.querySelector("#txt-limite").textContent;
    var celular_solicitud = document.querySelector("#txt-celular-contacto").textContent;
    var correo_solicitud = document.querySelector("#txt-correo-contacto").textContent;
    var jornada_solicitud = document.querySelector("#txt-jornada").textContent;

    $('input[name=fecha_limite]').val(fecha_limit_solicitud);
    $('input[name=celular]').val(celular_solicitud);
    $('input[name=correo]').val(correo_solicitud);
    $('input[name=jornada]').val(jornada_solicitud);
  }else{
    toastr.options.positionClass = 'toast-bottom-right';
    toastr.warning('Compruebe que todos los campos esten diligenciados correctamente!');
  }
   
});
