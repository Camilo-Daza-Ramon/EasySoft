@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-file-text-o"></i> Respuesta </h1>
@endsection

@section('main-content')

<div class="container-fluid spark-screen">

  <div class="row">
    <div class="col-md-12">
      <div class="col-md-4">
        <div class="box box-primary" id="panel-cliente">
          <div class="box-header with-border bg-blue">
            <h3 class="box-title"><i class="fa fa-user"></i> Datos Cliente</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table class="table table-condensed">
              <tbody>
                <tr>
                  <th>Identificacion</th>
                  <td>
                    <a href="{{route('clientes.show', $cliente->cliente_id)}}" target="_blank">{{$cliente->cliente->Identificacion}}</a>
                  </td>
                </tr>
                <tr>
                  <th>Nombre</th>
                  <td>{{$cliente->cliente->NombreBeneficiario}} {{$cliente->cliente->Apellidos}}</td>
                </tr>
                <tr>
                  <th>Dirección</th>
                  <td>
                    <a href="https://www.google.com/maps/search/{{$cliente->cliente->Latitud}},{{$cliente->cliente->Longitud}}" target="_blanck">{{$cliente->cliente->DireccionDeCorrespondencia}} {{$cliente->cliente->Barrio}}</a>
                  </td>
                </tr>
                <tr>
                  <th>Celular</th>
                  <td>
                    <p>{{$cliente->cliente->TelefonoDeContactoMovil}}</p>
                  </td>
                </tr>
                <tr>
                  <th>Correo Electronico</th>
                  <td>{{$cliente->cliente->CorreoElectronico}}</td>
                </tr>
                <tr>
                  <th>Total Deuda</th>
                  <td id="saldo_mora">
                    @if(isset($cliente->cliente->historial_factura_pago))
                    ${{number_format($cliente->cliente->historial_factura_pago->total_deuda, 0, ',', '.')}}
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Meses Mora</th>
                  <td>
                    @if(isset($cliente->cliente->historial_factura_pago))
                    {{round($cliente->cliente->historial_factura_pago->meses_mora)}}
                    @endif
                  </td>
                </tr>
                <tr>
                  <th>Valor Tarifa</th>
                  @if ($campaña->tipo == 'FACTURACION')
                  <td id="text-tarifa-internet">${{number_format($cliente_r->Internet, 0,',','.')}}</td>
                  @else
                  <td id="text-tarifa-internet">${{number_format($cliente->cliente->ValorTarifaInternet, 0, ',', '.')}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('ESTRATO',$campo_vizualizar))
                  <th>Estrato</th>
                  <td>{{$cliente->cliente->Estrato}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('MUNICIPIO',$campo_vizualizar))
                  <th>Municipio</th>
                  <td>{{$cliente->cliente->municipio->NombreMunicipio}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('DEPARTAMENTO',$campo_vizualizar))
                  <th>Departamento</th>
                  <td>{{$cliente->cliente->municipio->departamento->NombreDelDepartamento}}</td>
                  @endif
                </tr>

                <!------Facturacion-------->
                @if ($campaña->tipo == 'FACTURACION')
                <tr>
                  <th>Municipio</th>
                  <td>{{$cliente_r->Municipio}}</td>
                </tr>
                <tr>
                  @if(in_array('FACTURAID', $campo_vizualizar))
                  <th>Numero Factura</th>
                  <td>{{$cliente_r->FacturaId}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('PERIODO FACTURA',$campo_vizualizar))
                  <th>Periodo Factura</th>
                  <td>{{$cliente_r->Periodo}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('PROYECTO',$campo_vizualizar))
                  <th>Proyecto</th>
                  <td>{{$cliente_r->cliente->proyecto->NumeroDeProyecto}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('FACTURAID',$campo_vizualizar))
                  <th>Factura</th>
                  <td>{{$cliente_r->FacturaId}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('SALDO A FAVOR',$campo_vizualizar))
                  <th>Saldo a Favor</th>
                  <td>${{number_format($cliente_r->saldo_favor, 0, ',', '.')}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('PLAN CONTRATADO',$campo_vizualizar))
                  <th>Plan Contratado</th>
                  <td>{{$cliente_r->plan_contratado}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('DESCRIPCION DEL PLAN',$campo_vizualizar))
                  <th>Descripcion Plan</th>
                  <td>{{$cliente_r->descripcion_plan}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('ULTIMO PAGO',$campo_vizualizar))
                  <th>Ultimo Pago</th>
                  <td>${{number_format($cliente_r->ultimo_pago,0,',','.')}}</td>
                  @endif
                </tr>
                <tr>
                  @if(in_array('FECHA ULTIMO PAGO',$campo_vizualizar))
                  <th>Fecha Ultimo Pago</th>
                  <td>{{date("d-m-Y",strtotime($cliente_r->fecha_ultimo_pago))}}</td>
                  @endif
                </tr>
                @endif
                
                <tr>
                  <th>Trazabilidad</th>
                  @permission('campañas-respuestas-ver')
                  <td><button class="btn bt-default btn-xs " onclick="traer_respuesta({!!$cliente->id!!});return false;"><i class="fa fa-eye"></i></button></td>
                  @endpermission
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="box box-primary" id="panel-generar">
          <div class="box-header with-border bg-blue">
            <h3 class="box-title"> Respuesta de Campaña</h3>
          </div>

          <div class="box-body">
            @if (($solicitud_pendiente == null and $ticket == null) || $campaña->sin_restricciones)
            <form id="form-campana" action="{{route('campanas.responder',[$campaña->id , $cliente->id])}}" method="post" enctype="multipart/form-data">
              <div class="modal-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="text" hidden id="solicitud_fecha_limite" name="fecha_limite" value="">
                <input type="text" hidden id="solicitud_celular" name="celular" value="">
                <input type="text" hidden id="solicitud_correo" name="correo" value="">
                <input type="text" hidden id="solicitud_jornada" name="jornada" value="">

                <div class="row">

                  <div class="form-group col-md-6">
                    <label>*Estado:</label>
                    <select class="form-control" name="estado_cliente_campana" required>
                      <option value="">Seleccionar </option>
                      @foreach ($estados as $estado)
                        <option value="{{$estado}}">{{$estado}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group col-md-6">
                    <div class="radio">
                      <input type="radio" name="accion" value="responder" class="pr-2" required> <span style="margin-left:5px;">Responder Preguntas</span>
                    </div>
                    <div class="radio">
                      <input type="radio" name="accion" value="reagendar" class="pr-2" required> <span style="margin-left:5px;">Reagendar</span>
                    </div>
                    <div class="radio">
                      <input type="radio" name="accion" value="nada" class="pr-2" required> <span style="margin-left:5px;">Sin Acciones</span>
                    </div>
                  </div>
                </div>
                
                <div class="row" id="panel-reagendar" style="display:none;">
                  <div class="form-group col-md-6">
                    <label for="fecha_hora_rellamar">*Re-Agendar (Fecha-Hora):</label>
                    <input type="datetime-local" class="form-control" name="fecha_hora_rellamar" min="{{date('Y-m-d H:i')}}">
                  </div>
                </div>

                <div class="row" id="panel-preguntas" style="display:none;">

                  @foreach ($campaña->campos as $campanaCampo )

                    @if ($campanaCampo->estado == 1 && $campanaCampo->nombre != 'Estado')
                        @if ($campanaCampo->tipo == 'NUMERICO')
                            <div class="form-group col-md-6">
                                <label for="{{$campanaCampo->id}}">*{{$campanaCampo->nombre}}:</label>
                                <input type="number" name="{{$campanaCampo->id}}" id="{{$campanaCampo->id}}" class="form-control" placeholder="{{$campanaCampo->nombre}}" value="">
                            </div>
                        @elseif ($campanaCampo->tipo == 'TEXTAREA')
                            <div class="form-group col-md-6">
                                <label for="{{$campanaCampo->id}}">*{{$campanaCampo->nombre}}:</label>
                                <textarea type="text" name="{{$campanaCampo->id}}" id="{{$campanaCampo->id}}" class="form-control" placeholder="{{$campanaCampo->nombre}}" value=""></textarea>
                            </div>
                        @elseif ($campanaCampo->tipo == 'FECHA')
                            <div class="form-group col-md-6">
                                <label for="{{$campanaCampo->id}}">*{{$campanaCampo->nombre}}:</label>
                                <input type="date" name="{{$campanaCampo->id}}" id="{{$campanaCampo->id}}" class="form-control" value="">
                            </div>
                        @elseif ($campanaCampo->tipo == 'TEXT')
                            <div class="form-group col-md-6">
                                <label for="{{$campanaCampo->id}}">*{{$campanaCampo->nombre}}:</label>
                                <input type="text" name="{{$campanaCampo->id}}" id="{{$campanaCampo->id}}" class="form-control" placeholder="{{$campanaCampo->nombre}}" value="">
                            </div>
                        @elseif ($campanaCampo->tipo == 'SELECCION_CON_UNICA_RESPUESTA')
                            <div class="form-group col-md-6">

                                <label>*{{ $campanaCampo->nombre }}:</label>
                                <select class="form-control" name="{{ $campanaCampo->id }}" id="{{$campanaCampo->id}}">
                                    <option value="">Seleccione una opcion</option>
                                    @foreach ($campanaCampo->opciones as $opcion)
                                        @if ($opcion->estado == 1)
                                            <option value="{{ $opcion->valor }}">{{ $opcion->valor }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @elseif ($campanaCampo->tipo == 'SELECCION_CON_MULTIPLE_RESPUESTA')
                            <div class="form-group col-md-12">
                                <label>{{ $campanaCampo->nombre }}:</label><br>
                                <div class="col-md-12 " id="{{$campanaCampo->id}}">
                                    <div class="lista-nombres ">
                                        <ul>
                                            @foreach ($campanaCampo->opciones as $opcion)
                                                @if ($opcion->estado == 1)
                                                    <li>
                                                        <input class="material-icons" type="checkbox" name="{{$campanaCampo->id}}[]" value="{{ $opcion->valor }}" />
                                                        <label>{{ $opcion->valor }}</label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    <hr />
                                </div>
                            </div>

                        @elseif ($campanaCampo->tipo == 'ARCHIVO')
                            <div class="form-group col-md-12">
                                <label for="{{$campanaCampo->id}}">*{{$campanaCampo->nombre}}:</label>
                                <input type="file" name="{{$campanaCampo->id}}" id="{{$campanaCampo->id}}" class="form-control" value="" accept="image/*" />
                            </div>
                        @endif
                    @endif

                  @endforeach
                </div>

                <div class="row">
                  <div class="form-group col-md-12">
                    <label>Observacion</label>
                    <textarea type="text" id="observacion_respuesta" name="observacion" class="form-control" placeholder="" value=""></textarea>
                  </div>
                </div>

                
                <div class="row">
                  @if ($solicitud_pendiente == null)
                    <div class="col-md-12">
                        <div id="campos_solicitud" class="panel panel-info">

                        <div class="panel-heading">
                            <h4 class="text-center"><i class="fa fa-pencil-square-o"></i> Crear Solicitud</h4>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-6">
                            <label>Categorias Atención: </label>
                            <select name="categorias" disabled id="categorias" class="form-control">
                                <option value="">Elija una categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{$categoria->categoria}}">{{$categoria->categoria}}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="col-md-6">
                            <label>Motivo Atención: </label>
                            <select name="motivo" disabled id="motivo" class="form-control"></select>
                            </div>
                        </div>

                        </div>
                    </div>
                  @endif

                  <div class="col-md-12">
                    <div class="box box-info" id="panel-solicitud" style="display:none;">
                      <div class="box-header with-border bg-blue">
                        <h3 class="box-title"> <i class="fa fa-calendar"></i> Solicitud</h3>
                      </div>
                      <div class="box-body">
                        <div class="row">
                          <div class="col-md-12 text-center">
                            <h3>
                              <span id="txt-limite"></span>
                            </h3>
                            <h4>
                              <i class="fa fa-calendar-check-o"></i> Fecha limite de respuesta
                            </h4>
                            <h4>
                              <i class="fa fa-phone"></i> <b>Contacto:</b> <span id="txt-celular-contacto"></span>
                            </h4>
                            <h4>
                              <i class="fa fa-envelope-o"></i> <b>Correo:</b> <span id="txt-correo-contacto"></span>
                            </h4>
                            <h4>
                              <i class="fa fa-sun-o"></i> <b>Jornada:</b> <span id="txt-jornada"></span>
                            </h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal-footer">
                  @if ($campaña->estado == 'EN EJECUCION')
                  <div class="row">
                    
                    <div class="col-md-4 col-sm-12">
                      @if(isset($cliente->cliente->historial_factura_pago))
                          @if($acuerdos_activos == 0 and $cliente->cliente->historial_factura_pago->total_deuda > 0)
                              <button type="button" id="btn-acuerdo" class="btn btn-warning btn-block mt-1" data-toggle="modal" data-target="#createAcuerdo"> <i class="fa fa-check-square-o"></i> ACUERDO </button>
                          @endif
                      @endif
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <button type="button" id="btn-solicitud" class="btn btn-success btn-block mt-1" data-toggle="modal" data-target="#addSolicitud" disabled> <i class="fa fa-calendar-plus-o"></i> SOLICITUD </button>

                    </div>
                    <div class="col-md-4 col-sm-12">
                      <button type="submit" id="btn_enviar" class="btn btn-primary btn-block mt-1"> <i id="btn_enviar_icon" class="fa fa-save"></i> Responder</button>

                    </div>

                  </div>
                    
                  @endif
                </div>

              </div>
            </form>
            @elseif ( $ticket != null)

                <div class="alert alert-warning" id="alerta-ticket">
                    <h5 class="text-center"><i class="fa fa-check"></i> No es posible dilingenciar el formulario debido a que el cliente tiene un Ticket abierto. #
                        <a href="/tickets/{{$ticket->TicketId }}" target="_black"><b>{{ $ticket->TicketId }}</b></a>
                    </h5>
                </div>

            @elseif ($solicitud_pendiente != null)

                <div class="alert alert-warning" id="alerta-ticket">
                    <h5 class="text-center"><i class="fa fa-check"></i> No es posible dilingenciar el formulario debido a que el cliente tiene una solicitud pendiente.<br> #
                        <a href="{{route('solicitudes.show', $solicitud_pendiente)}}" target="_blank">
                            <b>{{$solicitud_pendiente}}</b>
                        </a>
                    </h5>
                </div>

            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@if(isset($cliente->cliente->historial_factura_pago))
    @if($acuerdos_activos == 0 and $cliente->cliente->historial_factura_pago->total_deuda > 0)
        @include('adminlte::campana.partials.create_acuerdo')
    @endif
@endif

@include('adminlte::atencion-clientes.partials.add-solicitud')

@permission('campañas-respuestas-ver')
    @include('adminlte::campana.partials.show-respuesta')
@endpermission

@section('mis_scripts')
<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script type="text/javascript">
  var jornada = null;
  var fecha_limite = null;
  var celular = null;
  var correo = null;
  var btn_solicitud = $('#btn-solicitud');
  var btn_add_solicitud = $('#btn-add-solicitud');
  var btn_enviar = $('#btn_enviar');
  const fecha_hoy = new Date();
</script>

<script type="text/javascript">
  const ultimo_dia = "{!!(intval(date('d')) > 25 )? date('Y-m-t', strtotime(date('Y-m-d'). ' + 1 month')) : date('Y-m-t')!!}";
</script>
<script type="text/javascript" src="/js/acuerdos/cuotas.js"></script>
<script type="text/javascript" src="/js/acuerdos/funcion_moneda.js"></script>
<script type="text/javascript" src="/js/acuerdos/validacion_porcentual.js"></script>
<script type="text/javascript" src="{{asset('js/atencion-cliente/motivos.js')}}"></script>
<script type="text/javascript" src="{{asset('js/campaña/campana_create.js')}}"></script>
<script type="text/javascript" src="{{asset('js/campaña/solicitud.js')}}"></script>
@permission('campañas-respuestas-ver')
<script type="text/javascript" src="/js/campaña/show.js"></script>
@endpermission

<script>
  var max_cuotas = {!!$campaña->cuotas_max_acuerdo!!}
  var valor_perdonar = {!!$campaña->valor_pardonar_acuerdo!!}
  var cliente_id = {!!$cliente->cliente->ClienteId!!}
  let campos = {!!$campaña->campos!!}

  $(function() {
    $("input[name=celular_contacto]").inputmask({
      "mask": "(999) 999-9999"
    });
  });

  $('#form-campana').on('submit', function(e) {
    btn_enviar.attr('disabled', true);
    $('#btn_enviar_icon').removeClass('fa-save');
    $('#btn_enviar_icon').addClass('fa-refresh fa-spin');
  });

  $('#btn-acuerdo').on('click', function() {
    var Ideuda = document.getElementById('saldo_mora');
    var tipo_descuento = document.getElementById('perdonar_porcentual');
    var deuda = Ideuda.innerText.replace(/\$|\./g, "");
    $('#deuda').val(deuda);
    $('#cliente_id').val(cliente_id);
    if (tipo_descuento.value == 'porcentual') {
      $('#descontado').val('');
      $('#label_perdonarP').show();
      $('#label_perdonarV').hide();
      $('#signo_porcentaje').removeClass("hide");
      $('#signo_pesos').addClass("hide");
    } else {
      $('#descontado').val('');
      $('#label_perdonarP').hide();
      $('#label_perdonarV').show();
      $('#signo_porcentaje').addClass("hide");
      $('#signo_pesos').removeClass("hide");
    }
  });

  $('input[name=accion]').on('change', function(){

    switch ($(this).val()) {
        case 'responder':

            $('#panel-reagendar').hide();
            $('#panel-preguntas').show();
            $('input[name="fecha_hora_rellamar"]').attr('required', false);

            $.each(campos, function(index, objCampo) {
                $('#' + objCampo.id + '').attr('required', true);
            });

            $('#observacion_respuesta').attr('required', false);
            
            break;

        case 'reagendar':
            $('#panel-reagendar').show();
            $('#panel-preguntas').hide();
            $('input[name="fecha_hora_rellamar"]').attr('required', true);

            $.each(campos, function(index, objCampo) {
                $('#' + objCampo.id + '').attr('required', false);
            });

            $('#observacion_respuesta').attr('required', true);

            break;
    
        default:
            $('#panel-preguntas').hide();
            $('#panel-reagendar').hide();
            $('input[name="fecha_hora_rellamar"]').attr('required', false);

            $.each(campos, function(index, objCampo) {
                $('#' + objCampo.id + '').attr('required', false);
            });

            $('#observacion_respuesta').attr('required', true);

            
            break;
    }
  });


  var observacion = document.getElementById('observacion_respuesta');

  observacion.addEventListener('input', function() {
    var text = observacion.value.trim();
    var categoria = document.getElementById('categorias');
    var motivo = document.getElementById('motivo');
    if (text === '') {
      motivo.value = '';
      categoria.value = '';
      $('#btn-solicitud').attr('disabled', true);
      $('#categorias').attr('disabled', true);
      $('#motivo').attr('disabled', true);
    } else {
      $('#categorias').attr('disabled', false);
      $('#motivo').attr('disabled', false);
    }
  });
  if (valor_perdonar != 0) {
    $('#valor_perdonar').attr('readonly', true);
    $('#perdonar_valor').attr('hidden', false);
    var check_pardonar = document.getElementById('perdonar_valor');
    $('#perdonar_valor').on('click', function() {
      if (check_pardonar.checked) {
        $('#valor_perdonar').val(valor_perdonar);
        descuentos(valor_perdonar);
      } else {
        $('#valor_perdonar').val(0);
      }
    });
  }
  $('#cuotas').on('input', function() {
    const cuotas = $(this).val();
    if (max_cuotas != 0) {
      if (cuotas > max_cuotas) {
        $('#cuotas').val('');
        $('#valor_inicial').val('');
        $('#valor_inicial').attr('readonly', false);
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning('Supera el maximo de cuotas! ' + max_cuotas);
      }
    }
    if (cuotas == 1) {
      var deuda = $('#deuda').val();
      $('#valor_inicial').val(deuda);
      $('#valor_inicial').attr('readonly', true);
    } else {
      $('#valor_inicial').val('');
      $('#valor_inicial').attr('readonly', false);
    }
  });
  $('#crear_acuerdo').on('click', function() {
    event.preventDefault();
    var form = $('#crear_acuerdo_ajax');
    var formData = form.serialize();
    var token = $('#token').val();
    $.ajax({
      url: '/acuerdos/Cajax',
      type: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response) {
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.success(response.mensaje);
        $('#createAcuerdo').modal('hide');
        $('#btn-acuerdo').remove();
      },
      error: function(xhr, status, error) {
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error(error);
      }
    });
  });
</script>

@endsection
@endsection