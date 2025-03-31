
let municipios = [];

const refreshMunicipios = () => {
    $('#municipio option:selected').each(function() {
        const optionSelected = {
            id: $(this).val(),
            text: $(this).text(),
            departamento: $('#departamento').val()
        };

        if (!municipios.some(mun => mun.id == optionSelected.id)) {
            municipios.push(optionSelected);
        }
    });
};

$('#municipio').on('change', function() {
    refreshMunicipios();
});


$(document).on('cargandoMunicipios', function() {
    refreshMunicipios();
});

$(document).on('municipiosCargados', function() {
    let municipiosId = [];
    $('#municipio option').each(function() {
        municipiosId.push($(this).val());
    });    
    
    municipios.forEach(function(value) {
        if (!municipiosId.includes(value.id.toString())) {
            $('#municipio').append('<option value="' + value.id + '" selected>' + value.text + '</option>').trigger('change');
        } else {
            $('#municipio option').filter(function() {
                return $(this).val() == value.id;
            }).prop('selected', true).trigger('change');
        }
    });

});

$('#municipio').on('select2:unselect', function(e) {

    const id = e.params.data.id;
    const text = e.params.data.text;
    const departamento = $('#departamento').val();

    $('#municipio option').each(function() {
        const municipioId = $(this).val();

        if (id == municipioId) {
            
            const municipio = municipios.find(function(value) {
                return value.id == municipioId;
            });

            if (departamento !== municipio.departamento) {
                $(this).remove();                
            }
        }

    });

    
    municipios = municipios.filter(function(value) {
        return value.id.toString() !== id;
    });
    $('#municipio option').filter(function() {
        return $(this).val() == id;
    }).prop('selected', false);

    $('li[title="' + text + '"]').remove();
    
});
