<div class="modal fade" id="archivoAdd">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-file-upload"> </i> Subir Archivo</h4>
            </div>
            <form action="" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        {{csrf_field()}}

                        <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-md-12">
                            <label>*Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{old('nombre')}}" required>
                            {!! $errors->first('nombre', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group{{ $errors->has('archivo') ? ' has-error' : '' }} col-md-12">
                            <label>*Archivo</label>
                            <input type="file" class="form-control" name="archivo">
                            {!! $errors->first('archivo', '<p class="help-block">:message</p>') !!}
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>

                        <button type="submit" class="btn btn-primary"> <i></i> Guardar</button>
                    </div>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>