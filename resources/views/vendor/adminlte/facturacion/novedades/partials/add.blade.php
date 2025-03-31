<div class="modal fade" id="addConcepto">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Conceptos</h4>
      </div>
      <div class="modal-body">        
          <div class="row">            
            <div class="form-group col-md-8">
              <label>Conceptos</label>
              <select class="form-control " name="concepto">
                <option value="">Elija una opción</option>
                @foreach($conceptos as $concepto)
                  <option value="{{$concepto['nombre']}}" data-cobrar="{{$concepto['cobrar']}}">{{$concepto['nombre']}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-md-4" style="display: none;">
              <label>Mes</label>
              <input type="month" class="form-control" name="mes">
            </div>

            <div class="form-group col-md-4">
              <label>ID - Ticket: </label>
              <input type="number" class="form-control" name="ticket" placeholder="ID Ticket">
            </div>

            <div class="form-group col-md-4">
              <label>Fecha Inicio</label>
              <input type="datetime-local" class="form-control" name="fecha_inicio" required>
            </div>

            <div class="form-group col-md-4">
              <label>Fecha Fin: </label>
              <input type="datetime-local" class="form-control" name="fecha_fin">
            </div>

            
          </div>

          <table class="table table-bordered">
            <thead>
              <tr class="text-center">
                <th>CANTIDAD</th>
                <th>UNI. MEDIDA</th>
                <th>VALOR UNI.</th>
                <th>IVA</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input type="number" class="form-control" name="cantidad" placeholder="Cantidad" disabled>
                </td>
                <td style="width: 26%">
                  <select class="form-control" name="unidad_medida" required>
                    <option value="">Elija una opción</option>
                    @foreach($unidades_medidas as $unidad_medida)
                      <option value="{{$unidad_medida}}">{{$unidad_medida}}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="number" class="form-control " name="valor_unidad" placeholder="Valor Uni." disabled>
                </td>
                <td>
                  <div class="input-group">
                    <input type="number" name="iva" class="form-control " placeholder="% IVA" disabled>
                    <span class="input-group-addon">%</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>       
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" onclick="addConcepto();">Agregar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->