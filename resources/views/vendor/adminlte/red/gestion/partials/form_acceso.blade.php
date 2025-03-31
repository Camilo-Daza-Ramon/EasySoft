<div class="modal fade" id="formModalDatosDeAcceso">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Nuevos Datos de Acceso</h4>
            </div>
            <div id="">

                <div style="padding: 20px;">

                    <div class="">
                        <label>*Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario">
                    </div>

                    <div class="">
                        <label>*Contraseña</label>
                        <div style="display: flex; gap:5px">
                            <input type="text" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña">
                            <button type="button" onclick="changeVisibilityPassword()" style="padding: 2px 10px;" class="btn btn-primary">
                                <i id="icon-eye-password" class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" id="submit-form-dato-acceso" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>