<div class="modal fade" id="activoAdd">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-plus-circle"> </i>  Agregar Activo Fijo</h4>
			</div>
			<form action="{{route('insumos.activos-fijos.store', $insumo->InsumoId)}}" method="post">
				<div class="modal-body">
					<div class="row">
						@include('adminlte::inventarios.insumos.activos-fijos.partials.form')
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit"class="btn btn-primary">Agregar</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
