<form id="form-instalacion" action="{{route('instalaciones.store')}}" method="post">
    {{csrf_field()}}
    <input type="hidden" name="cliente_id" value="{{$cliente->ClienteId}}">
    <div class="box-body">
        
        <div class="row">
            <div class="form-group{{ $errors->has('serial_ont') ? ' has-error' : '' }} col-xs-12 col-md-4">
                <label>Serial ONT</label>
                <input type="text" class="form-control" name="serial_ont" placeholder="Serial ONT"value=""  maxlength="20"  autocomplete="off">
                <span class="help-block"></span>
            </div>

            <div class="form-group{{ $errors->has('coordenadas') ? ' has-error' : '' }} col-xs-12 col-md-4">
                <label>Coordenadas</label>
                <div class="input-group input-group">
                    <input type="text" name="coordenadas" id="coordenadas" placeholder="Coordenadas" class="form-control"  autocomplete="off">
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-flat" type="button" onclick="getUserPosition()"><i class="fa fa-map-marker"></i> Obtener</button>
                    </span>
                </div>
                <span class="help-block"></span>
            </div>
            <hr width="90%">
        </div>

        <div id="contenido_formulario" style="display:none;">
            @include('adminlte::instalaciones.partials.form')
        
            @include('adminlte::instalaciones.partials.material.form')

            @include('adminlte::instalaciones.partials.evidencia.form')

            <div class="row  bg-blue">
                <div class="col-md-12 text-center">
                    <h5>DATOS DE CONEXION FÍSICA</h5>
                </div>    
            </div>
            <br>

            <div class="row">
            
                <div class="form-group{{ $errors->has('caja') ? ' has-error' : '' }} col-md-3">
                    <label class="control-label col-xs-7 col-md-12">*Caja</label>
                    <div class="col-xs-5 col-md-12 mb-2">
                        <input type="number" name="caja" class="form-control" placeholder="Cant." value="" min="0"  >
                    </div>
                </div>

                <div class="form-group{{ $errors->has('puerto') ? ' has-error' : '' }} col-md-3">
                    <label class="control-label col-xs-7 col-md-12">*Puerto</label>
                    <div class="col-xs-5 col-md-12 mb-2">
                        <input type="number" name="puerto" class="form-control" placeholder="Cant." value="" min="0"  >
                    </div>
                </div>

                <div class="form-group{{ $errors->has('sp_splitter') ? ' has-error' : '' }} col-md-3">
                    <label class="control-label col-xs-7 col-md-12">*SP Spliter</label>
                    <div class="col-xs-5 col-md-12 mb-2">
                        <input type="number" name="sp_splitter" class="form-control" placeholder="Cant." value="" min="0"  >
                    </div>
                </div>


                <div class="form-group{{ $errors->has('ss_splitter') ? ' has-error' : '' }} col-md-3">
                    <label class="control-label col-xs-7 col-md-12">*SS Spliter</label>
                    <div class="col-xs-5 col-md-12 mb-2">
                        <input type="number" name="ss_splitter" class="form-control" placeholder="Cant." value="" min="0"  >
                    </div>
                </div>

                <div class="form-group{{ $errors->has('tarjeta') ? ' has-error' : '' }} col-md-3">
                    <label class="control-label col-xs-7 col-md-12">*Tarjeta</label>
                    <div class="col-xs-5 col-md-12 mb-2">
                        <input type="number" name="tarjeta" class="form-control" placeholder="Cant." value="" min="0"  >
                    </div>
                </div>

                <div class="form-group{{ $errors->has('modulo') ? ' has-error' : '' }} col-md-3">
                    <label class="control-label col-xs-7 col-md-12">*Modulo</label>
                    <div class="col-xs-5 col-md-12 mb-2">
                        <input type="number" name="modulo" class="form-control" placeholder="Cant." value="" min="0"  >
                    </div>
                </div>

            

                <hr width="90%">

                <div class="form-group{{ $errors->has('observaciones') ? ' has-error' : '' }} col-md-12">
                    <label class="control-label">*Observaciones</label>
                    <textarea type="number" name="observaciones" class="form-control"></textarea>								
                </div>

            </div>

        </div>
    </div>			
    <div class="box-footer">
        <button type="submit" id ="btnAgregar" class="btn btn-success pull-right" disabled><i class="fa fa-floppy-o"></i>  Agregar</button>
    </div>

</form>