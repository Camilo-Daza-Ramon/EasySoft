<div class="modal fade" id="addFoto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 id="titulo-agreagar-evidencia" class="modal-title"><i class="fa fa-plus"></i> Agregar Evidencia</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('mantenimientos.archivos.store', $mantenimiento_id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">
        <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-7">

              <div class="input-group">
                <select class="form-control" name="tipo" id="tipo">
                  <option value="">Elija un tipo</option>
                  @foreach($evidencias as $item)
                    <option value="{{$item['archivo']}}">{{$item['nombre']}}</option>
                  @endforeach
                </select>
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