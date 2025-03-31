@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-hdd-o"></i>  Instalacion #{{$instalacion->id}} - <span style="font-size: 2rem;"> {{$instalacion->cliente->NombreBeneficiario}} {{$instalacion->cliente->Apellidos}}</span> </h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
        	<div class="col-md-6">
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
		        						<a href="{{route('clientes.show', $instalacion->ClienteId)}}" target="_black">{{$instalacion->cliente->TipoDeDocumento}} {{$instalacion->cliente->Identificacion}}</a>
		        					</td>
		        				</tr>
		        				<tr>
		        					<th>Nombre</th>
		        					<td>{{$instalacion->cliente->NombreBeneficiario}} {{$instalacion->cliente->Apellidos}}</td>
		        				</tr>
		        				<tr>
		        					<th>Direccion</th>
		        					<td>
		        						{{$instalacion->cliente->DireccionDeCorrespondencia}} - {{$instalacion->cliente->municipio->NombreMunicipio}} - {{$instalacion->cliente->municipio->departamento->NombreDelDepartamento}}
		        					</td>
		        				</tr>
		        				<tr>
		        					<th>Proyecto</th>
		        					<td>{{$instalacion->cliente->proyecto->NumeroDeProyecto}}</td>
		        				</tr>		        				
		        				<tr>
		        					<th>Estado Cliente</th>
		        					<td>
		        						@if($instalacion->cliente->Status == 'ACTIVO')
                                            {{$instalacion->cliente->EstadoDelServicio}}
                                        @elseif($instalacion->cliente->Status == 'APROBADO')
                                          <span class="label label-success">{{$instalacion->cliente->Status}}</span>
                                        @elseif($instalacion->cliente->Status == 'RECHAZADO')
                                          <span class="label label-danger">{{$instalacion->cliente->Status}}</span>
                                        @elseif($instalacion->cliente->Status == 'PENDIENTE')
                                          <span class="label label-warning">{{$instalacion->cliente->Status}}</span>
                                        @else
                                            {{$instalacion->cliente->Status}}
                                        @endif
		        					</td>
		        				</tr>
		        				<tr>
		        					<th>Fecha Instalación</th>
		        					<td>{{$instalacion->fecha}}</td>
		        				</tr>
		        			</tbody>
		        		</table>
		        	</div>
		        </div>

		        <div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title"><i class="fa fa-wrench"></i> Elementos de la Instalacion</h3>		              
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">

		            	<table class="table no-margin">
			                <thead>
			                	<tr>
			                		<th>Material</th>			                		
			                		<th>Cantidad</th>			                		
			                	</tr>
			                </thead>
			                <tbody>
			                	<tr>
			                		<td>Conector SC/APC</td>
			                		<td>{{$instalacion->conector}}</td>
			                	</tr>
			                	<tr>
			                		<td>Conector PigTail SC/APC</td>
			                		<td>{{$instalacion->pigtail}}</td>
			                	</tr>			                	
			                	<tr>
			                		<td>Retencion {{$instalacion->tipo_retenciones}}</td>
			                		<td>{{$instalacion->cant_retenciones}}</td>
			                	</tr>
			                	<tr>
			                		<td>Cinta Bandit</td>
			                		<td>{{$instalacion->cinta_bandit}} cm</td>
			                	</tr>
			                	<tr>
			                		<td>Hebilla</td>
			                		<td>{{$instalacion->hebilla}}</td>
			                	</tr>
			                	<tr>
			                		<td>Gancho Poste</td>
			                		<td>{{$instalacion->gancho_poste}}</td>
			                	</tr>
			                	<tr>
			                		<td>Gancho Pared</td>
			                		<td>{{$instalacion->gancho_pared}}</td>
			                	</tr>
			                	<tr>
			                		<td>Correa Amarre {{$instalacion->tipo_correa_amarre}}</td>
			                		<td>{{$instalacion->cant_correa_amarre}}</td>
			                	</tr>
			                	<tr>
			                		<td>Chazo {{$instalacion->tipo_chazo}}</td>
			                		<td>{{$instalacion->cant_chazo}}</td>
			                	</tr>
			                	<tr>
			                		<td>Tornillo 1/4</td>
			                		<td>{{$instalacion->tornillo}}</td>
			                	</tr>
			                	<tr>
			                		<td>Rosetas</td>
			                		<td>{{$instalacion->roseta}}</td>
			                	</tr>
			                	<tr>
			                		<td>Patch Cord FIBRA</td>
			                		<td>{{$instalacion->patch_cord_fibra}}</td>
			                	</tr>
			                	<tr>
			                		<td>Patch Cord UTP</td>
			                		<td>{{$instalacion->patch_cord_utp}}</td>
			                	</tr>
			                	<tr>
			                		<td>Fibra Optica Drop de 1 hilo <b>Desde: </b> {{$instalacion->fibra_drop_desde}} <b>Hasta: </b> {{$instalacion->fibra_drop_hasta}}</td>
			                		<td>{{$instalacion->fibra_drop_desde - $instalacion->fibra_drop_hasta}} Mts</td>
			                	</tr>
			                </tbody>
						</table>		              
		            </div>
		            <!-- /.box-body -->		            
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
        	<div class="col-md-6">
        		<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title"><i class="fa fa-hdd-o"></i> Datos de la Instalacion</h3>		              
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">

		            	<table class="table table-bordered">
		            		<tbody>
		            			<tr>
		                			<th class="bg-gray">Serial ONT</th>
		                			<td colspan="3">{{$instalacion->serial_ont}}</td>
		                		</tr>
		                		<tr>
		                			<th class="bg-gray">Caja</th>
		                			<td>{{$instalacion->caja}}</td>
		                			<th class="bg-gray">Puerto</th>
		                			<td>{{$instalacion->puerto}}</td>
		                		</tr>
		                		<tr>
		                			<th class="bg-gray">SP Spliter</th>
		                			<td>{{$instalacion->sp_splitter}}</td>
		                			<th class="bg-gray">SS Spliter</th>
		                			<td>{{$instalacion->ss_splitter}}</td>
		                		</tr> 
		                		<tr>
		                			<th class="bg-gray">Tarjeta</th>
		                			<td>{{$instalacion->tarjeta}}</td>
		                			<th class="bg-gray">Modulo</th>
		                			<td>{{$instalacion->modulo}}</td>
		                		</tr>
		                		<tr>
		                			<th class="bg-gray">OLT</th>
		                			<td colspan="3">{{$instalacion->olt}}</td>
		                		</tr>
			                </tbody>
						</table>
						<br>

						<table class="table table-bordered">
			                <tbody>
			                	<tr>
			                		<th class="bg-gray">Latitud</th>
			                		<td>{{$instalacion->latitud}}</td>
			                	</tr>
			                	<tr>
			                		<th class="bg-gray">Longitud</th>
			                		<td>{{$instalacion->longitud}}</td>
			                	</tr>
			                </tbody>
						</table>
						<br>

		            	<table class="table table-bordered">
			                <tbody>
			                	<tr>
			                		<th class="bg-gray">Número de Equipos Conectado</th>
			                		<td>{{$instalacion->cantidad_equipos_conectados}}</td>
			                	</tr>
			                	<tr>
			                		<th class="bg-gray">Tipo de Conexion Eléctrica</th>
			                		<td>{{$instalacion->tipo_conexion_electrica}}</td>
			                	</tr>
			                </tbody>
						</table>
						<br>

						<table class="table table-bordered">
			                <tbody>			                	
		                		<tr class="bg-gray">
		                			<th>Tipo de Equipo utilizado por el cliente para la conexion</th>
		                			<th>Marca</th>
		                			<th>Serial</th>
		                			<th>Estado</th>
		                		</tr>
		                		<tr>
		                			<td>{{$instalacion->tipo_conexion}}</td>
		                			<td>{{$instalacion->marca_equipo}}</td>
		                			<td>{{$instalacion->serial_equipo}}</td>
		                			<td>{{$instalacion->estado_equipo}}</td>
		                		</tr>
		                		<tr class="bg-gray">
		                			<th>Tipo de Protección Eléctrica</th>
		                			<th>Marca</th>
		                			<th>Serial</th>
		                			<th>Estado</th>
		                		</tr>
		                		<tr>
		                			<td>{{$instalacion->tipo_proteccion_electrica}}</td>
		                			<td>{{$instalacion->marca_proteccion_electrica}}</td>
		                			<td>{{$instalacion->serial_proteccion_electrica}}</td>
		                			<td>{{$instalacion->estado_conexion_electrica}}</td>
		                		</tr>
		                		<tr>
		                			<th class="bg-gray">Velocidad Bajada</th>
		                			<td>{{number_format($instalacion->velocidad_bajada,2, '.','')}}</td>
		                			<th class="bg-gray">Velocidad Subida</th>
		                			<td>{{number_format($instalacion->velocidad_subida,2, '.','')}}</td>
		                		</tr>
		                		<tr>
		                			<th  class="bg-gray">Servicio queda Activo?</th>
		                			<td colspan="3">{{$instalacion->servicio_activo}}</td>
		                			
		                		</tr>
		                		<tr>		                			
		                			<th class="bg-gray">Cumple con velocidad Contratada?</th>
		                			<td colspan="3">{{$instalacion->cumple_velocidad_contratada}}</td>
		                		</tr>

		                		<tr>
		                			<th class="bg-gray">Técnico</th>
		                			<td colspan="3">{{$instalacion->tecnico->name}}</td>
		                		</tr>

		                		<tr>
		                			<td colspan="4">
		                				<b>Observaciones</b>
		                				<p>{{$instalacion->observaciones}}</p>
		                			</td>
		                		</tr>
		                		<tr>
		                			<td>
		                				<label>Auditor:</label>
		                				@if(isset($instalacion->auditor))
		                					<p>{{$instalacion->auditor->name}}</p>
		                				@endif
		                			</td>
		                			<td colspan="3">
		                				<label>Estado:</label>
		                				<p>{{$instalacion->estado}}</p>
		                			</td>
		                		</tr>
		                		
			                </tbody>
						</table>		              
		            </div>
		            <!-- /.box-body -->		            
		        </div>

		        <div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title"> <i class="fa fa-image"></i> Evidencias</h3>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
		            	<table class="table table-hover">
					        <tbody>
					          <tr>
					            <th style="width: 10px">#</th>
					            <th>Nombre</th>
					            <th>Tipo</th>
					            <th>Tamaño</th>
					            <th>Estado</th>

			                    @if(Auth::user()->hasRole(['auditor']))
			                    	<th>Acciones</th>
			                    @endif		            
					          </tr>
					          <?php $i=0; $ids = 0;?>
					           @if(count($instalacion->archivo) > 0)
					            @foreach($instalacion->archivo as $archivo)                    
					              <tr>
					                <td>{{$i+=1}}</td>
					                <td> 
					                  <label id="archivo-{{$archivo->id}}" data-toggle="modal" data-target="#modal-attachment" data-tipo="{{$archivo->tipo_archivo}}" data-archivo="{{Storage::url($archivo->archivo)}}" style="cursor: pointer;">{{$archivo->nombre}}</label>
					                </td>
					                <td>{{$archivo->tipo_archivo}}</td>
					                <td>

										@if(Storage::disk('public')->exists($archivo->archivo))
					                  		{{number_format((float)((Storage::size('public/' .$archivo->archivo)) / 1e+6), 2, '.', '')}} MB
										@endif
					                </td>
					                <td>
					                  @if($archivo->estado == 'EN REVISION')                    
									  	@permission('auditorias-crear')
					                      <?php $ids += 1; ?>
					                      <div class="btn-group">
					                        <button type="button" id="estado-{{$archivo->id}}" class="btn btn-warning btn-xs">{{$archivo->estado}}</button>
					                        <button type="button" id="toggle-{{$archivo->id}}" class="btn btn-warning dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
					                          <span class="caret"></span>
					                          <span class="sr-only">Toggle Dropdown</span>
					                        </button>
					                        <ul class="dropdown-menu" role="menu">
					                          <li><a onclick="actualizar_archivo('APROBADO', {{$archivo->id}}, {{$archivo->instalacion_id}});">Aprobar</a></li>
					                          <li><a onclick="actualizar_archivo('RECHAZADO', {{$archivo->id}}, {{$archivo->instalacion_id}});">Rechazar</a></li>
					                        </ul>
					                      </div>
					                    @else
					                      <span class="label label-warning">{{$archivo->estado}}</span>
					                    @endpermission
					                  @else
					                    
					                    @if($archivo->estado == 'RECHAZADO')
					                      <span class="label label-danger">{{$archivo->estado}}</span>
					                    @else
					                      {{$archivo->estado}}
					                    @endif

					                  @endif                          
					                </td>

					                
										@permission('auditorias-crear')
					                    	<td>
					                    		<form action="{{route('instalaciones.archivos.destroy', [$instalacion->id, $archivo->id])}}" method="post">
									                <input type="hidden" name="_method" value="delete">
									                <input type="hidden" name="_token" value="{{csrf_token()}}">
									                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
									            </form>
					                    	</td>
										@endpermission

					                
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
					          @endif
					        </tbody>
					      </table>
		            </div>
		        </div>
        	</div>     

    </div>


    @if($instalacion->estado == 'PENDIENTE')
		@permission('auditorias-crear')
        <div class="col-md-3" style="position: fixed; bottom: 0px; right: 10px; z-index: 999999;">
          <div class="box box-warning" style="box-shadow: rgba(0, 0, 0, 0.5) 1px 2px 20px;">
            <div class="box-header with-border" style="background-color: #f39c12; color: #fff; ">
              <h3 class="box-title">Auditar</h3>

              <div class="box-tools pull-right">                    
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->

            <form action="{{route('instalaciones.auditar', $instalacion->id)}}" method="post">
              <input type="hidden" name="_method" value="PUT">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <select class="form-control" id="estado" name="estado" required>
                        <option value="">Estado</option>
                        <option value="APROBADO">APROBADO</option>
                        <option value="RECHAZADO">RECHAZADO</option>
                      </select>
                    </div>
                      <div class="form-group">                      
                        <select class="form-control" name="tecnico" id="tecnico" required>
                          <option value="">Elija un Tecnico</option>
                          <?php $add = 0; ?>
                          @foreach($tecnicos as $tecnico)
                            @if($instalacion->user_id == $tecnico->id)
                            <?php $add = 1; ?>
                              <option value="{{$tecnico->id}}" selected>{{$tecnico->name}}</option>
                            @else
                              <option value="{{$tecnico->id}}">{{$tecnico->name}}</option>
                            @endif
                          @endforeach

                          	@if($add == 0)
                          	<option value="{{$instalacion->user_id}}" selected>{{$instalacion->tecnico->name}}</option>
                          	@endif
                        </select>
                      </div>
                    <div class="form-group">                      
                      <select class="form-control" id="motivo_rechazo" name="motivo_rechazo" id="motivo_rechazo" required>
                        <option value="">Motivo Rechazo</option>
                        @foreach($motivos_rechazo as $valor)
                          @if($valor == $instalacion->motivo_rechazo)
                            <option value="{{$valor}}" selected>{{$valor}}</option>
                          @else
                            <option value="{{$valor}}">{{$valor}}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <textarea placeholder="Observaciones" class="form-control" rows="5" id="observaciones" name="observaciones" required>
                        {{$instalacion->descripcion_rechazo}}
                      </textarea>
                    </div>
                  </div>
                </div>   
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.box-body -->
              <div class="box-footer">                  
                  <button type="submit" id="auditar" class="btn btn-block btn-warning btn-flat" disabled>Auditar</button>
              </div>
            </form>
            <!-- /.box-footer-->
          </div>
        </div>
        @endpermission
      @endif

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

		  var ids = {{$ids}};

		  if (ids == 0) {
		    console.log(ids);
		    $('#auditar').removeAttr('disabled');
		  }

		  function actualizar_archivo(estado, id, instalacion){     

		    var parameters = {
		      estado : estado,
		      '_token' :  $('input:hidden[name=_token]').val(),
		      '_method' : 'PUT'
		    };      

		    $.post("/instalaciones/"+instalacion+"/archivos/" + id,parameters, function(data){
		      if(data.result){
		        $('#estado-'+ id).removeClass('btn-warning');
		        $('#toggle-'+id).removeClass('btn-warning');

		        if (estado == 'APROBADO') {
		          $('#estado-'+ id).addClass('btn-success');
		          $('#toggle-'+id).addClass('btn-success');
		        }else{
		          $('#estado-'+ id).addClass('btn-danger');
		          $('#toggle-'+id).addClass('btn-danger');
		        }

		        $('#estado-'+ id).text(estado);

		        ids = ids - 1;
		        if (ids == 0) {
		          console.log(ids);
		          $('#auditar').removeAttr('disabled');
		        }
		      }
		    });
		  }

		  $('#estado').on('change', function(){
		    if ($(this).val() == 'APROBADO') {
		      $('#motivo_rechazo').hide(2000);
		      $('#observaciones').hide(2000);
		    }else{
		      $('#motivo_rechazo').show(2000);
		      $('#observaciones').show(2000);
		    }
		  });


		  $('#estado').on('change', function(){
		    if ($(this).val() == 'APROBADO') {
		      	$('#motivo_rechazo').hide(2000);
		      	$('#motivo_rechazo').attr('required', false);
		      	$('#observaciones').hide(2000);
		      	$('#observaciones').attr('required', false);
		    }else{
		      	$('#motivo_rechazo').show(2000);
		      	$('#motivo_rechazo').attr('required', true);
		      	$('#observaciones').show(2000);
		      	$('#observaciones').attr('required', true);
		    }
		  });
		</script>

		
	@endsection
@endsection