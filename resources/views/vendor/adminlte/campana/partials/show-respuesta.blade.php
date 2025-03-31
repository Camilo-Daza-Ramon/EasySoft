<div class="modal fade" id="showRespuesta">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-exclamation-circle"></i> Respuesta</h4>
      </div>      
      <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
            <table  class="table table-responsive table-bordered">
             
              <tr>                 
                <th class="bg-gray">
                  <label>Nombre Campaña: </label>
                </th>
                <td>
                  <p id="nombre_campaña_txt"></p>
                </td>
                <th class="bg-gray">
                  <label>Tipo Campaña: </label>
                </th>
                <td>
                  <p id="tipo_campaña_txt"></p>
                </td>                           
              </tr>

              
              
              <tr>   
                
                <th class="bg-gray">
                  <label>Documento:</label>
                </th>
                <td>
                  <p id="documento_txt"></p>
                </td>

                <th class="bg-gray">
                  <label>Correo Cliente: </label>
                </th>
                <td>
                  <p><span id="correo_cliente_txt"></span></p>
                </td>
                
              </tr>

              <tr>
                <th class="bg-gray">
                  <label>Nombre Cliente: </label>
                </th>
                <td colspan="3">
                  <p><span id="nombre_cliete_txt"></span> <span id="apellido_cliete_txt"></span></p>
                </td>
              </tr>
              
              <tr>                 
                <th class="bg-gray">
                  <label>Estado:</label>
                </th>
                <td>
                  <p id="estado1_txt"></p>
                </td>
                
                <th class="bg-gray">
                  <label>Solicitud:</label>
                </th>
                <td>
                  <p id="solicitud_vista"></p>
                </td>
              </tr>              
            </table>                               
          </div>
          
                        
          <div class="col-md-12">
            <table id="respuestas" class="table table-bordered table-responsive">
              <thead>
                <tr class="bg-gray">
                  <th>Nombre Campo</th>
                  <th>Respuesta</th>  
                  <th>Usuario</th>  
                  <th>Fecha Respuesta</th>                    
                </tr>                
              </thead>
              <tbody id="respuestas_txt">
                
              </tbody>                             
            </table>
          </div> 
                                
          <div class="col-md-12">
            <table id="observaciones" class="table table-danger table-responsive">
              <thead class="thead-dark">
                <tr class="bg-gray">
                  <th>#</th>
                  <th>Observacion</th>                      
                  <th>Usuario</th>  
                  <th>Fecha Observacion</th>                    
                </tr>                
              </thead>
              <tbody id="observaciones_txt">
                
              </tbody>                             
            </table>
          </div>
                          
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