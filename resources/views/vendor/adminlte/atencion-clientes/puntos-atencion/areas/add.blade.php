<div class="modal fade" id="addAreaPunto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Area</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('puntos-atencion.areas.store', $punto_atencion->id)}}" method="post">
        
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <label>Nombre Area</label>
              <input type="text" class="form-control" name="nombre" required>
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