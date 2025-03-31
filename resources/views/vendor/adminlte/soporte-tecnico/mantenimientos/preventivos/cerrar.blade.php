@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-calendar-check-o"></i> Mantenimiento Preventivos #{{$mantenimiento->ProgMantid}}</h1>
@endsection

@section('main-content')
<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border bg-blue">
                <h3 class="box-title">Detalles</h3>
            </div>
            
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <form id="form-cerrar" action="{{route('preventivos.cerrar', $mantenimiento_id)}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-gray"># Mantenimiento</th>
                                <td colspan="3">
                                    <h4>{{$mantenimiento->NumeroDeMantenimiento}}</h4>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray">Cantidad de Clientes Afectados</th>
                                <td>{{$mantenimiento->CantidadUsuariosAfectados}}</td>

                                <th class="bg-gray">
                                    Estado
                                </th>
                                <td>
                                    {{$mantenimiento->estado}}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray">
                                    Tipo Mantenimiento
                                </th>
                                <td>
                                    @if(!empty($mantenimiento->Tipo))
                                    {{$mantenimiento->tipo_mantenimiento->tipo}} -
                                    {{$mantenimiento->tipo_mantenimiento->Descripcion}}
                                    @endif
                                </td>

                                <th class="bg-gray">
                                    Proyecto
                                </th>
                                <td>
                                    @if(!empty($mantenimiento->ProyectoId))
                                    {{$mantenimiento->proyecto->DescripcionProyecto}}
                                    @endif
                                </td>

                            </tr>
                            <tr>
                                <th class="bg-gray">
                                    Departamento
                                </th>
                                <td>
                                    {{$mantenimiento->municipio->NombreDepartamento}}
                                </td>
                                <th class="bg-gray">
                                    Municipio
                                </th>
                                <td>
                                    {{$mantenimiento->municipio->NombreMunicipio}}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray">
                                    <i class="fa fa-user-o margin-r-5"></i> Creado por
                                </th>
                                <td>
                                    @if(isset($mantenimiento->usuario_crea))
                                    {{$mantenimiento->usuario_crea->name}}
                                    @endif
                                </td>
                                <th class="bg-gray">
                                    <i class="fa fa-user margin-r-5"></i>Cerrado por
                                </th>
                                <td>
                                    @if(isset($mantenimiento->usuario_cierra))
                                    {{$mantenimiento->usuario_cierra->name}}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-gray">Fecha de Creación</th>
                                <td>{{$mantenimiento->Fecha}}</td>
                                <th class="bg-gray">Fecha Agendado</th>
                                <td>
                                    {{$mantenimiento->fecha_programada}}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <tr class="dark">
                            <th class="bg-blue text-center" colspan="4">DATOS DE CIERRE</th>
                        </tr>
                        <tr>
                            <th class="bg-gray">Fecha Inicio</th>
                            <th class="bg-gray">Fecha Fin</th>
                            <th class="bg-gray" colspan="2">Red</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="datetime-local" name="fecha_cierre_hora_inicio" class="form-control" value="{{$mantenimiento->fecha_cierre_hora_inicio}}" required>
                            </td>
                            <td>
                                <input type="datetime-local" name="fecha_cierre_hora_fin" class="form-control" value="{{$mantenimiento->fecha_cierre_hora_fin}}" required>
                            </td>
                            <td colspan="2">
                                <input type="text" name="red" class="form-control" value="{{$mantenimiento->IdentificacionDeLaRed}}" placeholder="Identif. de la Red" required>
                            </td>
                        </tr>

                        <tr>
                            <th class="bg-gray" colspan="2">Tipo Tecnologia</th>
                            <th class="bg-gray">Retornó el servicio?</th>
                            <th class="bg-gray">Servicios Activos</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <select class="form-control" name="tipo_tecnologia" required>
                                    <option value="">Elija una opción</option>
                                    @foreach($tipos_tecnologias as $tipo)
                                        <option value="{{$tipo}}" {{($mantenimiento->TipoDeTecnologiaImplementada == $tipo) ? 'selected' : ''}}> {{$tipo}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="retorna_servicio" required>
                                    <option value="">Elija una opción</option>
                                    @foreach($respuestas_cortas as $respuesta_corta)
                                        <option value="{{$respuesta_corta}}" {{($mantenimiento->SeRetornoServicio == $respuesta_corta)? 'selected' : ''}}>{{$respuesta_corta}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="servicio_activo" required>
                                    <option value="">Elija una opción</option>
                                    @foreach($respuestas_cortas as $respuesta_corta)
                                        <option value="{{$respuesta_corta}}" {{($mantenimiento->ServicioQuedaActivo == $respuesta_corta)? 'selected' : ''}}> {{$respuesta_corta}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Velocidad Bajada</th>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="velocidad_descarga" value="{{$mantenimiento->VelocidadDeBajada}}" min="0" placeholder="Downstream" required>
                                    <span class="input-group-addon">Mb</span>
                                </div>
                            </td>
                            <th class="bg-gray">Velocidad Subida</th>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="velocidad_subida" value="{{$mantenimiento->VelocidadDeSubida}}" min="0" placeholder="Upstream" required>
                                    <span class="input-group-addon">Mb</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Observaciones</th>
                            <td colspan="3">
                                <p class="text-justify">
                                    {{$mantenimiento->Observaciones}}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Hallazgos</th>
                            <td colspan="3">
                                <textarea name="hallazgos" class="form-control" rows="4" placeholder="Describa los hallazgos que encontró al realizar el mantenimiento." required>{{$mantenimiento->ObservacionesHallazgos}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Procedimiento</th>
                            <td colspan="3">
                                <textarea name="procedimiento" class="form-control" rows="4" placeholder="Describa el procedimiento del mantenimiento realizado." required>{{$mantenimiento->Procedimiento}}</textarea>
                            </td>
                        </tr>

                        @if(empty(Auth::user()->firma))
                        <tr>
                            <th class="bg-gray">Firma Tecnico</th>
                            <td colspan="3">
                                <select name="pregunta_firma_usuario" class="form-control" required>
                                    <option value="">Elija una opción</option>
                                    <option>FIRMAR</option>
                                    <option>SUBIR FIRMA</option>
                                </select>
                                <span class="help-block"></span>

                                <div id="firmaUsuarioSubir" class="form-group col-md-12" style="display:none;">
                                    <input type="file" class="form-control" name="firma_usuario" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
                                    <span class="help-block"></span>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </table>
                </form>
                <br>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding">
                    <!-- required for floating -->
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">

                        <li class="active">
                            <a href="#fotos" data-toggle="tab">
                                <i class="fa fa-image"></i> <span class="hidden-xs hidden-sm hidden-md">Fotos</span>
                            </a>
                        </li>
                        <li>
                            <a href="#equipos" data-toggle="tab">
                                <i class="fa fa-hdd-o"></i> <span class="hidden-xs hidden-sm hidden-md">Equipos</span>
                            </a>
                        </li>
                        <li>
                            <a href="#diagnosticos" data-toggle="tab">
                                <i class="fa fa-exclamation"></i> <span
                                    class="hidden-xs hidden-sm hidden-md">Diagnostico</span>
                            </a>
                        </li>
                        <li>
                            <a href="#direcciones" data-toggle="tab">
                                <i class="fa fa-map-marker"></i> <span
                                    class="hidden-xs hidden-sm hidden-md">Direcciones</span>
                            </a>
                        </li>
                        <li>
                            <a href="#pruebas" data-toggle="tab">
                                <i class="fa fa-list"></i> <span class="hidden-xs hidden-sm hidden-md">Pruebas</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-10 col-sm-10  col-md-10 col-lg-10">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="fotos">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.fotos.show')
                        </div>
                        <div class="tab-pane" id="equipos">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.equipos.show')
                        </div>
                        <div class="tab-pane" id="diagnosticos">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.diagnosticos.show')
                        </div>
                        <div class="tab-pane" id="direcciones">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.direcciones.show')
                        </div>
                        <div class="tab-pane" id="pruebas">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.pruebas.show')
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="button" id="btnCerrar" class="btn btn-success pull-right">Solicitar Cierre.</button>
            </div>

            <div id="result"></div>          
            
        </div>
    </div>
</div>



@permission('mantenimientos-archivos-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.fotos.add')
@endpermission

@permission('mantenimientos-equipos-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.equipos.add')
@endpermission

@permission('mantenimientos-diagnosticos-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.diagnosticos.add')
@endpermission

@permission('mantenimientos-pruebas-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.pruebas.add')
@endpermission

@include('adminlte::instalaciones.partials.firma.add')


@section('mis_scripts')

<script type="text/javascript" src="{{asset('js/mantenimientos/fotos.js')}}"></script>

<script src="/js/signature_pad.umd.js"></script>
<script src="/js/firma.js"></script>        
<script src="/js/usuarios/firma.js"></script>

@if(empty(Auth::user()->firma))
    <script type="text/javascript">
        var firma_usuario = null;
    </script>
@endif

<script type="text/javascript">

toastr.options.positionClass = 'toast-bottom-right';

$('#btnCerrar').click(function () {

    const boton = $(this);

    boton.attr('disabled',true);

    let form = document.getElementById('form-cerrar');
        
    if(form.checkValidity()) { // Valida los campos requeridos antes de enviar

        $('#result').removeClass('overlay').empty();			
        $('#result').addClass('overlay').append('<i class="fa fa-refresh fa-spin"></i>');
    
        var formData = new FormData(form);

        

        if ($('select[name="pregunta_firma_usuario"]').val() == 'FIRMAR') {
            formData.append('firma_usuario', firma_usuario);
        }else if ($('select[name="pregunta_firma_usuario"]').val() == 'SUBIR FIRMA'){
            formData.append('firma_usuario', $('input[name="firma_usuario"]')[0].files[0]);
        }

        $.ajax({
            url: form.action, // URL del formulario
            type: form.method, // Método (POST/GET)
            dataType: "json",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
                
                if(res['tipo_mensaje'] == 'success'){                        
                    toastr.success(res['mensaje']);

                    setTimeout(() => {
                        location.replace("/mantenimientos/preventivos");
                    }, "3000");

                }else{
                    boton.attr('disabled',false);

                    $('#result').removeClass('overlay').empty();                                
                    toastr.error(res['mensaje']); 
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                boton.attr('disabled',false);

                if(jqXHR.status == 422){

                    var objeto = JSON.parse(jqXHR.responseText);

                    $.each(objeto, function(index, respuestaObj){			                   
                        var padre = $('[name="' + index+'"]').parent();
                        padre.removeClass('has-success').addClass('has-error');
                        padre.find('.help-block').text(respuestaObj)
                        //padre.append('<span class="text-danger">' + respuestaObj +'</span>');
                    });

                    toastr.error("Corrija los campos");
                }else{
                    toastr.error(errorThrown);
                }
            }
        });

    }else{
        form.reportValidity(); // Muestra los mensajes de error de HTML5
        boton.attr('disabled',false);

    }
});
</script>

<script type="text/javascript">
    let baseUrlArchivosUpdate = "{{ route('mantenimientos.archivos.update', ['mantenimiento' => '__mantenimiento__', 'archivo' => '__archivo__']) }}";
    let baseUrlArchivosStore = "{{ route('mantenimientos.archivos.store', ['mantenimiento' => '__mantenimiento__']) }}";
    let baseUrlEquiposUpdate = "{{ route('mantenimientos.equipos.update', ['mantenimiento' => '__mantenimiento__', 'equipo' => '__equipo__']) }}";
    let baseUrlEquiposStore = "{{ route('mantenimientos.equipos.store', ['mantenimiento' => '__mantenimiento__']) }}";
    let baseUrlDireccionUpdate = "{{ route('mantenimientos.direcciones.update', ['mantenimiento' => '__mantenimiento__', 'direccione' => '__direccion__']) }}";
    let baseUrlDreccionStore = "{{ route('mantenimientos.direcciones.store', ['mantenimiento' => '__mantenimiento__']) }}";
    let baseUrlMaterialesUpdate = "{{ route('mantenimientos.materiales.update', ['mantenimiento' => '__mantenimiento__', 'materiale' => '__materiale__']) }}";
    let baseUrlMaterialesStore = "{{ route('mantenimientos.materiales.store', ['mantenimiento' => '__mantenimiento__']) }}";
</script>

<script type="text/javascript" src="/js/mantenimientos/submodulos.js"></script>

@endsection
@endsection