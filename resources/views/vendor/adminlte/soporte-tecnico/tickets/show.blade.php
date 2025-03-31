@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-user"></i>  Ticket #{{$ticket->TicketId}}</h1>
@endsection

@section('main-content')
  <div class="row">
    <div class="col-md-4">
      <div class="box box-primary">
        <div class="box-header with-border bg-blue">
          <h3 class="box-title"><i class="fa fa-user"></i> Datos Cliente</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

          <table class="table">
            <tbody>
              <tr>
                <th>Identificacion</th>
                <td>
                  <a href="{{route('clientes.show', $ticket->ClienteId)}}" target="_black">{{$ticket->cliente->TipoDeDocumento}} {{$ticket->cliente->Identificacion}}</a>
                </td>
              </tr>
              <tr>
                <th>Nombre</th>
                <td>{{$ticket->cliente->NombreBeneficiario}} {{$ticket->cliente->Apellidos}}</td>
              </tr>
              <tr>
                <th>Direccion</th>
                <td>
                  {{$ticket->cliente->DireccionDeCorrespondencia}} - {{$ticket->cliente->municipio->NombreMunicipio}} - {{$ticket->cliente->municipio->departamento->NombreDelDepartamento}}
                </td>
              </tr>
              <tr>
                <th>Proyecto</th>
                <td>{{$ticket->cliente->proyecto->NumeroDeProyecto}}</td>
              </tr>                   
              <tr>
                <th>Estado Cliente</th>
                <td>
                  @if($ticket->cliente->Status == 'ACTIVO')
                      {{$ticket->cliente->EstadoDelServicio}}
                  @elseif($ticket->cliente->Status == 'APROBADO')
                    <span class="label label-success">{{$ticket->cliente->Status}}</span>
                  @elseif($ticket->cliente->Status == 'ticket')
                    <span class="label label-danger">{{$ticket->cliente->Status}}</span>
                  @elseif($ticket->cliente->Status == 'PENDIENTE')
                    <span class="label label-warning">{{$ticket->cliente->Status}}</span>
                  @else
                      {{$ticket->cliente->Status}}
                  @endif
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>      
      <!-- /.box -->

      <!-- About Me Box -->
      <div class="box box-primary">
        <div class="box-header with-border bg-blue">
          <h3 class="box-title">Ubicación</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div id="map" style="width: 100%; height: 200px;"> </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>

    <div class="col-md-8">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs bg-blue">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Detalles</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Pruebas</a></li>
              <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Novedades</a></li>
              <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">Mantenimiento</a></li>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-comment margin-r-5"></i> Canal de Atención</strong>
                    <p class="text-muted">
                      {{$ticket->medio_atencion->Descripcion}}
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-tags margin-r-5"></i> Estado</strong>
                    <p class="text-muted">                
                      <span class="label {{($ticket->EstadoDeTicket == 0) ? 'label-default' : 'label-success'}}">{{$ticket->estado->Descripcion}}</span>                
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-12 col-xs-12">
                    <strong><i class="fa fa-exclamation-triangle margin-r-5"></i> Tipo de Falla</strong>
                    <p class="text-muted">
                      {{$ticket->tipo_fallo->DescipcionFallo}}
                    </p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-calendar margin-r-5"></i> Fecha de apertura</strong>
                    <p class="text-muted">
                      {{date('Y-m-d H:i:s', strtotime($ticket->FechaApertura))}}
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-calendar-check-o margin-r-5"></i> Fecha de solución</strong>
                    <p class="text-muted">
                      @if(!empty($ticket->FechaCierre))
                        {{$ticket->FechaCierre}}
                      @else
                        Sin definir.
                      @endif
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-clock-o margin-r-5"></i> Dias sin Solución</strong>
                    <p class="text-muted">
                      <?php
                       $contador = date_diff(date_create($ticket->FechaApertura), date_create($ticket->FechaCierre));
                      ?>
                      {{$contador->format('%a')}} Días
                    </p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-exclamation-triangle margin-r-5"></i> Prioridad</strong>
                    <p class="text-muted">
                      @if(!empty($ticket->PrioridadTicket))
                        {{$ticket->PrioridadTicket}}
                      @else
                        Sin definir.
                      @endif
                    </p>
                  </div> 

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-user-o margin-r-5"></i> Creado por</strong>
                    <p class="text-muted">
                      @if(isset($ticket->agente_creo))
                        {{$ticket->agente_creo->name}}
                      @else
                        Sin definir.
                      @endif
                    </p>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-picture-o  margin-r-5"></i> Evidencia</strong><br>
                    @if (!empty($ticket->ImagenTicket))
                      <span><a class="text-primary" href="#" data-toggle="modal" data-target="#modal-attachment" data-imagen="{{Storage::url($ticket->ImagenTicket)}}">Evidencia</a></span>    
                    @endif
                  </div>               
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <strong><i class="fa fa-book margin-r-5"></i> Descripción</strong>
                    <p class="text-muted text-justify">              
                      {{$ticket->Observacion}}
                    </p>
                  </div>
                  <div class="col-md-12">
                    @if($ticket->EstadoDeTicket == 0)
                      <hr>
                      <div class="post">
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{ Gravatar::get($ticket->cliente->CorreoElectronico) }}" alt="user image">
                              <span class="username">
                                <a href="#">Pepito Perez</a>
                                <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                              </span>
                          <span class="description">Solucionado - {{$ticket->FechaCierre}}</span>
                        </div>
                        <!-- /.user-block -->
                        <p>{{$ticket->Solucion}}</p>
                      </div>
                    @endif
                  </div>
                </div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane table-responsive" id="tab_2">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Prueba</th>
                      <th>Hora</th>
                      <th>Observacion</th>            
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($ticket->prueba as $prueba)
                    <tr>
                      <td></td>
                      <td>{{$prueba->tipo_prueba->Prueba}}</td>
                      <td>{{date('H:i:s', strtotime($prueba->Hora))}}</td>
                      <td>{{$prueba->Observacion}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane table-responsive" id="tab_3">
                @if(count($ticket->novedad) > 0)
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Concepto</th>
                      <th>Cantidad</th>
                      <th>Valor Unidad</th>
                      <th>IVA</th>
                      <th>Fecha Inicio</th>
                      <th>Fecha Fin</th>
                      <th>Estado</th>                
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($ticket->novedad as $novedad)          
                    <tr>
                      <td>{{$novedad->id}}</td>
                      <td>{{$novedad->concepto}}</td>
                      <td>
                        {{$novedad->cantidad}}
                      </td>
                      <td>${{number_format($novedad->valor_unidad, 0, ',', '.')}}</td>
                      <td>{{number_format($novedad->iva,0,'','')}}%</td>
                      <td>{{$novedad->fecha_inicio}}</td>
                      <td>{{$novedad->fecha_fin}}</td>
                      
                      <td>
                        @if($novedad->estado == 'PENDIENTE')
                          <span class="label label-warning">{{$novedad->estado}}</span>
                        @else
                          <span class="label label-default">{{$novedad->estado}}</span>
                        @endif
                      </td>                
                    </tr>
                  @endforeach
                  </tbody>
                </table>
                @else
                  <b>No hay novedades para este ticket.</b>
                @endif
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane table-responsive" id="tab_4">
                @if(count($ticket->mantenimiento) > 0)
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Tipo</th>
                      <th>Fecha</th>
                      <th>Fecha Cierre</th>
                      <th>Observacion</th>                
                      <th>Estado</th>                
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><a href="{{route('correctivos.show', $ticket->mantenimiento->MantId)}}" target="_blank">{{$ticket->mantenimiento->MantId}}</a></td>
                      <td>{{$ticket->mantenimiento->TipoMantenimiento}}</td>
                      <td>{{date('Y-m-d H:i:s', strtotime($ticket->mantenimiento->Fecha))}}</td>
                      <td>
                        @if(!empty($ticket->mantenimiento->fecha_cierre_hora_fin))
                          {{date('Y-m-d H:i:s', strtotime($ticket->mantenimiento->fecha_cierre_hora_fin))}}
                        @endif
                      </td>
                      <td>{{$ticket->mantenimiento->ObservacionDeCierre}}</td>
                      <td>{{$ticket->mantenimiento->estado}}</td>
                    </tr>
                  
                  </tbody>
                </table>
                @else
                <b>No hay mantenimiento creado para este ticket.</b>
                @endif
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
    </div>
  </div>

  @include('adminlte::partials.modalimagen')

  @section('mis_scripts')
    <script type="text/javascript" src="{{asset('js/tickets/show-imagen.js')}}"></script>

    <script>

      function initMap() {

        var uluru = {lat: {{(isset($ticket->cliente->Latitud) ? $ticket->cliente->Latitud: 0.0)}}, lng: {{(isset($ticket->cliente->Longitud))? $ticket->cliente->Longitud : 0.0}} };

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
          center: uluru,
          disableDefaultUI: true,
          styles: [
                    {
                      "featureType": "poi.business",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "poi.park",
                      "elementType": "labels.text",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    }
                  ]
        });

        var marker = new google.maps.Marker({
          position: uluru,
          map: map,
        });
      }
    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs&callback=initMap"></script>
  @endsection
@endsection