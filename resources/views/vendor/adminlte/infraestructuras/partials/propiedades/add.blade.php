<div class="modal fade" id="addPropiedades">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Propiedad</h4>
      </div>
      <form action="{{route('infraestructuras.propiedades.store', $infraestructura->id)}}" method="post">
        {{csrf_field()}}

        <div class="modal-body">
          <div class="row">            
            <div class="form-group col-md-6">
              <label>*Nombre</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="form-group col-md-6">
              <label>*Valor</label>
              <input type="text" class="form-control" name="valor" required>
            </div>
            <div class="form-group col-md-6">
              <label>*Unidad de Medida</label>
              <input type="text" class="form-control" name="unidad_medida" required>
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