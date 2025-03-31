function editarArchivo(id) {
    $('#titulo-agreagar-evidencia').text("Editar Evidencia");

    const button = $('#btn-editar-archivo-' + id);
    const archivoId = button.data('id_archivo');
    const mantenimientoId = button.data('id_mantenimiento');
    const nombre = button.data('nombre');

    //let baseUrl = "{{ route('mantenimientos.archivos.update', ['mantenimiento' => '__mantenimiento__', 'archivo' => '__archivo__']) }}";

    let url = baseUrlArchivosUpdate
        .replace('__mantenimiento__', mantenimientoId)
        .replace('__archivo__', archivoId);

    const form = $('#form-archivo-upload');
    form.attr('method', "POST");
    form.attr('action', url);

    if (form.find('input[name="_method"]').length === 0) {
        form.append('<input type="hidden" name="_method" value="PUT">');
    } else {
        form.find('input[name=_method]').val('PUT')
    }

    form.find('.row').first().css('display', 'none');

    const tHeaders = form.find('thead tr th');
    tHeaders[3].style.display = "none";
    tHeaders[2].textContent = "Nuevo Archivo";

    const table = $('#evidencias');
    table.empty();

    const tr = $('<tr>');
    tr.attr('id', 'foto-1');

    const td = $('<td>');

    const tdNombre = $('<td>');
    tdNombre.addClass('input-group-sm')

    const inputName = $('<input>', { class: 'form-control', type: 'text', name: 'nombre_foto', value: nombre, readonly: true })
    tdNombre.append(inputName);

    const tdArchivo = $('<td>');
    tdArchivo.addClass('input-group-sm');

    const inputArchivo = $('<input>', { class: 'form-control', type: 'file', name: 'foto', required: true })
    tdArchivo.append(inputArchivo);

    tr.append(td);
    tr.append(tdNombre);
    tr.append(tdArchivo);

    table.append(tr);

}


$('#btn-agregar-archivo').on('click', function (e) {
    $('#titulo-agreagar-evidencia').text("Agregar Evidencia");

    var modalFoto = $("#addFoto");
    var tipo_archivo = modalFoto.find('#tipo');

    tipo_archivo.find('option').filter('.disabled').each(function() { 
        $(this).removeClass('disabled'); 
        $(this).prop('disabled', false); 
    });

    const button = $(this);
    const mantenimientoId = button.data('id_mantenimiento');

    const table = $('#evidencias');
    table.empty();

    //let baseUrl = "{{ route('mantenimientos.archivos.store', ['mantenimiento' => '__mantenimiento__']) }}";

    let url = baseUrlArchivosStore
        .replace('__mantenimiento__', mantenimientoId)

    const form = $('#form-archivo-upload');
    form.attr('method', "POST")
    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    }

    form.find('.row').first().css('display', 'block');

    const tHeaders = form.find('thead tr th');
    tHeaders[3].style.display = "block";
    tHeaders[2].textContent = "Archivo";
});

function editarEquipo(mantenimiento_id, equipo_id) {
    $.get(`equipos/${equipo_id}/edit`).done(function (data) {
        $('#titulo-agregar-evidencia').text("Editar Equipo");

        const form = $('#form-equipo-upload');

        //let baseUrl = "{{ route('mantenimientos.equipos.update', ['mantenimiento' => '__mantenimiento__', 'equipo' => '__equipo__']) }}";

        let url = baseUrlEquiposUpdate
            .replace('__mantenimiento__', mantenimiento_id)
            .replace('__equipo__', equipo_id);

        form.attr('action', url);

        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        } else {
            form.find('input[name=_method]').val('PUT')
        }

        const inputNombre = form.find('input[name=nombre]')
        inputNombre.val(data.Equipo);

        const inputMarca = form.find('input[name=marca]')
        inputMarca.val(data.MarcaReferencia);

        const inputSerial = form.find('input[name=serial]')
        inputSerial.val(data.Serial);

        const inputCambio = form.find('select[name=cambio]')
        inputCambio.val(data.RealizoCambio);

        const inputObservaciones = form.find('textarea[name=observaciones]')
        inputObservaciones.val(data.Observaciones);
    });
}

$('#btn-add-equipo').on('click', function () {
    $('#titulo-agregar-evidencia').text("Agregar Equipo");

    const button = $(this);
    const mantenimientoId = button.data('id_mantenimiento');

    const form = $('#form-equipo-upload');

    //let baseUrl = "{{ route('mantenimientos.equipos.store', ['mantenimiento' => '__mantenimiento__']) }}";

    let url = baseUrlEquiposStore
        .replace('__mantenimiento__', mantenimientoId)

    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    }

    form[0].reset();

});

function editarDireccion(mantenimiento_id, direccion_id) {
    $.get(`direcciones/${direccion_id}/edit`).done(function (data) {
        $('#titulo-agregar-direccion').text("Editar Direccion");

        const form = $('#form-direccion-create');

        //let baseUrl = "{{ route('mantenimientos.direcciones.update', ['mantenimiento' => '__mantenimiento__', 'direccione' => '__direccion__']) }}";

        let url = baseUrlDireccionUpdate
            .replace('__mantenimiento__', mantenimiento_id)
            .replace('__direccion__', direccion_id);

        form.attr('action', url);

        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        } else {
            form.find('input[name=_method]').val('PUT')
        }

        const inputDireccion = form.find('input[name=direccion]')
        inputDireccion.val(data.Direccion);

        const inputBarrio = form.find('input[name=barrio]')
        inputBarrio.val(data.Barrio);

        const inputLatitud = form.find('input[name=latitud]')
        inputLatitud.val(data.Latitud);

        const inputLongitud = form.find('input[name=longitud]')
        inputLongitud.val(data.Longitud);
    });
}

$('#btn-add-direccion').on('click', function () {
    $('#titulo-agregar-direccion').text("Agregar Direccion");

    const button = $(this);
    const mantenimientoId = button.data('id_mantenimiento');

    const form = $('#form-direccion-create');

    //let baseUrl = "{{ route('mantenimientos.direcciones.store', ['mantenimiento' => '__mantenimiento__']) }}";

    let url = baseUrlDireccionStore
        .replace('__mantenimiento__', mantenimientoId)

    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    }

    form[0].reset();

});

function editarParadaReloj(mantenimiento_id, parada_reloj_id) {
    $.get(`paradas-reloj/${parada_reloj_id}/edit`).done(function (data) {
        $('#titulo-agregar-parada-reloj').text("Editar Parada de reloj");

        const form = $('#form-parada-reloj-create');

        //let baseUrl = "{{ route('mantenimientos.paradas-reloj.update', ['mantenimiento' => '__mantenimiento__', 'paradas_reloj' => '__paradas_reloj__']) }}";

        let url = baseUrlParadasRelojUpdate
            .replace('__mantenimiento__', mantenimiento_id)
            .replace('__paradas_reloj__', parada_reloj_id);

        form.attr('action', url);

        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        } else {
            form.find('input[name=_method]').val('PUT')
        }

        const inputFechaInicio = form.find('input[name=fecha_inicio]')
        inputFechaInicio.val(data.InicioParadaDeReloj);

        const inputHoraInicio = form.find('input[name=hora_inicio]')
        inputHoraInicio.val(formatHora(data.HoraInicio) + ":" + formatHora(data.MinInicio));

        const inputFechaFin = form.find('input[name=fecha_fin]')
        inputFechaFin.val(data.FinParadaDeReloj);

        const inputHoraFin = form.find('input[name=hora_fin]')
        inputHoraFin.val(formatHora(data.HoraFin) + ":" + formatHora(data.MinFin));

        const inputDescripcion = form.find('textarea[name=descripcion]')
        inputDescripcion.val(data.DescripcionParada);
    });
}

function formatHora(hora) {
    if (hora < 10) {
        return hora.padStart(2, "0");
    }
    return hora;
}

$('#btn-add-parada-reloj').on('click', function () {
    $('#titulo-agregar-parada-reloj').text("Agregar Parada de reloj");

    const button = $(this);
    const mantenimientoId = button.data('id_mantenimiento');

    const form = $('#form-parada-reloj-create');

    //let baseUrl = "{{ route('mantenimientos.paradas-reloj.store', ['mantenimiento' => '__mantenimiento__']) }}";

    let url = baseUrlParadasRelojStore
        .replace('__mantenimiento__', mantenimientoId)

    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    } 

    form[0].reset();

});

function editarMaterial(mantenimiento_id, material_id) {
    $.get(`materiales/${material_id}/edit`).done(function (data) {
        $('#titulo-agregar-material').text("Editar Material");

        const form = $('#form-material-create');

        //let baseUrl = "{{ route('mantenimientos.materiales.update', ['mantenimiento' => '__mantenimiento__', 'materiale' => '__materiale__']) }}";

        let url = baseUrlMaterialesUpdate
            .replace('__mantenimiento__', mantenimiento_id)
            .replace('__materiale__', material_id);

        form.attr('action', url);

        if (form.find('input[name="_method"]').length === 0) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        } else {
            form.find('input[name=_method]').val('PUT')
        }

        const inputCantidad = form.find('input[name=cantidad]')
        inputCantidad.val(data.Cantidad);

        const inputUnidad = form.find('select[name=unidad]')
        inputUnidad.val(data.Unidad);

        const inputInsumo = form.find('select[name=insumo]')
        inputInsumo.val(data.InsumoId);

        const inputDescripcion = form.find('textarea[name=descripcion]')
        inputDescripcion.val(data.Descripcion);
    });
}

$('#btn-add-material').on('click', function () {
    $('#titulo-agregar-material').text("Agregar Material");

    const button = $(this);
    const mantenimientoId = button.data('id_mantenimiento');

    const form = $('#form-material-create');

    //let baseUrl = "{{ route('mantenimientos.materiales.store', ['mantenimiento' => '__mantenimiento__']) }}";

    let url = baseUrlMaterialesStore
        .replace('__mantenimiento__', mantenimientoId)

    form.attr('action', url)

    if (form.find('input[name="_method"]').length != 0) {
        form.find('input[name="_method"]').attr('value', 'POST');
    }

    form[0].reset();

});

