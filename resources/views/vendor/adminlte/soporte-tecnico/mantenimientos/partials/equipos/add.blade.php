<div class="modal fade" id="addEquipo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 id="titulo-agregar-evidencia" class="modal-title"><i class="fa fa-plus"></i> Agregar Equipos</h4>
      </div>
      <form id="form-equipo-upload" action="{{route('mantenimientos.equipos.store', $mantenimiento_id)}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">
        <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">

        <div class="modal-body">
          <div class="row">            
            <div class="form-group col-md-6">
              <label>Nombre Equipo</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="form-group col-md-6">
              <label>Marca-Referencia</label>
              <input type="text" class="form-control" name="marca" required>
            </div>
            <div class="form-group col-md-6">
              <label>Serial</label>
              <input type="text" class="form-control" name="serial" required>
            </div>
            <div class="form-group col-md-6">
              <label>Cambió</label>
              <select class="form-control" name="cambio" required>
                <option value="">Elija una opción</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
              </select>
            </div>
            <div class="form-group col-md-12">
              <label>Observaciones</label>
              <textarea class="form-control" name="observaciones"></textarea>
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