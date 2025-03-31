<div class="modal fade" id="addSolicitud">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Solicitud</h4>
      </div>
      <div class="modal-body" id="form-ticket">
        <div class="row">
          <div class="form-group col-md-6">
            <label>Fecha Respuesta limite: </label>
            <input class="form-control" type="date" name="fecha_limite" value="{{date('Y-m-d')}}">
          </div>
          <div class="form-group col-md-6">
            <label>Celular de contacto: </label>
            <div class="input-group">
              <div class="input-group-addon">
              <i class="fa fa-phone"></i>
              </div>
              <input type="text" class="form-control" name="celular_contacto" data-inputmask='"mask": "(999) 999-9999"' data-mask>
            </div>
          </div>
          <div class="form-group col-md-6">
            <label>Correo: </label>
            <input type="email" class="form-control" name="correo_solicitud" autocomplete="off">
          </div>
          <div class="form-group col-md-6">
            <label>Jornada</label>
            <select class="form-control" name="jornada">
              <option value="">Elija una opcion</option>
              <option value="MAÑANA">MAÑANA</option>
              <option value="TARDE">TARDE</option>
              <option value="NOCHE">NOCHE</option>
              <option value="TODO EL DÍA">TODO EL DÍA</option>
            </select>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">
        <button class="btn btn-primary" id="btn-add-solicitud"> <i class="fa fa-floppy-o" id="icon-plus"></i> Agregar Solicitud</button>
      </div>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
