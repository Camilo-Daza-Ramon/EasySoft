<div class="modal fade" id="addParadaReloj">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="titulo-agregar-parada-reloj" class="modal-title"><i class="fa fa-plus"></i> Agregar Parada de reloj</h4>
            </div>
            <form id="form-parada-reloj-create" action="{{route('mantenimientos.paradas-reloj.store', $mantenimiento_id)}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hora Inicio</label>
                            <input type="time" class="form-control" name="hora_inicio" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hora Fin</label>
                            <input type="time" class="form-control" name="hora_fin" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Descripción</label>
                            <textarea class="form-control" name="descripcion" required></textarea>
                        </div>
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