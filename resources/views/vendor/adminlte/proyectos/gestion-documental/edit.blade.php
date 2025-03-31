<div class="modal fade" id="documentalEdit">
  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"> </i>  Editar Registro</h4>
			</div>
			<form action="" method="post">
				<div class="modal-body">
					<input type="hidden" name="_method" value="put">
					<div class="row">
            @include('adminlte::proyectos.gestion-documental.partials.form')
          </div>
          <div class="modal-footer">
						<button type="submit"class="btn btn-primary">Actualizar</button>
					</div>
				</div>
				
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
