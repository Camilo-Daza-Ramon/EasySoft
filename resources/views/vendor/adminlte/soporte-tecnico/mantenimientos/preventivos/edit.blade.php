@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-calendar-check-o"></i>  Editar Mantenimiento Preventivo #{{$mantenimiento->ProgMantid}}</h1>
@endsection

@section('main-content')
  <div class="row">

    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border bg-blue">
          <h3 class="box-title">Detalles</h3>
        </div>
        <form id="form-editar" action="{{route('preventivos.update', $mantenimiento->ProgMantid)}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            {{csrf_field()}}
            <!-- /.box-header -->
            <div class="box-body table-responsive">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th class="bg-gray"># Mantenimiento</th>
                    <td colspan="3"><h4>{{$mantenimiento->NumeroDeMantenimiento}}</h4></td>
                </tr>
                <tr>
                    <th class="bg-gray">Cantidad de Clientes Afectados</th>
                    <td>{{$mantenimiento->CantidadUsuariosAfectados}}</td>

                    <th class="bg-gray">
                    Estado
                    </th>
                    <td>                  
                    <select name="estado" class="form-control" required>
                        <option value="">Elija una opcion</option>
                        @foreach($estados as $estado)
                            <option value="{{$estado}}" {{($estado == $mantenimiento->estado)? 'selected' : ''}}>{{$estado}}</option>
                        @endforeach
                    </select>                  
                    </td>
                </tr>
                <tr>
                    <th class="bg-gray">
                    Tipo Mantenimiento
                    </th>
                    <td>
                        <select class="form-control " name="tipo" required>
                            <option value="">Elija una opción</option>
                            @foreach($tipos_mantenimientos as $tipo)                                    
                                <option value="{{$tipo->TipoDeMantenimiento}}" {{($mantenimiento->Tipo == $tipo->TipoDeMantenimiento)? 'selected' : ''}}>{{$tipo->Descripcion}}</option>
                            @endforeach
                        </select>
                    </td>

                    <th class="bg-gray">
                    Proyecto
                    </th>
                    <td>
                        <select class="form-control" name="proyecto" id="proyecto">
                            <option value="">Elija un proyecto</option>
                            @foreach($proyectos as $proyecto)
                                @if($proyecto->Status == 'A')
                                    <option value="{{$proyecto->ProyectoID}}" {{($mantenimiento->ProyectoId == $proyecto->ProyectoID) ? 'selected' : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>

                </tr>
                <tr>
                    <th class="bg-gray">
                    Departamento
                    </th>
                    <td>
                        <select class="form-control" name="departamento" id="departamento" required>
                            <option value="">Elija un departamento</option>
                            @foreach($departamentos as $departamento)
                                <option value="{{$departamento->DeptId}}" {{($mantenimiento->Departamento == $departamento->DeptId) ? 'selected' : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                            @endforeach
                        </select>
                    </td>
                    <th class="bg-gray">
                    Municipio
                    </th>
                    <td>
                        <select class="form-control" name="municipio" id="municipio" required>
                            <option value="">Elija un municipio</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="bg-gray">
                    <i class="fa fa-user-o margin-r-5"></i> Creado por
                    </th>
                    <td>
                        <select class="form-control" name="agente" required>
                            <option value="">Elija una opción</option>
                            @foreach($agentes as $agente)
                                <option value="{{$agente->id}}" {{($mantenimiento->user_crea == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <th class="bg-gray">
                        <i class="fa fa-user margin-r-5"></i>Cerrado por
                    </th>
                    <td colspan="3">
                        <select class="form-control" name="agente_cierra">
                            <option value="">Elija una opcion</option>
                            @foreach($agentes as $agente)
                                @if(empty($mantenimiento->user_cerro))
                                    <option value="{{$agente->id}}" {{ (Auth::user()->id == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                                @else
                                    <option value="{{$agente->id}}" {{($mantenimiento->user_cerro == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                    
                </tr>

                <tr>
                    <th class="bg-gray">Fecha de Creación</th>
                    <td>{{$mantenimiento->Fecha}}</td>
                    <th class="bg-gray">Fecha Agendado</th>
                    <td>
                        <input type="date" name="fecha_programada" class="form-control" value="{{$mantenimiento->fecha_programada}}" required>
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
                <th class="bg-gray">Dias sin Solución</th>
                <th class="bg-gray">Red</th>
                </tr>
                <tr>
                <td>
                    <input type="datetime-local" name="fecha_cierre_hora_inicio" class="form-control" value="{{$mantenimiento->fecha_cierre_hora_inicio}}" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
                </td>
                <td>
                    <input type="datetime-local" name="fecha_cierre_hora_fin" class="form-control" value="{{$mantenimiento->fecha_cierre_hora_fin}}" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
                </td>
                <td>
                    {{$indisponibilidad['dias']}} Días
                </td>
                <td>
                    <input type="text" name="red" class="form-control" value="{{$mantenimiento->IdentificacionDeLaRed}}" placeholder="Identif. de la Red" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
                </td>
                </tr>
                <tr class="bg-gray">
                <th rowspan="2">
                    TIEMPO DE INDISPONIBILIDAD
                </th>
                <th>En Días</th>
                <th>En Horas</th>
                <th>En Minutos</th>
                </tr>
                <tr>
                <td>                
                    {{$indisponibilidad['dias']}} Días
                </td>
                <td>
                    {{$indisponibilidad['horas']}} Horas
                </td>
                <td>{{$indisponibilidad['minutos']}} Minutos</td>
                </tr>
                <tr>
                <th class="bg-gray" colspan="2">Tipo Tecnologia</th>
                <th class="bg-gray">Retornó el servicio?</th>
                <th class="bg-gray">Servicios Activos</th>
                </tr>
                <tr>
                <td colspan="2">
                    <select class="form-control" name="tipo_tecnologia" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
                        <option value="">Elija una opción</option>
                        @foreach($tipos_tecnologias as $tipo)
                        <option value="{{$tipo}}" {{($mantenimiento->TipoDeTecnologiaImplementada == $tipo) ? 'selected' : ''}}>{{$tipo}}</option>
                        @endforeach
                    </select>
                </td>              
                <td>
                    <select class="form-control" name="retorna_servicio" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
                        <option value="">Elija una opción</option>
                        @foreach($respuestas_cortas as $respuesta_corta)
                        <option value="{{$respuesta_corta}}" {{($mantenimiento->SeRetornoServicio == $respuesta_corta)? 'selected' : ''}}>{{$respuesta_corta}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control" name="servicio_activo" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
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
                        <input type="number" class="form-control" name="velocidad_descarga" value="{{$mantenimiento->VelocidadDeBajada}}" min="0" placeholder="Downstream" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
                        <span class="input-group-addon">Mb</span>
                    </div>
                </td>
                <th class="bg-gray">Velocidad Subida</th>
                <td>
                    <div class="input-group">
                        <input type="number" class="form-control" name="velocidad_subida" value="{{$mantenimiento->VelocidadDeSubida}}" min="0" placeholder="Upstream" {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>
                        <span class="input-group-addon">Mb</span>
                    </div>
                </td>
                
                </tr>
                <tr>
                <th class="bg-gray">Observaciones</th>
                <td colspan="3">
                    <textarea name="observaciones" class="form-control" rows="4" placeholder="Indique las labores a realizar en el mantenimiento." {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>{{$mantenimiento->Observaciones}}</textarea>
                </td>
                </tr>
                <tr>
                <th class="bg-gray">Hallazgos</th>
                <td colspan="3">
                    <textarea name="hallazgos" class="form-control" rows="4" placeholder="Describa los hallazgos que encontró al realizar el mantenimiento." {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>{{$mantenimiento->ObservacionesHallazgos}}</textarea>
                </td>
                </tr>
                <tr>
                    <th class="bg-gray">Procedimiento</th>
                    <td colspan="3">
                        <textarea name="procedimiento" class="form-control" rows="4" placeholder="Describa el procedimiento del mantenimiento realizado." {{($mantenimiento->estado == 'CERRADO')? 'required' : ''}}>{{$mantenimiento->Procedimiento}}</textarea>
                    </td>
                </tr>                

                <tr>
                    <th class="bg-gray">
                    <i class="fa fa-user-secret margin-r-5"></i>Atendido por
                    </th>
                    <td>
                    <select class="form-control" name="atendido_por">
                        <option value="">Elija una opción</option>
                        @foreach($agentes as $agente)
                        <option value="{{$agente->id}}" {{($mantenimiento->user_atiende == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                        @endforeach
                    </select>
                    </td>

                    <th class="bg-gray">Firma Tecnico</th>
                    <td colspan="3">
                      @if(isset($mantenimiento->usuario_atiende))
                        @if(!empty($mantenimiento->usuario_atiende->firma))
                          <img src="{{Storage::url($mantenimiento->usuario_atiende->firma)}}" height="100px">
                        @endif
                      @endif

                      <select name="pregunta_firma_usuario" class="form-control" {{(isset($mantenimiento->usuario_atiende))? (empty($mantenimiento->usuario_atiende->firma))? 'required' : '' : '' }}>
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

            </table>      
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
            </div>
         </form>
      </div>
    </div>
  </div>

  @include('adminlte::instalaciones.partials.firma.add')


  @section('mis_scripts')

    <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios3.js')}}"></script>
    <script src="/js/signature_pad.umd.js"></script>
    <script src="/js/firma.js"></script>    
	<script src="/js/usuarios/firma.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		    buscar_departamentos({{$mantenimiento->Departamento}});
            buscar_municipio({{$mantenimiento->Municipio}});
		});
	</script>

    <script type="text/javascript">
        var firma_usuario = null;
    </script>

    <script type="text/javascript">

        toastr.options.positionClass = 'toast-bottom-right';

        $('#form-editar').on('submit',function (e) {

            e.preventDefault();

            const boton = $(this).find('button[type="submit"]');

            boton.attr('disabled',true);

            let form = document.getElementById('form-editar');
                

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
        });
    </script>
  

  @endsection
@endsection