<div class="modal fade" id="sendMail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-envelope-o"></i> Enviar Contrato y Actas</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('contratos.send')}}" method="POST">
          {{csrf_field()}}
          <input type="hidden" name="contrato_id" value="{{$contrato->id}}">

          <div class="row">            
            
            <div class="form-group col-md-7">
              <label>Elija los documentos que desea enviar:</label>

              @foreach($contrato->archivos as $archivo)
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="archivos[]" value="{{$archivo->archivo}}">
                    {{$archivo->nombre}}
                  </label>
                </div>
              @endforeach
            </div>

            <div class="form-group{{ $errors->has('correo') ? ' has-error' : '' }} col-md-7">
              <label>Correo: </label>
              <div class="input-group" >
                <select class="form-control" name="correo" required>
                  <option value="">Elija una opción</option>
                  <option value="{{$contrato->cliente->CorreoElectronico}}">
                    {{$contrato->cliente->CorreoElectronico}}
                  </option>
                </select>
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-info btn-flat">Enviar</button>
                </span>
              </div>
            </div>
          </div>     
        </form>
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer bg-gray">
        <p class="text-left">Se enviará al correo electronico del cliente los documentos que se hayan seleccionado de la lista.</p>
      </div>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->