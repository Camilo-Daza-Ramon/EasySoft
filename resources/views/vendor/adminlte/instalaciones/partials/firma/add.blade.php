<div class="modal fade" id="addFirma">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Firmar</h4>
      </div>
      <div class="modal-body">
        <canvas width=500 height=250 data-tipo="cliente"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" onclick="limpiar();">Limpiar</button>
        <button type="button" class="btn btn-primary" id="guardarFirma">Guardar </button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->