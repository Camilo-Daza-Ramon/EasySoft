<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row">
	<div id="form-group-cedula" class="form-group{{ $errors->has('cedula_atencion') ? ' has-error' : '' }} col-md-3">
		<label>*Documento:</label>
		<input type="number" name="cedula_atencion" class="form-control" placeholder="Documento" value="{{ (Session::has('errors')) ? old('cedula_atencion', '') : $atencion->identificacion }}" min="0" max="9999999999" autocomplete="off" required>
	</div>

	<div id="form-group-cedula_titular" class="form-group{{ $errors->has('cedula_titular') ? ' has-error' : '' }} col-md-3">
		<label>Documento Titular:</label>
		<input type="number" name="cedula_titular" class="form-control" placeholder="Documento" value="{{ (Session::has('errors')) ? old('cedula_titular', '') : $atencion->identificacion_titular }}" min="0" max="9999999999" autocomplete="off">
	</div>

	<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-6">
		<label>*Nombre Completo:</label>
		<input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre Completo" value="{{old('nombre')}}" autocomplete="off" required>
	</div>

	<div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-md-6">
		<label>*Departamentos:</label>
		<select name="departamento" id="departamento" class="form-control" required>
			<option value="">Elija un departamento</option>
			@foreach($departamentos as $departamento)
				<option value="{{$departamento->DeptId}}" {{ ((old('departamento','')) || (($departamento_id == $departamento->DeptId))) ? 'selected' : '' }}>{{$departamento->NombreDelDepartamento}}</option>
		    @endforeach
		</select>
	</div>

	<div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-md-6">
		<label>*Municipio:</label>
		<select name="municipio" id="municipio" class="form-control" required>

		</select>
	</div>

</div>

<div class="row">
	<div class="form-group{{ $errors->has('categorias') ? ' has-error' : '' }} col-md-6">
		<label>*Categorias Atención: </label>
		<select name="categorias" id="categorias" class="form-control" required>
			<option value="">Elija una categoria</option>
			@foreach($categorias as $categoria)
		        <option value="{{ $categoria->categoria }}" {{ ((old('categoria','')) || (($atencion->punto_atencion_cliente->motivo_categoria == $categoria->categoria))) ? 'selected' : '' }}>{{ $categoria->categoria }}</option>
		    @endforeach
		</select>
	</div>

	<div class="form-group{{ $errors->has('motivo') ? ' has-error' : '' }} col-md-6">
		<label>*Motivo Atención: </label>
		<select name="motivo" id="motivo" class="form-control" required></select>
	</div>

	<div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
     	<label>*Descripción Solicitud: </label>
        <textarea class="form-control" name="descripcion" required>{{old('descripcion')}}</textarea>
    </div>

    <div class="form-group{{ $errors->has('solucion') ? ' has-error' : '' }} col-md-12">
     	<label>*Solución: </label>
        <textarea class="form-control" name="solucion" required>{{old('solucion')}}</textarea>
    </div>

		<div id="panel-pqr-ticket">
			<div class="form-group{{ $errors->has('cun') ? ' has-error' : '' }} col-md-4">
				<label># CUN</label>
				<input type="text" name="cun" class="form-control" placeholder="CUN" value="{{old('cun')}}" autocomplete="off" >
			</div>

			<div class="form-group{{ $errors->has('ticket') ? ' has-error' : '' }} col-md-4">
				<label># Ticket:</label>
				<input type="text" name="ticket" class="form-control" placeholder="Ticket" value="{{old('ticket')}}" autocomplete="off" >
			</div>

			<div class="form-group{{ $errors->has('mantenimiento') ? ' has-error' : '' }} col-md-4">
				<label># Mantenimiento:</label>
				<input type="text" name="mantenimiento" class="form-control" placeholder="Mantenimiento" value="{{old('mantenimiento')}}" autocomplete="off" >
			</div>
		</div>

    <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-4">
     	<label>*Estado: </label>
        <select name="estado" id="estado" class="form-control" required>
        	<option value="">Elija una Opcion</option>
            <option value="ATENDIDO">ATENDIDO</option>
            <option value="ABANDONO">ABANDONO</option>
        </select>
    </div>
</div>
