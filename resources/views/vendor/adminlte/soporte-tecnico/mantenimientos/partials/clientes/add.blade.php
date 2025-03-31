<div class="modal fade" id="addCliente">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-user-plus"></i> Agregar Cliente</h4>
      </div>
      <form action="{{route('mantenimientos.clientes.store', $mantenimiento_id)}}" method="post">
      <div class="modal-body">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <input type="hidden" name="cliente_id" value="">
          <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">

          <div class="row">
            <div class="form-group col-md-4">
              <label>Tipo</label>
              <select class="form-control" name="tipo">
                <option value="">Elija una opción</option>
                <option value="INDIVIDUAL">INDIVIDUAL</option>
                <option value="MASIVO">MASIVO</option>
              </select>
            </div>
          </div>
          <div class="row hide" id="individual">
            <div class="form-group col-md-4">
              <label>Cedula</label>
              <div class="input-group input-group-sm">
                <input type="number" class="form-control" name="documento" id="documento" required>
                <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat" onclick="buscar_cliente();">Buscar</button>
                </span>
              </div>
            </div>

            <div class="form-group col-md-12">
              <table class="table">
                <thead>
                  <tr>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Municipio</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody id="tabla_clientes">
                  
                </tbody>
              </table>
            </div>
          </div>

          <div class="row hide" id="masivo">
            <div class="col-md-12">
              <label>Cedulas:</label>
              <textarea class="form-control" name="cedulas" placeholder="Ingrese las cedulas separadas por coma ,"></textarea>
            </div>
          </div>          
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">
        <button type="submit" id="btn_guardar" class="btn btn-success" disabled>Agregar</button>
      </div>
    </form>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->