@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-wrench"></i>  Mantenimiento #{{$mantenimiento->MantId}} - Editar</h1>
@endsection

@section('main-content')
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border bg-blue">
          <h3 class="box-title">Detalles</h3>
        </div>
        <form id="form-editar" action="{{route('correctivos.update', $mantenimiento->MantId)}}" method="post" enctype="multipart/form-data">
          <input type="hidden" name="_method" value="put">
          {{csrf_field()}} 
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th class="bg-gray">Numero Mantenimiento</th>
                  <td colspan="3"><h4>{{$mantenimiento->NumeroDeTicket}}</h4></td>
                </tr>

                <tr>
                  <th class="bg-gray">Cantidad de Clientes Afectados</th>
                  <td>
                    @if($mantenimiento->clientes->count() > 0)
                      {{$mantenimiento->clientes->count()}}
                    @elseif(!empty($mantenimiento->ClienteId))
                      1
                    @else
                      {{$mantenimiento->CantidadUsuariosAfectados}}
                    @endif                  
                  </td>

                  <th class="bg-gray">
                    Estado
                  </th>
                  <td>
                    <select class="form-control" name="estado" onchange="cambiar_estado(this)">
                      <option value="">Elija una opción</option>
                      @foreach($estados as $estado)
                        <option value="{{$estado}}" {{($mantenimiento->estado == $estado) ? 'selected' : ''}}>{{$estado}}</option>
                      @endforeach
                    </select> 
                  </td>


                </tr>

                <tr>
                  <th class="bg-gray">
                    Tipo Mantenimiento
                  </th>
                  <td>
                    <select class="form-control " name="tipo_mantenimiento" required>
                        <option value="">Elija una opción</option>
                        @foreach($tipos_mantenimientos as $tipo)
                          <option value="{{$tipo->TipoDeMantenimiento}}" {{($mantenimiento->TipoMantenimiento == $tipo->TipoDeMantenimiento) ? 'selected' : ''}}>{{$tipo->Descripcion}}</option>
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
                          <option value="{{$proyecto->ProyectoID}}" {{($mantenimiento->ProyectoId == $proyecto->ProyectoID) ? 'selected' : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
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
                          <option value="{{$departamento->DeptId}}" {{($mantenimiento->municipio->DeptId == $departamento->DeptId) ? 'selected' : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                      @endforeach
                    </select>
                  </td>

                  <th class="bg-gray">
                    Municipio
                  </th>
                  <td>
                    <select class="form-control" name="municipio" id="municipio">
                        <option value="">Elija un municipio</option>
                    </select>
                  </td>
                </tr>

                <tr>                
                  <th class="bg-gray">
                    <i class="fa fa-user-o margin-r-5"></i> Creado por
                  </th>
                  <td>
                    <select class="form-control" name="creado_por" required>
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
                    <select class="form-control" name="cerrado_por">
                      <option value="">Elija una opción</option>
                      @foreach($agentes as $agente)
                        @if(empty($mantenimiento->user_cerro))
                          <option value="{{$agente->id}}" {{(Auth::user()->id == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                        @else
                          <option value="{{$agente->id}}" {{($mantenimiento->user_cerro == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                        @endif
                      @endforeach
                    </select>
                  </td>
                </tr>
                
                <tr>
                  <th class="bg-gray">Fecha de apertura</th>
                  <td>
                    <input class="form-control" type="datetime-local" name="fecha_apertura" required value="{{date('Y-m-d\TH:i:s', strtotime($mantenimiento->Fecha))}}">
                  </td>
                  <th class="bg-gray">Fecha Límite</th>
                  <td>
                    <input class="form-control" type="date" name="fecha_limite" required value="{{date('Y-m-d', strtotime($mantenimiento->FechaMaxima))}}">
                  </td> 
                </tr>

                <tr>
                  <th class="bg-gray">
                    Tipo Falla
                  </th>
                  <td>
                    <select class="form-control" name="tipo_falla" required>
                      <option value="">Elija una opción</option>
                      @foreach($tipos_fallas as $tipo_f)
                        <option value="{{$tipo_f->TipoFallaId}}" {{($mantenimiento->TipoFalloID == $tipo_f->TipoFallaId) ? 'selected' : ''}}>{{$tipo_f->DescipcionFallo}}</option>
                      @endforeach
                    </select>
                  </td>                
                  <th class="bg-gray">Prioridad </th>
                  <td>
                    <select class="form-control" name="prioridad" required>
                      <option value="">Elija una opción</option>
                      @foreach($prioridades as $key => $values)
                        <option value="{{$key}}" {{($mantenimiento->Prioridad == $key) ? 'selected' : ''}}>{{$key}}. {{$values}}</option>
                      @endforeach
                    </select>
                  </td>
                </tr>

                <tr>
                  <th class="bg-gray">
                    Canal de Atención
                  </th>
                  <td>
                    <select class="form-control" name="canal_atencion" required>
                      <option value="">Elija una opción</option>
                      @foreach($canales_atencion as $canal_atencion)
                        <option value="{{$canal_atencion->TipoEntradaTicket}}" {{($mantenimiento->TipoEntrada == $canal_atencion->TipoEntradaTicket) ? 'selected' : ''}}>{{$canal_atencion->Descripcion}}</option>
                      @endforeach
                    </select>                 
                  </td>
                  
                  <th class="bg-gray">
                    Tikect
                  </th>
                  <td>
                    @if(!empty($mantenimiento->TicketId))
                      <a href="{{route('tickets.show', $mantenimiento->TicketId)}}">{{$mantenimiento->TicketId}}</a>
                    @else
                      Sin definir
                    @endif
                  </td>
                </tr>
                
                <tr>
                  <th class="bg-gray">Descripcion del problema</th>
                  <td colspan="3">
                    <textarea class="form-control" name="descripcion_problema" rows="4" required>{{$mantenimiento->DescripcionProblema}}</textarea>                  
                  </td>
                </tr>                  
              </tbody>
            </table>

            <table class="table table-bordered" id="datos_cierre">
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
                  <input class="form-control" type="datetime-local" name="fecha_hora_inicio_cierre" value="{{(!empty($mantenimiento->fecha_cierre_hora_inicio))? date('Y-m-d\TH:i:s', strtotime($mantenimiento->fecha_cierre_hora_inicio)) : ''}}" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                </td>
                <td>
                  <input class="form-control" type="datetime-local" name="fecha_hora_fin_cierre" value="{{(!empty($mantenimiento->fecha_cierre_hora_fin))? date('Y-m-d\TH:i:s', strtotime($mantenimiento->fecha_cierre_hora_fin)) : ''}}" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
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
                  <select class="form-control" name="tipo_tecnologia" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                      <option value="">Elija una opción</option>
                      @foreach($tipos_tecnologias as $tipo)
                        <option value="{{$tipo}}" {{($mantenimiento->TipoDeTecnologiaImplementada == $tipo) ? 'selected' : ''}}>{{$tipo}}</option>
                      @endforeach
                    </select>
                </td>
                <td>
                  <input type="text" class="form-control" name="red" value="{{$mantenimiento->Red}}" placeholder="Identif. de la Red">
                </td>
                <td>
                  <select class="form-control" name="retorna_servicio" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                    <option value="">Elija una opción</option>
                    @foreach($respuestas_cortas as $respuesta_corta)
                      <option value="{{$respuesta_corta}}" {{($mantenimiento->SeRetornoServicio == $respuesta_corta)? 'selected' : ''}}>{{$respuesta_corta}}</option>
                    @endforeach
                  </select>                
                </td>
                <td>
                  <select class="form-control" name="servicio_activo" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
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
                    <input type="number" class="form-control" name="velocidad_descarga" value="{{$mantenimiento->VelocidadDeBajada}}" placeholder="Downstream" min="0" step="0.01" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                    <span class="input-group-addon">Mb</span>
                  </div>
                </td>

                <th class="bg-gray">Velocidad Subida</th>
                <td>
                  <div class="input-group">
                    <input type="number" class="form-control" name="velocidad_subida" value="{{$mantenimiento->VelocidadDeSubida}}" placeholder="Upstream" min="0" step="0.01" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                    <span class="input-group-addon">Mb</span>
                  </div>
                </td>
                
              </tr>
              <tr>
                <th class="bg-gray">Solucion</th>
                <td colspan="3">
                  <textarea class="form-control" name="solucion" placeholder="Describa la solución al mantenimiento ejecutado." {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>{{$mantenimiento->Solucion}}</textarea>
                </td>
              </tr>
              <tr>
                <th class="bg-gray">Procedimiento</th>
                <td colspan="3">                
                  <textarea class="form-control" name="procedimiento" placeholder="Describa el procedimiento del mantenimiento realizado." {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>{{$mantenimiento->Procedimiento}}</textarea>
                </td>
              </tr>
              <tr>
                <th class="bg-gray">Observaciones</th>
                <td colspan="3">
                  <textarea class="form-control" name="observaciones" placeholder="Describa las observaciones del mantenimiento." {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>{{$mantenimiento->Observaciones}}</textarea>
                </td>
              </tr>

              <tr>
                <th class="bg-gray">Observaciones de Cierre</th>
                <td colspan="3">
                  <textarea class="form-control" name="observaciones_cierre" placeholder="Indique las observaciones que se identificaron al momento de cerrar el mantenimiento." {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>{{$mantenimiento->ObservacionDeCierre}}</textarea>
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

            @if(!empty($mantenimiento->ClienteId))
              <table class="table table-bordered" id="persona_recibe">
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
                                  <select name="parentezco" class="form-control" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                                      <option value="">Elija una opción</option>
                                      @foreach($parentezcos as $parentezco)
                                        <option {{($mantenimiento->parentezco == $parentezco)? 'selected' : ''}}>{{$parentezco}}</option>
                                      @endforeach
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
                              <input type="text" class="form-control" name="recibe_nombre" placeholder="Nombre Completo" value="{{$mantenimiento->nombre}}" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                          </td>
                      </tr>
                      <tr>
                          <th class="bg-gray">Cedula</th>
                          <td>
                              <input type="number" class="form-control" name="recibe_cedula" placeholder="Numero de Documento" value="{{$mantenimiento->cedula}}" min-length="5" {{($mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
                          </td>
                      </tr>
                      
                      <tr>
                          <th class="bg-gray">Firma</th>
                          <td>

                              @if(!empty($mantenimiento->firma))
                                <img src="{{Storage::url($mantenimiento->firma)}}" height="100px">
                              @endif
                              <select name="pregunta_firma" class="form-control" {{(empty($mantenimiento->firma) && $mantenimiento->estado != 'ABIERTO')? 'required' : ''}}>
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
        var firma_usuario = null;
    </script>
   

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

      $('#form-editar').on('submit',function (e) {

        e.preventDefault();

          const boton = $(this).find('button[type="submit"]');

          boton.attr('disabled',true);

          let form = document.getElementById('form-editar');
              

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
      });
    </script>

    <script type="text/javascript">

      $(document).ready(function() {
          buscar_departamentos({{$mantenimiento->DepartamentoId}});
          buscar_municipio({{$mantenimiento->MunicipioId}});

          cambiar_estado($('select[name="estado"]'));
      });

      const cambiar_estado = (estado) => {

        switch ($(estado).val()) {
          case 'CERRADO':
              $("#datos_cierre").find('input:not([name="firma_usuario"]), select:not([name="pregunta_firma_usuario"]), textarea').attr("required", true);
              $("#persona_recibe").find('select:not([name="pregunta_firma"]), input:not([name="firma"], [name="recibe_otro"]), textarea').attr("required", true);
            break;

          case 'PENDIENTE':
              $("#datos_cierre").find('input:not([name="firma_usuario"]), select:not([name="pregunta_firma_usuario"]), textarea').attr("required", true);
              $("#persona_recibe").find('input:not([name="firma"], [name="recibe_otro"]), textarea').attr("required", true);              
            break;

          default:
              $("#datos_cierre").find('input:not([name="firma_usuario"]), select:not([name="pregunta_firma_usuario"]),textarea').attr("required", false);
              $("#persona_recibe").find('input,textarea').attr("required", false);
            break;
        }   

        }
    </script>
  @endsection
@endsection