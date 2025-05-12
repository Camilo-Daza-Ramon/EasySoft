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
            <h5>MATERIALES PARA LA CONEXION DEL NODO SECUNDARIO</h5>
        </div>    
    </div>
    
    <!-- ROUTER -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ROUTER" class="control-label col-xs-7 col-md-12">Router</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ROUTER" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ROUTER_SERIAL">Serial del Router</label>
            <input type="text" class="form-control" name="ROUTER_SERIAL" placeholder="Serial del Router" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ROUTER_MARCA">Marca del Router</label>
            <input type="text" class="form-control" name="ROUTER_MARCA" placeholder="Marca del Router" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Router</label>
            <input type="file" class="form-control" name="ROUTER_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- ESTRUCTURA METÁLICA PARA PANEL SOLAR -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ESTRUCTURA_PANEL_SOLAR" class="control-label col-xs-7 col-md-12">Estructura Metálica para Panel Solar</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ESTRUCTURA_PANEL_SOLAR" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ESTRUCTURA_PANEL_SOLAR_SERIAL">Serial de la Estructura</label>
            <input type="text" class="form-control" name="ESTRUCTURA_PANEL_SOLAR_SERIAL" placeholder="Serial de la Estructura" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ESTRUCTURA_PANEL_SOLAR_MARCA">Marca de la Estructura</label>
            <input type="text" class="form-control" name="ESTRUCTURA_PANEL_SOLAR_MARCA" placeholder="Marca de la Estructura" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Estructura</label>
            <input type="file" class="form-control" name="ESTRUCTURA_PANEL_SOLAR_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- MÓDULO CONVERTIDOR DE SFP A ETHERNET -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="MODULO_SFP" class="control-label col-xs-7 col-md-12">Módulo Convertidor de SFP a Ethernet</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="MODULO_SFP" class="form-control" placeholder="Cant." value="" min="0" max="5">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_SERIAL1">Serial del Módulo (1)</label>
            <input type="text" class="form-control" name="MODULO_SFP_SERIAL1" placeholder="Serial del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_MARCA1">Marca del Módulo (1)</label>
            <input type="text" class="form-control" name="MODULO_SFP_MARCA1" placeholder="Marca del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Módulo (1)</label>
            <input type="file" class="form-control" name="MODULO_SFP_FOTO1" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- Módulo 2 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_SERIAL2">Serial del Módulo (2)</label>
            <input type="text" class="form-control" name="MODULO_SFP_SERIAL2" placeholder="Serial del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_MARCA2">Marca del Módulo (2)</label>
            <input type="text" class="form-control" name="MODULO_SFP_MARCA2" placeholder="Marca del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Módulo (2)</label>
            <input type="file" class="form-control" name="MODULO_SFP_FOTO2" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- Módulo 3 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_SERIAL3">Serial del Módulo (3)</label>
            <input type="text" class="form-control" name="MODULO_SFP_SERIAL3" placeholder="Serial del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_MARCA3">Marca del Módulo (3)</label>
            <input type="text" class="form-control" name="MODULO_SFP_MARCA3" placeholder="Marca del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Módulo (3)</label>
            <input type="file" class="form-control" name="MODULO_SFP_FOTO3" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- Módulo 4 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_SERIAL4">Serial del Módulo (4)</label>
            <input type="text" class="form-control" name="MODULO_SFP_SERIAL4" placeholder="Serial del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_MARCA4">Marca del Módulo (4)</label>
            <input type="text" class="form-control" name="MODULO_SFP_MARCA4" placeholder="Marca del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Módulo (4)</label>
            <input type="file" class="form-control" name="MODULO_SFP_FOTO4" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- Módulo 5 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_SERIAL5">Serial del Módulo (5)</label>
            <input type="text" class="form-control" name="MODULO_SFP_SERIAL5" placeholder="Serial del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_MARCA5">Marca del Módulo (5)</label>
            <input type="text" class="form-control" name="MODULO_SFP_MARCA5" placeholder="Marca del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Módulo (5)</label>
            <input type="file" class="form-control" name="MODULO_SFP_FOTO5" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- MÓDULO SFP 10 GBPS -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="MODULO_SFP_10GBPS" class="control-label col-xs-7 col-md-12">Módulo SFP 10 GBPS</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="MODULO_SFP_10GBPS" class="form-control" placeholder="Cant." value="" min="0" max="2">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_10GBPS_SERIAL1">Serial del Módulo (1)</label>
            <input type="text" class="form-control" name="MODULO_SFP_10GBPS_SERIAL1" placeholder="Serial del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_10GBPS_MARCA1">Marca del Módulo (1)</label>
            <input type="text" class="form-control" name="MODULO_SFP_10GBPS_MARCA1" placeholder="Marca del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Módulo (1)</label>
            <input type="file" class="form-control" name="MODULO_SFP_10GBPS_FOTO1" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    
    <!-- Módulo 2 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_10GBPS_SERIAL2">Serial del Módulo (2)</label>
            <input type="text" class="form-control" name="MODULO_SFP_10GBPS_SERIAL2" placeholder="Serial del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULO_SFP_10GBPS_MARCA2">Marca del Módulo (2)</label>
            <input type="text" class="form-control" name="MODULO_SFP_10GBPS_MARCA2" placeholder="Marca del Módulo" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Módulo (2)</label>
            <input type="file" class="form-control" name="MODULO_SFP_10GBPS_FOTO2" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- SWITCH DE ACCESO 10GE -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SWITCH_ACCESO" class="control-label col-xs-7 col-md-12">Switch de Acceso 10GE</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SWITCH_ACCESO" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCH_ACCESO_SERIAL">Serial del Switch</label>
            <input type="text" class="form-control" name="SWITCH_ACCESO_SERIAL" placeholder="Serial del Switch" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCH_ACCESO_MARCA">Marca del Switch</label>
            <input type="text" class="form-control" name="SWITCH_ACCESO_MARCA" placeholder="Marca del Switch" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Switch</label>
            <input type="file" class="form-control" name="SWITCH_ACCESO_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- SWITCHES 48+G 4SFP+ INSTANT ON POE 195W -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SWITCH_POE" class="control-label col-xs-7 col-md-12">Switches 48+G 4SFP</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SWITCH_POE" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCH_POE_SERIAL">Serial del Switch</label>
            <input type="text" class="form-control" name="SWITCH_POE_SERIAL" placeholder="Serial del Switch" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCH_POE_MARCA">Marca del Switch</label>
            <input type="text" class="form-control" name="SWITCH_POE_MARCA" placeholder="Marca del Switch" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Switch</label>
            <input type="file" class="form-control" name="SWITCH_POE_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- ANTENA DE TRANSMISIÓN SECTORIAL -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ANTENA_SECTORIAL" class="control-label col-xs-7 col-md-12">Antena de Transmisión Sectorial</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ANTENA_SECTORIAL" class="form-control" placeholder="Cant." value="" min="0" max="4">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_SERIAL1">Serial de la Antena (1)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_SERIAL1" placeholder="Serial de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_MARCA1">Marca de la Antena (1)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_MARCA1" placeholder="Marca de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Antena (1)</label>
            <input type="file" class="form-control" name="ANTENA_SECTORIAL_FOTO1" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    
    <!-- Antena 2 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_SERIAL2">Serial de la Antena (2)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_SERIAL2" placeholder="Serial de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_MARCA2">Marca de la Antena (2)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_MARCA2" placeholder="Marca de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Antena (2)</label>
            <input type="file" class="form-control" name="ANTENA_SECTORIAL_FOTO2" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- Antena 3 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_SERIAL3">Serial de la Antena (3)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_SERIAL3" placeholder="Serial de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_MARCA3">Marca de la Antena (3)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_MARCA3" placeholder="Marca de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Antena (3)</label>
            <input type="file" class="form-control" name="ANTENA_SECTORIAL_FOTO3" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- Antena 4 -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_SERIAL4">Serial de la Antena (4)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_SERIAL4" placeholder="Serial de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ANTENA_SECTORIAL_MARCA4">Marca de la Antena (4)</label>
            <input type="text" class="form-control" name="ANTENA_SECTORIAL_MARCA4" placeholder="Marca de la Antena" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Antena (4)</label>
            <input type="file" class="form-control" name="ANTENA_SECTORIAL_FOTO4" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- FIBRA ÓPTICA ADSS -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="FIBRA_OPTICA" class="control-label col-xs-7 col-md-12">Fibra Óptica 24 Hilos</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="FIBRA_OPTICA" class="form-control" placeholder="Cant." value="" min="0" max="30">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="FIBRA_OPTICA_SERIAL">Serial de la Fibra Óptica</label>
            <input type="text" class="form-control" name="FIBRA_OPTICA_SERIAL" placeholder="Serial de la Fibra Óptica" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="FIBRA_OPTICA_MARCA">Marca de la Fibra Óptica</label>
            <input type="text" class="form-control" name="FIBRA_OPTICA_MARCA" placeholder="Marca de la Fibra Óptica" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Fibra Óptica</label>
            <input type="file" class="form-control" name="FIBRA_OPTICA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- EMPALME FIBRA ÓPTICA -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="EMPALME_FIBRA" class="control-label col-xs-7 col-md-12">Empalme Fibra Óptica</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="EMPALME_FIBRA" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="EMPALME_FIBRA_SERIAL">Serial del Empalme</label>
            <input type="text" class="form-control" name="EMPALME_FIBRA_SERIAL" placeholder="Serial del Empalme" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="EMPALME_FIBRA_MARCA">Marca del Empalme</label>
            <input type="text" class="form-control" name="EMPALME_FIBRA_MARCA" placeholder="Marca del Empalme" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Empalme</label>
            <input type="file" class="form-control" name="EMPALME_FIBRA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- ADECUACIÓN DE TORRE PARA MONTAJE DE EQUIPOS -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="TORRE_MONTAJE" class="control-label col-xs-7 col-md-12">Torre para Montaje de Equipos</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="TORRE_MONTAJE" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="TORRE_MONTAJE_SERIAL">Serial de la Torre</label>
            <input type="text" class="form-control" name="TORRE_MONTAJE_SERIAL" placeholder="Serial de la Torre" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="TORRE_MONTAJE_MARCA">Marca de la Torre</label>
            <input type="text" class="form-control" name="TORRE_MONTAJE_MARCA" placeholder="Marca de la Torre" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Torre</label>
            <input type="file" class="form-control" name="TORRE_MONTAJE_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- ESTRUCTURA METÁLICA PARA EL GABINETE -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ESTRUCTURA_METALICA" class="control-label col-xs-7 col-md-12">Estructura Metálica para el Gabinete</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ESTRUCTURA_METALICA" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ESTRUCTURA_METALICA_SERIAL">Serial de la Estructura</label>
            <input type="text" class="form-control" name="ESTRUCTURA_METALICA_SERIAL" placeholder="Serial de la Estructura" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ESTRUCTURA_METALICA_MARCA">Marca de la Estructura</label>
            <input type="text" class="form-control" name="ESTRUCTURA_METALICA_MARCA" placeholder="Marca de la Estructura" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Estructura</label>
            <input type="file" class="form-control" name="ESTRUCTURA_METALICA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- GABINETE PARA EQUIPOS DE TELECOMUNICACIONES -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="GABINETE_TELECOM" class="control-label col-xs-7 col-md-12">Gabinete para Equipos</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="GABINETE_TELECOM" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="GABINETE_TELECOM_SERIAL">Serial del Gabinete</label>
            <input type="text" class="form-control" name="GABINETE_TELECOM_SERIAL" placeholder="Serial del Gabinete" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="GABINETE_TELECOM_MARCA">Marca del Gabinete</label>
            <input type="text" class="form-control" name="GABINETE_TELECOM_MARCA" placeholder="Marca del Gabinete" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Gabinete</label>
            <input type="file" class="form-control" name="GABINETE_TELECOM_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- SISTEMA FOTOVOLTAICO -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SISTEMA_FOTOVOLTAICO" class="control-label col-xs-7 col-md-12">Sistema Fotovoltaico</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SISTEMA_FOTOVOLTAICO" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_FOTOVOLTAICO_SERIAL">Serial del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_FOTOVOLTAICO_SERIAL" placeholder="Serial del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_FOTOVOLTAICO_MARCA">Marca del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_FOTOVOLTAICO_MARCA" placeholder="Marca del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Sistema</label>
            <input type="file" class="form-control" name="SISTEMA_FOTOVOLTAICO_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- CABLE UT CAT 6 24 AWG -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="CABLE_CAT6" class="control-label col-xs-7 col-md-12">Cable UT CAT 6 24 AWG</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="CABLE_CAT6" class="form-control" placeholder="Mtros de Cable" value="" min="0" max="80">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="CABLE_CAT6_SERIAL">Serial del Cable</label>
            <input type="text" class="form-control" name="CABLE_CAT6_SERIAL" placeholder="Serial del Cable" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="CABLE_CAT6_MARCA">Marca del Cable</label>
            <input type="text" class="form-control" name="CABLE_CAT6_MARCA" placeholder="Marca del Cable" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Cable</label>
            <input type="file" class="form-control" name="CABLE_CAT6_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
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
    <input type="hidden" name="estructura" value="NODO_SECUNDARIO">
    <div class="box-footer">
        <button type="submit" id ="btnAgregar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Agregar</button>
    </div>
</form>
