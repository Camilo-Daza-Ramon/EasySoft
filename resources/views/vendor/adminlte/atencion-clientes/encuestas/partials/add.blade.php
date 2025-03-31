<div class="modal fade" id="addEncuesta">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Encuesta</h4>
      </div>
      <form id="form-archivo-upload" action="{{route('encuestas.store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">        
        {{csrf_field()}}
        <div class="modal-body">          
          <div class="form-group col-md-12">
            <label>Descripcion</label>
            <textarea class="form-control" name="descripcion" placeholder="pregunta" required></textarea>
          </div>
          <div class="form-group col-md-12">
            <label>Respuestas</label>
            <textarea class="form-control" name="respuestas" placeholder="agregar respuestas separadas por coma" required></textarea>
          </div>          
          <div class="row">  
            <div class="form-group col-md-6">
              <label>Archivo de audio</label>
              <input type="file" class="form-control" name="archivo" accept=".mp3,audio/*">
            </div>
            <div class="form-group col-md-6">
              <label>Estado</label>
              <select class="form-control" name="estado" required>
                <option value="">Elija una opción</option>
                <option value="ACTIVA">ACTIVA</option>
                <option value="INACTIVA">INACTIVA</option>   
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