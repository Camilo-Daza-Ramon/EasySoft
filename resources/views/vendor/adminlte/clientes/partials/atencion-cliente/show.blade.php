<div class="modal fade" id="showAtencion">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-exclamation-circle"></i> Atencion Cliente</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>Cedula: </label>
              <p id="cedula_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Nombre: </label>
              <p id="nombre_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Municipio:</label>
              <p id="municipio_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Departamento:</label>
              <p id="departamento_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Motivo Atencion: </label>
              <p id="motivo_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Categoria:</label>
              <p id="categoria_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Medio de atención:</label>
              <p id="medio_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Agente o Asesor:</label>
              <p id="agente_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Fecha atencion: </label>
              <p id="fecha_atencion_txt"></p>
            </div>
            <div class="col-md-3">
              <label>Estado: </label>
              <p id="estado_txt"></p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <label>#SOLICITUD</label>
              <p><a href="#" id="solicitud_txt" target="_blank"></a></p>
            </div>

            <div class="col-md-3">
              <label>#CUN</label>
              <p><a href="#" id="cun_txt" target="_blank"></a></p>
            </div>
            <div class="col-md-3">
              <label>#Ticket</label>
              <p><a href="#" id="ticket_txt" target="_blank"></a></p>
            </div>
            <div class="col-md-3">
              <label>#Mantenimiento</label>
              <p><a href="#" id="mantenimiento_txt" target="_blank"></a></p>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label>Descripcion:</label>
              <p id="descripcion_txt"></p>
            </div>
            <div class="col-md-12 comment-text">
              <label>Solucion:</label>
              <p id="solucion_txt"></p>
            </div>
          </div>
      </div>
      <!-- /.modal-content -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
