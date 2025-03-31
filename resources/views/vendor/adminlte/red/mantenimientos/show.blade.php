@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-user"></i>  Mantenimiento #{{$mantenimiento->MantId}}</h1>
@endsection

@section('main-content')
  <div class="row">
    <div class="col-md-4">           
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
              <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Clientes Afectados</a></li>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <label>Numero Ticket</label>
                    <p>{{$mantenimiento->NumeroDeTicket}}</p>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <label>Canal de Contacto</label>
                    <p>
                      @if(!empty($mantenimiento->TipoEntrada))
                      {{$mantenimiento->medio_atencion->Descripcion}}
                      @else
                      sin definir
                      @endif
                    </p>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <label>Proyecto</label>
                    <p>{{$mantenimiento->proyecto->NumeroDeProyecto}}</p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <label>Direccion</label>
                    <p>{{$mantenimiento->Direccion}} - {{$mantenimiento->Barrrio}}</p>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <label>Departamento</label>
                    <p>{{$mantenimiento->municipio->NombreMunicipio}}</p>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <label>Municipio</label>
                    <p>{{$mantenimiento->municipio->NombreDepartamento}}</p>
                  </div>                  
                </div>

                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-comment margin-r-5"></i> Tipo Mantenimiento</strong>
                    <p class="text-muted">
                      {{$mantenimiento->tipo_mantenimiento->Descripcion}}
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-tags margin-r-5"></i> Estado</strong>
                    <p class="text-muted">                
                      <span class="label {{($mantenimiento->Estado == 'CERRADO') ? 'label-default' : 'label-success'}}">{{$mantenimiento->Estado}}</span>
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-12 col-xs-12">
                    <strong><i class="fa fa-exclamation-triangle margin-r-5"></i> Tipo de Falla</strong>
                    <p class="text-muted">
                      @if(!empty($mantenimiento->TipoFalloID))
                      {{$mantenimiento->tipo_fallo->DescipcionFallo}}
                      @else
                      sin definir.
                      @endif
                    </p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-calendar margin-r-5"></i> Fecha de apertura</strong>
                    <p class="text-muted">
                      {{date('Y-m-d H:i:s', strtotime($mantenimiento->Fecha))}}
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-calendar-check-o margin-r-5"></i> Fecha de solución</strong>
                    <p class="text-muted">
                      @if(!empty($mantenimiento->FechaCierre))
                        {{$mantenimiento->FechaCierre}}
                      @else
                        Sin definir.
                      @endif
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-clock-o margin-r-5"></i> Dias sin Solución</strong>
                    <p class="text-muted">
                      <?php
                       $contador = date_diff(date_create($mantenimiento->Fecha), date_create($mantenimiento->FechaCierre));
                      ?>
                      {{$contador->format('%a')}} Días
                    </p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-exclamation-triangle margin-r-5"></i> Prioridad</strong>
                    <p class="text-muted">
                      @if(!empty($mantenimiento->Prioridad))
                        {{$mantenimiento->Prioridad}}
                      @else
                        Sin definir.
                      @endif
                    </p>
                  </div> 

                  <div class="col-md-4 col-sm-6 col-xs-6">
                    <strong><i class="fa fa-user-o margin-r-5"></i> Creado por</strong>
                    <p class="text-muted">                      
                        Sin definir.
                    </p>
                  </div>             
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <strong><i class="fa fa-book margin-r-5"></i> Descripción</strong>
                    <p class="text-muted text-justify">              
                      {{$mantenimiento->DescripcionProblema}}
                    </p>
                  </div>
                  <div class="col-md-12">
                    @if($mantenimiento->Estado == 'CERRADO')
                      <hr>
                      <div class="post">
                        <div class="user-block">
                          <img class="img-circle img-bordered-sm" src="{{ Gravatar::get('prueba@gmail.com') }}" alt="user image">
                              <span class="username">
                                <a href="#">Pepito Perez</a>
                                <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                              </span>
                          <span class="description">Solucionado - {{$mantenimiento->FechaCierre}}</span>
                        </div>
                        <!-- /.user-block -->
                        <p>{{$mantenimiento->Procedimiento}}</p>
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
                   
                  </tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane table-responsive" id="tab_3">
                @if(count($mantenimiento->clientes) > 0)
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Identificacion</th>
                      <th>Nombre</th>                      
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 0; ?>
                    @foreach($mantenimiento->clientes as $clientes)
                    <tr>
                      <td>{{$i += 1}}</td>
                      <td>{{$clientes->cliente->Identificacion}}</td>
                      <td>{{$clientes->cliente->NombreBeneficiario}} {{$clientes->cliente->Apellidos}}</td>
                      <td>{{$clientes->cliente->Status}}</td>
                    </tr>
                    @endforeach
                  
                  </tbody>
                </table>
                @else
                  <b>No hay novedades para este ticket.</b>
                @endif
              </div>              
            </div>
            <!-- /.tab-content -->
          </div>
    </div>
  </div>
  @section('mis_scripts')
    <script>

      function initMap() {

        var uluru = {lat: {{(isset($mantenimiento->Latitud) ? $mantenimiento->Latitud: 0.0)}}, lng: {{(isset($mantenimiento->Longitud))? $mantenimiento->Longitud : 0.0}} };

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