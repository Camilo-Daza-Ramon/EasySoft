<div class="row  bg-blue">
    <div class="col-md-12 text-center">
        <h5>MATERIALES PARA LA CONEXION DEL NODO SECUNDARIO</h5>
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
        <label  for="PanelSerial">Serial Controlador</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial del Controlador"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca del Controlador</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca del Controlador"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Controlador</label>
        <select class="form-control" name="estado_equipo_pe" required>
            <option value="">Elija una opcion</option>
            @foreach($estados_otros as $estado_equipo)
                <option>{{$estado_equipo}}</option>
            @endforeach
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
        <label  for="PanelSerial">Serial AccesPoint</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial del AccesPoint"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca del AccesPoint</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca del AccesPoint"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del AccesPoint</label>
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
        <label for="Switch" class="control-label col-xs-7 col-md-12">Switch</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="Switch" class="form-control" placeholder="Cant." value="" min="0" max="2">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="PanelSerial">Serial del Switch</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial del Switch"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca del Switch</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca del Switch"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Switch</label>
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
        <label for="Bateria" class="control-label col-xs-7 col-md-12">Bateria</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="Bateria" class="form-control" placeholder="Cant." value="" min="0" max="1">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="PanelSerial">Serial de la Bateria</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial de la Bateria"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca de la Bateria</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca de la Bateria"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado de la Bateria</label>
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
        <label for="ConversorDCDC" class="control-label col-xs-7 col-md-12">Conversor DC-DC</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="ConversorDCDC" class="form-control" placeholder="Cant." value="" min="0" max="1">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="PanelSerial">Serial del Conversor</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial del Conversor"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca del Conversor</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca del Conversor"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Conversor</label>
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
        <label for="AntenaSectorial" class="control-label col-xs-7 col-md-12">Antena Sectorial</label>
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

<div class="row" style="margin-top: 10px; margin-left: 20px;">
    <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
        <label for="CamaraIP" class="control-label col-xs-7 col-md-12">Camara IP</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="CamaraIP" class="form-control" placeholder="Cant." value="" min="0" max="2">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="PanelSerial">Serial de la Camara</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial de la Camara"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca de la Camara</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca de la Camara"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado de la Camara</label>
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
        <label for="CerboGX" class="control-label col-xs-7 col-md-12">Cerbo GX</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="CerboGX" class="form-control" placeholder="Cant." value="" min="0" max="1">
        </div>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px;">
        <label  for="PanelSerial">Serial del Cerbo</label>
        <input type="text" class="form-control" name="PanelSerial" placeholder="Serial del Cerbo"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; margin-left: 12px;">
        <label  for="PanelMarca">Marca del Cerbo</label>
        <input type="text" class="form-control" name="PanelMarca" placeholder="Marca del Cerbo"value=""  maxlength="20"  autocomplete="off">
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6" style="width: 279.5px; margin-left: 12px;">
        <label>Estado del Cerbo</label>
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
<script>
    document.getElementById('paneles-input').addEventListener('change', function() {
    const cantidad = parseInt(this.value, 10); // Obtener el valor del input
    const mensaje = document.getElementById('accion-mensaje'); // Div para mostrar mensaje

    if (cantidad === 2) {
        // Acción cuando la cantidad sea 2
        mensaje.style.display = 'block';
        mensaje.textContent = 'La cantidad seleccionada es 2. Acción realizada.';
    } else {
        // Ocultar mensaje cuando el valor no sea 2
        mensaje.style.display = 'none';
    }
});

</script>