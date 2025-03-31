<div class="modal fade" id="vernovedad">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				
				<h4 class="modal-title"><i class="fa fa-edit"> </i>  Ver Novedad</h4>
			</div>
			<form action="" method="post">     
				<div class="modal-body">
					
					<input type="hidden" id="novedad_id" name="novedad_id" value="">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				
					<div class="form-group col-md-3 {{ $errors->has('concepto') ? 'has-error' : ''}}">
						<label for="concepto">*Concepto:</label>
					    <p id="concepto"></p>
					</div>

					<div class="form-group col-md-3 {{ $errors->has('cantidad') ? 'has-error' : ''}}">
						<label for="cantidad">*Cantidad:</label>
						<p id="cantidad"></p>
					</div>

					<div class="form-group col-md-3 {{ $errors->has('valor_unidad') ? 'has-error' : ''}}">
						<label for="valor_unidad">*Valor Unidad:</label>
						<p id="valor_unidad"></p>
					</div>

					<div class="form-group col-md-3 {{ $errors->has('unidad_medida') ? 'has-error' : ''}}">
						<label for="unidad_medida">*Uni.Medida:</label>
						<p id="unidad_medida"></p>
					</div>
					<div class="form-group col-md-3 {{ $errors->has('iva') ? 'has-error' : ''}}">
						<label for="iva">*IVA:</label>
						<p id="iva"></p>
					</div>

					<div class="form-group col-md-3 {{ $errors->has('fecha_inicio') ? 'has-error' : ''}}">
						<label for="fecha_inicio">*Fecha Inicio:</label>
						<p id="fecha_inicio"></p>
					</div>

					<div class="form-group col-md-3 {{ $errors->has('fecha_fin') ? 'has-error' : ''}}">
						<label for="fecha_fin">*Fecha Fin:</label>
						<p id="fecha_fin"></p>
					</div>
					
					<div class="form-group col-md-3 {{ $errors->has('estado') ? 'has-error' : ''}}">
						<label for="estado">*Estado:</label>
						<p id="estado"></p>
					</div>

					<div class="form-group col-md-3 {{ $errors->has('name') ? 'has-error' : ''}}">
						<label for="name">*Usuario:</label>
						<p id="name"></p>
					</div>

					<div class="form-group col-md-3 {{ $errors->has('fecha_real') ? 'has-error' : ''}}">
						<label for="fecha_real">*Fecha Real:</label>
						<p id="fecha_real"></p>
					</div>


					<div class="row">
					<div class="col-md-12">
						<table class="table">
							<thead>
								<tr>
								<th>NUMERO DE FACTURA</th>
								<th>PERIODO</th>
								<th>VALOR TOTAL</th>							
								</tr>
							</thead>
							<tbody id="facturas">
									
																		
							</tbody>
								
						</table>
					</div>
					</div>
						
				</div>
				<!-- /.modal-content -->
				<div class="modal-footer">
					<button type="button"  class="btn btn-success" data-dismiss="modal" aria-label="Close">
						Cerrar
					</button>				
				</div>
      		</form> 
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
