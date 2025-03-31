<div class="modal fade" id="addCedulas">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-plus-circle"> </i>  Agregar Cedulas</h4>
			</div>
			<form action="{{route('restricciones.store')}}" method="post">
				<div class="modal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row"> 

                        <div class="form-group col-md-12 {{ $errors->has('cedulas') ? 'has-error' : ''}}">
                            <label for="cedulas">*Cedulas:</label>
                            <textarea name="cedulas" class="form-control" placeholder="Ingrese las cedulas separadas por coma ','" value="{{old('cedulas')}}" required></textarea>
                            {!! $errors->first('cedulas', '<p class="help-block">:message</p>') !!}
						</div>
						<div class="form-grup col-md-12 {{ $errors->has('observaciones') ? 'has-error' : ''}}">
							<label>Observaciòn</label>
							<textarea class="form-control" name="observaciones" placeholder="Observacion" id="" cols="30" rows="10"></textarea>
							{!! $errors->first('observaciones', '<p class="help-block">:message</p>') !!}
						</div>
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
