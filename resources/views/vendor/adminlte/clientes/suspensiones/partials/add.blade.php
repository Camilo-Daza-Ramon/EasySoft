<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Suspender Clientes</h4>
      </div>
      <form action="{{route('clientes-suspensiones.store')}}" method="POST">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-4">
              <label>Tipo</label>
              <select class="form-control" name="tipo" required>
                <option value="">Elija una opción</option>
                <option value="MORA">MORA</option>
              </select>
            </div>
            <div class="form-group col-md-12">
              <label>Tipo</label>
              <textarea class="form-control" name="cedulas" required></textarea>
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