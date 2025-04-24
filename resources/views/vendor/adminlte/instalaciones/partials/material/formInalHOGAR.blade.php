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
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="RouterSerial">Router (Serial)</label>
        <input type="text" class="form-control" name="RouterSerial" placeholder="Serial Router"value=""  maxlength="20"  autocomplete="off" >
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="RouterMarca">Marca del Router</label>
        <input type="text" class="form-control" name="RouterMarca" placeholder="Marca del Router"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Router</label>
        <select class="form-control" name="estado_equipo_pe" required>
            <option value="">Elija una opcion</option>
            @foreach($estados_otros as $estado_equipo)
                <option>{{$estado_equipo}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
    </div> 
</div>
<div class="row" style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR"> 
        <label for="AntenaSectorial" class="control-label col-xs-7 col-md-12">Antena Receptora</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="AntenaSectorial" class="form-control" placeholder="Cant." value="" min="0" max="4">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="PanelSerial">Serial de la Antena</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial de la Antena"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca de la Antena</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca de la Antena"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado de la Antena</label>
        <select class="form-control" name="estado_equipo_pe" required>
            <option value="">Elija una opcion</option>
            @foreach($estados_otros as $estado_equipo)
                <option>{{$estado_equipo}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
    </div> 
</div>
<div class="form-group col-md-12">
    @include('adminlte::instalaciones.partials.formGuajira')
    @include('adminlte::instalaciones.partials.evidencia.form')
    @include('adminlte::partials.modal_show_archivos')
    @include('adminlte::instalaciones.partials.firma.add')
</div>
<div class="form-group{{ $errors->has('observaciones') ? ' has-error' : '' }} col-md-12">
    <label class="control-label">*Observaciones</label>
    <textarea type="number" name="observaciones" class="form-control"></textarea>								
</div>