function editarPropiedad(infra_id, prop_id) {
    const form = $('#addPropiedades form');
    const url = `${infra_id}/propiedades/${prop_id}`;

    $.get(`${url}/edit`).done(function (data) {
        
        $('#addPropiedades h4.modal-title').text("Editar Propiedad");

        form.attr('action', url);
        
        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        } else {
            form.find('input[name=_method]').val('PUT');
        }
        
        form.find('input[name=nombre]').val(data.nombre);

        form.find('input[name=valor]').val(data.valor);

        form.find('input[name=unidad_medida]').val(data.unidad_medida);
    });
}

$('#btn-add-propiedades').on('click', function () {
    $('#addPropiedades h4.modal-title').text("Agregar Propiedad");

    const button = $(this);
    const infraId = button.data('infra_id');

    const form = $('#addPropiedades form');

    const url = `${infraId}/propiedades`;

    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    }

    form[0].reset();
});

function editarContacto(infra_id, contacto_id) {
    const form = $('#addContactos form');
    const url = `${infra_id}/contactos/${contacto_id}`;
    
    $.get(`${url}/edit`).done(function (data) {
        
        $('#addContactos h4.modal-title').text("Editar Contacto");

        form.attr('action', url);
        
        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        } else {
            form.find('input[name=_method]').val('PUT');
        }
        
        form.find('input[name=nombre]').val(data.nombre);

        form.find('input[name=celular]').val(data.celular);

        form.find('input[name=telefono]').val(data.telefono);

        form.find('input[name=cargo_presentativo]').val(data.cargo_presentativo);

    });
}

$('#btn-add-contactos').on('click', function () {
    $('#addContactos h4.modal-title').text("Agregar Contacto");

    const button = $(this);
    const infraId = button.data('infra_id');

    const form = $('#addContactos form');

    const url = `${infraId}/contactos`;

    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    }

    form[0].reset();
});

function editarEquipo(infra_id, equipo_id) {
    const form = $('#addEquipos form');
    const url = `${infra_id}/equipos/${equipo_id}`;
    
    $.get(`${url}/edit`).done(function (data) {
        
        $('#addEquipos h4.modal-title').text("Editar Equipo");

        form.attr('action', url);
        form.find('button[type=submit]').attr('disabled', false);
        
        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        } else {
            form.find('input[name=_method]').val('PUT');
        }

        form.find('select[name=codigo]').val(data.activo_fijo.insumo.InsumoId);
        form.find('select[name=codigo]').attr('readonly', true);
        form.find('select[name=codigo]').attr('disabled', true);

        form.find('input[name=serial]').val(data.activo_fijo.Serial);
        form.find('input[name=serial]').attr('readonly', true);
        form.find('input[name=serial]').attr('disabled', true);

        form.find('input[name=ip_gestion]').val(data.ip_gestion);

        form.find('input[name=usuario]').val(data.usuario);

        form.find('input[name=password]').val(data.password);
    });
}

$('#btn-add-equipos').on('click', function () {
    $('#addEquipos h4.modal-title').text("Agregar Equipo");

    const button = $(this);
    const infraId = button.data('infra_id');

    const form = $('#addEquipos form');

    form.find('select[name=codigo]').attr('readonly', false);
    form.find('select[name=codigo]').attr('disabled', false);

    form.find('input[name=serial]').attr('readonly', false);
    form.find('input[name=serial]').attr('disabled', false);


    const url = `${infraId}/equipos`;

    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    }

    form[0].reset();
});