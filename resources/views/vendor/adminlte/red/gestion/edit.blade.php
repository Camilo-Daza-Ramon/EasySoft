@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-internet-explorer"></i> Gestion de Red</h1>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header bg-blue with-border">
                    <h2 class="box-title">Editar Plataforma de Red</h2>
                </div>
                <div class="box-body">
                    @permission('gestion-red-editar')
                    <form id="form-plataforma" action="{{ route('gestion.update', ['id' => $plataforma->id]) }}" method="POST" enctype="multipart/form-data">

                        <input name="_method" type="hidden" value="PUT">

                        @include('adminlte::red.gestion.partials.form')
                    </form>

                    @endpermission
                </div>
            </div>
        </div>
    </div>
</div>
@section('mis_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.js"></script>

<script>
    (function() {
        $('#municipio').select2();
    })();
</script>
<script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
<script type="text/javascript" src="/js/myfunctions/cambiarVisibilidadContraseña.js"></script>
<script type="text/javascript" src="/js/myfunctions/select2MultipleMunicipios.js"></script>
<script type="text/javascript">
    changeVisibilityPassword()
    buscar_departamentos()
    // VALIDATE FOMR INSTRUCCIONES

    $(document).on('click', '#submit-form-instruccion', function(e) {
        e.preventDefault();
        let fileInput = $('#archivo-instrucciones');
        let file = fileInput[0].files[0];

        if (file) {
            let ext = 'pdf';
            let fileExtension = file.name.split('.').pop().toLowerCase(); // Obtener la extensión del archivo

            if (ext === fileExtension) {
                $('#formModalInstrucciones').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('#banderaCrearInstruccion').val("1");
                let newOption = new Option(file.name, 0, false, false);
                $('#instrucciones').append(newOption).trigger('change');
                $('#instrucciones').val(0);
            } else {
                alert('El archivo debe ser de tipo: ' + ext);
                fileInput.val("");
            }
        } else {
            alert('Por favor, selecciona un archivo antes de guardar.');
            fileInput.focus();
        }
    });


    $('#submit-form-dato-acceso').on('click', function(e) {
        e.preventDefault();

        const usuario = $('#usuario').val();
        const contrasena = $('#contrasena').val();

        if (!usuario.trim() || !contrasena.trim()) {
            alert('Por favor, ingesa los datos de usuario o contraseña.');
            return
        }

        let newOption = new Option("Usuario: " + usuario, 0, false, false);
        $('#datos_acceso').append(newOption).trigger('change');
        $('#datos_acceso').val(0);
        $('#formModalDatosDeAcceso').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        //window.location.href = "{{ route('gestion.create') }}"
    });


    const municipiosSelected = '{!! json_encode($plataforma->municipios) !!}';
    const municipiosJson = JSON.parse(municipiosSelected);
    
    municipios = municipiosJson.map(function (value) {
        $('#municipio').append('<option value="' + value.MunicipioId + '" selected>' + value.NombreMunicipio + '</option>').trigger('change');
        return {
            id: value.MunicipioId,
            text: value.NombreMunicipio,
            departamento: value.NombreDepartamento
        }
    });
    
    
</script>

@endsection
@endsection