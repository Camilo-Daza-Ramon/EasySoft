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
            <h5>MATERIALES PARA LA CONEXION DEL NODO PRIMARIO</h5>
        </div>    
    </div>
    
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="GABINETES" class="control-label col-xs-7 col-md-12">Gabinetes de Piso 40U</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="GABINETES" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div> 
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="GABINETESSERIAL">Serial del Gabinete</label>
            <input type="text" class="form-control" name="GABINETESSERIAL" placeholder="Serial del Gabinete"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="GABINETEMARCA">Marca del Gabinete</label>
            <input type="text" class="form-control" name="GABINETEMARCA" placeholder="Marca del Gabinete"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Gabinete</label>
            <input type="file" class="form-control" name="FOTOGABINETE" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  >
            <span class="help-block"></span>
        </div> 
    </div>
    
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="MODULOSFP" class="control-label col-xs-7 col-md-12">Modulo de SFP a Cobre RJ45</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="MODULOSFP" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div> 
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="MODULOSFPSERIAL">Serial del Modulo</label>
            <input type="text" class="form-control" name="MODULOSFPSERIAL" placeholder="Serial del Modulo"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="MODULOSFPMARCA">Marca del Modulo</label>
            <input type="text" class="form-control" name="MODULOSFPMARCA" placeholder="Marca del Modulo"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Modulo</label>
            <input type="file" class="form-control" name="FOTOMODULO" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  >
            <span class="help-block"></span>
        </div> 
    </div>
    
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SWITCHIP" class="control-label col-xs-7 col-md-12">Switch IP RED DMOS</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SWITCHIP" class="form-control" placeholder="Cant." value="" min="0" max="2">
            </div>
        </div>
    
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="SWITCHIPSERIAL1">Serial del Switch (1)</label>
            <input type="text" class="form-control" name="SWITCHIPSERIAL1" placeholder="Serial del Switch"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="SWITCHIPMARCA1">Marca del Switch (1)</label>
            <input type="text" class="form-control" name="SWITCHIPMARCA1" placeholder="Marca del Switch"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Switch (1)</label>
            <input type="file" class="form-control" name="FOTOSWITCHIP1" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  >
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCHIPSERIAL2">Serial del Switch (2)</label>
            <input type="text" class="form-control" name="SWITCHIPSERIAL2" placeholder="Serial del Switch" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCHIPMARCA2">Marca del Switch (2)</label>
            <input type="text" class="form-control" name="SWITCHIPMARCA2" placeholder="Marca del Switch" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Switch (2)</label>
            <input type="file" class="form-control" name="FOTOSWITCHIP" value="" accept="image/png, image/gif, image/jpeg, image/jpg" >
            <span class="help-block"></span>
        </div>
    </div>
    
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="MODULOSFT10" class="control-label col-xs-7 col-md-12">Modulo SFP 10 GBPS</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="MODULOSFT10" class="form-control" placeholder="Cant." value="" min="0" max="4">
            </div>
        </div>
    
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="MODULOSFT10SERIAL1">Serial del Modulo (1)</label>
            <input type="text" class="form-control" name="MODULOSFT10SERIAL1" placeholder="Serial del Modulo"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="MODULOSFT10MARCA1">Marca del Modulo (1)</label>
            <input type="text" class="form-control" name="MODULOSFT10MARCA1" placeholder="Marca del Modulo"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Modulo (1)</label>
            <input type="file" class="form-control" name="FOTOMODULOSFT101" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  >
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULOSFT10SERIAL2">Serial del Modulo (2)</label>
            <input type="text" class="form-control" name="MODULOSFT10SERIAL2" placeholder="Serial del Modulo"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULOSFT10MARCA2">Marca del Modulo (2)</label>
            <input type="text" class="form-control" name="MODULOSFT10MARCA2" placeholder="Marca del Modulo"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Modulo (2)</label>
            <input type="file" class="form-control" name="FOTOMODULOSFT102" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  >
            <span class="help-block"></span>
        </div>
    </div>
    
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULOSFT10SERIAL3">Serial del Modulo (3)</label>
            <input type="text" class="form-control" name="MODULOSFT10SERIAL3" placeholder="Serial del Modulo" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULOSFT10MARCA3">Marca del Modulo (3)</label>
            <input type="text" class="form-control" name="MODULOSFT10MARCA3" placeholder="Marca del Modulo" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Modulo (3)</label>
            <input type="file" class="form-control" name="FOTOMODULOSFT103" value="" accept="image/png, image/gif, image/jpeg, image/jpg" >
            <span class="help-block"></span>
        </div>
    </div>
    
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULOSFT10SERIAL4">Serial del Modulo (4)</label>
            <input type="text" class="form-control" name="MODULOSFT10SERIAL4" placeholder="Serial del Modulo" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MODULOSFT10MARCA4">Marca del Modulo (4)</label>
            <input type="text" class="form-control" name="MODULOSFT10MARCA4" placeholder="Marca del Modulo" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Modulo (4)</label>
            <input type="file" class="form-control" name="FOTOMODULOSFT104" value="" accept="image/png, image/gif, image/jpeg, image/jpg" >
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- BANDEJA PARA RACK DE SERVIDORES -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="BANDEJARACK" class="control-label col-xs-7 col-md-12">Bandeja para Rack</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="MODULOSFT10" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="BANDEJARACK_SERIAL">Serial de la Bandeja</label>
            <input type="text" class="form-control" name="BANDEJARACK_SERIAL" placeholder="Serial de la Bandeja" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="BANDEJARACK_MARCA">Marca de la Bandeja</label>
            <input type="text" class="form-control" name="BANDEJARACK_MARCA" placeholder="Marca de la Bandeja" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Bandeja</label>
            <input type="file" class="form-control" name="BANDEJARACK_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- BANDEJA PARA ORGANIZACIÓN DE FIBRA ÓPTICA -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="BANDEJAFIBRA" class="control-label col-xs-7 col-md-12">Bandeja para Fibra Optica</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="BANDEJAFIBRA" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="BANDEJAFIBRA_SERIAL">Serial de la Bandeja</label>
            <input type="text" class="form-control" name="BANDEJAFIBRA_SERIAL" placeholder="Serial de la Bandeja" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="BANDEJAFIBRA_MARCA">Marca de la Bandeja</label>
            <input type="text" class="form-control" name="BANDEJAFIBRA_MARCA" placeholder="Marca de la Bandeja" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Bandeja</label>
            <input type="file" class="form-control" name="BANDEJAFIBRA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- ORGANIZADOR HORIZONTAL -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="ORGANIZADOR" class="control-label col-xs-7 col-md-12">Organizador Horizontal 1U</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="ORGANIZADOR" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ORGANIZADOR_SERIAL">Serial del Organizador</label>
            <input type="text" class="form-control" name="ORGANIZADOR_SERIAL" placeholder="Serial del Organizador" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="ORGANIZADOR_MARCA">Marca del Organizador</label>
            <input type="text" class="form-control" name="ORGANIZADOR_MARCA" placeholder="Marca del Organizador" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Organizador</label>
            <input type="file" class="form-control" name="ORGANIZADOR_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- SISTEMA HÍBRIDO ELÉCTRICO-SOLAR -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SISTEMA" class="control-label col-xs-7 col-md-12">Sistema Hibrido Electrico</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SISTEMA" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_SERIAL">Serial del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_SERIAL" placeholder="Serial del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_MARCA">Marca del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_MARCA" placeholder="Marca del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Organizador</label>
            <input type="file" class="form-control" name="SISTEMA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- AIRE ACONDICIONADO (Cantidad: 2) -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="AIRE" class="control-label col-xs-7 col-md-12">Aire Acondicionado</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="AIRE" class="form-control" placeholder="Cant." value="" min="0" max="2">
            </div>
        </div>
    
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="AIRE_SERIAL1">Serial del Aire (1)</label>
            <input type="text" class="form-control" name="AIRE_SERIAL1" placeholder="Serial del Aire"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="AIRE_MARCA1">Marca del Aire (1)</label>
            <input type="text" class="form-control" name="AIRE_MARCA1" placeholder="Marca del Aire"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Aire (1)</label>
            <input type="file" class="form-control" name="FOTO_AIRE1" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  >
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="AIRE_SERIAL2">Serial del Aire (2)</label>
            <input type="text" class="form-control" name="AIRE_SERIAL2" placeholder="Serial del Aire" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="AIRE_MARCA2">Marca del Aire (2)</label>
            <input type="text" class="form-control" name="AIRE_MARCA2" placeholder="Marca del Aire" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Aire (2)</label>
            <input type="file" class="form-control" name="FOTO_AIRE_MARCA2" value="" accept="image/png, image/gif, image/jpeg, image/jpg" >
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- MULTITOMA HORIZONTAL -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="MULTITOMA" class="control-label col-xs-7 col-md-12">Multitoma Horizontal</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="MULTITOMA" class="form-control" placeholder="Cant." value="" min="0" max="2">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MULTITOMA_SERIAL">Serial del Multitoma</label>
            <input type="text" class="form-control" name="MULTITOMA_SERIAL" placeholder="Serial del Multitoma" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="MULTITOMA_MARCA">Marca del Multitoma</label>
            <input type="text" class="form-control" name="MULTITOMA_MARCA" placeholder="Marca del Multitoma" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Organizador</label>
            <input type="file" class="form-control" name="MULTITOMA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- FIBRA ÓPTICA ADSS -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="FIBRAOPTICA" class="control-label col-xs-7 col-md-12">Fibra Óptica 24 Hilos</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="FIBRAOPTICA" class="form-control" placeholder="Cant." value="" min="0" max="1000">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="FIBRAOPTICA_SERIAL">Serial de la Fibra Óptica</label>
            <input type="text" class="form-control" name="FIBRAOPTICA_SERIAL" placeholder="Serial de la Fibra Óptica" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="FIBRAOPTICA_MARCA">Marca de la Fibra Óptica</label>
            <input type="text" class="form-control" name="FIBRAOPTICA_MARCA" placeholder="Marca de la Fibra Óptica" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía de la Fibra Óptica</label>
            <input type="file" class="form-control" name="FIBRAOPTICA_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- SISTEMA DE ADMINISTRACIÓN -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SISTEMA_ADMIN" class="control-label col-xs-7 col-md-12">Sistema de Administración</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SISTEMA_ADMIN" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_ADMIN_SERIAL">Serial del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_ADMIN_SERIAL" placeholder="Serial del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_ADMIN_MARCA">Marca del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_ADMIN_MARCA" placeholder="Marca del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Sistema</label>
            <input type="file" class="form-control" name="SISTEMA_ADMIN_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    <!-- SISTEMA DE SEGURIDAD Y CONTROL DE ACCESO -->
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SISTEMA_SEGURIDAD" class="control-label col-xs-7 col-md-12">Sistema de Seguridad y Control de Acceso</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SISTEMA_SEGURIDAD" class="form-control" placeholder="Cant." value="" min="0" max="1">
            </div>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_SEGURIDAD_SERIAL">Serial del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_SEGURIDAD_SERIAL" placeholder="Serial del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SISTEMA_SEGURIDAD_MARCA">Marca del Sistema</label>
            <input type="text" class="form-control" name="SISTEMA_SEGURIDAD_MARCA" placeholder="Marca del Sistema" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Sistema</label>
            <input type="file" class="form-control" name="SISTEMA_SEGURIDAD_FOTO" accept="image/png, image/gif, image/jpeg, image/jpg">
            <span class="help-block"></span>
        </div>
    </div>
    
    {{-- SWITCH 24G 4SFP (Cantidad: 2)  --}}
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-3" id="form-nodo-PAC-CC-HOGAR">
            <label for="SWITCH24G" class="control-label col-xs-7 col-md-12">Switch 24G 4SFP</label>
            <div class="col-xs-5 col-md-12 mb-2">
                <input type="number" name="SWITCH24G" class="form-control" placeholder="Cant." value="" min="0" max="2">
            </div>
        </div>
    
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="SWITCH24G_SERIAL1">Serial del Switch (1)</label>
            <input type="text" class="form-control" name="SWITCH24G_SERIAL1" placeholder="Serial del Switch"value=""  maxlength="20"  autocomplete="off" >
            <span class="help-block"></span>
        </div>
        <div class="form-group col-xs-12 col-md-4" style="width: 279.5px; ">
            <label  for="SWITCH24G_MARCA1">Marca del Switch (1)</label>
            <input type="text" class="form-control" name="SWITCH24G_MARCA1" placeholder="Marca del Switch"value=""  maxlength="20"  autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografia del Switch (1)</label>
            <input type="file" class="form-control" name="FOTO_SWITCH24G1" value="" accept="image/png, image/gif, image/jpeg,  image/jpg"  >
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row" style="margin-top: 10px; margin-left: 10px;">
        <div class="form-group col-md-6" style="width: 279.5px; margin-left: 23px;">
            <!-- Si este div no tiene contenido, puedes eliminarlo o agregar contenido aquí -->
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCH24G_SERIAL2">Serial del Switch (2)</label>
            <input type="text" class="form-control" name="SWITCH24G_SERIAL2" placeholder="Serial del Switch" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label for="SWITCH24G_MARCA2">Marca del Switch (2)</label>
            <input type="text" class="form-control" name="SWITCH24G_MARCA2" placeholder="Marca del Switch" value="" maxlength="20" autocomplete="off">
            <span class="help-block"></span>
        </div>
        <div class="form-group col-md-6" style="width: 279.5px; ">
            <label>Fotografía del Switch (2)</label>
            <input type="file" class="form-control" name="FOTO_SWITCH24G2" value="" accept="image/png, image/gif, image/jpeg, image/jpg" >
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
    <input type="hidden" name="estructura" value="NODO_PRIMARIO">
    <div class="box-footer">
        <button type="submit" id ="btnAgregar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Agregar</button>
    </div>
</form>
