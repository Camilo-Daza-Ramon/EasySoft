<div class="modal fade" id="editSuspension">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> Editar Suspensión Temporal</h4>
      </div>
      <form id="form_edit_suspension" action="" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        <div class="modal-body">
          <div class="row">

            <div class="form-group{{ $errors->has('cedula') ? ' has-error' : '' }} col-md-6">
              <label>Cedula:</label>
              <input type="number" name="cedula" id="cedula" class="form-control" value="" autocomplete="off" readonly>
            </div>

            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-6">
              <label>Nombre:</label>
              <input type="text" name="nombre" id="nombre" class="form-control" value="" autocomplete="off" readonly>
            </div>            

            <div class="form-group{{ $errors->has('mes_inicio') ? ' has-error' : '' }} col-md-4">
              <label>*Mes Inicio:</label>
              <input type="month" name="mes_inicio" id="mes_inicio" class="form-control" value="{{old('mes_inicio')}}" autocomplete="off" min="{{date('Y-m', strtotime(date('Y-m-d'). '+ 1 month'))}}" onblur="establecerFechaLimite($('#editSuspension'), $(this));" required>
            </div>

            <div class="form-group{{ $errors->has('fecha_fin') ? ' has-error' : '' }} col-md-4">
              <label>*Fecha Fin:</label>
              <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="" min="{{date('Y-m', strtotime(date('Y-m-d'). '+ 1 month')) . '-01'}}" autocomplete="off" required>
            </div>

            <div class="form-group{{ $errors->has('fecha_solicitud') ? ' has-error' : '' }} col-md-4">
              <label>*Fecha Solicitud:</label>
              <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control" value="" autocomplete="off" required readonly>
            </div>

            <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
              <label>*Descripcion:</label>
              <textarea class="form-control" name="descripcion" id="descripcion" required></textarea>		
            </div>

            <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-6">
              <label>*Estado:</label>
              <select name="estado" id="estado" class="form-control" required>
                <option value="">Elija un estado</option>
                <option value="ACTIVA">ACTIVA</option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="FINALIZADA">FINALIZADA</option>

              </select>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Actualizar </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->