@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-home"></i>  Proyecto - {{$proyecto->NumeroDeProyecto}}</h1>
@endsection

@section('code_header')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{count($proyecto->cliente)}}</h3>

                  <p>Total Clientes</p>
                </div>
                <div class="icon">
                  <i class="fa fa-group"></i>
                </div>
                <a href="" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{$total_clientes_activos}}</h3>

                  <p>Total Activos</p>
                </div>
                <div class="icon">
                  <i class="fa fa-hdd-o"></i>
                </div>
                <a href="" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{$total_clientes_inactivos}}</h3>

                  <p>Total Inactivos</p>
                </div>
                <div class="icon">
                  <i class="fa fa fa-ban"></i>
                </div>
                <a href="#" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{$total_suspendidos}}</h3>

                  <p>Total Suspendidos</p>
                </div>
                <div class="icon">
                  <i class="fa fa-meh-o"></i>
                </div>
                <a href="" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
        </div>

        <div class="row">

            <div class="col-md-12">             
                <div class="box box-info" id="espera">
                    <div class="box-header bg-blue">
                      <h2 class="box-title">Total Clientes</h2>
                    </div>

                    <div class="box-body">
                        <div class="charts-chart">
                          <div id="ventas_totales_municipio" style="height: 100%; width: 100%;"></div>
                        </div>
                    </div>

                    <div class="box-footer clearfix table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Municipio</th>
                              <th>Departamento</th>
                              <th title="Clientes que tienen el servicio de Internet">Activos</th>
                              <th title="Clientes que estan en proceso de instalacion.">En instalacion</th>
                              <th title="Clientes que estan pendientes por auditar">Pendientes</th>
                              <th title="Clientes que se rechazaron en la auditoria y se deben subsanar">Rechazados</th>
                              <th title="Clientes que ya no pertenecen a Amigo Red">Inactivos</th>
                              <th>Total</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($proyectos_municipios as $municipio)
                            <tr>
                              <td>{{$municipio->MunicipioId}}</td>
                              <td>{{$municipio->municipio}}</td>
                              <td>{{$municipio->departamento}}</td>
                              
                              <td>
                                <span class="label bg-green">{{$municipio->ACTIVO}}</span>
                                
                              </td>
                              <td>
                                <span class="label bg-primary"><?php echo $municipio->{'EN INSTALACION'}; ?></span>
                              </td>
                              <td>
                                <span class="label bg-yellow">{{$municipio->PENDIENTE}}</span>
                                </td>
                              <td>
                                <span class="label bg-red">{{$municipio->RECHAZADO}}</span>
                                </td>
                              <td>
                                <span class="label bg-gray">{{$municipio->INACTIVO}}</span>
                                </td>
                              <td>{{($municipio->ACTIVO) + ($municipio->{'EN INSTALACION'}) + ($municipio->PENDIENTE) + ($municipio->RECHAZADO) + ($municipio->INACTIVO)}}</td>
                              <td>
                                <button class="btn btn-success btn-xs" title="mapa" data-toggle="modal" data-target="#showMapa" data-municipio="{{$municipio->MunicipioId}}" data-proyecto="{{$proyecto->ProyectoID}}"> <i class="fa fa-map-o"></i></button>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>

          @if(!empty($grafica_municipio_instalaciones))
            <div class="col-md-12">             
                <div class="box box-info" id="espera">
                    <div class="box-header bg-blue">
                      <h2 class="box-title">Total Instalaciones</h2>
                    </div>
                    <div class="box-body">
                      {!! $grafica_municipio_instalaciones->html() !!}
                    </div>

                    <div class="box-footer clearfix table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Municipio</th>
                              <th>Aprobados</th>
                              <th>Pendientes</th>
                              <th>Rechazados</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($instalaciones_grupo as $instalacion)
                            <tr>
                              <td></td>
                              <td>{{$instalacion->municipio}}</td>
                              <td>                                
                                  {{$instalacion->APROBADO}}
                              </td>
                              <td>                               
                                  {{$instalacion->PENDIENTE}}
                              </td>
                              <td>                                
                                  {{$instalacion->RECHAZADO}}
                              </td>                              
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
          @endif

          <div class="col-md-12">
            <div class="box box-primary">
              <div class="box-header with-border bg-blue">
                <h3 class="box-title">Avance del Proyecto</h3>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive">
                <table class="table table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Municipio</th>
                      <th>Departamento</th>
                      <th>Meta</th>
                      <th>Total Accesos Meta</th>
                      <th>Total Activos Meta</th>
                      <th>Pendientes Meta</th>
                      <th>Total Reportados Meta</th>                      
                      <th>Total Clientes Activos</th>
                      <th>Disponibles</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $i = 0; 
                      $total_accesos_meta = 0; 
                      $existe = ''; 
                      $municipios_repetidos_meta = array_count_values(array_column($municipios_meta, 'municipio'));
                      ?>
                    @foreach($municipios_meta as $municipio_meta)
                    
                    <tr>
                      <td>{{$i+=1}}</td>
                      <td>{{$municipio_meta['municipio']}}</td>
                      <td>{{$municipio_meta['departamento']}}</td>
                      <td>{{$municipio_meta['meta']}}</td>
                      <td>{{$municipio_meta['total_meta']}}</td>
                      <td>{{$municipio_meta['total_activos_meta']}}</td>
                      <td>
                        @if(($municipio_meta['total_meta'] - $municipio_meta['total_activos_meta']) > 0)
                          <a class="text-red" href="/cambios-reemplazos?municipio={{$municipio_meta['id']}}&meta={{$municipio_meta['meta']}}&estado=PENDIENTE" target="_blank">{{$municipio_meta['total_meta'] - $municipio_meta['total_activos_meta']}}</a>
                        @else
                          {{$municipio_meta['total_meta'] - $municipio_meta['total_activos_meta']}}
                        @endif
                      </td>

                      @if($existe != $municipio_meta['municipio'])

                      <?php 
                        $muni = $municipio_meta['municipio'];//240
                        $row_spam = $municipios_repetidos_meta["$muni"];
                        $otro = array_search($muni, array_column($data, 'municipio'));
                      ?>

                      <td rowspan="{{$row_spam}}">
                        @if(isset($data["$otro"]))
                          {{$reportados = (!empty($data))? $data["$otro"]["reportados"] : 0}}
                        @endif                        
                      </td>
                      
                      <td rowspan="{{$row_spam}}">
                        @if(isset($data["$otro"]))
                          {{$activos=  (!empty($data))? $data["$otro"]["clientes_activos"] : 0}}
                        @endif
                      </td>
                      
                      <td rowspan="{{$row_spam}}">
                        @if(isset($data["$otro"]))
                        <span class="description-percentage {!!(($activos - $municipio_meta['total_municipio']) < 0) ? 'text-red' : 'text-green'!!}">
                          <i class="fa {!!(($activos - $municipio_meta['total_municipio']) < 0) ? 'fa-caret-down' : 'fa-caret-up'!!}"></i> {{$activos - $municipio_meta['total_municipio']}}
                        </span>
                        @endif
                      </td>
                      @endif
                    </tr>
                    <?php 
                    $existe =  $municipio_meta['municipio'];
                    $total_accesos_meta +=  $municipio_meta['total_meta'];
                    ?>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" class="text-right">TOTAL:</th>
                      <td>{{$total_accesos_meta}}</td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
          </div>

            <div class="col-md-6">
              <div class="box box-info" id="espera">
                <div class="box-header bg-blue">
                  <h2 class="box-title">Estados del cliente</h2>
                </div>
                <div class="box-body">
                  {!! $grafica_estado_clientes->html() !!}
                </div>
              </div>
            </div> 

            <div class="col-md-6">
              <div class="box box-info" id="espera">
                <div class="box-header bg-blue">
                  <h2 class="box-title">Estados del servicio del cliente</h2>
                </div>
                <div class="box-body">
                  {!! $grafica_estado_servicio_clientes->html() !!}
                </div>
              </div>
            </div> 

            <div class="col-md-12">
              <div class="box box-info" id="espera">
                <div class="box-header bg-blue">
                  <h2 class="box-title">Ventas en el mes</h2>

                  <div class="box-tools">
                    <div class="navbar-form navbar-left">
                      <div class="form-group input-group-sm">

                        <select class="form-control" name="departamento" id="departamento">
                          <option value="">Elija un departamento</option>
                          @foreach($departamentos as $departamento)                            
                            <option value="{{$departamento->DeptId}}">{{$departamento->NombreDepartamento}}</option>
                          @endforeach
                        </select>

                        <select class="form-control" name="proyecto_municipio_id" id="proyecto_municipio_id">
                            <option value="">Elija un municipio</option>
                        </select>

                        <input type="month" name="mes" id="mes" class="form-control" value="{{date('Y-m')}}">
                      </div>
                      <button type="submit" id="filtrar_ventas" class="btn btn-default btn-sm"> <i class="fa fa-search"></i>  Filtrar</button>
                    </div>
                  </div>
                </div>
                <div class="box-body table-responsive">
                  <div class="charts-chart">
                      <div id="grafica_ventas" style="height: 100%; width: 100%;"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">             
              <div class="box box-info" id="espera">
                <div class="box-header bg-blue">
                  <h3 class="box-title">Total de ventas por Asesor</h3>
                </div>
                <div class="box-body table-responsive">
                   <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Asesor Comercial</th>
                        <th>Total Ventas</th>
                        @role(['comercial', 'admin'])
                        <th>Acciones</th>
                        @endrole
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i=1; ?>
                      @foreach($total_ventas_asesor as $dato)

                        <tr>
                          <td>{{$i}}</td>
                          @if(!empty($dato->vendedor))                  
                            <td><a href="{{route('perfil.show', array($dato->vendedor->id, $proyecto->ProyectoID))}}">{{$dato->vendedor->name}}</a></td>
                          @else                  
                            <td>SIN ASIGNAR</td>
                          @endif
                          <td>{{$dato->cantidad}}</td>
                          @role(['comercial', 'admin'])
                          <td>
                            @if(isset($dato->vendedor->id))                            
                            <a href="{{route('exportar.estado', $dato->vendedor->id)}}" class="btn btn-xs btn-success" title="Descargar Rechazados">
                              <i class="fa fa-file-excel-o"></i>
                            </a>
                            @endif
                          </td>
                          @endrole
                        </tr>
                        <?php $i+=1; ?>
                      @endforeach
                      
                    </tbody>
                  </table>
                </div>
                <div class="box-footer clearfix">
                    
                </div>
              </div>
            </div>
          </div>
    </div>

    @include('adminlte::proyectos.partials.mapa')

    @section('mis_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>
    {!! $grafica_municipio_instalaciones->script() !!}
    {!! $grafica_estado_servicio_clientes->script() !!}
    {!! $grafica_estado_clientes->script() !!}

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs"></script>
    <script>
      $('#showMapa').on('show.bs.modal', function (event) {
        var a = $(event.relatedTarget) // Button that triggered the modal
        var proyecto = a.data('proyecto');
        var municipio = a.data('municipio');
        var url = '/proyectos/'+proyecto+'/mapa';


        var parametros = {
          municipio : municipio,
          proyecto : proyecto,
          '_token' : $('input:hidden[name=_token]').val() 
        }

        $.post(url, parametros).done(function(data){
          if(!jQuery.isEmptyObject(data)){
            initMap(data);
          }
          

        }).fail(function(){
          alert('error');
        });
        
        var modal = $(this);
        /*modal.find('#titulo').text(titulo);
        if (tipo == 'pdf') {
          modal.find('#presentacion').html('<iframe src="'+ recipient +'" width="100%" height="600" style="height: 85vh;"></iframe>');        
        }else{        
          modal.find('#presentacion').html('<img src="'+ recipient +'" id="img-attachment" class="img-responsive" width="100%">');
        }*/
      });



        function initMap(data) {
          var map;
          var bounds = new google.maps.LatLngBounds();
          var mapOptions = {
              mapTypeId: 'roadmap'
          };
                          
          // Display a map on the web page
          map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
          map.setTilt(50);

          var markers = [];
          var infoWindowContent = [];
          var imagen = '/img/marker.png';

          $.each(data['graficar'], function(index, clienteObj){

            var estado = clienteObj.estado;
            imagen = '/img/marker-'+(estado.toLowerCase()).replace(' ', '-')+'.png';

            
              markers.push([clienteObj.titulo,clienteObj.latitud,clienteObj.longitud,imagen]);
              infoWindowContent.push([
               '<label>Cedula:</label> '+ clienteObj.titulo +' <br><label>Estado:</label> '+ clienteObj.estado +' <br> <label>Direccion:</label> '+ clienteObj.direccion +'<br> <a href="/clientes/'+ clienteObj.id+ '" target="_black"> Más Información</a>'
              ]);
          });
              
          // Add multiple markers to map
          var infoWindow = new google.maps.InfoWindow(), marker, i;
          
          // Place each marker on the map  
          for( i = 0; i < markers.length; i++ ) {
              var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
              bounds.extend(position);
              marker = new google.maps.Marker({
                  position: position,
                  map: map,
                  icon: markers[i][3],
                  title: markers[i][0]
              });
              
              // Add info window to marker    
              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                      infoWindow.setContent(infoWindowContent[i][0]);
                      infoWindow.open(map, marker);
                  }
              })(marker, i));

              // Center the map to fit all markers on the screen
              map.fitBounds(bounds);
          }

          // Set zoom level
          var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
              this.setZoom(14);
              google.maps.event.removeListener(boundsListener);
          });
            
        }
        // Load initialize function
        //google.maps.event.addDomListener(window, 'load', initMap);
    </script>

    <script type="text/javascript">
      $(document).ready(function(){
        graficarVentas();
      });

      function graficarVentas(){
        var rYKQBImpMn = new Highcharts.Chart({
            chart: {
                renderTo: "ventas_totales_municipio",
            },
            title: {
                text:  "Ventas Totales por municipio",
                x: -20 //center
            },
            credits: {
                enabled: false
            },
            xAxis: {
                title: {
                    text: ""
                },
                categories: {!! $data_label !!},
            },
            yAxis: {
                title: {
                    text: "Total"
                },
                plotLines: [{
                    value: 0,
                    height: 1,
                    width: 1                    
                }]
            },
            legend: {},
            series: {!! $data_ventas_totales !!}
        });
      }
    </script>

    <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipiosproyectos.js')}}"></script>
    <script type="text/javascript">
      $('#departamento').on('change', function(){
          var parameters = {
              departamento_id : $(this).val(),
              proyecto_id : {{$proyecto->ProyectoID}},
              '_token' : $('input:hidden[name=_token]').val()
          };

          $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){

              $('#proyecto_municipio_id').empty();
              $('#proyecto_municipio_id').append('<option value="">Elija un municipio</option>');
              $.each(data, function(index, municipiosObj){                   
                  $('#proyecto_municipio_id').append('<option value="'+municipiosObj.MunicipioId+'">'+municipiosObj.NombreMunicipio+'</option>');                    
              });
          }).fail(function(e){
              alert('error');
          });
      });
    </script>
    <script type="text/javascript">

      $('#filtrar_ventas').on('click', function(){

      var proyecto = {{$proyecto->ProyectoID}};
      var municipio = $('#proyecto_municipio_id').val();
      var nombre_municipio = $('#proyecto_municipio_id option:selected').text();
      var mes = $('#mes').val();

        var parametros = {
          'proyecto' : proyecto,
          'municipio' : municipio,
          'mes' : mes,
          '_token' : $('input:hidden[name=_token]').val() 
        }

        $.post('/usuarios/ventas', parametros).done(function(data){

          if (municipio.length == 0) {
            nombre_municipio = '';
          }

          if(!jQuery.isEmptyObject(data.labels)){              
            graficarVentasMes(data.labels, data.ventas, mes, nombre_municipio);
          }else{
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.warning("No hay datos");
          }
        }).fail(function(e){
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.error(e.statusText);
        });
      });

        $(function() {
          var proyecto = {{$proyecto->ProyectoID}};
          var mes = $('#mes').val(); 

          var parametros = {
            'proyecto' : proyecto,
            'municipio' : null,
            'mes' : mes,
            '_token' : $('input:hidden[name=_token]').val() 
          }

          $.post('/usuarios/ventas', parametros).done(function(data){

            if(!jQuery.isEmptyObject(data.labels)){              
              graficarVentasMes(data.labels, data.ventas, mes, '');
            }else{
              toastr.options.positionClass = 'toast-bottom-right';
              toastr.warning("No hay datos");
            }
          }).fail(function(){
              alert('error');
          });
        });

        function graficarVentasMes(label1, dataset1, mes, municipio){
            var grafica_ventas = new Highcharts.Chart({
                chart: {
                    renderTo: "grafica_ventas",
                },
                title: {
                    text:  municipio + " Mes " + mes,
                    x: -20 //center
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    title: {
                        text: ""
                    },
                    categories: label1,
                },
                yAxis: {
                    title: {
                        text: "Total"
                    },
                    plotLines: [{
                        value: 0,
                        height: 1,
                        width: 1
                    }]
                },
                legend: {},
                series: [{
                    name: "Total",
                    data: dataset1
                }]
            });
        }
    </script>
    @endsection
@endsection