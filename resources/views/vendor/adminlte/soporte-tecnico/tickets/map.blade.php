@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-map-marker"></i>  Mantenimientos</h1>
@endsection

@section('code_header')
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.css')}}">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
            	<div class="box box-info">
					<div class="box-body">                       
                        <div class="row">
                            <div class="col">
                                <div class="form-row rounded box-shadow">
                                    <div class="form-group col-md-12">
                                        <div id="mapCanvas" style="width: 100%; height: 600px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('mis_scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs"></script>
    <script>       

        function initMap() {
            var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                mapTypeId: 'roadmap'
            };
                            
            // Display a map on the web page
            map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
            map.setTilt(50);

            var data = {!!$graficar!!};
            var markers = [];
            var infoWindowContent = [];

            $.each(data, function(index, ticketObj){
                markers.push([ticketObj.titulo,ticketObj.latitud,ticketObj.longitud]);
                infoWindowContent.push(['<h4>' + ticketObj.titulo +' </h4><label>Ticket:</label> '+ ticketObj.ticket +' <br><label>Fecha Reporte:</label> '+ ticketObj.fecha +' <br> <label>Direccion:</label> '+ ticketObj.direccion +'<br> <a href="#"> Más Información</a>']);

            });
                
            // Multiple markers location, latitude, and longitude
             /*var markers = [
                ['Brooklyn Museum, NY', 40.671531, -73.963588],
                ['Brooklyn Public Library, NY', 40.672587, -73.968146],
                ['Prospect Park Zoo, NY', 40.665588, -73.965336]
            ];
                                
            // Info window content
           var infoWindowContent = [
                ['<div class="info_content">' +
                '<h3>Brooklyn Museum</h3>' +
                '<p>The Brooklyn Museum is an art museum located in the New York City borough of Brooklyn.</p>' + '</div>'],
                ['<div class="info_content">' +
                '<h3>Brooklyn Public Library</h3>' +
                '<p>The Brooklyn Public Library (BPL) is the public library system of the borough of Brooklyn, in New York City.</p>' +
                '</div>'],
                ['<div class="info_content">' +
                '<h3>Prospect Park Zoo</h3>' +
                '<p>The Prospect Park Zoo is a 12-acre (4.9 ha) zoo located off Flatbush Avenue on the eastern side of Prospect Park, Brooklyn, New York City.</p>' +
                '</div>']
            ];*/
                
            // Add multiple markers to map
            var infoWindow = new google.maps.InfoWindow(), marker, i;
            
            // Place each marker on the map  
            for( i = 0; i < markers.length; i++ ) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
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
        google.maps.event.addDomListener(window, 'load', initMap);
    </script>   
    @endsection
@endsection