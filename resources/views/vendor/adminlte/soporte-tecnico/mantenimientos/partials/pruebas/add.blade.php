<div class="modal fade" id="addPrueba">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Pruebas</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('mantenimientos.pruebas.store', $mantenimiento_id)}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">
        <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">

        <div class="modal-body">
          <div class="row">
            @foreach($pruebas as $prueba)
            <div class="col-md-6">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="prueba[]" id="prueba_{{$prueba->TipoFallaId}}" value="{{$prueba->TipoFallaId}}">
                <label style="text-transform :capitalize;" class="form-check-label" for="prueba_{{$prueba->TipoFallaId}}">
                  {{ mb_strtolower($prueba->DescipcionFallo) }}
                </label>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btn-guardar">Guardar </button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->