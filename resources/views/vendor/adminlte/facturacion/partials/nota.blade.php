<div class="modal fade" id="addNota">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Nota Contable</h4>
      </div>      
      <div class="modal-body">       
          <input type="hidden" name="factura_id" value="{{$factura->FacturaId}}">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <div class="row">
            <div class="form-group col-md-4 col-xs-6">
              <label>Tipo de Nota: </label>
              <select name="tipo_nota" id="tipo_nota" class="form-control" required>
                <option value="">Elija una opción</option>
                <option value="DEBITO">DEBITO</option>
                <option value="CREDITO">CREDITO</option>
              </select>
            </div>
            <div class="form-group col-md-4 col-xs-6">
              <label>Concepto: </label>
              <select name="tipo_concepto" id="tipo_concepto" class="form-control" required>
                <option value="">Elija una opción</option>                
              </select>
            </div>
            <div class="form-group col-md-4 col-xs-6">
              <label>Tipo Operacion: </label>
              <select name="tipo_operacion" id="tipo_operacion" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($tipos_operacion as $tipo_operacion)
                  <option value="{{$tipo_operacion->id}}">{{$tipo_operacion->nombre}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4 col-xs-6">
              <label>Tipo Negociacion: </label>
              <select name="tipo_negociacion" id="tipo_negociacion" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($tipos_negociacion as $tipo_negociacion)
                  <option value="{{$tipo_negociacion->id}}">{{$tipo_negociacion->nombre}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-8 col-xs-6">
              <label>Medio de Pago: </label>
              <select name="tipo_medio_pago" id="tipo_medio_pago" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($medios_pago as $medio_pago)
                  <option value="{{$medio_pago->id}}">{{$medio_pago->nombre}}</option>
                @endforeach
              </select>
            </div>
            <div class="hide" id="panel_anulacion">
              <div class="form-group col-md-4 col-xs-6">
                <label>Anular Factura?</label>
                <select class="form-control" name="anular" id="anular">
                  <option value="NO">NO</option>
                  <option value="SI">SI</option>
                </select>
              </div>
              <div class="form-group col-md-4 col-xs-6">
                <label>Porcentaje de descuento</label>
                <input type="number" name="descuento" id="descuento" class="form-control" min="1" max="100">
              </div>
              <div class="form-group col-md-4 col-xs-6">
                <label>Motivo de Anulación</label>
                <input type="text" name="motivo_descuento" id="motivo_descuento" class="form-control">
              </div>
            </div>
          </div>
          <table class="table">
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
            <tbody id="conceptos">
              
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5" class="text-right"> TOTAL:</th>
                <td><span id="total"></span></td>
              </tr>
              <tr>
                <td colspan="2">
                  <input type="text" class="form-control input-sm" name="concepto" placeholder="Concepto">
                </td>
                <td>
                  <input type="number" class="form-control input-sm" name="cantidad" id="cantidad" placeholder="Cantidad" min="1">
                </td>
                <td>
                  <input type="number" class="form-control input-sm" name="valor_unidad" id="valor_unidad" placeholder="Valor Uni." min="1">
                </td>
                <td>
                  <input type="number" name="iva" class="form-control input-sm" id="iva" placeholder="% IVA" min="0">
                  
                </td>
                <td>
                  <span id="valor_total">$0.00</span>
                </td>
                <td>
                  <button type="button" class="btn btn-success btn-xs" onclick="addConcepto();">
                    <i class="fa fa-plus"></i>
                  </button>
                </td>
              </tr>
            </tfoot>            
          </table>
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">
        <div class="checkbox pull-left">
          <label>
            @if(isset($factura->factura_electronica))
              @if(!empty($factura->factura_electronica->documento_id_feel))

                <input type="checkbox" name="reportar_dian" {{($factura->Periodo == date('Ym'))? '' : 'disabled'}}> Reportar a la DIAN
              @else
                no es posible reportar debido a que no se guardó el ID de la factura generada en FEEL
              @endif
            @else
              <input type="checkbox" name="reportar_dian" disabled> Reportar a la DIAN
            @endif
          </label>
        </div>
        <button id="guardar_nota" class="btn btn-primary"> Guardar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->