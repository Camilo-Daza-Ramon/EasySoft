<div class="modal fade" id="addSuspension">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> Agregar Suspensión Temporal</h4>
      </div>
      <form id="form_add_suspension" action="" method="post">
        <div class="modal-body">
          <div class="row">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="cliente_id" value="">

            <div class="form-group{{ $errors->has('numero_cedula') ? ' has-error' : '' }} col-md-6">
              <label>*Cedula Cliente:</label>
              <div class="input-group">
                <input type="number" name="numero_cedula" id="numero_cedula" class="form-control" placeholder="Cedula" minlength="4" step="1" value="{{old('numero_cedula')}}" autocomplete="off" required>
                <span class="input-group-btn">
                  <button type="button" class="btn btn-info btn-flat" onclick="buscarCliente()"> <i class="fa fa-search"></i> Buscar</button>
                </span>
              </div>
            </div>

            <div class="col-md-6">
                <p><label for="">Nombre:</label> <span id="nombretxt"></span></p>
                <p><label for="">Total Dias:</label> <span id="diastxt"></span></p>
            </div>

            <div id="panel-formulario" style="display:none;">

                <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12">
                    <label>*Descripcion:</label>
                    <textarea class="form-control" name="descripcion" id="descripcion" required>{{old('descripcion')}}</textarea>		
                </div>

                <div class="form-group{{ $errors->has('mes_inicio') ? ' has-error' : '' }} col-md-4">
                    <label>*Mes Inicio:</label>
                    <input type="month" name="mes_inicio" id="mes_inicio" class="form-control" value="{{old('mes_inicio')}}" autocomplete="off" min="{{date('Y-m', strtotime(date('Y-m-d'). '+ 1 month'))}}" onblur="establecerFechaLimite($('#addSuspension'), $(this));" required>
                </div>

                <div class="form-group{{ $errors->has('fecha_fin') ? ' has-error' : '' }} col-md-4">
                    <label>*Fecha Fin:</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{old('fecha_fin')}}" autocomplete="off" min="{{date('Y-m', strtotime(date('Y-m-d'). '+ 1 month')) . '-01'}}" max="" required>
                </div>

                <div class="form-group{{ $errors->has('fecha_solicitud') ? ' has-error' : '' }} col-md-4">
                    <label>*Fecha Solicitud:</label>
                    <input type="date" name="fecha_solicitud" id="fecha_solicitud" class="form-control" value="{{date('Y-m-d')}}" autocomplete="off" required>
                </div>
            </div>            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" id="btn-enviar-form" class="btn btn-primary" disabled>Crear</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->