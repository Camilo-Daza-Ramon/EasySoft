<div class="modal fade" id="addPunto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Punto de Atención</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('puntos-atencion.store')}}" method="post">        
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-6">
              <label>Nombre</label>
              <input type="text" class="form-control" name="nombre" placeholder="Nombre" required />
            </div>
            <div class="form-group col-md-6">
              <label>Proyecto</label>
              <select class="form-control" name="proyecto" id="proyecto" required>
                <option value="">Elija un proyecto</option>
                @foreach($proyectos as $proyecto)
                  <option value="{{$proyecto->ProyectoID}}">{{$proyecto->NumeroDeProyecto}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">            
            <div class="form-group col-md-6">
              <label>Departamento</label>
              <select class="form-control" name="departamento" id="departamento" required>
                <option value="">Elija un departamento</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Municipio</label>
              <select class="form-control" name="municipio" id="municipio" required>
                <option value="">Elija un municipio</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label>Dirección</label>
              <input type="text" class="form-control" name="direccion" required>
            </div>
            <div class="form-group col-md-6">
              <label>Barrio</label>
              <input type="text" class="form-control" name="barrio" required>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label>Latitud</label>
              <input type="text" class="form-control" name="latitud" required>
            </div>
            <div class="form-group col-md-4">
              <label>Longitud</label>
              <input type="text" class="form-control" name="longitud" required>
            </div>
            <div class="form-group col-md-4">
              <label>Estado</label>
              <select class="form-control" name="estado" required>
                <option value="">Elija una opción</option>
                <option value="ACTIVO">ACTIVO</option>
                <option value="INACTIVO">INACTIVO</option>   
              </select>
            </div>
          </div>          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btn-archivo">Agregar </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->