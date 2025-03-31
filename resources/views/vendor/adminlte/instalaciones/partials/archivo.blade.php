<div class="modal fade" id="addArchivo">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Archivo</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('instalaciones.archivos.store', $instalacion->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
        <input type="hidden" name="documento" value="{{$instalacion->cliente->Identificacion}}">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="form-group">
            <label>Tipo de Archivo</label>
            <select class="form-control" name="nombre" required>
              <option value="">Elija una opción</option>
              @foreach($evidencias as $item)
                <option value="{{$item['archivo']}}">{{$item['nombre']}}</option>
              @endforeach
              
            </select>
          </div>
          <div class="row">  
            <div class="form-group col-md-12">
              <label>Archivo</label>
              <input type="file" class="form-control" name="archivo" required>
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