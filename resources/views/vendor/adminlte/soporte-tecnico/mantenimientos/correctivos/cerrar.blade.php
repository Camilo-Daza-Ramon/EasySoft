@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-calendar-check-o"></i> CERRAR Mantenimiento Correctivo #{{$mantenimiento->MantId}}</h1>
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
                <form id="form-cerrar" action="{{route('correctivos.cerrar', $mantenimiento_id)}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th class="bg-gray"># Mantenimiento</th>
                            <td colspan="3"><h4>{{$mantenimiento->NumeroDeTicket}}</h4></td>
                        </tr>

                        <tr>
                            <th class="bg-gray">Cantidad de Clientes Afectados</th>
                            <td>
                            @if(!empty($mantenimiento->ClienteId))
                                1
                            @else
                                {{$mantenimiento->clientes()->count()}}
                            @endif
                            </td>

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
                            @if(!empty($mantenimiento->TipoMantenimiento))
                            {{$mantenimiento->tipo_mantenimiento->tipo}} - {{$mantenimiento->tipo_mantenimiento->Descripcion}}
                            @endif
                            </td>

                            <th class="bg-gray">
                            Proyecto
                            </th>
                            <td>
                            @if(!empty($mantenimiento->ProyectoId))
                                {{$mantenimiento->proyecto->NumeroDeProyecto}}
                            @else
                                SIN DEFINIR
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
                            <td></td>
                            <th class="bg-gray">
                            <i class="fa fa-user margin-r-5"></i>Cerrado por
                            </th>
                            <td></td>
                        </tr>

                        <tr>
                            <th class="bg-gray">Fecha de apertura</th>
                            <td>{{date('Y-m-d H:i:s', strtotime($mantenimiento->Fecha))}}</td>
                            <th class="bg-gray">Fecha Límite</th>
                            <td>
                            @if(!empty($mantenimiento->FechaMaxima))
                                {{date('Y-m-d', strtotime($mantenimiento->FechaMaxima))}}
                            @endif
                            </td> 
                        </tr>

                        <tr>
                            <th class="bg-gray">
                                Tipo Falla
                            </th>
                            <td>
                            @if(!empty($mantenimiento->TipoFalloID))
                                {{$mantenimiento->tipo_fallo->DescipcionFallo}}
                            @endif
                            </td>

                            <th class="bg-gray">Prioridad </th>
                            <td>
                            @if(!empty($mantenimiento->Prioridad))
                                {{$mantenimiento->Prioridad}}
                            @else
                                Sin definir.
                            @endif
                            </td>   
                        </tr>

                        <tr>
                            <th class="bg-gray">
                            Canal de Atención
                            </th>
                            <td>
                            @if(!empty($mantenimiento->TipoEntrada))
                            {{$mantenimiento->medio_atencion->Descripcion}}
                            @else
                            sin definir
                            @endif
                            </td>

                            <th class="bg-gray">
                            Tikect
                            </th>
                            <td>
                            @if(!empty($mantenimiento->TicketId))
                                <a href="{{route('tickets.show', $mantenimiento->TicketId)}}">{{$mantenimiento->TicketId}}</a>
                            @else
                                sin definir
                            @endif
                            </td>

                                        
                        </tr>              
                        
                        <tr>
                            <th class="bg-gray">Descripcion del problema</th>
                            <td colspan="3">{{$mantenimiento->DescripcionProblema}}</td>
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
                            <th class="bg-gray">Dias sin Solución</th>
                            <th class="bg-gray">Tiempo de Indisponibilidad</th>
                        </tr>
                        <tr>
                            <td>
                            <input class="form-control" type="datetime-local" name="fecha_hora_inicio_cierre" value="{{(!empty($mantenimiento->fecha_cierre_hora_inicio))? date('Y-m-d\TH:i:s', strtotime($mantenimiento->fecha_cierre_hora_inicio)) : ''}}" required>
                            </td>
                            <td>
                            <input class="form-control" type="datetime-local" name="fecha_hora_fin_cierre" value="{{(!empty($mantenimiento->fecha_cierre_hora_fin))? date('Y-m-d\TH:i:s', strtotime($mantenimiento->fecha_cierre_hora_fin)) : ''}}" required>
                            </td>
                            <td>
                            <?php
                                $contador = null;

                                if(!empty($mantenimiento->fecha_cierre_hora_fin)){
                                $contador = date_diff(date_create($mantenimiento->Fecha), date_create($mantenimiento->fecha_cierre_hora_fin));
                                }else{
                                $contador = date_diff(date_create($mantenimiento->Fecha), date_create('now'));
                                }
                            ?>
                            {{$contador->format('%a')}} Días
                            </td>
                            <td>
                            Por definir
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Tipo Tecnologia</th>
                            <th class="bg-gray">Red</th>
                            <th class="bg-gray">Retornó el servicio?</th>
                            <th class="bg-gray">Servicios Activos</th>
                        </tr>
                        <tr>
                            <td>
                            <select class="form-control" name="tipo_tecnologia" required>
                                <option value="">Elija una opción</option>
                                @foreach($tipos_tecnologias as $tipo)
                                    <option value="{{$tipo}}" required>{{$tipo}}</option>
                                @endforeach
                                </select>
                            </td>
                            <td>
                            <input type="text" class="form-control" name="red" value="{{$mantenimiento->Red}}" placeholder="Identif. de la Red" required>
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
                                <option value="{{$respuesta_corta}}" {{($mantenimiento->ServicioQuedaActivo == $respuesta_corta)? 'selected' : ''}}>{{$respuesta_corta}}</option>
                                @endforeach
                            </select> 

                            </td>
                        </tr>
                        <tr>

                            <th class="bg-gray">Velocidad Bajada</th>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="velocidad_descarga" value="{{$mantenimiento->VelocidadDeBajada}}" placeholder="Downstream" min="0" step="0.01" required>
                                    <span class="input-group-addon">Mb</span>
                                </div>
                            </td>

                            <th class="bg-gray">Velocidad Subida</th>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="velocidad_subida" value="{{$mantenimiento->VelocidadDeSubida}}" placeholder="Upstream" min="0" step="0.01" required>
                                    <span class="input-group-addon">Mb</span>
                                </div>
                            </td>
                            
                        </tr>
                        <tr>
                            <th class="bg-gray">Solucion</th>
                            <td colspan="3">
                            <textarea class="form-control" name="solucion" placeholder="Describa la solución al mantenimiento ejecutado." required>{{$mantenimiento->Solucion}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Procedimiento</th>
                            <td colspan="3">                
                            <textarea class="form-control" name="procedimiento" placeholder="Describa el procedimiento del mantenimiento realizado." required>{{$mantenimiento->Procedimiento}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Observaciones</th>
                            <td colspan="3">
                            <textarea class="form-control" name="observaciones" placeholder="Describa las observaciones del mantenimiento." required>{{$mantenimiento->Observaciones}}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-gray">Observaciones de Cierre</th>
                            <td colspan="3">
                            <textarea class="form-control" name="observaciones_cierre" placeholder="Indique las observaciones que se identificaron al momento de cerrar el mantenimiento." required>{{$mantenimiento->ObservacionDeCierre}}</textarea>
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

                    @if(!empty($mantenimiento->ClienteId))
                        <table class="table table-bordered">
                            <thead>
                                <tr class="dark"> 
                                    <th class="bg-blue text-center" colspan="2">PERSONA QUIEN RECIBE EL MANTENIMIENTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="bg-gray">Parentezco</th>
                                    <td>
                                        <div>
                                            <select name="parentezco" class="form-control" required>
                                                <option value="">Elija una opción</option>
                                                <option>TITULAR</option>
                                                <option>FAMILIAR</option>
                                                <option>OTRO</option>
                                            </select>
                                        </div>
                                        <div style="display:none;">
                                            <br>
                                            <input type="text" name="recibe_otro" placeholder="Parentezco con el titular" class="form-control">
                                        </div>
                                        
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-gray">Nombre</th>
                                    <td>
                                        <input type="text" class="form-control" name="recibe_nombre" placeholder="Nombre Completo" require>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-gray">Cedula</th>
                                    <td>
                                        <input type="number" class="form-control" name="recibe_cedula" placeholder="Numero de Documento" min-length="5" require>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th class="bg-gray">Firma</th>
                                    <td>
                                        <select name="pregunta_firma" class="form-control" required>
                                            <option value="">Elija una opción</option>
                                            <option>FIRMAR</option>
                                            <option>SUBIR FIRMA</option>
                                        </select>        
                                        <span class="help-block"></span>

                                        <div id="firmaSubir" class="form-group col-md-12" style="display:none;">										
                                            <input type="file" class="form-control" name="firma" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
                                            <span class="help-block"></span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            
                        </table>
                    @endif
                </form>
                <br>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding">
                    <!-- required for floating -->
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">

                        <li class="active">
                            <a href="#clientes" data-toggle="tab">
                            <i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Clientes Afectados</span>
                            </a>
                        </li>

                        <li>
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
                        <li>
                          <a href="#soluciones" data-toggle="tab">
                            <i class="fa fa-check-square-o"></i>  <span class="hidden-xs hidden-sm hidden-md">Soluciones</span>
                          </a>
                        </li>
                        <li>
                            <a href="#materiales" data-toggle="tab">
                                <i class="fa fa-wrench"></i>  <span class="hidden-xs hidden-sm hidden-md">Materiales</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-10 col-sm-10  col-md-10 col-lg-10">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="clientes">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.clientes.show')
                        </div>

                        <div class="tab-pane" id="fotos">
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
                        <div class="tab-pane" id="soluciones">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.soluciones.show')
                        </div>
                        <div class="tab-pane" id="materiales">
                            @include('adminlte::soporte-tecnico.mantenimientos.partials.materiales.show')
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

    @permission('mantenimientos-direcciones-crear')
        @include('adminlte::soporte-tecnico.mantenimientos.partials.direcciones.add')
    @endpermission

    @permission('mantenimientos-pruebas-crear')
        @include('adminlte::soporte-tecnico.mantenimientos.partials.pruebas.add')
    @endpermission

    @permission('mantenimientos-soluciones-crear')
      @include('adminlte::soporte-tecnico.mantenimientos.partials.soluciones.add')
    @endpermission

    @permission('mantenimientos-materiales-crear')
        @include('adminlte::soporte-tecnico.mantenimientos.partials.materiales.add')
    @endpermission

    @include('adminlte::instalaciones.partials.firma.add')

    @section('mis_scripts')
        <script src="/js/signature_pad.umd.js"></script>
        <script src="/js/firma.js"></script>        
		<script src="/js/usuarios/firma.js"></script>

        <script type="text/javascript" src="{{asset('js/mantenimientos/fotos.js')}}"></script>

        @if(empty(Auth::user()->firma))
            <script type="text/javascript">
                var firma_usuario = null;
            </script>
        @endif



        @if(!empty($mantenimiento->ClienteId))

            <script>
                var firma = null;    
                const titular = '{{$mantenimiento->cliente->NombreBeneficiario ." " . $mantenimiento->cliente->Apellidos}}';
                const cedula_titular = '{{$mantenimiento->cliente->Identificacion}}';
            </script>
            <script src="/js/clientes/firma.js"></script>

            <script type="text/javascript">

                $('select[name="parentezco"').on('change', function(){
                    if($(this).val().length > 0) {
                        if($(this).val() == "TITULAR"){

                            if(titular.length > 0){
                                $('input[name="recibe_nombre"]').val(titular);
                                $('input[name="recibe_cedula"]').val(cedula_titular);

                            }

                            $('input[name="recibe_otro"]').attr('required', false).parent().hide();

                        }else{
                            $('input[name="recibe_nombre"]').val('');
                            $('input[name="recibe_cedula"]').val('');
                            $('input[name="recibe_otro"]').attr('required', true).parent().show();
                        }
                    }
                })

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

                    if ($('select[name="pregunta_firma"]').val() == 'FIRMAR') {
                        formData.append('firma', firma);
                    }

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
                                    location.replace("/mantenimientos/correctivos");
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
            let baseUrlMaterialesUpdate = "{{ route('mantenimientos.materiales.update', ['mantenimiento' => '__mantenimiento__', 'materiale' => '__materiale__']) }}";
            let baseUrlMaterialesStore = "{{ route('mantenimientos.materiales.store', ['mantenimiento' => '__mantenimiento__']) }}";
        </script>

        <script type="text/javascript" src="/js/mantenimientos/submodulos.js"></script>

    @endsection
@endsection