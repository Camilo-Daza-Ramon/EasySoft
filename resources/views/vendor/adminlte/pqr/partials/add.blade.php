<div class="modal fade" id="addPqr">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Crear PQR</h4>
      </div>
      <form id="form-pqr" action="{{route('pqr.store')}}" method="post">
        {{csrf_field()}}
        <div class="modal-body" id="form-ticket">
          <div class="row">
            <div class="form-group col-md-4">
              <label>*Canal de Atención: </label>
              <select name="canal_atencion" id="canal_atencion_pqr" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($ticket_medios_atencion as $medio_atencion)

                  @if(isset($atencion))
                    @if($atencion->medio_atencion == 'LLAMADA' && $medio_atencion->TipoEntradaTicket == 'TK01')
                      <option value="{{$medio_atencion->TipoEntradaTicket}}" selected>{{$medio_atencion->Descripcion}}</option>
                    @elseif($atencion->medio_atencion == 'PUNTO FISICO' && $medio_atencion->TipoEntradaTicket == 'TK06')
                      <option value="{{$medio_atencion->TipoEntradaTicket}}" selected>{{$medio_atencion->Descripcion}}</option>
                    @else
                      <option value="{{$medio_atencion->TipoEntradaTicket}}">{{$medio_atencion->Descripcion}}</option>
                    @endif
                  @else
                    <option value="{{$medio_atencion->TipoEntradaTicket}}">{{$medio_atencion->Descripcion}}</option>
                  @endif

                @endforeach
              </select>
            </div>

            <div class="form-group col-md-4">
              <label>*Fecha estimada de cierre:</label>
              <input type="date" name="fecha_estimada_cierre" class="form-control" value="{{date('Y-m-d', strtotime(date('Y-m-d').' + 10 days'))}}" required>
            </div>

            <div class="form-group col-md-4">
              <label>*Fecha límite:</label>
              <input type="date" name="fecha_limite_pqr" class="form-control" value="{{date('Y-m-d', strtotime(date('Y-m-d').' + 21 days'))}}" required>
            </div>

            <div class="form-group col-md-4">
              <label>*Tipo: </label>
              <select name="tipo_solicitud" id="tipo_solicitud" class="form-control" required>
                <option value="">Elija una opción</option>
                <option value="FELICITACION">FELICITACION</option>
                <option value="PETICION">PETICION</option>
                <option value="QUEJA">QUEJA</option>
                <option value="RECLAMO">RECLAMO</option>
                <option value="RECURSO DE REPOSICION">RECURSO DE REPOSICION</option>
                <option value="SUGERENCIA">SUGERENCIA</option>
              </select>
            </div>

            <div class="form-group col-md-4">
              <label>*Tipo Evento: </label>
              <select name="tipo_evento" id="tipo_evento" class="form-control" required>
                <option value="">Elija una opción</option>
                @foreach($tipos_eventos as $tipo_evento)
                <option value="{{$tipo_evento->IdTipoEvento}}">{{$tipo_evento->TipoEvento}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>*Prioridad: </label>
              <select name="prioridad" id="prioridad_pqr" class="form-control" required>
                <option value="">Elija una opción</option>
                <option value="1">1 - Completa pérdida del servicio de internet.</option>
                <option value="2">2 - Intermitencia o Lentitud.</option>
                <option value="3">3 - Aclaración a dudas sobre la prestación del servicio.</option>
                <option value="4">4 - Solicitud de traslado por parte del cliente.</option>
              </select>
            </div>

            <div class="form-group col-md-2">
              <label>*identificación:</label>
              <input type="number" name="identificacion" class="form-control" value="{{(isset($atencion))? $atencion->identificacion : old('cedula')}}" required>
            </div>
            <div class="form-group col-md-2">
              <label>*Celular:</label>
              <input type="number" name="celular" class="form-control" value="{{(isset($solicitud))? str_replace(['(',')',' ','-'],'',$solicitud->celular) : old('celular')}}" required>
            </div>
            <div class="form-group col-md-4">
              <label>*Nombre:</label>
              <input type="text" name="nombre_pqr" class="form-control" value="{{(isset($atencion))? $atencion->nombre : old('nombre')}}" required>
            </div>
            <div class="form-group col-md-4">
              <label>*Correo:</label>
              <input type="email" name="correo_pqr" class="form-control" value="{{(isset($solicitud))? $solicitud->correo : old('correo')}}" required>
            </div>
            <div class="form-group col-md-12">
              <label>*Hechos: </label>
              <textarea name="hechos" id="hechos" class="form-control" required>{{(isset($atencion))? $atencion->descripcion : old('hechos')}}</textarea>
            </div>
            <div class="form-group col-md-12">
              <label>Solución: </label>
              <textarea name="solucion" id="solucion_pqr" class="form-control"></textarea>
            </div>
          </div>
        </div> 
        <!-- /.modal-content -->
        <div class="modal-footer">
          <button type="submit" id="crear_pqr" class="btn btn-primary"> <i class="fa fa-floppy-o" id="icon-guardar"></i> Crear</button>
        </div>
      </form>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
