<div class="box-body table-responsive">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th class="bg-gray">*Nombre de la Infraestructura</th>
                <td colspan="3">
                    <input type="text" class="form-control" placeholder="Nombre" name="nombre" value="{{ (Session::has('errors')) ? old('nombre', '') :''}}" autocomplete="off" required>
                    {!! $errors->first('nombre', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>

            <tr>
                <th class="bg-gray">*Latitud</th>
                <td>
                    <input type="text" class="form-control" placeholder="Latitud" name="latitud" value="{{ (Session::has('errors')) ? old('latitud', '') :''}}" autocomplete="off" required>
                    {!! $errors->first('latitud', '<p class="help-block">:message</p>') !!}
                </td>

                <th class="bg-gray">*Longitud</th>
                <td>
                    <input type="text" class="form-control" placeholder="Longitud" name="longitud" value="{{ (Session::has('errors')) ? old('longitud', '') :''}}" autocomplete="off" required>
                    {!! $errors->first('longitud', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>

            <tr>
                <th class="bg-gray">
                    *Departamento
                </th>
                <td>
                    <select id="departamento" class="form-control" name="departamento">
                        <option value="">Elija un departamento</option>
                        @foreach($departamentos as $departamento)
                        <option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
                        @endforeach
                    </select>
                </td>

                <th class="bg-gray">
                    *Municipio
                </th>
                <td>
                    <select id="municipio" class="form-control" name="municipio">
                        <option value="">Elija un municipio</option>
                    </select>
                </td>

            </tr>

            <tr>
                <th class="bg-gray">
                    *Categoría
                </th>
                <td>
                    <select id="categoria" class="form-control" name="categoria">
                        <option value="">Elija una categoría</option>
                        @foreach($categorias as $cat)
                        <option value="{{$cat}}">{{$cat}}</option>
                        @endforeach
                    </select>
                </td>

                <th class="bg-gray">
                    *Tipos de Categoría
                </th>
                <td>
                    <select id="tipo_categoria" class="form-control" name="tipo_categoria">
                        <option value="">Elija un tipo de categoría</option>
                        @foreach($tipos_categoria as $tcat)
                        <option value="{{$tcat}}">{{$tcat}}</option>
                        @endforeach
                    </select>
                </td>

            </tr>

            <tr>
                <th class="bg-gray">
                    *Dirección
                </th>
                <td>
                    <input type="text" class="form-control" placeholder="Dirección" name="direccion" value="{{ (Session::has('errors')) ? old('direccion', '') :''}}" autocomplete="off" required>
                    {!! $errors->first('direccion', '<p class="help-block">:message</p>') !!}
                </td>


                <th class="bg-gray">
                    *Estado
                </th>
                <td>
                    <select id="estados" class="form-control" name="estado" {{isset($infraestructura) ? '' : 'disabled' }} >
                        <option value="">Elija un estado</option>
                        @foreach($estados as $estado)
                        <option value="{{$estado}}" {{!isset($infraestructura) && $estado === 'ACTIVO' ? 'selected' : ''}}>{{$estado}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <th class="bg-gray">
                    Proveedor
                </th>
                <td>
                    <select id="tipo_categoria" class="form-control" name="proveedor">
                        <option value="">Elija un proveedor</option>
                        @foreach($proveedores as $proveedor)
                        <option value="{{$proveedor->id}}">{{$proveedor->identificacion}} - {{$proveedor->nombre}}</option>
                        @endforeach
                    </select>
                </td>
                <th class="bg-gray">
                    Infraestructura Padre
                </th>
                <td>
                    <select id="infraestructura_id" class="form-control" name="infraestructura_id">
                        <option value="">Elija una Infraestructura Padre</option>
                        @foreach($infras as $infra)
                        <option value="{{$infra->id}}">{{$infra->nombre}}</option>
                        @endforeach
                    </select>
                </td>

            </tr>

            <tr>
                <th class="bg-gray">Descripción</th>
                <td colspan="3">
                    <textarea class="form-control" name="descripcion" rows="4"></textarea>
                </td>
            </tr>

            <tr>
                <th class="bg-gray">Datos Ubicación</th>
                <td colspan="3">
                    <textarea class="form-control" name="datos_ubicacion" rows="4"></textarea>
                </td>
            </tr>
        </tbody>
    </table>


</div>