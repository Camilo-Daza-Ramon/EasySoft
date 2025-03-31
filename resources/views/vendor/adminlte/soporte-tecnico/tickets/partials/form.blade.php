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
  <div class="form-group col-md-12">
    <label>Descripción ticket: </label>
    <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
</div>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>Prueba</th>
      <th width="150px">Hora</th>
      <th>Observación</th>                
    </tr>                
  </thead>
  <tbody id="pruebas">
    
  </tbody>
  <tfoot>  
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
        <input type="time" class="form-control input-sm" name="hora" id="hora" step="2">
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