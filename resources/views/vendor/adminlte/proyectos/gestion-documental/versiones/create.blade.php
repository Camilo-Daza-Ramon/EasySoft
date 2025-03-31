<div class="modal fade" id="versionAdd">
  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-plus"> </i>  Crear Version</h4>
			</div>
			<form action="{{route('documental-proyectos.versiones.store', $documental_proyecto->id)}}" method="post">
				<div class="modal-body">				
					<div class="row"> 
          				@include('adminlte::proyectos.gestion-documental.versiones.partials.form')
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
						<button type="submit"class="btn btn-primary">Guardar</button>
					</div>
				</div>
				
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

