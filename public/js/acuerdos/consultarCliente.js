var cedula = $('#text-cedula');
var nombre = $('#text-nombre');
var correo_cliente = $('#text-correo');
var direccion = $('#text-direccion');
var telefono = $('#text-telefono');
var proyecto = $('#text-proyecto');
var estado = $('#text-estado');
var total_deuda = $('#text-total-deuda');
var tarifa = $('#text-tarifa-internet');
var link_cliente = $('#link-cliente');

function consultar_cliente(documento){
    $('#deuda').val('');
    $('#t_cuotas').empty();
    $('#cuotas').val('');
    $('#valor_inicial').val('');
    $('#dia_pagar').val('');
    $('#perdonar_porcentual').val('');
    $('#valor_perdonar').val('');
    $('#descontado').val('');

    var parametros = {
        cedula : documento,
        '_token' : $('input:hidden[name=_token]').val()
    };
   
    $.post('/acuerdos/ajax', parametros).done(function(data){
        if (data != false){
            if (data != 'null') {
                $('#panel-cliente').show(1000);  
                cliente_id = data.id;
                cedula.text(data.cedula); 
                nombre.text(data.nombre);
                correo_cliente.text(data.correo);
                direccion.text(data.direccion);
                telefono.text(data.telefono);
                proyecto.text(data.proyecto); 
                estado.text(data.estado);
                tarifa.text(formato_dinero(data.ValorTarifaInternet));
                total_deuda.text(formato_dinero(data.total_deuda));
                $('#cliente_id').val(data.id);
                $('#deuda').val(data.total_deuda);
    
            
            }else{
                $('#panel-cliente').hide(1000);
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.error('Cliente no existe!');
            }

        }else{
            $('#panel-cliente').hide(1000);
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.error('Cliente cuenta con un acuerdo ACTIVO!');
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
  

  