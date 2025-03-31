<div class="modal fade" id="showNota">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-exclamation-circle"></i> Nota Contable</h4>
      </div>      
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>Tipo de Nota: </label>
              <p id="tipo_nota_txt"></p>             
            </div>
            <div class="col-md-3">
              <label>Concepto: </label>
              <p id="concepto_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Tipo Operacion: </label>
              <p id="tipo_operacion_txt"></p>              
            </div>
            <div class="col-md-3">
              <label>Fecha Expedisión:</label>
              <p id="fecha_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Tipo Negociacion: </label>
              <p id="tipo_negociacion_txt"></p>              
            </div>
            <div class="col-md-3">
              <label>Medio de Pago: </label>
              <p id="tipo_medio_pago_txt"></p>              
            </div>
            <div class="col-md-3">
              <label># Nota DIAN:</label>
              <p id="dian_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Estado:</label>
              <p id="estado_txt"></p>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Concepto</th>
                    <th width="100px">Cantidad</th>
                    <th width="120px">Valor Uni.</th>
                    <th width="100px">%IVA</th>
                    <th width="120px">Valor Total</th>
                  </tr>                
                </thead>
                <tbody id="productos_txt">
                  
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="2">Motivo Descuento</th>
                    <td colspan="2" id="motivo_txt"></td>
                    <th class="text-right"> % Descuento:</th>
                    <td class="text-right"><span id="descuento_txt"></span>%</td>
                  </tr>
                  <tr>
                    <th colspan="5" class="text-right"> TOTAL:</th>
                    <td class="text-right"><span id="total_txt"></span></td>
                  </tr>
                </tfoot>            
              </table>
            </div>
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->