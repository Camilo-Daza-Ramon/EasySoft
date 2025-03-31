@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-home"></i>  Campaña - {{$campaña->nombre}}</h1>
@endsection
@section('main-content')
    <div class="row">      
        <div class="container-fluid spark-screen">       
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info" id="espera">
                        <div class="box-header bg-blue">
                            <div class="form-group col-md-11">
                                <form id="form-buscar" action="{{route('campanas.show',$campaña->id)}}" role="search" method="GET">                                                                                                                         
                                    <div class="form-group col-md-4">
                                        <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">
                                    </div>   
                                    
                                    <div class="form-group col-md-4">
                                        <select class="form-control" id="estado" name="estado" >
                                            <option value="">Elija un estado</option>
                                            @foreach($estados as $estado)
                                                <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{$estado}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
    
                                    <div class="form-group col-md-4">
                                        <select class="form-control" id="departamento" name="departamento" >
                                            <option value="">Elija un departamento</option>
                                            @foreach($departamentos as $departamento)
                                                <option value="{{$departamento->id}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->id) ? 'selected' : '') : ''}}>{{$departamento->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>    
                                    
                                    <div class="form-group col-md-4">
                                        <select class="form-control" id="municipio" name="municipio" >
                                            <option value="">Elija un municipio</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <select class="form-control" id="barrio" name="barrio" >
                                            <option value="">Elija un barrio</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <input type="number" class="form-control" name="mora_desde" placeholder="Mora Desde" value="{{ (isset($_GET['mora_desde'])? $_GET['mora_desde']:'') }}" autocomplete="off">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <input type="number" class="form-control" name="mora_hasta" placeholder="Mora Hasta" value="{{(isset($_GET['mora_hasta'])? $_GET['mora_hasta']:'')}}" autocomplete="off">
                                    </div>

                                    <div class="form-group col-md-4 checkbox">
                                        <label>
                                            <input type="checkbox" name="solicitudes_pendientes" value="1" {{(isset($_GET['solicitudes_pendientes'])? 'checked':'')}}> Solicitudes Pendiente
                                        </label>
                                    </div> 
                        
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                
                                </form>
                            </div> 
                                                        
                            <div class="btn-group pull-right form-group ">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('campañas-exportar')
                                        <li>
                                            <a href="#" id="exportar" data-id="{{$campaña->id}}"><i class="fa fa-file-excel-o"></i> Exportar</a>
                                        </li>
                                    @endpermission
                                    @permission('campañas-estadisticas')
                                        <li>
                                            <a href="{{route('campanas.estadisticas',$campaña->id)}}"><i class="fa fa-pie-chart"></i>Estadisticas</a>
                                        </li>
                                    @endpermission
                                        <li><a href="#" data-toggle="modal" data-target="#mapa"> <i class="fa fa-map-marker"></i> Mapa</a></li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>                                                                                                               
                                        <th>#</th>
                                        <th>Documento</th>
                                        <th>Nombre</th>                                    
                                        <th>Numero Celular</th>
                                        <th>Meses Mora</th>                                                         
                                        <th>Estado Cliente</th>        
                                        <th>Estado</th>                                                                								
                                        <th>Acciones</th>                                        
                                    </tr>
                                    <?php $contar = 0; ?>
                                    @foreach($clientes as $cliente)                                    
                                        <tr>
                                            <td>{{$contar+=1}}</td>
                                            <td>{{$cliente->cliente->Identificacion}}</td>
                                            <td>{{$cliente->cliente->NombreBeneficiario}} {{$cliente->cliente->Apellidos}}</td>
                                            <td>
                                                <p>78{{$cliente->cliente->TelefonoDeContactoMovil}}</p>
                                            </td>
                                            <td>
                                                @if(isset($cliente->cliente->historial_factura_pago))
                                                    {{round($cliente->cliente->historial_factura_pago->meses_mora)}}
                                                @endif
                                            </td>
                                            <td>{{$cliente->cliente->Status}}</td>
                                            <td>
                                                @if($cliente->estado != 'PENDIENTE')
                                                    <span class="label label-primary">{{$cliente->estado}}</span>                                                   
                                                @else
                                                    <span class="label label-warning">{{$cliente->estado}}</span>                                                    
                                                @endif
                                            </td>
                                            <td>
                                                @if($cliente->respuestas->count() > 0)
                                                    @permission('campañas-respuestas-ver')
                                                        <button class="btn bt-default btn-xs " onclick="traer_respuesta({!!$cliente->id!!});return false;"><i class="fa fa-eye"></i></button>
                                                    @endpermission
                                                @else
                                                    @if ($campaña->estado != 'FINALIZADA' or auth()->user()->can('campañas-ejecucion'))
                                                        @permission('campañas-respuestas-crear')
                                                            <a href="{{route('campanas.llamar', [$campaña->id , $cliente->id ])}}" class="btn btn-success btn-xs"> <i class="fa fa-phone"></i> </a>
                                                        @endpermission
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer clearfix">
                            <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$clientes->currentPage()}} de {{$clientes->lastPage()}}. Total registros {{$clientes->total()}}</span>
                            <!-- paginacion aquí -->                        
                            {!! $clientes->appends(Request::only(['documento','estado','departamento','municipio','mora_desde','mora_hasta']))->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('adminlte::campana.mapa')

    @permission('campañas-respuestas-ver')
        @include('adminlte::campana.partials.show-respuesta')
    @endpermission
    
   

    @section('mis_scripts')
    
        @permission('campañas-respuestas-ver')
            <script type="text/javascript" src="/js/campaña/show.js"></script>
        @endpermission

        @permission('campañas-listar')
            <script type="text/javascript" src="/js/campaña/buscar_municipio.js"></script>
            <script type="text/javascript" src="/js/myfunctions/barrios.js"></script> 
        @endpermission
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs"></script>
        

        <script>
            const campana_id = {!!$campaña->id!!};
            toastr.options.positionClass = 'toast-bottom-right';
            
            departamento = "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}";
            municipio = "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}";
            barrio = "{!! (isset($_GET['barrio'])? $_GET['barrio']:'') !!}";
            
            $(document).ready(function(){

                if(municipio.length > 0){
                    buscar_municipio(departamento, municipio);
                }

                if(barrio.length > 0){
                    buscar_barrios(municipio, barrio);
                }
                
            });

        </script>
        
        @permission('campañas-exportar')
            <script type="text/javascript" >
                $('#exportar').on('click',function(){
                    var enlace = document.getElementById('exportar');

                    var parametros = {   
                        campana_id :  enlace.dataset.id,  
                        estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",     
                        municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                        departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                        '_token' : $('input:hidden[name=_token]').val()
                    }
                    $('#opciones').attr('disabled',true);
                    $('#icon-opciones').removeClass('fa-gears');
                    $('#icon-opciones').addClass('fa-refresh fa-spin');


                    $.ajax({
                        type: "POST",
                        url: '/campanas/exportar',
                        data: parametros,
                        xhrFields: {
                            responseType: 'blob' // to avoid binary data being mangled on charset conversion
                        },
                        success: function(blob, status, xhr) {
                            // check for a filename
                            var filename = "";
                            var disposition = xhr.getResponseHeader('Content-Disposition');
                            if (disposition && disposition.indexOf('attachment') !== -1) {
                                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                var matches = filenameRegex.exec(disposition);
                                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                            }

                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);

                                if (filename) {
                                    // use HTML5 a[download] attribute to specify filename
                                    var a = document.createElement("a");
                                    // safari doesn't support this yet
                                    if (typeof a.download === 'undefined') {
                                        window.location.href = downloadUrl;
                                    } else {
                                        a.href = downloadUrl;
                                        a.download = filename;
                                        document.body.appendChild(a);
                                        a.click();
                                    }
                                } else {
                                    window.location.href = downloadUrl;
                                }

                                setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                            }

                            $('#opciones').attr('disabled',false);
                            $('#icon-opciones').removeClass('fa-refresh fa-spin');
                            $('#icon-opciones').addClass('fa-gears');
                        }, 
                        error: function(blob, status, xhr){
                            toastr.options.positionClass = 'toast-bottom-right';
                            toastr.error(xhr);

                            $('#opciones').attr('disabled',false);
                            $('#icon-opciones').removeClass('fa-refresh fa-spin');
                            $('#icon-opciones').addClass('fa-gears');
                        }
                    });

                });
            </script>
        @endpermission
        <script>
            var map;

            $('#mapa').on('show.bs.modal', function (event) {                
                var data = {!!$graficar!!};                
                // Load initialize function
                google.maps.event.addDomListener(window, 'load', initMap(data, campana_id));
                getUserPosition();
            });

            function initMap(data, campana) {
                
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
                        <a class="btn btn-xs btn-success btn-block" href="/campanas/${campana}/llamar/${clienteObj.id}"> Diligenciar Formulario</a>
                    `]);

                });
                    
                // Add multiple markers to map
                var infoWindow = new google.maps.InfoWindow(), marker, i;
                
                // Place each marker on the map  
                for( i = 0; i < markers.length; i++ ) {
                    var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
                    bounds.extend(position);
                    marker = new google.maps.Marker({
                        icon: '/img/marker-activo.png',
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

        <script>
            $('select[name=municipio]').on('change', function(){
                if($(this).val().length > 0){
                    buscar_barrios($(this).val());
                }
            });
        </script>

    @endsection
    
@endsection