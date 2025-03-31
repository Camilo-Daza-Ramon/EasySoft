@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-map-marker"></i> {{$punto_atencion->nombre}}</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
        	<div class="col-md-12">
        		<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title">Punto de Atencion</h3>

		              	@permission('puntos-atencion-actualizar')		               
                        <div class="box-tools pull-right">
                            <a href="{{route('puntos-atencion.edit', $punto_atencion->id)}}" class="btn btn-default float-bottom btn-sm">
				                <i class="fa fa-edit"></i>  Editar          
				            </a>
                        </div>
                       	@endpermission
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
		            	<div>
		            		<div class="col-md-12 no-padding">
								<div id="map" style="width: 100%; height: 300px; margin: 15px 0px 15px 0px;"></div>
							</div>
		            	</div>
		        		<div class="row">
		        			<div class="col-md-4">
		        				<label>Direccion</label>
		        				<p>{{$punto_atencion->direccion}}</p>
		        			</div>
		        			<div class="col-md-4">
		        				<label>Barrio</label>
		        				<p>{{$punto_atencion->barrio}}</p>
		        			</div>
		        			<div class="col-md-4">
		        				<label>Estado</label>
		        				<p>{{$punto_atencion->estado}}</p>
		        			</div>
		        		</div>
		        		<div class="row">
		        			<div class="col-md-4">
		        				<label>Proyecto</label>
		        				<p>{{$punto_atencion->proyecto->NumeroDeProyecto}}</p>
		        			</div>
		        			<div class="col-md-4">
		        				<label>Municipio</label>
		        				<p>{{$punto_atencion->municipio->NombreMunicipio}}</p>
		        			</div>
		        			<div class="col-md-4">
		        				<label>Departamento</label>
		        				<p>{{$punto_atencion->municipio->NombreDepartamento}}</p>
		        			</div>
		        		</div>
		        		<div class="row">
		        			<div class="col-md-4">
		        				<label>Latitud</label>
		        				<p>{{$punto_atencion->latitud}}</p>
		        			</div>
		        			<div class="col-md-4">
		        				<label>Longitud</label>
		        				<p>{{$punto_atencion->longitud}}</p>
		        			</div>		        			
		        		</div>
		        	</div>
		        </div>


	        </div>
        	<div class="col-md-5">
        		<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title">Areas</h3>

		              	@permission('puntos-atencion-areas-crear')
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addAreaPunto">
				                <i class="fa fa-plus"></i>  Agregar          
				            </button>
                        </div>
                        @endpermission
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">

		            	<table class="table table-bordered">
		            		<thead>
		            			<tr>
									<th style="width: 10px">#</th>
									<th>Nombre</th>
									<th>Accion</th>
								</tr>								
		            		</thead>
			                <tbody>
			                	@foreach($punto_atencion->punto_atencion_area as $area)
			                	<tr>
			                		<td>{{$area->id}}</td>
			                		<td>{{$area->nombre}}</td>
			                	</tr>
			                	@endforeach
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
    	<div class="col-md-7">
    		<div class="box box-primary">
            <div class="box-header with-border bg-blue">
              <h3 class="box-title">Ventanillas</h3>
              <div class="box-tools pull-right">

              	@permission('puntos-atencion-ventanillas-crear')
              	<button type="button" class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addVentanillaPunto">
              		<i class="fa fa-plus"></i>  Agregar          
		            </button>
		            @endpermission
		          </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            	<table class="table table-bordered">
            		<thead>
            			<tr>
										<th style="width: 10px">#</th>
										<th>Nombre</th>									
										<th>Área</th>
										<th>Asesor</th>
										<th>Accion</th>
									</tr>								
            		</thead>
            		<tbody>
            			@foreach($punto_atencion->punto_atencion_area as $area)
            				@if(count($area->punto_atencion_ventanilla) > 0)
            					@foreach($area->punto_atencion_ventanilla as $ventana)
	            					<tr>
	            						<td>{{$ventana->id}}</td>
	            						<td>{{$ventana->nombre}}</td>
	            						<td>{{$area->nombre}}</td>
	            						<td>{{$ventana->user->name}}</td>
	            						<td>
	            							@permission('puntos-atencion-ventanillas-eliminar')
	            								<form action="{{route('puntos-atencion.ventanillas.destroy', [$punto_atencion->id, $ventana->id])}}" method="post">
                                <input type="hidden" name="_method" value="delete">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
	                              <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
	                                <i class="fa fa-trash-o"></i>   
	                              </button>
                              </form>
	            							@endpermission
	            						</td>
	            					</tr>
	            				@endforeach
            				@endif
            			@endforeach
            		</tbody>
            	</table>
            </div>
          </div>
        </div>
      </div>
    </div>

    @permission('puntos-atencion-areas-crear')
    	@include('adminlte::atencion-clientes.puntos-atencion.areas.add')
    @endpermission

    @permission('puntos-atencion-ventanillas-crear')
    	@include('adminlte::atencion-clientes.puntos-atencion.ventanillas.add')
    @endpermission

    @section('mis_scripts')
    <script type="text/javascript">
    	imagen = '/img/marker-{!!$punto_atencion->estado!!}.png';

      function initMap() {

        var uluru = {lat: {{(isset($punto_atencion->latitud) ? $punto_atencion->latitud: 0.0)}}, lng: {{(isset($punto_atencion->longitud))? $punto_atencion->longitud : 0.0}} };

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
          icon: imagen
        });

        var infowindow = new google.maps.InfoWindow({
          content: "{{$punto_atencion->direccion}} <br> <b><b>"
        });

        infowindow.open(map, marker);
      }
    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs&callback=initMap"></script>
	@endsection
@endsection