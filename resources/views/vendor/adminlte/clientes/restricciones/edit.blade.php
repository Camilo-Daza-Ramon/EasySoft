<div class="modal fade" id="clienteRestEdit">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"> </i>  Editar cliente restriccion</h4>
			</div>
			<form action="" method="post">
				<div class="modal-body">
					<input type="hidden" name="_method" value="put">
                    @include('adminlte::clientes.restricciones.partials.form')
				</div>
				<div class="modal-footer">
					<button type="submit"class="btn btn-primary">Actualizar</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>