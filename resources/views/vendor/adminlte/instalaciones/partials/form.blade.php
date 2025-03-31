
<div class="row">

    <div class="col-md-12 text-center">
        <h4>Tipo de Equipo utilizado por el cliente para la conexion</h4>
    </div>

    <div class="form-group{{ $errors->has('tipo_equipo') ? ' has-error' : '' }} col-md-3">
        <label>*Tipo</label>
        <select class="form-control" name="tipo_equipo" required>
            <option value="">Elija una opcion</option>
            @foreach($tipos_equipos as $tipo_equipo)
                <option>{{$tipo_equipo}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('marca_equipo') ? ' has-error' : '' }} col-md-3 col-xs-6">
        <label>*Marca </label>
        <input type="text" class="form-control" name="marca_equipo" placeholder="Marca" value="" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('serial_equipo') ? ' has-error' : '' }} col-md-3 col-xs-6">
        <label>*Serial</label>
        <input type="text" class="form-control" name="serial_equipo" placeholder="Serial" value="" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('estado_equipo') ? ' has-error' : '' }} col-md-3 col-xs-12">
        <label>*Estado</label>
        <select class="form-control" name="estado_equipo" required>
            <option value="">Elija una opcion</option>
            @foreach($estados_otros as $estado_equipo)
                <option>{{$estado_equipo}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
    </div>
    <hr width="90%">
</div>

<div class="row">

    <div class="col-md-12 text-center">
        <h4>Tipo de Conexion Electrica</h4>
    </div>

    <div class="form-group{{ $errors->has('tipo_conexion') ? ' has-error' : '' }} col-md-3">
        <label>*Tipo Conexion</label>
        <select class="form-control" name="tipo_conexion" required>
            <option value="">Elija una opcion</option>
            @foreach($tipos_conexion as $tipo_conexion)
                <option>{{$tipo_conexion}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('tipo_proteccion') ? ' has-error' : '' }} col-md-3">
        <label>*Tipo Proteccion</label>
        <select class="form-control" name="tipo_proteccion" required>
            <option value="">Elija una opcion</option>
            @foreach($tipos_pelectrica as $tipo_pelectrica)
                <option>{{$tipo_pelectrica}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('marca_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6">
        <label>*Marca </label>
        <input type="text" class="form-control" name="marca_equipo_pe" placeholder="Marca" value="" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('estado_equipo_pe') ? ' has-error' : '' }} col-md-3 col-xs-6">
        <label>*Estado</label>
        <select class="form-control" name="estado_equipo_pe" required>
            <option value="">Elija una opcion</option>
            @foreach($estados_otros as $estado_equipo)
                <option>{{$estado_equipo}}</option>
            @endforeach
        </select>
        <span class="help-block"></span>
    </div> 
</div>

<hr width="90%">

<div class="row">   

    <div class="form-group{{ $errors->has('cantidad_equipos') ? ' has-error' : '' }} col-md-3">
        <label>*Cantidad de Equipos Conectados</label>
        <input type="number" class="form-control" name="cantidad_equipos" placeholder="Cant. Equi." value="" min="1" max="99" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('vel_bajada') ? ' has-error' : '' }} col-md-2 col-xs-6">
        <label>*Vel. Bajada</label>
        <input type="number" class="form-control" name="vel_bajada" placeholder="Vel. Bajada"value="" step="0.01" required>
        <span class="help-block"></span>
    </div>
    <div class="form-group{{ $errors->has('vel_subida') ? ' has-error' : '' }} col-md-2 col-xs-6">
        <label>*Vel. Subida</label>
        <input type="number" class="form-control" name="vel_subida" placeholder="Vel. Subida" value="" step="0.01" required>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('servicio_activo') ? ' has-error' : '' }} col-md-3 col-xs-12">
        <label>*Servicio queda activo?</label>
        <select class="form-control" name="servicio_activo" required>
            <option value="">Elija una opcion</option>
            <option value="SI">SI</option>
            <option value="NO">NO</option>
        </select>
        <span class="help-block"></span>
    </div>

    <div class="form-group{{ $errors->has('cumple_velocidad') ? ' has-error' : '' }} col-md-3 col-xs-12">
        <label>*Cumple Velocidad Contratada?</label>
        <select class="form-control" name="cumple_velocidad" required>
            <option value="">Elija una opcion</option>
            <option value="SI">SI</option>
            <option value="NO">NO</option>
        </select>
        <span class="help-block"></span>
    </div>
</div>	