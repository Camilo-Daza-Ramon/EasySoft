<div class="modal fade" id="documentoVersionado">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-upload"> </i>  Subir Versión</h4>
			</div>
			<form action="" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="row">
						{{csrf_field()}}

						<div class="form-group{{ $errors->has('documento') ? ' has-error' : '' }} col-md-12">
							<label>*Documento</label>
							<input class="form-control" type="file" name="documento" value="{{old('documento')}}" required>
						</div>

						<div class="form-group{{ $errors->has('fecha_expedicion') ? ' has-error' : '' }} col-md-4">
							<label>*Fecha Expedición</label>
							<input class="form-control" type="date" name="fecha_expedicion" value="{{old('fecha_expedicion')}}" required>
						</div>

						<div class="form-group{{ $errors->has('fecha_vencimiento') ? ' has-error' : '' }} col-md-4">
							<label>Fecha Vencimiento</label>
							<input type="date" name="fecha_vencimiento" class="form-control" autocomplete="off" value="{{old('fecha_vencimiento')}}">
						</div>

						<div class="form-group{{ $errors->has('version') ? ' has-error' : '' }} col-md-4">
							<label>Versión</label>
							<input type="text" name="version" class="form-control" autocomplete="off" value="{{old('version')}}" >
						</div>

						<div class="form-group{{ $errors->has('contenido_documento') ? ' has-error' : '' }} col-md-12">
							<label>Contenido del documento</label>
							<textarea name="contenido_documento" class="form-control">{{ (Session::has('errors')) ? old('contenido_documento', '') : '' }}</textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit"class="btn btn-primary">Subir</button>
				</div>
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
