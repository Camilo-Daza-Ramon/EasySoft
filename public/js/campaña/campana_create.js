$(function() {
                
    $("#adicional").on('click', function() {

        select_tipos = `<select class="form-control" style="margin-top: 23px;" name="tipo[]" onchange="validar_seleccion(this)" required>`;
        select_tipos += `<option value="">Elija un tipo</option>`;

        // Iterar sobre el array y agregar los options
        tipos_campos.forEach(tipo => {
            select_tipos += `<option value="${tipo}">${tipo.replace('_', ' ')}</option>`;
        });

        // Cerrar el select
        select_tipos += `</select>`;



        $("#informcaion_registrar tbody").append(`<tr class="fila-par">
            <td>
                <input id="nombre_campo" class="form-control first-mayus" required name="nombres[]" onblur="validar_iguales(this)" placeholder="Nombre Campo" />
            </td>
            <td>
                ${select_tipos}<br> 
                <div id="opciones">
                </div>
            </td>
            <td width="30px">
                <input type="button" class="btn btn-danger btn-sm eliminar" value="Quitar" />
            </td>
            <td hidden>
                <button  type="button" class="btn btn-info btn-xs" onclick="agregar_opcion(this)"><i class="fa fa-plus"></i></button>\
            </td>
        </tr>`);
    }); 

    
    
    $(document).on("click", ".eliminar", function() {
        var parent = $(this).parents().get(0);
        $(parent).parent().remove();
    });

    $(document).on("click", "#eliminar_option", function() {
        var parent = $(this).parents().get(0);
        $(parent).parent().remove();
    });

});

function agregar_opcion(button) {
    var nombre_campo  = $(button).closest("tr").find('td #nombre_campo').val().replace(/ /g,"_");
    console.log(nombre_campo);
    $(button).closest("tr").find('td:eq(1) #opciones').append('<div>\
                                <div class="col-md-10">\
                                    <input class="form-control" style="margin-top: 10px;" name="'+nombre_campo+'[]" placeholder="opcion" type="text" required/>\
                                </div>\
                                <div class="col-md-1">\
                                    <button id="eliminar_option" name="eliminar_option" type="button" class="btn btn-danger btn-xs" style="margin-top: 10px;"><i class="fa fa-minus-circle"></i></button>\
                                </div>\
                            </div>')
};

function agregar_opcionEdit(button) {
    var id_campo  = $(button).closest("div.dropdown").attr("id");

    $(button).closest("div.dropdown").append(`
        <div name="nueva_opcion" >
            <div class="col-md-8">
                <input class="form-control" type="text" placeholder="Opcion" name="${id_campo}" id="${id_campo}" required/>
            </div>
            <div class="col-md-1">
                <button id="guardar_opcion" name="guardar_opcion" onclick="agregar_opcionAdd(this)" type="button" data-campo="${id_campo}" class="btn btn-success btn-xs" style="margin-top: 10px;"><i class="fa fa-plus-square-o"></i></button>
            </div>
            <div class="col-md-1">
                <button id="eliminar_option" name="eliminar_option" type="button" class="btn btn-danger btn-xs" style="margin-top: 10px;"><i class="fa fa-minus-circle"></i></button>
            </div>
        </div>
    `)
   
};

function editarOpcion(opcion) {

    
    var url = '/campos/opciones/'+opcion;

    $.ajax({
        url: url ,
        method: 'PUT',
        data:{
            '_token' : $('input:hidden[name=_token]').val()
        },
        
    })
    .done(function(res){
        console.log(res);                  
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.success(res);
        
    })
    .fail( function(xhr, textStatus, errorThrown ) {
        
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(errorThrown);			            
    });            
}

function agregar_opcionAdd(button) {

    var parametros = {
        campo : $(button).attr('data-campo'),
        opcion : $(button).parent().parent().find('input').val(),
        '_token' : $('input:hidden[name=_token]').val()
    };

    

    $.post('/opcion/ajax', parametros) 
    .done(function(res){
        $(button).closest("div.dropdown").append(`
            <div class="row">
                <div class="col-md-8">
                    <li class="list-option">${parametros.opcion}</li>
                </div>
                <div class="col-md-4">           
                    <input type="checkbox"   onchange="editarOpcion(${res})"  value="1" checked="checked">           
                </div>
            </div>
        `)
        
        $(button).parent().parent().remove();
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.success('Opcion agregada corectamente');       
    })
    .fail( function(xhr, textStatus, errorThrown ) {
        
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(errorThrown);			                     
    });


    
};

function validar_seleccion(select){
    
    let valor = $(select).val();
    var campo = $(select).closest("tr");

    if(campo.find('td:eq(0) #nombre_campo').val() !== ''){

        if(valor === 'SELECCION_CON_MULTIPLE_RESPUESTA' || valor === 'SELECCION_CON_UNICA_RESPUESTA'){
            campo.find('td:eq(3)').show();
            campo.find('td:eq(0) #nombre_campo').attr('readonly',true)
        }else{
            campo.find('td:eq(0) #nombre_campo').attr('readonly',false)
            campo.find('td:eq(3)').hide();
            campo.find('td:eq(1) #opciones').empty();

        }
    }else{
        $(select).prop("selectedIndex", -1);
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning('Darle nombre al campo a crear!');
    }
}


$('#tipo_campana').on('change',function(){
    tipo_campana(this);
    
});

$('#fecha_inicio').on('change',function(){
    var fecha_inicio = document.getElementById('fecha_inicio').value;
    var fechaActual = new Date();
    var fecha = new Date(fecha_inicio);
    fecha.setDate(fecha.getDate() + 1);


    // Compara 
    if (fecha < fechaActual) {     
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning('La fecha no es admitida!');
        $('#fecha_inicio').val('');
    }
});

let nombres = [];
function tipo_campana(select_tipo){

    var array = [];
    //document.getElementById("campos_visualizar").innerHTML = ""; 
    $('#campos_visualizar').empty();
    if($(select_tipo).val() === 'FACTURACION'){
        $('#periodo_factura').parent().show();
        $('#periodo_factura').attr('required',true);
        $('#estado_cliente').parent().hide();
        $('#estado_cliente').attr('required',false);
        $('#informacion_mostrar').show();
        $('#proyecto').empty();        
        $('#municipio').empty();

        var selectElement = document.getElementById("periodo_factura");
        selectElement.value = '';

        var selectElement = document.getElementById("estado_cliente");
        selectElement.value = '';

        array = campos_facturacion;
        
    }else if($(select_tipo).val() === 'CLIENTES'){
        $('#periodo_factura').parent().hide();
        $('#periodo_factura').attr('required',false);
        $('#estado_cliente').parent().show();
        $('#estado_cliente').attr('required',true);
        $('#informacion_mostrar').show();

        array = campos_cliente;

        $('#proyecto').empty();
        $('#municipio').empty();
        $('#proyecto').append('<option value="">Elija un proyecto</option>');
        $.each(proyectos, function(index, proyectosObj){
            $('select[name=proyecto]').append('<option value="' +proyectosObj.ProyectoId + '">' + proyectosObj.NumeroDeProyecto + '</option>');                                                             
        });
     
    }else{
        $('#informacion_mostrar').hide();
    }

    array.forEach(function (elemento) { 
        //console.log(elemento);
        $("#campos_visualizar").append(
            '<li><input class="material-icons" type="checkbox" name="campos[]" value="' +
            elemento +
            '"><label>' +
            elemento +
            "</label></input></li>"
        );
    });
}

function validar_iguales(input){
    let inputNombres = document.querySelectorAll('input[name="nombres[]"]');

    // Limpiar el arreglo nombres
    nombres = [];

    // Recorrer los inputs y agregar us valores al arreglo nombres
    inputNombres.forEach(function(input) {
        nombres.push(input.value);
    });
    // elimina lo regitro repetido
    let conjuntoNombres = new Set(nombres);

    if (conjuntoNombres.size < nombres.length) {
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning('Los campos ha registrar no se pueden repetir!');
        $('#crear_campaña').hide();
        $('#actualizar_campana').hide();

    } else {
        $('#crear_campaña').show();
        $('#actualizar_campana').show();

    }


}
 






