<div class="modal fade" id="addFoto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Evidencia</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('pqr.archivos.store', $pqr->PqrId)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="col-md-7">

              <div class="input-group">

                <input type="text" name="tipo" id="tipo" class="form-control">
                <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat" id="btn-addfoto">Agregar</button>
                </span>
              </div>
            </div>            
          </div>
          <br>
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Archivo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="evidencias">
            </tbody>
          </table>
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