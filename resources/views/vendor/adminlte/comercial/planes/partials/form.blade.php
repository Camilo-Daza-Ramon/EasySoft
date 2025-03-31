<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row">
	<div class="form-group{{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-6">
		<label>*Proyecto:</label>
		<select name="proyecto" id="proyecto" class="form-control" required>
			<option value="">Elija un proyecto</option>
			@foreach($proyectos as $proyecto1)
				<option value="{{$proyecto1->ProyectoID}}" {{ (old('proyecto','') == $proyecto1->ProyectoID) ? 'selected' : '' }}>{{$proyecto1->NumeroDeProyecto}}</option>		        
		    @endforeach		
		</select>
	</div>

	<div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-6">
		<label>Nombre Plan:</label>
		<input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Plan" value="{{old('nombre')}}" autocomplete="off">
	</div>

	<div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
		<label>*Descripcion Plan:</label>
		<textarea class="form-control" name="descripcion" id="descripcion">{{old('descripcion')}}</textarea>		
	</div>
	
	<div class="form-group{{ $errors->has('estrato') ? ' has-error' : '' }} col-md-4">
		<label>*Estrato:</label>
		<select name="estrato" id="estrato" class="form-control" required>
			<option value="">Elija un estrato</option>
			@foreach($estratos as $estrato)
				<option value="{{$estrato}}">{{$estrato}}</option>		        
		    @endforeach		
		</select>
	</div>

	<div class="form-group{{ $errors->has('velocidad_descarga') ? ' has-error' : '' }} col-md-4">
		<label>*Velocidad de descarga:</label>
		<input type="number" name="velocidad_descarga" id="velocidad_descarga" class="form-control" placeholder="Velocidad Internet" value="{{old('velocidad_descarga')}}" autocomplete="off" min="1" max="999" maxlength="3" minlength="1" required>
	</div>

	<div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }} col-md-4">
		<label>*Valor:</label>
		<input type="number" name="valor" id="valor" class="form-control" placeholder="Valor Internet" value="{{old('valor')}}" min="0"  required>
	</div>


	<div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }} col-md-6">
		<label>*Tipo de Plan:</label>
		<select name="tipo" id="tipo" class="form-control" required>
			<option value="">Elija un tipo</option>
			@foreach($tipos_planes as $tipo)
				<option value="{{$tipo}}">{{$tipo}}</option>		        
		    @endforeach
		</select>
	</div>

	<div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-6">
		<label>*Estado:</label>
		<select name="estado" id="estado" class="form-control" required>
			<option value="">Elija un estado</option>
			@foreach($estados as $estado)
				<option value="{{$estado['valor']}}">{{$estado['nombre']}}</option>		        
		    @endforeach
		</select>
	</div>
	
	<div class="form-group col-md-12">
		<label for="plan_municipios">Municipio</label>
		<select name="plan_municipios[]" id="plan_municipios" multiple class="form-control js-example-basic-multiple col-md-12">
        @foreach($proyecto->proyecto_municipio as $proyecto_municipio2)
            <option value="{{$proyecto_municipio2->id }}">{{$proyecto_municipio2->municipio->NombreMunicipio}}</option>
        @endforeach
    	</select>
    </div>
</div>