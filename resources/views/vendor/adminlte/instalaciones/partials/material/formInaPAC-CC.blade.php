<form id="form-instalacion" action="{{route('instalaciones.store')}}" method="post"enctype="multipart/form-data">
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
            <h5>MATERIALES PARA LA CONEXION DEL PAC/CC</h5>
        </div>    
    </div>
    
    <!-- ANTENA RECEPTORA 5 GHZ -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ANTENA_5GHZ" class="control-label col-xs-7 col-md-12">Antena Receptora 5 GHz</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ANTENA_5GHZ" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_5GHZ_SERIAL">Serial de la Antena</label>
            <input type="text" class="form-control" name="ANTENA_5GHZ_SERIAL" placeholder="Serial de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_5GHZ_MARCA">Marca de la Antena</label>
            <input type="text" class="form-control" name="ANTENA_5GHZ_MARCA" placeholder="Marca de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Antena</label>
            <input type="file" class="form-control" name="ANTENA_5GHZ_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- GABINETE PARA EXTERIORES COMPACTO 5U -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="GABINETE_5U" class="control-label col-xs-7 col-md-12">Gabinete para Exteriores Compacto 5U</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="GABINETE_5U" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="GABINETE_5U_SERIAL">Serial del Gabinete</label>
            <input type="text" class="form-control" name="GABINETE_5U_SERIAL" placeholder="Serial del Gabinete" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="GABINETE_5U_MARCA">Marca del Gabinete</label>
            <input type="text" class="form-control" name="GABINETE_5U_MARCA" placeholder="Marca del Gabinete" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Gabinete</label>
            <input type="file" class="form-control" name="GABINETE_5U_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- WI-FI PUNTO DE ACCESO -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="WIFI_AP" class="control-label col-xs-7 col-md-12">Wi-Fi Punto de Acceso</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="WIFI_AP" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="WIFI_AP_SERIAL">Serial del Punto de Acceso</label>
            <input type="text" class="form-control" name="WIFI_AP_SERIAL" placeholder="Serial del Punto de Acceso" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="WIFI_AP_MARCA">Marca del Punto de Acceso</label>
            <input type="text" class="form-control" name="WIFI_AP_MARCA" placeholder="Marca del Punto de Acceso" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Punto de Acceso</label>
            <input type="file" class="form-control" name="WIFI_AP_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- POSTE DE FIBRA DE VIDRIO -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="POSTE_FIBRA" class="control-label col-xs-7 col-md-12">Poste de Fibra de Vidrio</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="POSTE_FIBRA" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="POSTE_FIBRA_SERIAL">Serial del Poste</label>
            <input type="text" class="form-control" name="POSTE_FIBRA_SERIAL" placeholder="Serial del Poste" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="POSTE_FIBRA_MARCA">Marca del Poste</label>
            <input type="text" class="form-control" name="POSTE_FIBRA_MARCA" placeholder="Marca del Poste" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Poste</label>
            <input type="file" class="form-control" name="POSTE_FIBRA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    <!-- SISTEMA DE PUESTA A TIERRA -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SISTEMA_TIERRA" class="control-label col-xs-7 col-md-12">Sistema de Puesta a Tierra</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SISTEMA_TIERRA" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_TIERRA_SERIAL">Serial del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_TIERRA_SERIAL" placeholder="Serial del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_TIERRA_MARCA">Marca del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_TIERRA_MARCA" placeholder="Marca del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Sistema</label>
            <input type="file" class="form-control" name="SISTEMA_TIERRA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    <!-- ROUTER CON 10 PUERTOS GIGABIT Y UN PUERTO SFP -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ROUTER_10PUERTOS" class="control-label col-xs-7 col-md-12">Router con 10 Puertos Gigabit</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ROUTER_10PUERTOS" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ROUTER_10PUERTOS_SERIAL">Serial del Router</label>
            <input type="text" class="form-control" name="ROUTER_10PUERTOS_SERIAL" placeholder="Serial del Router" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ROUTER_10PUERTOS_MARCA">Marca del Router</label>
            <input type="text" class="form-control" name="ROUTER_10PUERTOS_MARCA" placeholder="Marca del Router" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Router</label>
            <input type="file" class="form-control" name="ROUTER_10PUERTOS_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    <!-- ACCESORIOS DE CONECTIVIDAD -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ACCESORIOS_CONECTIVIDAD" class="control-label col-xs-7 col-md-12">Accesorios de Conectividad</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ACCESORIOS_CONECTIVIDAD" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ACCESORIOS_CONECTIVIDAD_SERIAL">Serial de los Accesorios</label>
            <input type="text" class="form-control" name="ACCESORIOS_CONECTIVIDAD_SERIAL" placeholder="Serial de los Accesorios" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ACCESORIOS_CONECTIVIDAD_MARCA">Marca de los Accesorios</label>
            <input type="text" class="form-control" name="ACCESORIOS_CONECTIVIDAD_MARCA" placeholder="Marca de los Accesorios" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de los Accesorios</label>
            <input type="file" class="form-control" name="ACCESORIOS_CONECTIVIDAD_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    <!-- INSTALACIÓN DE TÓTEM INCLUYENDO SEÑALÉTICA -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="TOTEM_SENALETICA" class="control-label col-xs-7 col-md-12">Instalación de Tótem Incluyendo Señalética</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="TOTEM_SENALETICA" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="TOTEM_SENALETICA_SERIAL">Serial del Tótem</label>
            <input type="text" class="form-control" name="TOTEM_SENALETICA_SERIAL" placeholder="Serial del Tótem" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="TOTEM_SENALETICA_MARCA">Marca del Tótem</label>
            <input type="text" class="form-control" name="TOTEM_SENALETICA_MARCA" placeholder="Marca del Tótem" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Tótem</label>
            <input type="file" class="form-control" name="TOTEM_SENALETICA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    <!-- SISTEMA DE ACONDICIONAMIENTO ELÉCTRICO -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SISTEMA_ELECTRICO" class="control-label col-xs-7 col-md-12">Sistema de Acondicionamiento Eléctrico</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SISTEMA_ELECTRICO" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_ELECTRICO_SERIAL">Serial del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_ELECTRICO_SERIAL" placeholder="Serial del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_ELECTRICO_MARCA">Marca del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_ELECTRICO_MARCA" placeholder="Marca del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Sistema</label>
            <input type="file" class="form-control" name="SISTEMA_ELECTRICO_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
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
    <input type="hidden" name="estructura" value="PAC_CC">
    <div class="box-footer">
        <button type="submit" id ="btnAgregar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Agregar</button>
    </div>
</form>