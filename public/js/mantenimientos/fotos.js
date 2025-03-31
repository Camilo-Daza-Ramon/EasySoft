var modalFoto = $("#addFoto");
var tipo_archivo = modalFoto.find('#tipo');
var btn_addfoto = modalFoto.find('#btn-addfoto');
var nombre = modalFoto.find('input[name=nombre]');
var archivo = modalFoto.find('input[name=archivo]');
var evidencias = modalFoto.find('#evidencias');
var i = 1;


tipo_archivo.on('change', function(){
    if ($(this).val == 'otro') {
        nombre.removeClass('hide');
    }else{
        nombre.addClass('hide');
    }
});

btn_addfoto.on('click', function(){

    $total_nombres_repetidos = evidencias.find('input[value="'+tipo_archivo.val()+'"]').length


    if($total_nombres_repetidos > 0){

        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning("El nombre del archivo ya existe");

    }else{

        var nombre_archivo = "";
        let lectura = "readonly";

        if (tipo_archivo.val().length > 0) {        

            if (tipo_archivo.val() != 'otro') {
                nombre_archivo = tipo_archivo.val();
            }else{
                lectura = null;
            }

            evidencias.append(`
                <tr id="foto-${i}"> 
                    <td></td>
                    <td class="input-group-sm">
                        <input class="form-control" type="text" name="nombre_foto[]" value="${nombre_archivo}" ${lectura}>
                    </td>
                    <td class="input-group-sm">
                        <input class="form-control" type="file" name="foto[]" required>
                    </td>
                    <td>
                        <button type="button" class="btn text-danger btn-xs" onclick="removeFoto(${i})"><i class="fa fa-remove"></i></button>
                    </td>
                </tr>`);
                            
            i+=1;

            if (tipo_archivo.val() != 'otro') {

                tipo_archivo.children("option:selected").attr('disabled', true);
                tipo_archivo.children("option:selected").addClass('disabled');
            }

            tipo_archivo.prop('selectedIndex',0);       

        }else{
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.warning("Todos los campos se deben diligenciar.");
        }
    }



});


function removeFoto(id){

    var nombre_foto = $('#foto-' + id).find('td').eq(1).children().val();

    if (confirm("Desea Eliminar los campos " + nombre_foto)) {

        $('#foto-' + id).remove();

        //Quita el disable de la opcion del producto que se esta eliminado            
        modalFoto.find('select[name=tipo] option[value="'+ nombre_foto +'"]').attr('disabled', false);
        modalFoto.find('select[name=tipo] option[value="'+ nombre_foto +'"]').removeClass('disabled');       
    }
}
