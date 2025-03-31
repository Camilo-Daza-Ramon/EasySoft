<div class="modal fade" id="addArchivo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Archivo</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('contratos-archivos.store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
        <input type="hidden" name="contrato_id" value="{{$contrato->id}}">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-6">
              <label>Tipo de Archivo</label>
              <select class="form-control" name="nombre" required>
                <option value="">Elija una opción</option>
                <option value="contrato">Contrato</option>
                <option value="acta_juramentada">Acta Juramentada</option>
                <option value="constancia_no_firma">Constancia "NO FIRMA"</option>
                <option value="acta_finalizacion">Acta de Finalización</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <div class="radio margin-t-30">
                <label>
                  <input type="radio" name="accion" value="generar">
                  Generar
                </label>
              </div>                  
            </div>
            <div class="form-group col-md-3">
              <div class="radio margin-t-30">
                <label>
                  <input type="radio" name="accion" value="subir">
                  Subir
                </label>
              </div>
            </div> 
            <div class="form-group col-md-12" id="archivo-area" style="display: none;">
              <label>Archivo</label>
              <input type="file" class="form-control" name="archivo">
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