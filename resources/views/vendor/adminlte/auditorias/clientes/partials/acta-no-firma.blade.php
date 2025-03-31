<div class="col-md-3">
    <a target="_blank" href="{{ route('auditorias.clientes.generar.acta', ['id' => $cliente->ClienteId ]) }}">
        <button type="button" class="btn btn-block btn-primary">
            <i class="fa fa-eject" aria-hidden="true"></i>
            Generar Acta de No Firma
        </button>
    </a>
</div>

<div class="col-md-3">
    <button id="btn-cargar-acta" data-toggle="modal" data-target="#loadActaNoFirma" type="button" class="btn btn-block btn-warning">
        <i class="fa fa-upload" aria-hidden="true"></i>
        Cargar Acta de No Firma
    </button>
</div>



<div class="modal fade" id="loadActaNoFirma">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-blue">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Acta de No firma</h4>
            </div>
            <form id="form-acta-no-firma-upload" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="contrato_id" value="{{$contratos[0]->id}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12" id="archivo-area">
                            <label>Archivo</label>
                            <input required type="file" class="form-control" name="archivo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-subir-acta-no-firma">Agregar </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->