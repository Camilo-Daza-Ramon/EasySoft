<div class="row  bg-blue">
    <div class="col-md-12 text-center">
        <h5>MATERIALES PARA LA CONEXION DEL PAC/CC</h5>
    </div>    
</div>

<div class="row" style="margin-top: 10px; margin-left: 20px;">

    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR"> 
        <label for="Paneles" class="control-label col-xs-7 col-md-12" style="width: 279.5px;">Paneles Solares	</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="Paneles" class="form-control" placeholder="Cant." value="" min="0" max="2">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="PanelSerial">Panel Serial</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial del Panel"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca del Panel</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca del Panel"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR" style="width: 279.5px; margin-left: 12px;"> 
        <label for="PotenciaPaneles" class="control-label col-xs-7 col-md-12" style="padding-left:0px">Potencia de los Paneles</label>
        <select class="form-control" name="PotenciaPaneles" id="potencia-panel" required>
            <option value="">Elija la potencia del panel</option>
            <option value=580 >580</option>
            <option value=630 >630</option>
        </select>
    </div>
</div>

<div class="row" style="margin-top: 10px; margin-left: 20px;">

    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
        <label for="ControladorSolar" class="control-label col-xs-7 col-md-12">Controlador Solar</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="ControladorSolar" class="form-control" placeholder="Cant." value="" min="0" max="1">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="ControladorSerial">Serial Controlador</label>
        <input type="text" class="form-control" name="ControladorSerial" placeholder="Serial del Controlador"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="ControladorMarca">Marca del Controlador</label>
        <input type="text" class="form-control" name="ControladorMarca" placeholder="Marca del Controlador"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Controlador</label>
        <select class="form-control" name="estado_controlador" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
        </select>
        <span class="help-block"></span>
    </div>
</div>

<div class="row"  style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
        <label for="AccesPoint" class="control-label col-xs-7 col-md-12">AccesPoint</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="AccesPoint" class="form-control" placeholder="Cant." value="" min="0" max="1">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="AccesPointSerial">Serial AccesPoint</label>
        <input type="text" class="form-control" name="AccesPointSerial" placeholder="Serial del AccesPoint"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="AccesPointMarca">Marca del AccesPoint</label>
        <input type="text" class="form-control" name="AccesPointMarca" placeholder="Marca del AccesPoint"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del AccesPoint</label>
        <select class="form-control" name="estado_acces_point" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
        </select>
        <span class="help-block"></span>
    </div>
</div>

<div class="row" style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR"> 
        <label for="SwitchPOE" class="control-label col-xs-7 col-md-12">Switch POE</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="SwitchPOE" class="form-control" placeholder="Cant." value="" min="0" max="2">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="SwitchPOESerial">Serial del Switch POE</label>
        <input type="text" class="form-control" name="SwitchPOESerial" placeholder="Serial del Switch POE"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="SwitchPOEMarca">Marca del Switch POE</label>
        <input type="text" class="form-control" name="SwitchPOEMarca" placeholder="Marca del Switch POE"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Switch POE</label>
        <select class="form-control" name="estado_switch_poe" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
        </select>
        <span class="help-block"></span>
    </div>
</div>

<div class="row" style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
        <label for="Bateria" class="control-label col-xs-7 col-md-12">Bateria</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="Bateria" class="form-control" placeholder="Cant." value="" min="0" max="1">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="BateriaSerial">Serial de la Bateria</label>
        <input type="text" class="form-control" name="BateriaSerial" placeholder="Serial de la Bateria"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="BateriaMarca">Marca de la Bateria</label>
        <input type="text" class="form-control" name="BateriaMarca" placeholder="Marca de la Bateria"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado de la Batería</label>
        <select class="form-control" name="estado_bateria" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
        </select>
        <span class="help-block"></span>
    </div> 
</div>

<div class="row" style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR"> 
        <label for="AntenaSectorial" class="control-label col-xs-7 col-md-12">Antena Sectorial</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="AntenaSectorial" class="form-control" placeholder="Cant." value="" min="0" max="4">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="AntenaSectorialSerial">Serial de la Antena</label>
        <input type="text" class="form-control" name="AntenaSectorialSerial" placeholder="Serial de la Antena"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="AntenaSectorialMarca">Marca de la Antena</label>
        <input type="text" class="form-control" name="AntenaSectorialMarca" placeholder="Marca de la Antena"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado de la Antena</label>
        <select class="form-control" name="estado_antena_sectorial" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
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
        <label  for="AntenaSectorialSerial">Serial de la Antena</label>
        <input type="text" class="form-control" name="AntenaSectorialSerial" placeholder="Serial de la Antena"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="AntenaSectorialMarca">Marca de la Antena</label>
        <input type="text" class="form-control" name="AntenaSectorialMarca" placeholder="Marca de la Antena"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado de la Antena</label>
        <select class="form-control" name="estado_antena_receptora" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
        </select>
        <span class="help-block"></span>
    </div> 
</div>

<div class="row" style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
        <label for="CamaraIP" class="control-label col-xs-7 col-md-12">Camara IP</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="CamaraIP" class="form-control" placeholder="Cant." value="" min="0" max="2">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="CamaraIPSerial">Serial de la Camara</label>
        <input type="text" class="form-control" name="CamaraIPSerial" placeholder="Serial de la Camara"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="CamaraIPMarca">Marca de la Camara</label>
        <input type="text" class="form-control" name="CamaraIPMarca" placeholder="Marca de la Camara"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado de la Cámara</label>
        <select class="form-control" name="estado_camara" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
        </select>
        <span class="help-block"></span>
    </div>
</div>
<div class="row" style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
        <label for="Inversor" class="control-label col-xs-7 col-md-12">Inversor</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="Inversor" class="form-control" placeholder="Cant." value="" min="0" max="2">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="InversorSerial">Serial del Inversor</label>
        <input type="text" class="form-control" name="InversorSerial" placeholder="Serial del Inversor"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="InversorMarca">Marca del Inversor</label>
        <input type="text" class="form-control" name="InversorMarca" placeholder="Marca del Inversor"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Inversor</label>
        <select class="form-control" name="estado_inversor" required>
            <option value="">Elija una opción</option>
            <option value="Conectado">Conectado</option>
            <option value="No Conectado">No Conectado</option>
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