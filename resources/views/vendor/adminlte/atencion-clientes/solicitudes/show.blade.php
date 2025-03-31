@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Mesa - Ver
@endsection

@section('contentheader_title')
    <h1><i class="fa fa-commenting-o"></i>  Solicitud</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border bg-blue">
              <h3 class="box-title">Datos Atención al cliente</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-3">
                    <label>Cedula: </label>
                    @if ($solicitud->atencion_cliente_id != null)
                      @if(!empty($atencion->ClienteId))
                        <p>
                          <a href="{{route('clientes.show', $atencion->ClienteId)}}" target="_blank">{{$atencion->identificacion}}</a>
                        </p>
                      @else
                        <p>{{$atencion->identificacion}}</p>
                      @endif
                    @else
  
                      <p>
                        <a href="{{route('clientes.show', $atencion->cliente->ClienteId)}}" target="_blank">{{$atencion->cliente->Identificacion}}</a>
                      </p>
                                          
                    @endif
                  </div>
                  <div class="col-md-3">
                    <label>Nombre: </label>
                    @if ($solicitud->atencion_cliente_id != null)
                      <p>{{$atencion->nombre}}</p>
                    @else
                      <p>{{$atencion->cliente->NombreBeneficiario}}{{$atencion->cliente->Apellidos}}</p>
                    @endif
                  </div>
                  <div class="col-md-3">
                    <label>Municipio:</label>
                    <p>{{$solicitud->municipio->NombreMunicipio}}</p>                   
                  </div>
                  <div class="col-md-3">
                    <label>Departamento:</label>
                    <p>{{$solicitud->municipio->departamento->NombreDelDepartamento}}</p>
                  </div>
                </div>              
                <div class="col-md-4">
                  <label>Motivo Atencion: </label><br>
                  <p>{{$solicitud->motivo_atencion->motivo}}</p>
                  
                </div>
                <div class="col-md-4">
                  <label>Categoria:</label><br>
                  <p>{{$solicitud->motivo_atencion->categoria}}</p>
                </div>
                <div class="col-md-4">
                  <label>Medio de atención:</label>
                  @if ($solicitud->atencion_cliente_id != null)
                    <p>{{$atencion->medio_atencion}}</p>
                  @else
                    <p>LLAMADA DE CAMPAÑA</p>
                  @endif
                </div>
                <div class="col-md-4">
                  <label>Agente o Asesor:</label>
                  @if ($solicitud->atencion_cliente_id != null)
                    <p>{{$atencion->user->name}}</p>
                  @else
                    <?php $contador = 0 ?>
                    @foreach ( $atencion->respuestas as $respuesta)
                     
                      @if ($contador == 0)
                        <?php $contador =+ 1 ?>
                        <p>{{$respuesta->usuario->name}}</p>                       
                      @endif
                    @endforeach
                  @endif
                </div>
                <div class="col-md-4">
                  <label>Fecha atencion: </label>
                  @if ($solicitud->atencion_cliente_id != null)
                    <p>{{date('Y-m-d H:i:s', strtotime($atencion->fecha_atencion_agente))}}</p>
                  @else
                    <p>{{$solicitud->fecha_hora_solicitud}}</p>
                  @endif
                </div>
                <div class="col-md-4">
                  <label>Estado: </label>
                  <p>{{$solicitud->estado}}</p>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label>Descripcion:</label>
                  <p id="descripcion_pqr_s">{{$solicitud->descripcion}}</p>
                </div>

                @if ($solicitud->atencion_cliente_id != null)
                <div class="col-md-12 comment-text">
                  <label>Solucion:</label>
                  <p>{{$atencion->solucion}}</p>
                </div>
                @endif
              </div>
            </div>
          </div>

          <div class="box box-success">
            <div class="box-body">

              <div class="row">
                <div class="col-md-6">
                  <table class="table table-striped">
                    <tbody>
                      <tr class="bg-blue">
                        <th colspan="2" class="text-center">DATOS SOLICITUD</th>
                      </tr>

                      <tr>
                        <th>Fecha Límite</th>
                        <td>
                          <p>{{date('Y-m-d', strtotime($solicitud->fecha_limite))}}</p>
                        </td>
                      </tr>

                      <tr>
                        <th>Celular de contacto</th>
                        <td>
                          <p>
                            {{str_replace(["(",")"," ","-"],"",$solicitud->celular)}}
                          </p>
                        </td>
                      </tr>

                      <tr>
                        <th>Correo</th>
                        <td>
                          <p>{{$solicitud->correo}}</p>
                        </td>
                      </tr>

                      <tr>
                        <th>Jornada</th>
                        <td>
                          <p>{{$solicitud->jornada}}</p>
                        </td>
                      </tr>

                      <tr>
                        <th>Estado solicitud</th>
                        <td>
                          @if($solicitud->estado == 'PENDIENTE')
                            <span class="label label-warning">{{$solicitud->estado}}</span>
                          @elseif($solicitud->estado == 'VENCIDA')
                            <span class="label label-danger">{{$solicitud->estado}}</span>
                          @else
                            <span class="label label-default">{{$solicitud->estado}}</span>
                          @endif
                        </td>
                      </tr>

                      <tr>
                        <th>Fecha de atención</th>
                        <td>
                          <p>
                            @if(!empty($solicitud->fecha_hora_atendida))
                              {{date('Y-m-d H:i:s', strtotime($solicitud->fecha_hora_atendida))}}
                            @endif
                          </p>
                        </td>
                      </tr>

                      <tr>
                        <th>Agente atendió</th>
                        <td>
                          <p>
                            @if(!empty($solicitud->user_id))
                              {{$solicitud->user->name}}
                            @endif
                          </p>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </div>
                <div class="col-md-6">
                  <form action="{{route('solicitudes.update', $solicitud->id)}}" method="post">
                    <input type="hidden" name="_method" value="PUT">
                    {{csrf_field()}}
                    <table class="table table-striped">
                      <tbody>
                        <tr class="bg-blue">
                          <th colspan="2" class="text-center">SOPORTE SOLICITUD</th>
                        </tr>
                        <tr>
                          {{csrf_field()}}
                          <th>#CUN</th>
                          <td class="{{ $errors->has('cun') ? 'has-error' : ''}}">
                            @if(!empty($atencion->pqr_id))
                              <p>
                                <a href="{{route('pqr.show', $atencion->pqr_id)}}" target="_blank">{{$atencion->pqr->CUN}}</a>
                              </p>
                            @elseif($solicitud->estado == 'PENDIENTE' || $solicitud->estado == 'VENCIDA')
                              <input type="text" name="cun" class="form-control" placeholder="CUN" value="{{old('cun')}}" autocomplete="off" >
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <th>#TICKET</th>
                          <td class="{{ $errors->has('ticket') ? 'has-error' : ''}}">
                            @if(!empty($atencion->ticket_id))
                            <p>
                              <a href="{{route('tickets.show', $atencion->ticket_id)}}" target="_blank">{{$atencion->ticket_id}}</a>
                            </p>
                            @elseif($solicitud->estado == 'PENDIENTE' || $solicitud->estado == 'VENCIDA')
                              <input type="text" name="ticket" class="form-control" placeholder="Ticket" value="{{old('ticket')}}" autocomplete="off" >
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <th>#MANTENIMIENTO</th>
                          <td class="{{ $errors->has('mantenimiento') ? 'has-error' : ''}}">
                            @if(!empty($atencion->mantenimiento_id))
                            <p>
                              <a href="{{route('mantenimientos.show', $atencion->mantenimiento_id)}}" target="_blank">{{$atencion->mantenimiento_id}}</a>
                            </p>
                            @elseif($solicitud->estado == 'PENDIENTE' || $solicitud->estado == 'VENCIDA')
                              <input type="text" name="mantenimiento" class="form-control" placeholder="Mantenimiento" value="{{old('mantenimiento')}}" autocomplete="off" >
                            @endif
                          </td>
                        </tr>

                        @if($solicitud->estado == 'PENDIENTE' || $solicitud->estado == 'VENCIDA')
                        <tr>
                          <td colspan="2">
                            <button type="submit" class="btn btn-block bg-default"><i class="fa fa-check"></i> ATENDER</button>
                          </td>
                        </tr>
                        @endif
                      </tbody>
                    </table>
                  </form>

                  @if($solicitud->estado == 'PENDIENTE' || $solicitud->estado == 'VENCIDA')
                  <div class="row">

                    <div class="col-md-6">
                      <button type="button" class="btn btn-app bg-purple" id="btn-pqr" data-toggle="modal" data-target="#addPqr"> <i class="fa fa-bullhorn"></i> PQRS </button>
                    </div>

                    @if(!empty($atencion->cliente_id))

                    <div class="col-md-6">
                      <button type="button" class="btn btn-app bg-olive" id="link-mantenimiento"> <i class="fa fa-wrench"></i> MANTENIMIENTO </button>
                    </div>
                    @endif


                  </div>
                  @endif
                </div>

              </div>


            </div>
          </div>



        </div>

        <div class="col-md-4">
          <div class="box box-warning direct-chat direct-chat-warning">
            <div class="box-header with-border bg-yellow">
              <h3 class="box-title">Comentarios</h3>
              <div class="box-tools pull-right"></div>
            </div>
            <div class="box-body">
              <div class="direct-chat-messages">

                @foreach($solicitud->comentarios as $comentario)
                <div class="direct-chat-msg right">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-right">{{$comentario->user->name}}</span>
                    <span class="direct-chat-timestamp pull-left">{{$comentario->created_at}}</span>
                  </div>
                  <img class="direct-chat-img" src="{{Gravatar::get(Auth::user()->email) }}" alt="message user image">
                  <div class="direct-chat-text">{{$comentario->comentario}}</div>
                </div>
                @endforeach
              </div>
            </div>
            <div class="box-footer">
              @if($solicitud->estado == "PENDIENTE" || $solicitud->estado == 'VENCIDA')
              <form action="{{route('solicitudes.comentarios.store', $solicitud->id)}}" method="post">
                {{csrf_field()}}
                <div class="input-group">
                  <input type="text" name="comentario" placeholder="Escribir comentario ..." class="form-control">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-warning btn-flat">Enviar</button>
                  </span>
                </div>
              </form>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>

    @include('adminlte::soporte-tecnico.tickets.partials.add')
    @include('adminlte::pqr.partials.add')


    @section('mis_scripts')
    <script type="text/javascript" src="{{asset('js/tickets/add.js')}}"></script>
    <script type="text/javascript">
      var btn_crear_pqr = $('#crear_pqr');
      var municipio =  "{!!(!empty($atencion->municipio_id)) ? $atencion->municipio_id : '' !!}";
      var departamento =  "{!!(!empty($atencion->municipio_id)) ? $atencion->municipio->DeptId : '' !!}";
      var cun = $('input[name=cun]');
      var modal_pqr = $('#addPqr');
      var btn_pqr = $('#btn-pqr');
      var cliente_id = "{!!(!empty($atencion->cliente_id)) ? $atencion->cliente_id : '' !!}";
      var ticket = $('input[name=ticket]');
    </script>
    <script type="text/javascript" src="{{asset('js/pqr/add.js')}}"></script>


    <script type="text/javascript">
      const checkbox = document.getElementById('crear_pqr');
			const checkbox_visita = document.getElementById('agendar_visita');
			var correo = document.getElementById("text-correo");
			var correo_solicitud = document.getElementById("txt-correo-contacto");

      $('#link-mantenimiento').on('click',function (event) {
				const hora = document.getElementById("hora");
				hora.value = new Date().toLocaleTimeString();
						
			})
 
			checkbox_visita.addEventListener('change', function(){
				if(checkbox.checked){
					checkbox.checked = false;
					$('#datos_pqr').hide();	 

					
					$('#descripcion_ticket_').show();
					$('#descripcion_pqr').hide();
					$('#descripcion_ticket_').attr('required',true);
					$('#descripcion_pqr').attr('required',false);

						
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.warning('No puede crear PQR y agendar visita al mismo tiempo');
					
				}
			});

			checkbox.addEventListener('change', function() {

				if (checkbox.checked) {
					
					$('#descripcion_ticket_').hide();
					$('#descripcion_ticket_').attr('required',false); 
          $('#descripcion_ticket').attr('required',false);

					$('#descripcion_pqr').show();
					$('#descripcion_pqr').attr('required',true);


					if(checkbox_visita.checked){
						checkbox_visita.checked = false ;
									
						toastr.options.positionClass = 'toast-bottom-right';
						toastr.warning('No puede crear PQR y agendar visita al mismo tiempo');
					}
					 
					$('#nombre_pqr').val($('#nombre').val());					
					$('#municipio_pqr').val($('#municipio').val());
					$('#departamento_pqr').val($('#departamento').val());
					$('#hechos_ticket').val($('textarea[name=descripcion]').val());
					$('#solucion').val($('textarea[name=solucion]').val());
					$('#datos_pqr').show();

          if($('#descripcion_ticket').val()!=''){
						$('#solucion').val($('#descripcion_ticket').val());	
					}
          const descripcion_pqr_s = document.getElementById('descripcion_pqr_s');
         

          if(descripcion_pqr_s.textContent.trim() !== ''){
            $('#hechos_pqr').val(descripcion_pqr_s.textContent.trim());	
          }

														
				} else {
          
          $('#datos_pqr').hide();
					$('#descripcion_ticket_').show();
					$('#descripcion_pqr').hide();					
					
				}
			});

      $("#form_ticket").on("submit",function(e){ 

        document.getElementById("hora").value = "";

        toastr.options.positionClass = 'toast-bottom-right';

        event.preventDefault();
        var f = $(this);
        var formData = new FormData(this)

        for(var i = 0; i < pruebas.length; i++){
          for ( var key in pruebas[i]){
            formData.append('pruebas['+i+']['+key+']', pruebas[i][key]);
          }
        }

        formData.append('cliente_id', cliente_id);


        $('#crear_ticket').attr('disabled',true);
        $('#icon-guardar').removeClass('fa-floppy-o');
        $('#icon-guardar').addClass('fa-refresh fa-spin');

        $.ajax({
          url:"/tickets",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false

        })

        .done(function(data){
          
          console.log('enviado');
          var respuesta_j = JSON.parse(data);
          console.log(respuesta_j);

          if(respuesta_j['result'] == 'error'){
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.error(respuesta_j.respuesta);
            $('#crear_ticket').attr('disabled',true);
            $('#icon-guardar').removeClass('fa-refresh fa-spin');
            $('#icon-guardar').addClass('fa-floppy-o');

          }else{
            //const footer = document.querySelector('#footer_ticket');
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.success(respuesta_j.respuesta);
            ticket.val(respuesta_j.ticket);
            $('input[name=cun]').val(respuesta_j.pqr);
            $('#crear_ticket').attr('disabled',true);

            if (checkbox.checked) { 
              $('textarea[name=descripcion]').val($('#hechos_pqr').val());
              $('textarea[name=solucion]').val($('#solucion').val());	
            }else{
              $('textarea[name=solucion]').val($('#descripcion_ticket').val());
            }
                  
            $('#form-ticket').empty();
            $('#footer_ticket').hide();
            //footer.style.display = 'none';

            
            
            if(respuesta_j.ticket != null){
              $('#form-ticket').append('<h2>Se creó el ticket <b>#'+respuesta_j.ticket+'</b><br></h2>');
            }

            if(respuesta_j.pqr != null){
              $('#form-ticket').append('<h2>Se creó la pqr  <b>#'+respuesta_j.pqr+'</b></h2>');
            }



          }
        }).fail(function(xhr, textStatus, errorThrown){

          btn_crear_ticket.attr('disabled',false);
          $('#icon-guardar').removeClass('fa-refresh fa-spin');
          $('#icon-guardar').addClass('fa-floppy-o');

          toastr.options.positionClass = 'toast-bottom-right';
          toastr.error(errorThrown);
        });
      });
    </script>


  @endsection
@endsection
