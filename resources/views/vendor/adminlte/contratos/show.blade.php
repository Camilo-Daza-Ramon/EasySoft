@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-graduation-cap"></i>  Contrato - {{$contrato->referencia}}</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
        	<div class="col-md-4">
        		<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title">Detalles</h3>

		               @permission('contratos-editar')
                        <div class="box-tools pull-right">
                            <a href="{{route('clientes.contratos.edit', [$contrato->ClienteId, $contrato->id])}}" class="btn btn-default float-bottom btn-sm">
				                <i class="fa fa-edit"></i>  Editar          
				            </a>
                        </div>
                        @endpermission
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
		        		<table class="table">
		        			<tbody>
		        				<tr>
		        					<th>Cliente</th>
		        					<td>
		        						<a href="{{route('clientes.show',$contrato->ClienteId)}}" target="_black">{{$contrato->cliente->Identificacion}}</a>
		        					</td>
		        				</tr>
		        				<tr>
		        					<th>Contrato #:</th>
		        					<td>{{$contrato->referencia}}</td>
		        				</tr>
		        				<tr>
		        					<th>Vigencia:</th>
		        					<td>{{$contrato->vigencia_meses}} MESES</td>
		        				</tr>
		        				<tr>
		        					<th>Fecha de Contrato:</th>
		        					<td>{{$contrato->fecha_inicio}}</td>
		        				</tr>
		        				<tr>
		        					<th>Fecha de Instalacion:</th>
		        					<td>{{$contrato->fecha_instalacion}}</td>
		        				</tr>
								<tr>
		        					<th>Fecha de Operación:</th>
		        					<td>{{$contrato->fecha_operacion}}</td>
		        				</tr>
		        				<tr>
		        					<th class="text-danger">Fecha de Finalización:</th>
		        					<td>{{$contrato->fecha_final}}</td>
		        				</tr>
								<tr>
		        					<th class="text-yellow">TIEMPO POR DISFRUTAR:</th>
		        					<td>{{$por_difrutar}}</td>
		        				</tr>
		        				<tr>
		        					<th>Tipo de Cobro:</th>
		        					<td>{{$contrato->tipo_cobro}}</td>
		        				</tr>
		        				<tr>
		        					<th>Valor:</th>
		        					<td>${{number_format($contrato->servicio->sum('valor'),0,',','.')}}</td>
		        				</tr>
		        				<tr>
		        					<th>Clausula de Permanencia:</th>
		        					<td>		        						
		        						@if($contrato->clausula_permanencia)
											SI
										@else
											NO
										@endif
		        					</td>
		        				</tr>
		        				<tr>
		        					<th>Vendedor:</th>
		        					<td>{{$contrato->vendedor->name}}</td>
		        				</tr>
		        				<tr>
		        					<th>Estado:</th>
		        					<td>@if($contrato->estado == 'VIGENTE')
						                <span class="label label-success">{{$contrato->estado}}</span>
						              @else
						                <span class="label label-default">{{$contrato->estado}}</span>
						              @endif</td>
		        				</tr>

		        				<tr>
		        					<th>Observacion:</th>
		        					<td>{{$contrato->observacion}}</td>
		        				</tr>

		        			</tbody>
		        		</table>
		        	</div>
		        </div>

		        <!-- DIRECT CHAT -->
	            <div class="box box-warning direct-chat direct-chat-warning">
	                <div class="box-header with-border">
	                  <h3 class="box-title">Log de Cambios</h3>

	                  <div class="box-tools pull-right">                    
	                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
	                    </button>
	                  </div>
	                </div>
	                <!-- /.box-header -->
	                <div class="box-body">

	                	<!-- Conversations are loaded here -->
		                <div class="direct-chat-messages"> 
	                	@foreach($contrato->evento as $eventos)
	                		<div class="direct-chat-msg right">
		                      <div class="direct-chat-info clearfix">
		                        <span class="direct-chat-name pull-right">{{(isset($eventos->user)? $eventos->user->name : 'EasySotf')}}</span>
		                        <span class="direct-chat-timestamp pull-left">{{$eventos->created_at}}</span>
		                      </div>
		                      <!-- /.direct-chat-info -->
		                      @if(isset($eventos->user))
		                      	<img class="direct-chat-img" src="{{(!empty($eventos->user->avatar)? Storage::url($eventos->user->avatar) : Gravatar::get(Auth::user()->email))}}" alt="message user image">
		                      @else
		                      	<img class="direct-chat-img" src="{{Gravatar::get(Auth::user()->email)}}" alt="message user image">
		                      @endif	                      
		                      
		                      <!-- /.direct-chat-img -->
		                      <div class="direct-chat-text">
		                        {{$eventos->descripcion}}
		                      </div>
		                      <!-- /.direct-chat-text -->
		                    </div>		                  
		                @endforeach
		                </div>
		                <!--/.direct-chat-messages-->             
	                </div>
	                <!-- /.box-body -->
	            </div>
	            <!--/.direct-chat -->
	        </div>
        	<div class="col-md-8">
        		<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title">Servicios</h3>

		              @role(['admin', 'agente-noc', 'indicadores'])
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addServicio">
				                <i class="fa fa-plus"></i>  Agregar          
				            </button>
                        </div>
                       @endrole
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">

		            	<table class="table table-bordered">
		            		<thead>
		            			<tr>
									<th style="width: 10px">#</th>
									<th>Nombre</th>
									<th>Cantidad</th>
									<th>Valor</th>
									<th>IVA</th>
									<th>Estado</th>
									<th>Accion</th>
								</tr>
								@foreach($contrato->servicio as $servicio)
									<tr>
										<td>{{$servicio->id}}</td>
										<td>
											<p>{{$servicio->nombre}} <br> {{$servicio->descripcion}}</p>
										</td>
										<td>{{$servicio->cantidad}} {{$servicio->unidad_medida}}</td>
										<td>${{number_format($servicio->valor, 0, ',', '.')}}</td>
										<td>
											@if($servicio->iva)
												SI
											@else
												NO
											@endif
										</td>
										<td>
											@if (Auth::user()->can('contratos-servicios-actualizar'))
												@if(isset($ont))
													<form action="{{route('contrato.servicio.update',$servicio->id)}}" method="post">
														<input type="hidden" name="_method" value="PUT">
														<input type="hidden" name="_token" value="{{csrf_token()}}">
														<input type="hidden" name="fsp" value="{{$fsp}}">
					                					<input type="hidden" name="ont_id" value="{{$ontid}}">

														<div class="input-group input-group-sm hidden-xs" style="width: 150px;">
										                  <select class="form-control pull-right" name="estado" required>
										                  	<option value="">Pendiente</option>
										                    @foreach($estados_servicio as $dato)
										                    	@if($dato == $servicio->estado)
										                    		<option value="{{$dato}}" selected>{{$dato}}</option>
										                    	@else
										                    		<option value="{{$dato}}">{{$dato}}</option>
										                    	@endif								                    
										                    @endforeach
										                  </select>
										                  <div class="input-group-btn">
										                    <button type="submit" class="btn btn-primary" onclick="return confirm('Cambiar el estado de la ONT');"><i class="fa fa-save"></i></button>
										                  </div>
										                </div>
													</form>
												@else
													{{$servicio->estado}}
												@endif
											@else
												{{$servicio->estado}}
											@endif
										</td>
										<td>
											@permission('contratos-servicios-eliminar')
											<form action="{{route('contrato.servicio.delete',$servicio->id)}}" method="post">
							                    <input type="hidden" name="_method" value="delete">
							                    <input type="hidden" name="_token" value="{{csrf_token()}}">
							                   
							                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar el servicio?');" title="Eliminar">
							                          <i class="fa fa-trash"></i>
							                    </button>
							                </form>
							                @endpermission
										</td>
									</tr>
								@endforeach
		            		</thead>
			                <tbody>
								
							</tbody>
						</table>


		              
		            </div>
		            <!-- /.box-body -->
		            <div class="box-footer text-center">
		              
		            </div>
		            <!-- /.box-footer -->
		          </div>
        	</div>
        	<div class="col-md-8">
				<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title">Archivos</h3>

		              <div class="box-tools pull-right">
		              	@permission('contrato-enviar')
			              	@if($contrato->archivos->count() > 0)
				                <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#sendMail">
				                	<i class="fa fa-envelope-o"></i> Enviar
				                </button>
			                @endif
			            @endpermission
		                @permission('contrato-archivo-crear')
		                <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#addArchivo">
			                <i class="fa fa-plus"></i>  Agregar          
			            </div>
			            @endpermission
		              </div>
		              <!-- /.box-tools -->
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>Nombre</th>
									<th>Tipo</th>
									<th>Estado</th>
									<th>Accion</th>
								</tr>
								
							</thead>
							<tbody>
								@foreach($contrato->archivos as $archivo)

								<tr>
									<td>{{$archivo->id}}</td>
									<td>
										<a href="{{Storage::url($archivo->archivo)}}" target="_blank">{{$archivo->nombre}}</a>
									</td>
									<td>{{$archivo->tipo_archivo}}</td>
									<td>{{$archivo->estado}}</td>
									<td>
										@permission('contrato-archivo-eliminar')
										<form action="{{route('contratos-archivos.destroy', $archivo->id)}}" method="post">
											<input type="hidden" name="_method" value="delete">
											<input type="hidden" name="_token" value="{{csrf_token()}}">
											<button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
										</form>
										@endpermission
									</td>
								</tr>
								@endforeach
							</tbody>
							

							
						</table>
					</div>
		            <!-- /.box-body -->
		        </div>
			</div>
        	<div class="col-md-8">
			  <div class="panel panel-default"> 
			    <div class="panel-heading">
			      <h2>
			        <i class="fa fa-hdd-o"></i>  Datos ONT        

			        <div class="panel-tools pull-right">
			          @permission('clientes-aprovisionar-eliminar')
			            @if(isset($contrato->cliente->cliente_ont_olt) || !empty($ont['error']))
			              <form action="{{route('clientes.aprovisionar.destroy', $contrato->cliente->cliente_ont_olt->id)}}" method="post">
			                <input type="hidden" name="_method" value="delete">
			                <input type="hidden" name="_token" value="{{csrf_token()}}">

			                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"> <i class="fa fa-trash"></i> Eliminar</button>
			              </form>
			            @else
			              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addModal"> <i class="fa fa-gear"></i> Configurar</button>
			            @endif
			          @endpermission
			        </div>
			      </h2>
			      
			    </div>
			    <div class="panel-body table-responsive">
			      <table class="table table-striped"> 
			        @if(isset($ont))
			          @foreach($ont as $info => $value)			            
			              <tr>
			                <th>{{$info}}</th>
			                <td colspan="5">{{$value}}</td>
			              </tr>
			          @endforeach

			          <tr>			            
			            <th>Modificado por:</th>
			            <td>{{$contrato->cliente->cliente_ont_olt->user->name}}</td>
			            <th>Fecha modificacion:</th>
			            <td>{{$contrato->cliente->cliente_ont_olt->updated_at}}</td>
			          </tr>
					@elseif(isset($reporteOntFallido))
						<tr>
							<th>ERROR</th>
							<td>La presente ONT reporta algún tipo de fallo.</td>
						</tr>
						<tr>
							<th>Ont Serial</th>
							<td>{{ $reporteOntFallido->ONT_Serial ? $reporteOntFallido->ONT_Serial : 'N.A' }}</td>
						</tr>
						<tr>
							<th>Mensaje de Error</th>
							<td>{{ $reporteOntFallido->mensaje ? $reporteOntFallido->mensaje : $reporteOntFallido}}</td>
						</tr>
			        @else
			          <h3 class="text-danger">No hay Información</h3>
			        @endif
			      </table>
			    </div>
			    @if(!isset($contrato->cliente->cliente_ont_olt))
			    	@if(isset($contrato->servicio[0]))
			    		@include('adminlte::contratos.partials.add-ont')
			    	@endif
			    @endif
			  </div>
			</div>
		</div>

        @if(!empty($contrato->archivo))
        <div class="modal fade" id="contrato" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <div>
                    <iframe src="{{Storage::url($contrato->archivo)}}" width="100%" height="600" style="height: 85vh;"></iframe>
                  </div> 
                </div>
              </div>
            </div>
        </div>
        @endif

    </div>

    @permission('contrato-archivo-crear')
    	@include('adminlte::contratos.partials.add-archivo')
    @endpermission

    @role(['admin', 'agente-noc', 'indicadores'])
		@include('adminlte::contratos.partials.add-servicio')
		
	<!-- /.modal -->
	@endrole

	@include('adminlte::contratos.partials.send-contrato')



	
	

    @section('mis_scripts')
		<script type="text/javascript">
		    

		    $('#ont-buscar').on('click', function(){
		      var parametros = {
		        serial : $('#serial').val(),
		        '_token' : $('input:hidden[name=_token]').val()             
		      };

		      $.post("/inventarios/ajax",parametros, function(data){
		          $('#ont-resultado').empty();

		            if (data.resultado == true) {
		            	$('#form-buscar-ont').hide(1000);

		            	$('#ont_id').val(data.inventario['ActivoFijoId']);
		              	$('#ont-resultado').append('<tr><td>'+ data.inventario['ActivoFijoId'] +'</td><td>'+ data.inventario['Descripcion'] +'</td><td>'+ data.inventario['Modelo'] +'</td><td>'+ data.inventario['Serial'] +'</td><td>'+ data.inventario['Estado'] +'</td></tr>');

		              	$('#table-ont').show(4000);

		            	$('#form-ont').show(4000);
		            }else{
		            	toastr.options.positionClass = 'toast-bottom-right';
            			toastr.warning(data.resultado);
		            }
		      });
		    });

		    function validar(){
				var contrato = <?php echo("'" . $contrato->fecha_inicio . "'"); ?>;
				var instalacion = $('#fecha_instalacion').val();

				if (instalacion < contrato) {
					alert('la fecha de instalación no puede ser menor que la del contrato!');
					return false;
				}else{
					return true;
				}
			}

			$('input[type=radio][name=accion]').change(function(){

				if (this.value == 'subir') {
					$('#archivo-area').show(1000);
				}else{
					$('#archivo-area').hide(1000);
				}

			});
		</script>
	@endsection
@endsection