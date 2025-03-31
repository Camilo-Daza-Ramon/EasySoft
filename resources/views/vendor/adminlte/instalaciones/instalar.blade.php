@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-hdd-o"></i>  Clientes por instalar</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('instalaciones.instalar')}}" role="search" method="GET">                            

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">
                                </div>

                                <div class="form-group col-md-3">
                                    <select class="form-control" name="proyecto" id="proyecto">
                                        <option value="">Elija un proyecto</option>
                                        @foreach($proyectos as $proyecto)

                                            <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <select class="form-control" name="departamento" id="departamento">
                                        <option value="">Elija un departamento</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <select class="form-control" name="municipio" id="municipio">
                                        <option value="">Elija un municipio</option>
                                    </select> 
                                </div>

                                <div class="col-md-1">
                                    <a href="#" data-toggle="modal" data-target="#mapa" class="btn bg-purple">
                                        <i class="fa fa-map-marker"></i> Mapa
                                    </a>
                                </div>
                                
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fa fa-search"></i>  Buscar
                                    </button>
                                </div>
                            </div>
                        </form>                        


                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Dirección</th>                                    
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Proyecto</th>
                                    <th>Estado</th>
                                </tr>
                                @foreach($instalaciones as $dato)
                                    <tr>
                                        <th>
                                            <a href="{{route('instalaciones.create', $dato->ClienteId)}}">{{$dato->Identificacion}}</a>
                                        </th>
                                        <td>{{$dato->DireccionDeCorrespondencia}} - {{$dato->Barrio}}</td>                                 


                                        <td>{{mb_convert_case($dato->NombreBeneficiario . ' ' . $dato->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
                                        
                                            @if(!empty($dato->municipio))
                                                <td>{{$dato->municipio->NombreMunicipio}}</td>
                                                <td>{{$dato->municipio->departamento->NombreDelDepartamento}}</td>
                                            @else
                                                <td>{{$dato->ubicacion->municipio->NombreMunicipio}}</td>
                                                <td>{{$dato->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                            @endif
                                        <td>{{$dato->proyecto->NumeroDeProyecto}}</td>

                                        <td>
                                            <span class="label label-primary">{{$dato->Status}}</span>                                        
                                        </td>                                    
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$instalaciones->currentPage()}} de {{$instalaciones->lastPage()}}. Total registros {{$instalaciones->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $instalaciones->appends(Request::only(['proyecto', 'departamento', 'municipio']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('adminlte::campana.mapa')

    @section('mis_scripts')   
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs"></script>
        <script>
            var map;

            $('#mapa').on('show.bs.modal', function (event) {                
                var data = {!!$graficar!!};                
                // Load initialize function
                google.maps.event.addDomListener(window, 'load', initMap(data));
                getUserPosition();
            });

            function initMap(data) {
                
                var bounds = new google.maps.LatLngBounds();
                var mapOptions = {                    
                    mapTypeId: 'roadmap'
                };
                                
                // Display a map on the web page
                map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
                map.setTilt(50);

                
                var markers = [];
                var infoWindowContent = [];

                $.each(data, function(index, clienteObj){
                    markers.push([clienteObj.latitud,clienteObj.longitud]);
                    infoWindowContent.push([`
                        <h4>Datos Cliente</h4>
                        <label>Nombre:</label> ${clienteObj.nombre}<br> 
                        <label>Direccion:</label>  ${clienteObj.direccion}  <br> 
                        <label>Barrio:</label>  ${clienteObj.barrio}  <br>
                        <a class="btn btn-xs btn-primary btn-block" href="/instalaciones/create/${clienteObj.id}"> Instalar</a>
                    `]);

                });
                    
                // Add multiple markers to map
                var infoWindow = new google.maps.InfoWindow(), marker, i;
                
                // Place each marker on the map  
                for( i = 0; i < markers.length; i++ ) {
                    var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
                    bounds.extend(position);
                    marker = new google.maps.Marker({
                        icon: '/img/marker-en-instalacion.png',
                        position: position,
                        map: map,
                        title: 'Datos Cliente'
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
                    this.setZoom(8);
                    google.maps.event.removeListener(boundsListener);
                });
                    
            }

            function getUserPosition() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(onSuccessGeolocating, onErrorGeolocating,{
                        enableHighAccuracy: true,
                        maximumAge:         5000,
                        timeout:            10000
                    });
                 }else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            }


            function onErrorGeolocating(error){
              switch(error.code){

                case error.PERMISSION_DENIED:
                  alert('ERROR: No se permitió o no se tienen suficientes privilegios para acceder al servicio de geolocalización.');
                      $('#permisos').modal('show');
                break;

                case error.POSITION_UNAVAILABLE:
                  alert("ERROR: El dispositivo no pudo determinar correctamente su ubicación.");
                break;

                case error.TIMEOUT:
                  alert("ERROR: El intento de geolocalización tomó mas tiempo del permitido.");
                break;

                default:
                  alert("ERROR: Problema desconocido.");
                break;
              }
            }

            const onSuccessGeolocating = (position) => {

                const latitud = position.coords.latitude;
                const longitud = position.coords.longitude;
                
                // Nueva posición para el marcador
                var nuevaPosicion = { lat: latitud, lng: longitud };

                // Crear un nuevo marcador
                var nuevoMarcador = new google.maps.Marker({
                    position: nuevaPosicion,
                    map: map, // Agregar el marcador al mapa existente
                    title: 'Yo'
                });

                // Contenido de la ventana de información del nuevo marcador
                var contenidoInfoVentana = '<p>Aquí estoy</p>';

                // Crear una nueva ventana de información para el nuevo marcador
                var nuevaVentanaInfo = new google.maps.InfoWindow({
                    content: contenidoInfoVentana
                });

                // Mostrar la ventana de información cuando se hace clic en el marcador
                nuevoMarcador.addListener('click', function() {
                    nuevaVentanaInfo.open(map, nuevoMarcador);
                });
            }

            
        </script>


    @endsection
@endsection