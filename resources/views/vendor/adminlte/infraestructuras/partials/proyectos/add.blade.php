<div class="modal fade" id="addFallo">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Asociar Proectos</h4>
      </div>
      <form action="{{route('infraestructuras.proyectos.store', $infraestructura->id)}}" method="post">
        {{csrf_field()}}
        <div class="modal-body">
          <div class="row">
            @foreach($proyectos as $proyecto)
            <div class="col-md-6">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="proyectos[]" id="proyecto_{{$proyecto->ProyectoID}}" value="{{$proyecto->ProyectoID}}">
                <label style="text-transform :capitalize;" class="form-check-label" for="proyecto_{{$proyecto->ProyectoID}}">
                  {{ mb_strtolower($proyecto->NumeroDeProyecto) }}
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