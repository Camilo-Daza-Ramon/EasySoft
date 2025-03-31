<div class="modal fade" id="addPrueba">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Prueba</h4>
      </div>
      <form action="{{route('tickets.pruebas.store', $ticket->TicketId)}}" method="post">
      {{csrf_field()}}
      <div class="modal-body">           
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Prueba</th>
              <th>Fecha</th>
              <th width="150px">Hora</th>
              <th>Observación</th>                
            </tr>                
          </thead>
          <tbody id="pruebas">
            <tr>
              <td colspan="2">
                <select type="text" class="form-control input-sm" name="prueba" required>
                  <option value="">Elija una opción</option>
                  <?php 
                  foreach ($tipos_pruebas as $tipo_prueba) {
                    foreach ($ticket->prueba as $prueba) {
                      if ($prueba->PruebaId == $tipo_prueba->PruebaId){
                        echo '<option value="'.$tipo_prueba->PruebaId.'" disabled>'.$tipo_prueba->Prueba.'</option>';
                        continue 2;
                      }
                    }

                    echo '<option value="'.$tipo_prueba->PruebaId.'">'.$tipo_prueba->Prueba.'</option>';
                  }
                  ?>                  
                </select>
              </td>
              <td>
                <input type="date" class="form-control input-sm" name="fecha" value="{{date('Y-m-d', strtotime($ticket->FechaApertura))}}" disabled>
              </td>
              <td>
                <input type="time" class="form-control input-sm" name="hora" step="2" required>
              </td>
              <td>                  
                <textarea class="form-control input-sm" name="observacion" required>                    
                </textarea>
              </td>
            </tr>
          </tbody>            
        </table>
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">        
        <button type="submit" class="btn btn-primary"> <i class="fa fa-floppy-o"></i> Crear</button>
      </div>
    </div>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->