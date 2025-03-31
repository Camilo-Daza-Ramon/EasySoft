<div class="modal fade" id="addDocumentacion">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Documentacion</h4>
      </div>
      <form action="{{route('proyectos.documentacion.store', $proyecto->ProyectoID)}}" method="post">
        <div class="modal-body">
          @include('adminlte::proyectos.documentacion.partials.form')          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Agregar </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->