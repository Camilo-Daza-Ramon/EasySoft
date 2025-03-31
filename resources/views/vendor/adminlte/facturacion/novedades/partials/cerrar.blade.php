<div class="modal fade" id="cerrarNovedad">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-calendar-check-o"></i> Cerrar Novedad</h4>
      </div>
      <form action="{{route('novedades.cerrar')}}" method="post">     
      <div class="modal-body">
        
        <input type="hidden" id="novedad_id" name="novedad_id" value="">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
          <div class="col-md-4">
            <label>Nombre</label>
            <p id="nombre_txt"></p>
          </div>
          <div class="col-md-4">
            <label>Municipio</label>
            <p id="municipio_txt"></p>
          </div>
          <div class="col-md-4">
            <label>Proyecto</label>
            <p id="proyecto_txt"></p>
          </div>          
        </div>

        <div class="row">

          <div class="form-group col-md-4">
            <label>Fecha Inicio</label>
            <input type="datetime-local" id="fecha_inicio_txt" class="form-control" name="fecha_inicio" disabled>
          </div>

          <div class="form-group col-md-4">
            <label>Fecha Fin: </label>
            <input type="datetime-local" class="form-control" name="fecha_fin" id="fecha_fin" required>
          </div>

          <div class="form-group col-md-4">
            <label>#Ticket: </label>
            <input type="number" class="form-control" id="ticket" name="ticket" placeholder="# Ticket">
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <table class="table">
              <thead>
                <tr>
                  <th>CONCEPTO</th>
                  <th>CANTIDAD</th>
                  <th>VALOR UNIDAD</th>
                  <th>IVA</th>
                  <th>ESTADO</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td id="concepto_txt"></td>
                  <td id="cantidad_txt"></td>
                  <td id="valor_txt"></td>
                  <td id="iva_txt"></td>
                  <td id="estado_txt"></td>
                </tr>
              </tbody>
              
            </table>
          </div>
        </div>
               
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" onclick="return validar_fecha_cierre();">Cerrar</button>
      </div>
      </form> 
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->