<div class="modal fade" id="formModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar OLT</h4>
      </div>
      <form id="form" action="" method="POST">
        <input id="metodo" type="hidden" name="" value="">
        <div class="modal-body">

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="form-group">
          <label>*Nombre</label>
          <input type="text" class="form-control{{ $errors->has('nombre') ? ' has-error' : '' }}" name="nombre" placeholder="Nombre" required>
          </div>          

          <div class="row">
          <div class="form-group{{ $errors->has('ip') ? ' has-error' : '' }} col-lg-4">
            <label>*IP</label>
            <input type="text" class="form-control" name="ip" placeholder="0.0.0.0" required>
          </div>

          <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }} col-lg-4">
            <label>*Usuario</label>
            <input type="text" class="form-control" name="usuario" placeholder="usuario" required>
          </div>

          <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} col-lg-4">
            <label>Contraseña</label>
            <input type="password" class="form-control" name="password" placeholder="***" required>
          </div>

          <div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-lg-6">
            <label>*Departamento: </label>
              <select class="form-control" name="departamento" id="departamento2" required>
                <option value="">Elija una opción</option>
                @foreach($departamentos as $departamento)
                  <option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
                @endforeach
                  
              </select>                 
          </div>
          <div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-lg-6">
            <label>*Municipio: </label> 
            <select class="form-control" name="municipio" id="municipio2" required>
            </select>
          </div>

          <div class="form-group{{ $errors->has('latitud') ? ' has-error' : '' }} col-lg-6">
            <label>*Latitud</label>
            <input type="text" class="form-control" name="latitud" placeholder="latitud" required>
          </div>

          <div class="form-group{{ $errors->has('longitud') ? ' has-error' : '' }} col-lg-6">
            <label>*Longitud</label>
            <input type="text" class="form-control" name="longitud" placeholder="***" required>
          </div>
          

          <div class="form-group col-lg-6">
            <label>*Versión</label>
            <select class="form-control" name="version" required>
              <option value="">Elija una version</option>
              <option value="1">1</option>
              <option value="2">2</option>
            </select>
          </div>

          <div class="form-group col-lg-6">
            <label>*Estado</label>
            <select class="form-control" name="estado" required>
              <option value="">Elija un Estado</option>
              <option value="ACTIVO">ACTIVO</option>
              <option value="INACTIVO">INACTIVO</option>
            </select>
          </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->