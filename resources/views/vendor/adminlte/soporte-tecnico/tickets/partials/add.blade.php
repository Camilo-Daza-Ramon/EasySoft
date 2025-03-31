<div class="modal fade" id="addTicket">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Ticket</h4>
      </div>  
      <form id="form_ticket" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="modal-body" id="form-ticket">
          <div class="row">
            <div class="form-group col-md-4"> 
              <label>Canal de Atención: </label>
              <select name="canal_atencion" id="canal_atencion" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($ticket_medios_atencion as $medio_atencion)
                <option value="{{$medio_atencion->TipoEntradaTicket}}">{{$medio_atencion->Descripcion}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>Tipo de Falla: </label>
              <select name="tipo_falla" id="tipo_falla" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($tipos_fallas as $tipo_falla)
                <option value="{{$tipo_falla->TipoFallaId}}">{{$tipo_falla->DescipcionFallo}}</option>
                @endforeach              
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>Prioridad: </label>
              <select name="prioridad" id="prioridad" class="form-control" required>
                <option value="">Elija una opción</option>                
                <option value="1">Completa pérdida del servicio de internet.</option>
                <option value="2">Intermitencia o Lentitud.</option>
                <option value="3">Aclaración a dudas sobre la prestación del servicio.</option>
              </select>
            </div>
            <div class="form-group col-md-4"> 
              <label for="file">*Evidencia final del servicio:</label>
              <input type="file" accept="image/*" class="form-control" name="ImagenTicket" required>              </div>
              <div class="form-group col-md-12" id="descripcion_ticket_">
                <label>*Descripción Ticket: </label>
                <textarea  name="descripcion" id="descripcion_ticket" class="form-control" required></textarea>
              </div>
              <div class="form-group col-md-12" id="descripcion_pqr" hidden>  
                <label>*Hechos: </label>
                <textarea  name="hechos" id="hechos_pqr" class="form-control" ></textarea> 
              </div>
          </div>

          <div class="row"  id="datos_pqr" hidden>                        
            <div class="form-group col-md-12">
              <label>*Solución: </label>
              <textarea  name="solucion" id="solucion" class="form-control" ></textarea> 
            </div>
            <input hidden type="text" name="departamento" id="departamento_pqr" value="">
            <input hidden type="text" name="municipio" id="municipio_pqr" value="">
            <input hidden type="text" name="nombre_pqr" id="nombre_pqr" value="">


          </div>
          
          <table class="table">
            <thead>
              <tr>  
                <th>#</th>             
                <th>*Prueba</th>
                <th width="150px">*Hora</th>
                <th>*Observación</th>   
                <th>Acciones</th>            
              </tr>                
            </thead>
            <tbody id="pruebas">
              
            </tbody>
            <tfoot >  
              <tr>
                <td colspan="2">
                  <select type="text" class="form-control input-sm" name="prueba">
                    <option value="">Elija una opción</option>
                    @foreach($tipos_pruebas as $tipo_prueba)
                      <option value="{{$tipo_prueba->PruebaId}}">{{$tipo_prueba->Prueba}}</option>
                    @endforeach
                  </select>
                </td>
                <td> 
                  <input type="time" class="form-control input-sm" name="hora" id="hora" min="00:00:00" max="23:59:59" step="1">
                </td>
                <td>                  
                  <textarea class="form-control input-sm" name="observacion" id="observacion">                    
                  </textarea>
                </td>               
                <td>
                  <button type="button" class="btn btn-success btn-xs" onclick="addPrueba();">
                    <i class="fa fa-plus"></i>
                  </button>
                </td>
              </tr>
            </tfoot>            
          </table>
        </div> 
        
        <!-- /.modal-content --> 
        <div class="modal-footer" id="footer_ticket"> 
          <div class="checkbox pull-left"> 
            <label>
              <input type="checkbox" name="escalar_mantenimiento" id="agendar_visita" value="1" disabled> Agendar Visita            
            </label>
            <label>
              <input type="checkbox" name="crear_pqr" id="crear_pqr" value="1"> Crear PQR
            </label>
          </div>
          <button type="submit" id="crear_ticket" name="crear_ticket" class="btn btn-primary" disabled> <i class="fa fa-floppy-o" id="icon-guardar"></i> Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>
