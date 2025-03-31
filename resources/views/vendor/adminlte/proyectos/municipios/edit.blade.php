<div class="modal fade" id="editMunicipio">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Municipio</h4>
      </div>
      <form id="form_editar_municipio" action="" method="post">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="meta_id" value="">

        <div class="modal-body">
          @include('adminlte::proyectos.municipios.partials.form')
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