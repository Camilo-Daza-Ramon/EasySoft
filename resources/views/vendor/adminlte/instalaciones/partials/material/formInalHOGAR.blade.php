<form id="form-instalacion" action="{{route('instalaciones.store')}}" method="post" enctype="multipart/form-data">    
    {{csrf_field()}}
    <input type="hidden" name="cliente_id" value="{{$cliente->ClienteId}}">
    <div class="row">
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
    </div>
    <div class="row  bg-blue">
        <div class="col-md-12 text-center">
            <h5>MATERIALES PARA LA CONEXION DEL HOGAR</h5>
        </div>    
    </div>
    <div class="row" style="margin-top: 10px; margin-left: 20px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="Router" class="control-label col-xs-7 col-md-12">Router</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="Router" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div> 
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="RouterSerial">Router (Serial)</label>
            <input type="text" class="form-control" name="RouterSerial" placeholder="Serial Router"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="RouterMarca">Marca del Router</label>
            <input type="text" class="form-control" name="RouterMarca" placeholder="Marca del Router"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Router</label>
            <input type="file" class="form-control" name="ROUTER_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row" style="margin-top: 10px; margin-left: 20px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR"> 
            <label for="AntenaReceptora" class="control-label col-xs-7 col-md-12">Antena Receptora</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="AntenaSectorial" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
            <label  for="AntenaReceptora_Serial">Serial de la Antena</label>
            <input type="text" class="form-control" name="AntenaReceptora_Serial" placeholder="Serial de la Antena"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="AntenaReceptora_Marca">Marca de la Antena</label>
            <input type="text" class="form-control" name="AntenaReceptora_Marca" placeholder="Marca de la Antena"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Antena</label>
            <input type="file" class="form-control" name="AntenaReceptora_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="form-group col-md-12" id="formulario-adicional">
        <div class="form-group col-md-12">
            @include('adminlte::instalaciones.partials.formGuajira')
            @include('adminlte::instalaciones.partials.evidencia.form')
            @include('adminlte::partials.modal_show_archivos')
        </div>
    </div>
    <div class="form-group{{ $errors->has('observaciones') ? ' has-error' : '' }} col-md-12">
        <label class="control-label">*Observaciones</label>
        <textarea type="number" name="observaciones" class="form-control"></textarea>								
    </div>
    <input type="hidden" name="estructura" value="HOGAR">
    <div class="box-footer">
        <button type="submit" id ="btnAgregar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Agregar</button>
    </div>
</form>
