<div class="modal fade" id="addContactos">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Contacto</h4>
      </div>
      <form action="{{route('infraestructuras.contactos.store', $infraestructura->id)}}" method="post">
        {{csrf_field()}}

        <div class="modal-body">
          <div class="row">            
            <div class="form-group col-md-6">
              <label>*Nombre</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="form-group col-md-6">
              <label>*Celular</label>
              <input type="number" class="form-control" name="celular" required>
            </div>
            <div class="form-group col-md-6">
              <label>*Teléfono</label>
              <input type="number" class="form-control" name="telefono" required>
            </div>
            <div class="form-group col-md-6">
              <label>*Cargo presentativo</label>
              <input type="text" class="form-control" name="cargo_presentativo" required>
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