{{csrf_field()}}

<div class="form-group col-md-4 {{ $errors->has('nombre') ? 'has-error' : ''}}">
    <label for="nombre">*Nombre</label>
    <input type="text" class="form-control" placeholder="Nombre" name="nombre" value="{{ (Session::has('errors')) ? old('nombre', '') :''}}" autocomplete="off" required>
    {!! $errors->first('nombre', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-4 {{ $errors->has('correo_electronico') ? 'has-error' : ''}}">
    <label for="correo_electronico">*Correo Electrónico</label>
    <input type="text" class="form-control" placeholder="Email" name="correo_electronico" value="{{ (Session::has('errors')) ? old('correo_electronico', '') :''}}" autocomplete="off" required>
    {!! $errors->first('correo_electronico', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-4 {{ $errors->has('tipo') ? 'has-error' : ''}}">
    <label for="tipo">*Tipo</label>
    <select  class="form-control" name="tipo" required>
        <option value="">Elija una opción</option>
        <option value="INTERNET">Internet</option>
        <option value="SERVICIOS TECNICOS">Servicios Técnicos</option>
    </select>
</div>


<div class="form-group col-md-4 {{ $errors->has('tipo_identificacion') ? 'has-error' : ''}}">
    <label for="tipo_identificacion">*Tipo de Identificación:</label>
    <select  class="form-control" name="tipo_identificacion" required>
        <option value="">Elija una opción</option>
        @foreach($tipos_identificacion as $tipo_identificacion)
            <option value="{{$tipo_identificacion}}">{{$tipo_identificacion}}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-8 {{ $errors->has('identificacion') ? 'has-error' : ''}}">
    <label for="identificacion">*Identificación:</label>
    <input type="number" class="form-control" placeholder="Identificacion" name="identificacion" value="{{ (Session::has('errors')) ? old('identificacion', '') :''}}" autocomplete="off" required>
    {!! $errors->first('identificacion', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-6 {{ $errors->has('telefono') ? 'has-error' : ''}}">
    <label for="telefono">Teléfono:</label>
    <input type="number" class="form-control" placeholder="Teléfono" name="telefono" value="{{ (Session::has('errors')) ? old('telefono', '') :''}}" autocomplete="off" >
    {!! $errors->first('telefono', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-6 {{ $errors->has('celular') ? 'has-error' : ''}}">
    <label for="identificacion">Celular:</label>
    <input type="number" class="form-control" placeholder="celular" name="celular" value="{{ (Session::has('errors')) ? old('celular', '') :''}}" autocomplete="off" >
    {!! $errors->first('celular', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group col-md-6 {{ $errors->has('departamento') ? 'has-error' : ''}}">
    <label for="departamento">*Departamento:</label>
    <select id="departamento_select" class="form-control" name="departamento">
        <option value="">Elija un departamento</option>
        @foreach($departamentos as $departamento)
            <option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-6 {{ $errors->has('municipio') ? 'has-error' : ''}}">
    <label for="municipio">*Municipio:</label>
    <select id="municipio_select" class="form-control" name="municipio">
        <option value="">Elija un municipio</option>
    </select> 
</div>

<div class="form-group col-md-12 {{ $errors->has('direccion') ? 'has-error' : ''}}">
    <label for="direccion">*Dirección:</label>
    <input type="text" class="form-control" placeholder="Direccion" name="direccion" value="{{ (Session::has('errors')) ? old('direccion', '') :''}}" autocomplete="off" required>
    {!! $errors->first('direccion', '<p class="help-block">:message</p>') !!}
</div>

  
  

