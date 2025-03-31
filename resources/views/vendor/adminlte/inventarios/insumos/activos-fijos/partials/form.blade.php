<input type="hidden" name="_token" value="{{ csrf_token() }}">
 

  <div class="form-group col-md-6 {{ $errors->has('marca') ? 'has-error' : ''}}">
    <label for="marca">*Marca:</label>
    <input type="text" name="marca" class="form-control" placeholder="Marca" value="{{old('marca')}}" required>
    {!! $errors->first('marca', '<p class="help-block">:message</p>') !!}
  </div>

  <div class="form-group col-md-6 {{ $errors->has('modelo') ? 'has-error' : ''}}">
    <label for="modelo">*Modelo:</label>
    <input type="text" name="modelo" class="form-control" placeholder="Modelo" value="{{old('modelo')}}" required>
    {!! $errors->first('modelo', '<p class="help-block">:message</p>') !!}
  </div>

  <div class="form-group col-md-6 {{ $errors->has('referencia') ? 'has-error' : ''}}">
    <label for="referencia">*Referencia:</label>
    <input type="text" name="referencia" class="form-control" placeholder="Referencia" value="{{old('referencia')}}" required>
    {!! $errors->first('referencia', '<p class="help-block">:message</p>') !!}
  </div>

  <div class="form-group col-md-6 {{ $errors->has('serial') ? 'has-error' : ''}}">
    <label for="serial">*Serial:</label>
    <input type="text" name="serial" class="form-control" placeholder="Serial" value="{{old('serial')}}" required>
    {!! $errors->first('serial', '<p class="help-block">:message</p>') !!}
  </div>
