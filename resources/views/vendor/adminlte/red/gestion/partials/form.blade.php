<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row">

    <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }} col-lg-6">
        <label>*Nombre</label>
        <input value="{{ $plataforma != null ? $plataforma->nombre : '' }}" type="text" id="nombre" class="form-control" name="nombre" placeholder="Nombre" required>
    </div>
    <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }} col-lg-6">
        <label>*Link</label>
        <input value="{{ $plataforma != null ? $plataforma->link : '' }}" type="url" id="link" class="form-control" name="link" placeholder="https://google.com" required>
    </div>


    <div class="form-group col-lg-6">
        <label>*Instrucciones</label>
        <div style="display: flex; gap: 5px;">

            <select id="instrucciones" class="form-control" name="instrucciones" required>
                <option value="">Elija un archivo de instrucciones</option>
                @foreach($instrucciones as $instruccion)
                <option value="{{$instruccion->id}}" {{ ( $plataforma != null && $instruccion->id == $plataforma->instruccion_id ) ? 'selected' : ''}}>
                    {{$instruccion->nombre}}
                </option>
                @endforeach
            </select>
            <h5>o</h5>
            <div style="margin: auto;" data-toggle="modal" data-target="#formModalInstrucciones" data-tipo="show" class="btn btn-default float-bottom btn-sm" data-tipo="agregar">
                <i class="fa fa-plus"></i> Agregar Instrucciones
            </div>
        </div>
    </div>

    <div class="form-group col-lg-6">
        <label>*Datos de Acceso</label>
        <div style="display: flex; gap: 5px;">

            <select id="datos_acceso" class="form-control" name="datos_acceso" required>
                <option value="">Elija un grupo de datos de acceso</option>
                @foreach($datos_acceso as $dato)
                <option value="{{$dato->id}}" {{ ( $plataforma != null && $dato->id == $plataforma->dato_acceso_id ) ? 'selected' : ''}}>
                    <b>Usuario:</b> {{$dato->usuario}}
                </option>
                @endforeach
            </select>
            <h5>o</h5>
            <div style="margin: auto;" data-toggle="modal" data-target="#formModalDatosDeAcceso" data-tipo="show" class="btn btn-default float-bottom btn-sm" data-tipo="agregar">
                <i class="fa fa-plus"></i> Agregar Datos de Acceso
            </div>
        </div>
    </div>

    <div class="form-group col-lg-6">
        <label>*Proyecto</label>

        <select id="proyecto" class="form-control" name="proyecto" required>
            <option value="">Elija un proyecto</option>
            @foreach($proyectos as $proyecto)
            <option value="{{$proyecto->ProyectoID}}" {{ ( $plataforma != null && $proyecto->ProyectoID == $plataforma->proyecto_id ) ? 'selected' : ''}}>
                {{$proyecto->NumeroDeProyecto}}
            </option>
            @endforeach
        </select>
    </div>


    <div class="form-group col-lg-6">
        <label>*Departamento</label>

        <select id="departamento" class="form-control" name="departamento" required>

        </select>
    </div>

    <div class="form-group col-lg-6">
        <label>
            *Municipios
        </label>
        <select multiple id="municipio" class="form-control" name="municipios[]" required>
            @foreach($municipios as $index => $municipio)

            <option value="{{ $municipio->MunicipioId }}" {{ ( $plataforma != null && in_array($municipio->MunicipioId,array_column($plataforma->municipios->toArray(), 'MunicipioId'))) ? 'selected' : ''}}>
                {{ $municipio->NombreMunicipio }}
            </option>
            @endforeach
        </select>
    </div>


</div>
<button type="submit" id="submit-form-plataforma" class="btn btn-labeled btn-primary"><span class="btn-label"></span>{{ $plataforma != null ? 'Editar' : 'Guardar' }}</button>
@include('adminlte::red.gestion.partials.form_instruccion')
@include('adminlte::red.gestion.partials.form_acceso')