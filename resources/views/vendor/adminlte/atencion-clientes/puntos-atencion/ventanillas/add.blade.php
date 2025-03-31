<div class="modal fade" id="addVentanillaPunto">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Ventanilla</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('puntos-atencion.ventanillas.store', $punto_atencion->id)}}" method="post">
        
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-6">
              <label>Nombre ventanilla</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="form-group col-md-6">
              <label>Área</label>
              <select name="area" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($areas as $area)
                  <option value="{{$area->id}}">{{$area->nombre}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Asesor</label>
              <select name="asesor" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($asesores as $asesor)
                  <option value="{{$asesor->id}}">{{$asesor->name}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btn-archivo">Agregar </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->