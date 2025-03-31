<div class="modal fade" id="addDireccion">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 id="titulo-agregar-direccion" class="modal-title"><i class="fa fa-plus"></i> Agregar Direcciones</h4>
      </div>
      <form id="form-direccion-create" action="{{route('mantenimientos.direcciones.store', $mantenimiento_id)}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">
        <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">
        <div class="modal-body">
          <div class="row">            
            <div class="form-group col-md-5">
              <label>Direccion</label>
              <input type="text" class="form-control" name="direccion" required>
            </div>
            <div class="form-group col-md-3">
              <label>Barrio</label>
              <input type="text" class="form-control" name="barrio" required>
            </div>
            <div class="form-group col-md-2">
              <label>Latitud</label>
              <input type="text" class="form-control" name="latitud" required>
            </div>
            <div class="form-group col-md-2">
              <label>longitud</label>
              <input type="text" class="form-control" name="longitud" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btn-guardar">Guardar </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->