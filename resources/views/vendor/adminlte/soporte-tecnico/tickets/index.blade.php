@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-graduation-cap"></i>  Tickets</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" action="{{route('tickets.index')}}" role="search" method="GET">  
                            @permission('tickets-exportar')
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                    <li><a href="#" id="ver_mapa"> <i class="fa fa-map-marker"></i> Mapa</a></li>
                                    
                                </ul>
                            </div>
                            @endpermission
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">                                            
                                        </div> 

                                        <div class="form-group col-md-4">
                                            <input type="number" class="form-control" name="ticket" placeholder="Número Ticket" value="{{(isset($_GET['ticket'])? $_GET['ticket']:'')}}" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="estado" id="estado">
                                                <option value="">Elija un estado</option>
                                                @foreach($estados as $estado)

                                                    <option value="{{$estado->EstadoTicket}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado->EstadoTicket) ? 'selected' : '') : ''}}>{{$estado->Descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="">Elija un proyecto</option>
                                                @foreach($proyectos as $proyecto)

                                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="departamento" id="departamento">
                                                <option value="">Elija un departamento</option>
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                                </div>
                            </div>                              
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col"># Ticket</th> 
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Dias sin Resolver</th>
                                    @permission('tickets-eliminar')
                                    <th>Acciones</th>
                                    @endpermission
                                </tr>
                                @foreach($tickets as $dato)

                                <?php 
                                    $contador = date_diff(date_create($dato->FechaApertura), date_create($dato->FechaCierre));
                                    $total_dias = $contador->format('%a');
                                ?>
                                <tr>
                                    <th>
                                        @if($dato->EstadoDeTicket == 0)
                                            <i class="fa fa-circle text-gray"></i>
                                        @elseif($total_dias >= 15)
                                            <i class="fa fa-circle text-red"></i>
                                        @elseif($total_dias >= 10 && $total_dias < 15)
                                            <i class="fa fa-circle text-yellow"></i>
                                        @elseif($total_dias < 10)
                                            <i class="fa fa-circle text-default"></i>
                                        @endif
                                    </th>
                                    <th>
                                        <a href="{{route('tickets.show', $dato->TicketId)}}">{{$dato->TicketId}}</a>
                                    </th>

                                    <td>
                                        {{$dato->cliente->NombreBeneficiario}} {{$dato->cliente->Apellidos}}
                                    </td>

                                    @if(!empty($dato->cliente->ubicacion))
                                        <td>{{$dato->cliente->ubicacion->municipio->NombreMunicipio}}</td>
                                        <td>{{$dato->cliente->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                    @else
                                        <td>{{$dato->cliente->municipio->NombreMunicipio}}</td>
                                        <td>{{$dato->cliente->municipio->departamento->NombreDelDepartamento}}</td>
                                    @endif
                                    <td>
                                        {{$dato->estado->Descripcion}}
                                    </td>

                                    <td>
                                        <?php 
                                            $contador = date_diff(date_create($dato->FechaApertura), date_create($dato->FechaCierre));
                                        ?>
                                        {{$contador->format('%a')}} Días sin solución
                                    </td>
                                    
                                    <td>
                                        @permission('tickets-editar')
                                            <a href="{{route('tickets.edit', $dato->TicketId)}}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endpermission

                                        @if($dato->estado->Descripcion != 'Cerrado')
                                            @permission('tickets-eliminar')
                                            <form action="{{route('tickets.destroy', $dato->TicketId)}}" method="post" style="display:inline-block">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                

                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                    <i class="fa fa-trash-o"></i>   
                                                </button>
                                            </form>
                                            @endpermission
                                        @endif
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$tickets->currentPage()}} de {{$tickets->lastPage()}}. Total registros {{$tickets->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $tickets->appends(Request::only(['proyecto','departamento','municipio','estado']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-mapa" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">

            <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
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
    @section('mis_scripts')

        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs"></script>
        <script>

            $('#ver_mapa').on('click', function(){
                $('#modal-mapa').modal('show');
                initMap();
            });

            var data;

            function initMap() {
                var map;
                var bounds = new google.maps.LatLngBounds();
                var mapOptions = {
                    mapTypeId: 'roadmap'
                };
                                
                // Display a map on the web page
                map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
                map.setTilt(50);

                data = {!!$graficar!!};
                var markers = [];
                var infoWindowContent = [];

                $.each(data, function(index, ticketObj){
                    markers.push([ticketObj.titulo,ticketObj.latitud,ticketObj.longitud]);
                    infoWindowContent.push(['<h4>' + ticketObj.titulo +' </h4><label>Ticket:</label> '+ ticketObj.ticket +' <br><label>Fecha Reporte:</label> '+ ticketObj.fecha +' <br> <label>Direccion:</label> '+ ticketObj.direccion +' <br> <label>Municipio:</label> '+ ticketObj.municipio +' <br> <a href="/tickets/'+ticketObj.ticket +'" target="_blanck"> Más Información</a>']);

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
                    this.setZoom(6);
                    google.maps.event.removeListener(boundsListener);
                });
                
            }
            // Load initialize function
            google.maps.event.addDomListener(window, 'load', initMap);
        </script>

        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    identificacion : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                    ticket : "{!! (isset($_GET['ticket'])? $_GET['ticket']:'') !!}",
                    proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                    departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                    estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $('#opciones').attr('disabled',true);
                $('#icon-opciones').removeClass('fa-gears');
                $('#icon-opciones').addClass('fa-refresh fa-spin');

                $.ajax({
                    type: "POST",
                    url: '/tickets/exportar',
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
    @endsection
@endsection