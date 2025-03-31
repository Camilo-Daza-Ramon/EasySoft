@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-edit"></i> Editar Instalacion - {{$instalacion->id}}</h1>
    
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-5">
			<div class="box box-info">				
				<div class="box-header with-border bg-blue">
					<h3 class="box-title">Editar Instalacion</h3>
				</div>
				<div class="box-body">
					<form id="form-cliente" action="{{route('instalaciones.update', $instalacion->id)}}" method="post">
						<input type="hidden" name="_method" value="PUT">
						{{csrf_field()}}		

						<div class="row">

							<div class="form-group{{ $errors->has('vel_bajada') ? ' has-error' : '' }} col-md-6">
								<label>Vel. Bajada</label>
								<input type="number" class="form-control" name="vel_bajada" value="{{number_format($instalacion->velocidad_bajada,2, '.','')}}" step="0.01" required>
							</div>
							<div class="form-group{{ $errors->has('vel_subida') ? ' has-error' : '' }} col-md-6">
								<label>Vel. Subida</label>
								<input type="number" class="form-control" name="vel_subida" value="{{number_format($instalacion->velocidad_subida,2, '.','')}}" step="0.01" required>
							</div>

							<div class="form-group{{ $errors->has('servicio_activo') ? ' has-error' : '' }} col-md-6">
								<label>Servicio queda activo?</label>
								<select class="form-control" name="servicio_activo" required>
									<option value="">Elija una opcion</option>
									@if($instalacion->servicio_activo == 'SI')
										<option value="SI" selected>SI</option>
									@else
										<option value="SI">SI</option>
									@endif
									<option value="NO">NO</option>
								</select>
							</div>

							<div class="form-group{{ $errors->has('cumple_velocidad') ? ' has-error' : '' }} col-md-6">
								<label>Cumple Velocidad Contratada?</label>
								<select class="form-control" name="cumple_velocidad" required>
									<option value="">Elija una opcion</option>
									@if($instalacion->cumple_velocidad_contratada == 'SI')
										<option value="SI" selected>SI</option>
									@else
										<option value="SI">SI</option>
									@endif
									<option value="NO">NO</option>
								</select>
							</div>

							<div class="form-group{{ $errors->has('serial_ont') ? ' has-error' : '' }} col-md-6">
								<label>Serial ONT</label>
								<input type="text" class="form-control" name="serial_ont" value="{{$instalacion->serial_ont}}"required>
							</div>

							<div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-6">
								<label>*Estado</label>
								<select class="form-control" name="estado" required>
									<option value="">Elija una opcion</option>
									@foreach($estados as $estado)
										@if($estado == $instalacion->estado)
											<option value="{{$estado}}" selected>{{$estado}}</option>
										@else
											<option value="{{$estado}}">{{$estado}}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>	

						@permission('instalaciones-inventarios-editar')
						<div class="row">							
							<div class="col-md-12">
								<table class="table no-margin">
					                <thead class="bg-blue">
					                	<tr>
					                		<td colspan="2">
					                			<h4>Elementos de la Instalacion</h4>
					                		</td>
					                	</tr>
					                	
					                </thead>
					                <tbody>
					                	<tr>
					                		<th>Material</th>			                		
					                		<th>Cantidad</th>			                		
					                	</tr>
					                	<tr>
					                		<td>Conector SC/APC</td>
					                		<td>
					                			<input type="number" name="conector" class="form-control" value="{{$instalacion->conector}}" min="0"  required>			                			
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Conector PigTail SC/APC</td>
					                		<td>
					                			<input type="number" name="pigtail" class="form-control" value="{{$instalacion->pigtail}}" min="0"  required>
					                		</td>
					                	</tr>			                	
					                	<tr>
					                		<td>Retencion {{$instalacion->tipo_retenciones}}</td>
					                		<td>
					                			<input type="number" name="cant_retenciones" class="form-control" value="{{$instalacion->cant_retenciones}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Cinta Bandit</td>
					                		<td>
					                			<div class="input-group">
													<input type="number" name="cinta_bandit" class="form-control" value="{{$instalacion->cinta_bandit}}" min="0"  required>
													<span class="input-group-addon">CM</span>
												</div>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Hebilla</td>
					                		<td>
					                			<input type="number" name="hebilla" class="form-control" value="{{$instalacion->hebilla}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Gancho Poste</td>
					                		<td>
					                			<input type="number" name="gancho_poste" class="form-control" value="{{$instalacion->gancho_poste}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Gancho Pared</td>
					                		<td>
					                			<input type="number" name="gancho_pared" class="form-control" value="{{$instalacion->gancho_pared}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Correa Amarre {{$instalacion->tipo_correa_amarre}}</td>
					                		<td>
					                			<input type="number" name="cant_correa_amarre" class="form-control" value="{{$instalacion->cant_correa_amarre}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Chazo {{$instalacion->tipo_chazo}}</td>
					                		<td>
					                			<input type="number" name="cant_chazo" class="form-control" value="{{$instalacion->cant_chazo}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Tornillo 1/4</td>
					                		<td>
					                			<input type="number" name="tornillo" class="form-control" value="{{$instalacion->tornillo}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Rosetas</td>
					                		<td>
					                			<input type="number" name="roseta" class="form-control" value="{{$instalacion->roseta}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Patch Cord FIBRA</td>
					                		<td>
					                			<input type="number" name="patch_cord_fibra" class="form-control" value="{{$instalacion->patch_cord_fibra}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<td>Patch Cord UTP</td>
					                		<td>
					                			<input type="number" name="patch_cord_utp" class="form-control" value="{{$instalacion->patch_cord_utp}}" min="0"  required>
					                		</td>
					                	</tr>
					                	<tr>
					                		<th colspan="2" class="text-center">Fibra Optica Drop de 1 hilo</th>
					                	</tr>
					                	<tr>
					                		<td>Desde:</td>
					                		<td>
					                			<div class="input-group">
													<input type="number" name="fibra_drop_desde" class="form-control" value="{{$instalacion->fibra_drop_desde}}" min="0" step="0.01" required>
													<span class="input-group-addon">Mts</span>
												</div>
					                		</td>	
					                	</tr>
					                	<tr>
					                		<td>Hasta:</td>
					                		<td>
					                			<div class="input-group">
													<input type="number" name="fibra_drop_hasta" class="form-control" value="{{$instalacion->fibra_drop_hasta}}" min="0" step="0.01" required>
													<span class="input-group-addon">Mts</span>
												</div>
					                		</td>	
					                	</tr>
					                	<tr>
					                		<td>Total Metros:</td>
					                		<td id="total_metros_fibra" class="text-right">{{$instalacion->fibra_drop_desde - $instalacion->fibra_drop_hasta}} Mts</td>
					                	</tr>
					                </tbody>
								</table>
								
							</div>
						</div>
						@endpermission		

						<button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Actualizar</button>
					</form>
				</div>				
			</div>

			@if($instalacion->estado == 'RECHAZADO')
	        	<div class="box box-danger">
	            <div class="box-header with-border bg-red">
	              <h3 class="box-title"><i class="fa fa-map-pin"></i> Autoria</h3>		              
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
	            	<h3>Motivo Rechazo</h3>
	            	<p>{{$instalacion->motivo_rechazo}}</p>
	            	<p><b>Descripcion:</b> {{$instalacion->descripcion_rechazo}}</p>
	            </div>
	            <!-- /.box-body -->		            
	        </div>
	        @endif
		</div>

		<div class="col-md-7">
			<div class="box box-info">				
				<div class="box-header with-border bg-blue">
					<h3 class="box-title">Editar Evidencias</h3>


					<div class="box-tools pull-right">
					@permission('instalacion-archivo-crear')
			            <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addArchivo">
			                <i class="fa fa-plus"></i>  Agregar          
			            </div>
			        @endpermission
			    	</div>
				</div>
				<div class="box-body table-responsive">
					<table class="table table-hover">
				        <tbody>
				          <tr>
				            <th style="width: 10px">#</th>
				            <th>Nombre</th>
				            <th>Tipo</th>
				            <th>Tamaño</th>
				            <th>Estado</th>
							<th>Acciones</th>
				          </tr>
				          <?php $i=0; $ids = 0;?>
				           
					        @foreach($instalacion->archivo as $archivo)                    
					          <tr>
					            <td>{{$i+=1}}</td>
					            <td> 
					              <label id="archivo-{{$archivo->id}}" data-toggle="modal" data-target="#modal-attachment" data-tipo="{{$archivo->tipo_archivo}}" data-archivo="{{Storage::url($archivo->archivo)}}" style="cursor: pointer;">{{$archivo->nombre}}</label>
					            </td>
					            <td>{{$archivo->tipo_archivo}}</td>
					            <td>
					              {{number_format((float)((Storage::size('public/' .$archivo->archivo)) / 1e+6), 2, '.', '')}} MB
					            </td>
					            <td>

								@if($archivo->estado == "EN REVISION")
									<p>{{$archivo->estado}}</p>
								@else
					            	<p class="{{($archivo->estado == 'RECHAZADO')? 'text-danger' : ''}}">{{$archivo->estado}}</p>
								@endif
					            </td>
					            
					            <td>
					            	@permission('instalacion-archivo-eliminar')
									<form action="{{route('instalaciones.archivos.destroy', [$instalacion->id, $archivo->id])}}" method="post">
										<input type="hidden" name="_method" value="delete">
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
									</form>
					              	@endpermission
					              	
									@if($archivo->estado == 'RECHAZADO')
										<form id="form-subir" action="{{route('instalaciones.archivos.update', [$instalacion->id,$archivo->id])}}" role="search" method="POST" class="navbar-form navbar-left" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="_method" value="PUT">
										<input type="hidden" name="estado" value="EN REVISION">

										<div class="input-group {{ $errors->has('archivo') ? ' has-error' : '' }} input-group-sm">
											<input type="file" class="form-control input-sm" name="archivo" required>
												<span class="input-group-btn">
													<button id="btn-subir" type="submit" class="btn btn-info btn-flat"><i class="fa fa-upload"></i> Subir</button>
												</span>
											</div>
										</form>
									@else
									<p></p>
									@endif
					            </td>
					            

					          </tr>
					        @endforeach

					        <div class="modal fade" id="modal-attachment" tabindex="-1" role="dialog">
					            <div class="modal-dialog modal-lg" role="document">
					              <div class="modal-content">
					                <div class="modal-body">
					                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					                    <span aria-hidden="true">&times;</span>
					                  </button>
					                  <div id="presentacion">
					                    
					                  </div> 
					                </div>
					              </div>
					            </div>
					        </div>
				        </tbody>
				      </table>
				</div>				
			</div>
		</div>
	</div>
</div>

@permission('instalacion-archivo-crear')
    @include('adminlte::instalaciones.partials.archivo')
@endpermission
	@section('mis_scripts')
	<script type="text/javascript">
	  $('#modal-attachment').on('show.bs.modal', function (event) {
	      var a = $(event.relatedTarget) // Button that triggered the modal
	      var tipo = a.data('tipo');
	      var recipient = a.data('archivo') // Extract info from data-* attributes
	      
	      var modal = $(this)
	      if (tipo == 'pdf') {
	        modal.find('#presentacion').html('<iframe src="'+ recipient +'" width="100%" height="600" style="height: 85vh;"></iframe>');        
	      }else{        
	        modal.find('#presentacion').html('<img src="'+ recipient +'" id="img-attachment" class="img-responsive" width="100%">');
	      }
	  });

	  	$('input[name=fibra_drop_desde]').keyup(function () {        
          var desde = parseFloat($(this).val());
          var hasta = parseFloat($('input[name=fibra_drop_hasta]').val());
          $('#total_metros_fibra').text((desde - hasta) + " Mts");
    	});

    	$('input[name=fibra_drop_hasta]').keyup(function () {        
          var hasta = parseFloat($(this).val());
          var desde = parseFloat($('input[name=fibra_drop_desde]').val());
          $('#total_metros_fibra').text((desde - hasta) + " Mts");
    	});

	  $('#form-subir').submit(function(event){
	  	$('#btn-subir').attr('disabled', true);
	  });
	</script>

	@endsection
@endsection