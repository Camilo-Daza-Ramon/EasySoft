<div class="modal fade" id="mantenimientoAdd">
  <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-plus"> </i>  Crear Mantenimiento Preventivo</h4>
			</div>
			<form action="{{route('preventivos.store')}}" method="post">
				<div class="modal-body">				
					<div class="row">
                        {{csrf_field()}}                        
                        <div class="form-group {{ $errors->has('tipo') ? ' has-error' : '' }} col-md-8">
                            <label>*Tipo</label>
                            <select class="form-control " name="tipo" required>
                                <option value="">Elija una opción</option>
                                @foreach($tipos_mantenimientos as $tipo)                                    
                                    <option value="{{$tipo->TipoDeMantenimiento}}" {{(Session::has('errors')) ? ((old('tipo', '') == $tipo->TipoDeMantenimiento) ? 'selected' : '') : ''}}>{{$tipo->Descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group{{ $errors->has('fecha_programada') ? ' has-error' : '' }} col-md-4">
                            <label>*Fecha de Agendamiento</label>
                            <input type="date" name="fecha_programada" class="form-control" value="{{old('fecha_programada', '')}}" required>
                        </div>

                        <div class="form-group {{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-4">
                            <label>Proyecto</label>
                            <select class="form-control" name="proyecto" onchange="proyectos($(this), 'mantenimientoAdd')" id="proyecto">
                                <option value="">Elija un proyecto</option>
                                @foreach($proyectos as $proyecto)
                                    @if($proyecto->Status == 'A')
                                        <option value="{{$proyecto->ProyectoID}}" {{(Session::has('errors')) ? ((old('proyecto', '') == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ $errors->has('departamento') ? ' has-error' : '' }} col-md-4">
                            <label>*Departamento</label>
                            <select class="form-control" name="departamento" onchange="departamentos($(this), 'mantenimientoAdd')" id="departamento" required>
                                <option value="">Elija un departamento</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{$departamento->DeptId}}" {{(Session::has('errors')) ? ((old('departamento', '') == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ $errors->has('municipio') ? ' has-error' : '' }} col-md-4"> 
                            <label>*Municipio</label>
                            <select class="form-control" name="municipio" id="municipio" required>
                                <option value="">Elija un municipio</option>
                            </select>
                        </div>   

                        <div class="form-group{{ $errors->has('observaciones') ? ' has-error' : '' }} col-md-12">
                            <label>*Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="4" placeholder="Indique las labores a realizar en el mantenimiento." required>{{old('observaciones', '')}}</textarea>
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

