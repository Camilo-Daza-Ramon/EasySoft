<div class="modal fade" id="activoEdit">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-edit"> </i>  Editar Activo Fijo</h4>
			</div>
			<form action="" method="post">
                <input type="hidden" name="_method" value="put">
				<div class="modal-body">
                    <div class="row">
                        @include('adminlte::inventarios.insumos.activos-fijos.partials.form')
                        <div class="form-group col-md-6 {{ $errors->has('estado') ? 'has-error' : ''}}">
                            <label for="estado">*Estado:</label>
                            <select name="estado" class="form-control">
                                @foreach($estados as $estado)
                                    <option value="{{$estado}}">{{$estado}}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('estado', '<p class="help-block">:message</p>') !!}
                        </div>
                        
                    </div>
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
