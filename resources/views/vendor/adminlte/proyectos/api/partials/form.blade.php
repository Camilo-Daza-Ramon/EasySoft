{{csrf_field()}}
<input type="hidden" name="proyecto_id" value="{{(isset($proyecto->ProyectoID))? $proyecto->ProyectoID: ''}}">
<div class="form-group">
  <label class="col-sm-2 control-label">*URL</label>
  <div class="col-sm-10">
    <input type="url" class="form-control" name="url" value="{{ (Session::has('errors')) ? old('url', '') : (!empty($proyecto->facturacion_api)) ? $proyecto->facturacion_api->url_api : ''}}" required>
  </div>    
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">*Token</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="token" value="{{ (Session::has('errors')) ? old('token', '') : (!empty($proyecto->facturacion_api)) ? $proyecto->facturacion_api->token_identificador : ''}}" required>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">*Controlador</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="controlador" value="{{ (Session::has('errors')) ? old('controlador', '') : (!empty($proyecto->facturacion_api)) ? $proyecto->facturacion_api->controlador : ''}}" required>
  </div>
</div>
<div class="form-group">
  <label class="col-sm-2 control-label">*Acción</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="accion" value="{{ (Session::has('errors')) ? old('accion', '') : (!empty($proyecto->facturacion_api)) ? $proyecto->facturacion_api->accion : ''}}" required>
  </div>
</div>
