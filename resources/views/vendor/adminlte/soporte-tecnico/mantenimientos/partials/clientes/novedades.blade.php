<div class="modal fade" id="addNovedades">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-user-plus"></i> Generador de Novedades Masivas</h4>
      </div>
      <form id="form-novedades-masivas" action="{{route('novedades.agregar_masivas')}}" method="post">
        <div class="modal-body">
            {{csrf_field()}}
            <input type="hidden" name="mantenimiento" value="{{$mantenimiento->MantId}}">
                        
            <div class="row">
              <div class="form-group col-md-4">
                <label>Conceptos</label>
              <select class="form-control " name="concepto" required="true">
                <option value="Ajustes por falta de servicio" selected>Ajustes por falta de servicio</option>
              </select>
              </div>

              <div class="form-group col-md-4">
                <label>Fecha Inicio</label>
                <input class="form-control" type="datetime-local" name="fecha_inicio" value="{{date('Y-m-d\TH:i:s', strtotime($fecha_hora_inicio))}}" required="true" >
              </div>
              <div class="form-group col-md-4">
                <label>Fecha Fin</label>
                <input class="form-control" type="datetime-local" name="fecha_fin" value="{{date('Y-m-d\TH:i:s', strtotime($fecha_hora_fin))}}" required="true">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-3">
                <label>Cantidad</label>
                <input class="form-control" type="number" name="cantidad" value="{{$indisponibilidad['minutos']}}" required="true" step="0.001">
              </div>
              <div class="form-group col-md-3">
                <label>Unidad de medida</label>
                <select class="form-control" name="unidad_medida" required="true">
                  <option value="MINUTOS" selected>MINUTOS</option>                
              </select>
              </div>
              <div class="form-group col-md-3">
                <label>Valor. Uni.</label>
                <input class="form-control" type="number" name="valor_unidad" value="0" required="true" step="0.01">
              </div>
              <div class="form-group col-md-3">
                <label>% IVA</label>
                <input type="number" name="iva" class="form-control " placeholder="% IVA" value="0" required>
              </div>

              <div class="form-group col-md-4">
                <label>*Forma de aplicación</label>
                <select name="forma_aplicacion" class="form-control" required>
                  <option value="">Elija una opción</option>
                  <option value="DESCONTAR" selected>DESCONTAR</option>
                  <option value="COBRAR">COBRAR</option>
                </select>
              </div>

            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label>Cedulas</label>
                <textarea class="form-control" name="cedulas" required="true" placeholder="Separadas por coma ,">{{$cedulas}}</textarea>
              </div>
            </div>    
                     
        </div>
        <!-- /.modal-content -->
        <div class="modal-footer">
          <button type="submit" id="btn_masivas_crear" class="btn btn-success"> <i class="fa fa-floppy-o"></i> Generar</button>
        </div>
      </form>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->