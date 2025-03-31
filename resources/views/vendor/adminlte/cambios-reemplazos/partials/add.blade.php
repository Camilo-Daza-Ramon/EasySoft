<div class="modal fade" id="addReemplazo">
		<div class="modal-dialog  modal-lg">
		    <div class="modal-content">
		      <div class="modal-header bg-blue">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Reemplazo</h4>
		      </div>
		    	<form id="form-cambios-reemplazos-store" action="{{route('cambios-reemplazos.store')}}" method="post">
		    		<input type="hidden" name="meta_cliente_id" value="">
			        <input type="hidden" name="contrato_a_id" value="">
			        <input type="hidden" name="cliente_n_id" value="">
			        <input type="hidden" name="contrato_n_id" value="">
			        <input type="hidden" name="reemplazo_id" value="">
			        
			        {{csrf_field()}}
			        <div class="modal-body">
			        	<h3 id="title-resumen" class="text-center" style="display: none;">Resumen</h3>
			        	<div class="panel panel-default" id="cliente"> 
    						<div class="panel-heading">
    							<h2 class="panel-title"><i class="fa fa-user"></i> Cliente Antiguo</h2>
    						</div>
    						<div class="panel-body">
    							<div class="row">
						          	<div class="col-md-4">			          		
						          		<p>
						          			<label>Cedula:</label>
						          			<span id="cedula"></span>
						          		</p>
						          	</div>
						          	<div class="col-md-4">
						          		<p>
						          			<label>Nombre:</label>
						          			<span id="nombre"></span>
						          		</p>
						          	</div>
						          	<div class="col-md-4">
						          		<p>
						          			<label>Municipio:</label>
						          			<span id="municipio"></span>
						          		</p>
						          	</div>					          							          	
						          	<div class="col-md-4">
						          		<p>
						          			<label>Estado:</label>
						          			<span id="estado"></span>
						          		</p>
						          	</div>
						          	
						          	<div class="col-md-3" id="dato-contrato-antiguo" style="display: none;">
						          		<p>
						          			<label>Contrato #</label>
						          			<span id="contrato-antiguo"></span>
						          		</p>
						          	</div>				          	
						        </div>
    						</div>
    					</div>

    					<div class="panel panel-default" id="contrato" style="display: none;"> 
    						<div class="panel-heading">
    							<h2 class="panel-title"> <i class="fa fa-briefcase"></i> Contrato Cliente <span id="tipo"></span></h2>
    						</div>
    						<div class="panel-body table-responsive">
    							<table class="table table-hover">
					                <thead>
					                  <tr>
					                    <th style="width: 10px">#</th>					                    
					                    <th>N° Contrato</th>
										<th>Instalación</th>
										<th>Operación</th>
					                    <th>Finalización</th>
					                    <th>Tipo Cobro</th>
					                    <th>Vigencia</th>
					                    <th style="width: 40px">Valor</th>
					                    <th>Estado</th>					                    
					                  </tr>
					                </thead>
					                <tbody id="lista-contrato">
					                    
					                </tbody>
					            </table>
    						</div>
    					</div>

    					<div class="panel panel-default" id="clientes" style="display: none;"> 
    						<div class="panel-heading">
    							<h2 class="panel-title"> <i class="fa fa-user"></i> Cliente Nuevo</h2>
    						</div>
    						<div class="panel-body table-responsive">
    							<table class="table table-bordered table-hover dataTable" id="tabla-clientes">
					                <thead>
					                  <tr>
					                    <th style="width: 10px">#</th>					                    
					                    <th>Cedula</th>
					                    <th style="width: 180px">Nombre</th>
					                    <th>Municipio</th>
					                    <th>Estado</th>				                    
					                  </tr>
					                </thead>
					                
					            </table>
    						</div>
    					</div>

    					<div class="panel panel-default" id="resumen" style="display: none;"> 
    						<div class="panel-heading">
    							<h2 class="panel-title"><i class="fa fa-user"></i> Cliente Nuevo</h2>
    						</div>
    						<div class="panel-body">
    							<div class="row">
						          	<div class="col-md-4">			          		
						          		<p>
						          			<label>Cedula:</label>
						          			<span id="cedula-n"></span>
						          		</p>
						          	</div>
						          	<div class="col-md-4">
						          		<p>
						          			<label>Nombre:</label>
						          			<span id="nombre-n"></span>
						          		</p>
						          	</div>
						          	<div class="col-md-4">
						          		<p>
						          			<label>Municipio:</label>
						          			<span id="municipio-n"></span>
						          		</p>
						          	</div>	          	
						          	<div class="col-md-4">
						          		<p>
						          			<label>Estado:</label>
						          			<span id="estado-n"></span>
						          		</p>
						          	</div>						          	
						          	<div class="col-md-4">
						          		<p>
						          			<label>Contrato #</label>
						          			<span id="contrato-n"></span>
						          		</p>
						          	</div>
						          	<div class="col-md-4">
						          		<p>
						          			<label>Fecha del Reemplazo</label>
						          			<input type="date" name="fecha" class="form-control" required>
						          		</p>
						          	</div>
						          	<div class="col-md-12">
						          		<p>
						          			<label>Observacion</label>
						          			<textarea class="form-control" name="observacion" placeholder="Escriba alguna observacion que tenga"></textarea>
						          		</p>
						          	</div>
						        </div>
    						</div>
    					</div>
		          	</div>
			        <div class="modal-footer">
			          	<div class="text-center">
    						<button class="btn btn-primary" type="button" id="siguiente" onclick=""> Siguiente</button>

    						<button class="btn btn-success pull-right" type="submit" id="confirmar" style="display: none;"> Confirmar</button>

    					</div>
			        </div>
		      	</form>
		    </div>
		    <!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>