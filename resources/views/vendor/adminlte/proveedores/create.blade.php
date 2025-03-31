<div class="modal fade" id="proveedorAdd">
  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-plus"> </i>  Crear Proveedor</h4>
			</div>
			<form  id="form-proveedores-crear" action="{{route('proveedores.store')}}" method="post">
				<div class="modal-body">				
					<div class="row">
          				@include('adminlte::proveedores.partials.form')
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</div>				
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

