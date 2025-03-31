<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus-circle"> </i>  Crear Usuario</h4>
      </div>
    	<form id="agregar" action="{{route('usuarios.store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
	        {{csrf_field()}}
	        <input type="hidden" name="email" value="">
	        <input type="hidden" name="user" value="">
	        <div class="modal-body">
	        	<div class="row">
	        		<div class="col">
		        	    <div class="form-row rounded box-shadow">

		        	    	<div class="form-group{{ $errors->has('user') ? ' has-error' : '' }} col-md-4">
				                <label for="user" class="control-label">Nombre</label>
				                <input id="user" type="text" class="form-control" name="user" value="{{ old('user') }}" required>

				                @if ($errors->has('user'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('user') }}</strong>
				                    </span>
				                @endif
				            </div>                      
				            
				            <div class="form-group col-md-4">
				                <label for="cedula" class="control-label">Cedula</label>
				                <input id="cedula" type="text" class="form-control" name="cedula" value="{{ old('cedula') }}" required>

				                @if ($errors->has('cedula'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('cedula') }}</strong>
				                    </span>
				                @endif
				            </div>

				            <div class="form-group{{ $errors->has('rol') ? ' has-error' : '' }} col-md-4">
				                <label for="rol" class="control-label">Rol</label>
				                <select name="rol" id="rol" class="form-control" value="{{ old('rol') }}">
				                    <option value="">Elija un Rol</option>
				                    @foreach($roles as $dato)
				                    	<option value="{{$dato->id}}">{{$dato->display_name}}</option>
				                    @endforeach
				                </select>

				                @if ($errors->has('rol'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('rol') }}</strong>
				                    </span>
				                @endif
				            </div>

				            <div class="form-group col-md-4">
				                <label for="celular" class="control-label">Telefono</label>
				                <input id="celular" type="number" class="form-control" name="celular" value="{{ old('celular') }}">

				                @if ($errors->has('celular'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('celular') }}</strong>
				                    </span>
				                @endif
				            </div>

				            <div class="form-group col-md-4">
				                <label for="email" class="control-label">Corero</label>
				                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

				                @if ($errors->has('email'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('email') }}</strong>
				                    </span>
				                @endif
				            </div>

				            <div class="form-group col-md-4">
				                <label for="contrasena" class="control-label">Contraseña</label>
				                <input id="contrasena" type="password" class="form-control" name="contrasena" value="{{ old('contrasena') }}" required>

				                @if ($errors->has('contrasena'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('contrasena') }}</strong>
				                    </span>
				                @endif
				            </div>

							<div class="form-group col-md-12">
								<label for="proyectos">Proyectos</label>
								<select name="proyectos[]" id="proyectos" multiple class="form-control js-example-basic-multiple col-md-12">
								@foreach($proyectos as $proyecto)
									<option value="{{$proyecto->ProyectoID}}">{{$proyecto->NumeroDeProyecto}}</option>
								@endforeach
								</select>
							</div>        

				            <div class="form-group col-md-8">
				                <label for="firma" class="control-label">Firma</label>
				                <input id="firma" type="file" class="form-control" name="firma" value="{{ old('firma') }}">

				                @if ($errors->has('firma'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('firma') }}</strong>
				                    </span>
				                @endif
				            </div>

				            <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-4">
				                <label for="estado" class="control-label">Estado</label>
				                <select name="estado" id="estado" class="form-control" value="{{ old('estado') }}">
				                    <option value="">Elija un Estado</option>
				                    <option value="ACTIVO">ACTIVO</option>
				                    <option value="INACTIVO">INACTIVO</option>
				                </select>

				                @if ($errors->has('estado'))
				                    <span class="help-block">
				                        <strong>{{ $errors->first('estado') }}</strong>
				                    </span>
				                @endif
				            </div>
				        </div>
				    </div>
				</div>
	        </div>
	        <div class="modal-footer">
	            <button type="submit" id="enviar" class="btn btn-primary pull-left">Guardar</button>
	        </div>
	    </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>