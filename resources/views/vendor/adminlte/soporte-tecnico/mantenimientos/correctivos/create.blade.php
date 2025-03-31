<div class="modal fade" id="mantenimientoAdd">
  <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-blue">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><i class="fa fa-plus"> </i>  Crear Mantenimiento Correctivo Masivo</h4>
			</div>
			<form action="{{route('correctivos.store')}}" method="post">
				<div class="modal-body">				
					<div class="row">
                        {{csrf_field()}}                        
                        <div class="form-group {{ $errors->has('tipo') ? ' has-error' : '' }} col-md-4">
                            <label>*Tipo</label>
                            <select class="form-control " name="tipo" required>
                                <option value="">Elija una opción</option>
                                @foreach($tipos_mantenimientos as $tipo)
                                    @if($tipo->Descripcion != 'Acceso')
                                        <option value="{{$tipo->TipoDeMantenimiento}}" {{(Session::has('errors')) ? ((old('tipo', '') == $tipo->TipoDeMantenimiento) ? 'selected' : '') : ''}}>{{$tipo->Descripcion}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ $errors->has('tipo_falla') ? ' has-error' : '' }} col-md-8">
                            <label>*Tipo Falla</label>
                            <select class="form-control " name="tipo_falla" required>
                                <option value="">Elija una opción</option>
                                @foreach($tipos_fallas as $tipo_f)                                    
                                    <option value="{{$tipo_f->TipoFallaId}}" {{(Session::has('errors')) ? ((old('tipo_falla', '') == $tipo_f->TipoFallaId) ? 'selected' : '') : ''}}>{{$tipo_f->DescipcionFallo}}</option>
                                @endforeach
                            </select>
                        </div>                       

                        <div class="form-group {{ $errors->has('canal_atencion') ? ' has-error' : '' }} col-md-4">
                            <label>*Canal de Atención</label>
                            <select class="form-control" name="canal_atencion" required>
                                <option value="">Elija una opción</option>
                                @foreach($canales_atencion as $canal_atencion)
                                    <option value="{{$canal_atencion->TipoEntradaTicket}}" {{(Session::has('errors')) ? ((old('canal_atencion', '') == $canal_atencion->TipoEntradaTicket) ? 'selected' : '') : ''}}>{{$canal_atencion->Descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ $errors->has('prioridad') ? ' has-error' : '' }} col-md-8">
                            <label>*Prioridad</label>
                            <select class="form-control" name="prioridad" required>
                                <option value="">Elija una opción</option>
                                @foreach($prioridades as $key => $values)
                                    <option value="{{$key}}" {{(Session::has('errors')) ? ((old('prioridad', '') == $key) ? 'selected' : '') : ''}}>{{$key}}. {{$values}}</option>
                                @endforeach
                            </select>
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
                        
                        <div class="form-group{{ $errors->has('clientes_afectados') ? ' has-error' : '' }} col-md-12">
                            <label>*Clientes Afectados</label>
                            <textarea name="clientes_afectados" class="form-control" rows="4" placeholder="Ingrese los números de cedula de los clientes afectados separados por coma (,)" required>{{old('clientes_afectados', '')}}</textarea>
                        </div>  

                        <div class="form-group{{ $errors->has('descripcion_problema') ? ' has-error' : '' }} col-md-12">
                            <label>*Descripcion del problema</label>
                            <textarea name="descripcion_problema" class="form-control" rows="4" placeholder="Indique el problema que lleva a la creación del mantenimiento" required>{{old('descripcion_problema', '')}}</textarea>
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

