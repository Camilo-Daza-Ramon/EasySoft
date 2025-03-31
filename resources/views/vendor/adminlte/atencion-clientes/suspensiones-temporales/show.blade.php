<div class="modal fade" id="showSuspension">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-eye"></i> Suspensión Temporal</h4>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-gray">Cedula:</th>
                        <td id="txt-cedula"></td>
                        <th class="bg-gray">Nombre:</th>
                        <td id="txt-nombre"></td>
                    </tr>

                    <tr>
                        <th class="bg-gray">Creado por:</th>
                        <td colspan="3" id="txt-usuario"></td>
                    </tr>

                    <tr>                        
                        <th class="bg-gray">Fecha Hora Inicio:</th>
                        <td id="txt-fecha_inicio"></td>
                        <th class="bg-gray">Fecha Hora Fin:</th>
                        <td id="txt-fecha_fin"></td>
                    </tr>

                    <tr> 
                        <th class="bg-gray">Fecha Solicitud:</th>
                        <td id="txt-fecha_solicitud"></td>
                        <th class="bg-gray">Dias Solicitados:</th>
                        <td id="txt-dias_solicitados"></td>
                    </tr>

                    <tr>
                        <th class="bg-gray">Descripción:</th>
                        <td colspan="3" id="txt-descripcion"></td>
                    </tr>
                </table>
            </div>            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->