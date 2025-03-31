<div class="modal fade" id="editAPI">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> Editar API</h4>
      </div>
      <form action="{{route('facturacion_electronica_api.update', $proyecto->facturacion_api->id)}}" class="form-horizontal" method="post">
        <div class="modal-body">
          <input type="hidden" name="_method" value="put">
          @include('adminlte::proyectos.api.partials.form')          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Actualizar </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->