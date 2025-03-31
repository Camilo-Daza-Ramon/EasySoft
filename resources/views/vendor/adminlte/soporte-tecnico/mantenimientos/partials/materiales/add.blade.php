<div class="modal fade" id="addMaterial">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 id="titulo-agregar-material" class="modal-title"><i class="fa fa-plus"></i> Agregar Material</h4>
            </div>
            <form id="form-material-create" action="{{route('mantenimientos.materiales.store', $mantenimiento_id)}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="mantenimiento_tipo" value="{{$mantenimiento_tipo}}">
                <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Cantidad</label>
                            <input type="number" class="form-control" name="cantidad" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Unidad de Medida</label>
                            <select class="form-control" name="unidad" required>
                                <option value="">Elija una opción</option>
                                @foreach($unidades_medidas as $unidad_medida)
                                    <option value="{{$unidad_medida}}">{{$unidad_medida}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Insumo</label>
                            <select name="insumo" class="form-control" required>
                                <option value="">Elija una opción</option>
                                @foreach($insumos_materiales as $insumo)
                                <option value="{{$insumo->InsumoId}}">{{$insumo->Descripcion}}</option>
                                @endforeach
                            </select>
                           
                        </div>
                        <div class="form-group col-md-12">
                            <label>Descripción</label>
                            <textarea class="form-control" name="descripcion"></textarea>
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