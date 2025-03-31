{{csrf_field()}}

<div class="form-group col-md-6 {{ $errors->has('nombre') ? 'has-error' : ''}}">
      <label for="cedula">*Cedula:</label>
      <input type="number" class="form-control" placeholder="Cedula" name="cedula" value="{{ (Session::has('errors')) ? old('cedula', '') :''}}" autocomplete="off" required>
      {!! $errors->first('cedula', '<p class="help-block">:message</p>') !!}
  </div>

  <div class="form-group col-md-6 {{ $errors->has('nombre') ? 'has-error' : ''}}">
      <label for="valor">*Valor:</label>
      <input type="number" class="form-control" placeholder="Valor" name="valor" value="{{ (Session::has('errors')) ? old('valor', '') :''}}" autocomplete="off" step="0.01" min="1" required>
      {!! $errors->first('valor', '<p class="help-block">:message</p>') !!}
  </div>

  <div class="form-group col-md-4 {{ $errors->has('nombre') ? 'has-error' : ''}}">
      <label for="referencia">*Referencia de Pago:</label>
      <input type="text" class="form-control" placeholder="Referencia" name="referencia" value="{{ (Session::has('errors')) ? old('cedula', '') :''}}" autocomplete="off" required>
      {!! $errors->first('referencia', '<p class="help-block">:message</p>') !!}
  </div>

<div class="form-group{{ $errors->has('fecha_hora_pago') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
  <label>*Fecha de pago:</label>
  <input type="datetime-local" name="fecha_hora_pago" class="form-control" value="{{ (Session::has('errors')) ? old('fecha_hora_pago', '') :''}}" max="{{date('Y-m-d H:i:s')}}" required>
</div>

<div class="form-group col-md-4 {{ $errors->has('medio_pago') ? 'has-error' : ''}}">
      <label for="medio_pago">*Medio de Pago:</label>
      <select  class="form-control" name="medio_pago" value="{{ (Session::has('errors')) ? old('medio_pago', '') :'' }}" required>
        <option value="">Elija una opción</option>
        <option disabled>EFECTY</option>
        <option>NEQUI</option>        
      </select>
  </div>

  
  

