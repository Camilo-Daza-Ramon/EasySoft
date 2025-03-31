function traer_panes(){

    var parametros = {
        'proyecto' : proyecto,
        '_token' : $('input:hidden[name=_token]').val()
    }

    $('#tabla-planes').DataTable( {
        "pageLength": 15,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
        },
        "ordering": true,
        "ajax": {
            "url": "/planes-comerciales/ajax",
            "type": "POST",
            "data": parametros,
            "dataSrc": function ( json ) {

                if(jQuery.isEmptyObject(json.datos)){
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning("No hay planes");
                }else{
                    var data = [];
                    for (var i = 0; i <= (json.datos.length - 1); i++) {
                        var items = {};

                        var texto = "";

                        //console.log(json.data['proyecto_municipio']);

                        

                        var dato = "" ;
                        var editar = "";

                        $.each(json.datos[i]['municipios'], function(index, municipiosObj){

                            dato +='<span class="badge bg-default">'+municipiosObj+'</span> ';

                        });

                        items.id = i;
                        items.nombre = json.datos[i]['nombre'];
                        items.tipo = json.datos[i]['tipo'];
                        items.estrato = json.datos[i]['estrato'];
                        items.velocidad = json.datos[i]['velocidad_descarga'];
                        items.valor = json.datos[i]['valor'];
                        items.municipio = dato;
                        items.estado = json.datos[i]['estado'];

                        
                        editar = '<button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editPlanComercial"  data-id="'+json.datos[i]['id']+'"><i class="fa fa-edit"></i></button>'
                     

                        items.accion = editar;

                        data.push(items);
                    }

                    return data;
                }                                
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "nombre" },
            { "data": "tipo" },
            { "data": "estrato" },
            { "data": "velocidad" },
            { "data": "valor" },
            { "data": "municipio" },
            { "data": "estado" },
            { "data": "accion" }
        ],
        "createdRow": function( row, data, dataIndex ) {
            //console.log(data);                          
            $(row).attr('id', 'plan-'+data['id'] );                            
        }
    });
}