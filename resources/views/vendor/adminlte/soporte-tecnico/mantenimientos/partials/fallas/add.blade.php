<div class="modal fade" id="addFallo">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Fallos</h4>
      </div>
      <form action="{{route('mantenimientos.fallas.store', $mantenimiento_id)}}" method="post">
        {{csrf_field()}}
        <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">
        <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">
        <div class="modal-body">
          <div class="row">
            @foreach($fallos as $fallo)
            <div class="col-md-6">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="fallos[]" id="fallo_{{$fallo->TipoFallaId}}" value="{{$fallo->TipoFallaId}}">
                <label style="text-transform :capitalize;" class="form-check-label" for="fallo_{{$fallo->TipoFallaId}}">
                  {{ mb_strtolower($fallo->DescipcionFallo) }}
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