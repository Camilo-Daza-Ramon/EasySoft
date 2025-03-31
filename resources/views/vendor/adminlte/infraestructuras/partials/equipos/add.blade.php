<div class="modal fade" id="addEquipos">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Equipo</h4>
            </div>
            <form action="{{route('infraestructuras.equipos.store', $infraestructura->id)}}" method="post">
                {{csrf_field()}}

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>*Código del Insumo</label>
                            <select id="codigo" class="form-control" name="codigo">
                                <option value="">Elija un insumo</option>
                                @foreach($insumos as $insumo)
                                <option value="{{$insumo->InsumoId}}">{{$insumo->Codigo}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>*Serial</label>
                            <input type="text" class="form-control" name="serial" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>IP Gestión</label>
                            <input type="text" class="form-control" name="ip_gestion" >
                        </div>
                        <div class="form-group col-md-6">
                            <label>Usuario</label>
                            <input type="text" class="form-control" name="usuario" >
                        </div>
                        <div class="form-group col-md-6">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" disabled id="btn-guardar">Guardar </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->