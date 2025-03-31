<div class="modal fade" id="showCuotas">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Cuotas</h4>
      </div>      
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <table  class="table table-bordered table-responsive">
              <head>
                <tr>
                  <td class="bg-gray"><label>Nombre Cliente: </label></td>
                  <td><p id="nombre_cliente_txt"></p></td>
                  <td class="bg-gray"><label>Identificacion: </label></td>
                  <td><p id="identificacion_txt"></p></td> 
                </tr>
                <tr>                 
                  <td class="bg-gray"><label>Contacto: </label></td>
                  <td><p id="contacto_txt"></p></td>                                                             
                  <td class="bg-gray"><label>Correo: </label></td>
                  <td><p id="correo_txt"></p></td>                              
                </tr>
                <tr>                 
                  <td class="bg-gray"><label>Proyecto:</label></td>
                  <td><p id="proyecto_txt"></p></td>                                               
                  <td class="bg-gray"><label>Estado Cliente:</label></td>
                  <td><p id="estado_cliente_txt"></p></td>                              
                </tr>
                <tr>                 
                  <td class="bg-gray"><label>Tarifa:</label></td>
                  <td><p id="tarifa_txt"></p></td>                                                            
                  <td class="bg-gray"><label>Deuda:</label></td>
                  <td><p id="deuda_txt"></p></td>                              
                </tr>
                <tr>
                  <td class="bg-gray"><label>Tipo Descuento:</label></td>
                  <td><p id="tipo_descuento_txt"></p></td> 
                  <td class="bg-gray"><label>Descontado:</label></td>
                  <td><p id="descuento_txt"></p></td>                                           
                </tr>
                <tr>
                  <td class="bg-gray"><label>Valor perdonado:</label></td>
                  <td><p id="valor_perdonar_txt"></p></td>                            
                  <td class="bg-gray"><label>Estado Acuerdo:</label></td>
                  <td><p id="estado_acuerdo_txt"></p></td>                                               
                </tr>
                <tr>
                  <td class="bg-gray"><label>Descripción Acuerdo:</label></td>
                  <td><p id="descripcion_acuerdo_txt"></p></td>                              
                </tr>        
              </head>
            </table>                               
          </div>
          <br>                       
          <div class="col-md-12">
            <table id="cuotas" class="table table-bordered table-responsive">
              <thead>
                <tr class="bg-gray">
                  <th>Fecha Pago</th>  
                  <th>Cuota</th>
                  <th>Valor</th>  
                  <th>Estado</th>                    
                </tr>                
              </thead>
              <tbody id="cuotas_txt">
                
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