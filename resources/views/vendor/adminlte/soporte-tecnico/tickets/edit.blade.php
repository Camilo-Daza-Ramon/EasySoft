@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-edit"></i>  Editar Ticket #{{$ticket->TicketId}}</h1>
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
                    <span class="label label-success">{{$instalacion->cliente->Status}}</span>
                  @elseif($ticket->cliente->Status == 'RECHAZADO')
                    <span class="label label-danger">{{$instalacion->cliente->Status}}</span>
                  @elseif($ticket->cliente->Status == 'PENDIENTE')
                    <span class="label label-warning">{{$instalacion->cliente->Status}}</span>
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
                <form action="{{route('tickets.update', $ticket->TicketId)}}" method="post">
                  <input type="hidden" name="_method" value="PUT">
                  {{csrf_field()}}
                  <div class="row">
                    <div class="form-group{{ $errors->has('canal_atencion') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
                      <label><i class="fa fa-comment margin-r-5"></i> Canal de Atención</label>

                      <select name="canal_atencion" class="form-control" required>
                        <option value="">Elija una opción</option>
                        @foreach($ticket_medios_atencion as $medio_atencion)
                        <option value="{{$medio_atencion->TipoEntradaTicket}}" {!!($ticket->TipoDeEntrada == $medio_atencion->TipoEntradaTicket)? 'selected': ''!!}>{{$medio_atencion->Descripcion}}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
                      <label><i class="fa fa-tags margin-r-5"></i> Estado</label>
                      <select name="estado" class="form-control" required>
                        <option value="">Elija una opción</option>
                        @foreach($estados as $estado)
                        <option value="{{$estado->EstadoTicket}}" {!!($ticket->EstadoDeTicket == $estado->EstadoTicket)? 'selected': ''!!}>{{$estado->Descripcion}}</option>
                        @endforeach
                      </select>                    
                    </div>

                    <div class="form-group{{ $errors->has('tipo_falla') ? ' has-error' : '' }} col-md-4 col-sm-12 col-xs-12">
                      <label><i class="fa fa-exclamation-triangle margin-r-5"></i> Tipo de Falla</label>
                      <select name="tipo_falla" class="form-control" required>
                        <option value="">Elija una opción</option>
                        @foreach($tipos_fallas as $tipo_falla)
                        <option value="{{$tipo_falla->TipoFallaId}}" {!!($ticket->CodigoTipoDeFallo == $tipo_falla->TipoFallaId)? 'selected': ''!!}>{{$tipo_falla->DescipcionFallo}}</option>
                        @endforeach
                      </select>                    
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
                      <label><i class="fa fa-calendar margin-r-5"></i> Fecha de apertura</label>
                      <input type="datetime-local" name="fecha" class="form-control" value="{{date('Y-m-d\TH:i:s', strtotime($ticket->FechaApertura))}}" required>
                    </div>

                    <div class="form-group{{ $errors->has('fecha_cierre') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
                      <label><i class="fa fa-calendar-check-o margin-r-5"></i> Fecha de solución</label>
                      <input type="datetime-local" name="fecha_cierre" class="form-control" value="{{(isset($ticket->FechaCierre)) ? date('Y-m-d\TH:i:s', strtotime($ticket->FechaCierre)) : ''}}">
                      
                    </div>

                    <div class="form-group col-md-4 col-sm-6 col-xs-6">
                      <label><i class="fa fa-clock-o margin-r-5"></i> Dias sin Solución</label>
                      <?php
                         $contador = date_diff(date_create($ticket->FechaApertura), date_create($ticket->FechaCierre));
                        ?>
                      <input type="text" class="form-control" name="dias-sin-solucion" disabled value="{{$contador->format('%a')}} Días">
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group{{ $errors->has('prioridad') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
                      <label><i class="fa fa-exclamation-triangle margin-r-5"></i> Prioridad</label>
                      <select name="prioridad" class="form-control" required>
                        <option value="">Elija una opción</option>
                        @foreach($prioridades as $prioridad)
                        <option value="{{$prioridad['nivel']}}" {!!($ticket->PrioridadTicket == $prioridad['nivel'])? 'selected': ''!!}>{{$prioridad['descripcion']}}</option>
                        @endforeach
                      </select>
                    </div> 

                    <div class="form-group{{ $errors->has('user_crea') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
                      <label><i class="fa fa-user-o margin-r-5"></i> Creado por</label>
                      <select name="user_crea" id="user_crea" class="form-control" required>
                        <option value="">Elija una opción</option>
                          <?php $add = 0; ?>
                          @foreach($agentes as $agente)
                            @if($ticket->user_crea == $agente->id)
                              <?php $add = 1; ?>
                              <option value="{{$agente->id}}" selected>{{$agente->name}}</option>
                            @else
                              <option value="{{$agente->id}}">{{$agente->name}}</option>
                            @endif
                          @endforeach

                          @if($add == 0 && !empty($ticket->user_crea))
                          <option value="{{$ticket->user_crea}}" selected>{{$ticket->agente_creo->name}}</option>
                          @endif
                      </select>                    
                    </div>             
                  </div>

                  <div class="row">
                    <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
                      <label><i class="fa fa-book margin-r-5"></i> Descripción</label>
                      <textarea class="form-control" name="descripcion">{{$ticket->Observacion}}</textarea>                   
                    </div>
                    <div class="form-group{{ $errors->has('solucion') ? ' has-error' : '' }} col-md-12">
                      <label><i class="fa fa-check margin-r-5"></i> Solución</label>
                      <textarea class="form-control" name="solucion">
                        {{$ticket->Solucion}}
                      </textarea>
                    </div>
                    <div class="form-group">
                      <div class="form-group{{ $errors->has('escalar_mantenimiento') ? ' has-error' : '' }} col-sm-12">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="escalar_mantenimiento" {{($ticket->Escalado) ? 'checked' : 's'}}> Escalar a Mantenimiento 
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                
                <div class="panel panel-default"> 
                  <div class="panel-heading">
                    <h2><i class="fa fa-folder-open-o"></i>  Pruebas
                      
                      <div class="box-tools pull-right">
                        <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addPrueba">
                            <i class="fa fa-plus"></i>  <span class="hidden-xs">Agregar</span>
                        </div>
                      </div>
                      
                    </h2>
                  </div>
                  <div class="panel-body table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Prueba</th>
                          <th>Hora</th>
                          <th>Observacion</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($ticket->prueba as $prueba)
                        <tr>
                          <td></td>
                          <td>{{$prueba->tipo_prueba->Prueba}}</td>
                          <td>{{date('H:i:s', strtotime($prueba->Hora))}}</td>
                          <td>{{$prueba->Observacion}}</td>
                          <td>
                            <form action="{{route('tickets.pruebas.destroy',[$ticket->TicketId ,$prueba->PruebaTiqId])}}" method="post">
                              <input type="hidden" name="_method" value="delete">
                              <input type="hidden" name="_token" value="{{csrf_token()}}">
                              <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                <i class="fa fa-trash-o"></i>   
                              </button>
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
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
                      <td>{{$ticket->mantenimiento->MantId}}</td>
                      <td>{{$ticket->mantenimiento->TipoMantenimiento}}</td>
                      <td>{{date('Y-m-d H:i:s', strtotime($ticket->mantenimiento->Fecha))}}</td>
                      <td>
                        @if(!empty($ticket->mantenimiento->FechaCierre))
                          {{date('Y-m-d H:i:s', strtotime($ticket->mantenimiento->FechaCierre))}}
                        @endif
                      </td>
                      <td>{{$ticket->mantenimiento->ObservacionDeCierre}}</td>
                      <td>{{$ticket->mantenimiento->Estado}}</td>
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

  @include('adminlte::soporte-tecnico.tickets.partials.add-prueba')
  @section('mis_scripts')
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