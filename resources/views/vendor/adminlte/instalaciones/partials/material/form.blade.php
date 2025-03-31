

<div class="row  bg-blue">
    <div class="col-md-12 text-center">
        <h5>MATERIAL USADO EN LA INSTALACIÓN</h5>
    </div>    
</div>

<div class="row">
    <br>
    <div class="form-group{{ $errors->has('conector') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Conector SC/APC	</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="conector" class="form-control" placeholder="Cant." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('pigtail') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Conector PigTail SC/APC	</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="pigtail" class="form-control" placeholder="Cant." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('cinta_bandit') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Cinta Bandit</label>

        <div class="col-xs-5 col-md-12 mb-2">
            <div class="input-group">
                <input type="number" name="cinta_bandit" class="form-control" placeholder="Cant." value="" min="0"  required>
                <span class="input-group-addon">CM</span>
            </div>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('hebilla') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Hebilla</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="hebilla" class="form-control" placeholder="Cant. Heb." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('gancho_poste') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Gancho Poste</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="gancho_poste" class="form-control" placeholder="Cant." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('gancho_pared') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Gancho Pared</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="gancho_pared" class="form-control" placeholder="Cant." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('tornillo') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Tornillo 1/4</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="tornillo" class="form-control" placeholder="Cant. Tor." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('roseta') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Rosetas</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="roseta" class="form-control" placeholder="Cant. Ros." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('patch_cord_fibra') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Patch Cord FIBRA</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="patch_cord_fibra" class="form-control" placeholder="Cant. Pas." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('patch_cord_utp') ? ' has-error' : '' }} col-md-3">
        <label class="control-label col-xs-7 col-md-12">Patch Cord UTP</label>
        <div class="col-xs-5 col-md-12 mb-2">
            <input type="number" name="patch_cord_utp" class="form-control" placeholder="Cant. Pas." value="" min="0"  required>
        </div>
        <span class="help-block"></span>
    </div>


</div>

<div class="row">

    <div class="col-md-6 border-right">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>Retenciones</h4>        
            </div>
            <div class="form-group{{ $errors->has('tipo_retencion') ? ' has-error' : '' }} col-md-6 col-xs-8">
                <label>Tipo</label>
                <select class="form-control" name="tipo_retencion" required>
                    <option value="">Elija una opcion</option>
                    @foreach($tipos_retenciones as $tipo_retencion)
                        <option>{{$tipo_retencion}}</option>
                    @endforeach
                </select>
                <span class="help-block"></span>
            </div>

            <div class="form-group{{ $errors->has('cantidad_retenciones') ? ' has-error' : '' }} col-md-6 col-xs-4">
                <label>Cantidad	</label>
                <input type="number" name="cantidad_retenciones" class="form-control" value="" min="0"  required>
                <span class="help-block"></span>
            </div>

            <hr width="90%">
        </div>
    </div>

    <div class="col-md-6 border-right">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>Correa de Amarre</h4>
                
            </div>
            <div class="form-group{{ $errors->has('tipo_correa') ? ' has-error' : '' }} col-md-6 col-xs-8">
                <label>Tipo</label>
                <select class="form-control" name="tipo_correa" required>
                    <option value="">Elija una opcion</option>
                    @foreach($tipos_correas as $tipo_correa)
                        <option>{{$tipo_correa}}</option>
                    @endforeach
                </select>
                <span class="help-block"></span>
            </div>

            <div class="form-group{{ $errors->has('cant_correa_amarre') ? ' has-error' : '' }} col-md-6 col-xs-4">
                <label>Cantidad	</label>
                <input type="number" name="cant_correa_amarre" class="form-control" value="" min="0"  required>
                <span class="help-block"></span>
            </div>

            <hr width="90%">
        </div>
    </div>

</div>


<div class="row">
    <div class="col-md-6 border-right">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>Chazos</h4>        
            </div>
            <div class="form-group{{ $errors->has('tipo_chazo') ? ' has-error' : '' }} col-md-6 col-xs-8">
                <label>Tipo</label>
                <select class="form-control" name="tipo_chazo" required>
                    <option value="">Elija una opcion</option>
                    @foreach($tipos_chazos as $tipo_chazo)
                        <option>{{$tipo_chazo}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group{{ $errors->has('cant_chazo') ? ' has-error' : '' }} col-md-6 col-xs-4">
                <label>Cantidad	</label>
                <input type="number" name="cant_chazo" class="form-control" value="" min="0"  required>
            </div>
            <hr width="90%">
        </div>
    </div>
    <div class="col-md-6 border-right">
        <div class="row">
            <div class="col-md-12 text-center">
                <h4 style="margin-bottom: 0px;">Fibra Optica Drop de 1 hilo</h4>
                <span class="total_fibra">0 mts</span>
            </div>
            <div class="form-group{{ $errors->has('fibra_drop_desde') ? ' has-error' : '' }} col-md-6 col-xs-6">
                <label>Desde</label>
                <div class="input-group">
                    <input type="number" name="fibra_drop_desde" class="form-control" placeholder="Desde" value="" min="1" step="0.01" onkeyup="total_fibra()" required>
                    <span class="input-group-addon">Mts</span>
                </div>            
            </div>

            <div class="form-group{{ $errors->has('fibra_drop_hasta') ? ' has-error' : '' }} col-md-6 col-xs-6">
                <label>Hasta	</label>
                <div class="input-group">
                    <input type="number" name="fibra_drop_hasta" class="form-control" placeholder="Hasta" value="" min="0" step="0.01" onkeyup="total_fibra()" required>
                    <span class="input-group-addon">Mts</span>
                </div>
            </div>
            <hr width="90%">
        </div>
    </div>
</div>






