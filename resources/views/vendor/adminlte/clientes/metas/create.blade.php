<div class="modal fade" id="metasClientesAdd">
  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-plus"> </i>  Asignar clientes a Meta</h4>
			</div>
			<form action="{{route('metas-clientes.store')}}" method="post">
                {{csrf_field()}}
				<div class="modal-body">				
					<div class="row"> 
                        <div class="form-group col-md-6 {{ $errors->has('proyecto') ? 'has-error' : ''}}">
                            <label for="proyecto">*Proyecto:</label>
                            <select  class="form-control" name="proyecto" value="{{ (Session::has('errors')) ? old('proyecto', '') :'' }}" required onchange="listar_metas(this);">
                                <option value="">Elija una opción</option>
                                @foreach($proyectos as $proyecto)
                                    <option value="{{ $proyecto->ProyectoID }}">{{$proyecto->NumeroDeProyecto}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-6 {{ $errors->has('meta') ? 'has-error' : ''}}">
                            <label for="meta">*Meta:</label>
                            <select name="meta" class="form-control" required></select>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('cedulas') ? 'has-error' : ''}}">
                            <textarea name="cedulas" class="form-control" placeholder="Cedulas ceparadas por coma ," required></textarea>
                        </div>
					</div>
					<div class="modal-footer">
						<button type="submit"class="btn btn-primary">Guardar</button>
					</div>
				</div>
				
			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

