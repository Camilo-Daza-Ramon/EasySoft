{{csrf_field()}}
<table class="table">
    <tbody>

    @if($accion == 'EDITAR')
        <tr>
            <th class="bg-gray">CUN</th>
            <td>
                <h4>{{$pqr->CUN}}</h4>
            </td>

            <th class="bg-gray">Estado</th>
            <td>
                <select name="estado" class="form-control" required>
                    <option value="">Elija una Opción</option>
                    @foreach($estados as $estado)
                        <option value="{{$estado}}" {{($pqr->Status == $estado)? 'selected' : ''}}>{{$estado}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    @endif

        <tr>
            <th class="bg-gray">Cedula</th>
            <td>
                <input type="number" class="form-control" name="cedula" value="{{$pqr->IdentificacionCliente}}" required>
            </td>
            
            <th class="bg-gray">Nombre</th>
            <td><input type="text" class="form-control" name="nombre" value="{{$pqr->NombreBeneficiario}}" required></td>
        </tr>

        <tr>
            <th class="bg-gray">Correo</th>
            <td> <input type="email" class="form-control" name="correo" value="{{$pqr->CorreoElectronico}}" required></td>

            <th class="bg-gray">Celular</th>
            <td>
                <input type="text" class="form-control" name="celular" placeholder="Celular" value="{{$pqr->NumeroDeCelular}}" required>
            </td>

        </tr>

        <tr>

            <th class="bg-gray">Telefono</th>
            <td>
                <input type="text" class="form-control" name="telefono" placeholder="Telefono" value="{{$pqr->NumeroDeTelefono}}">
            </td>
            <th class="bg-gray">Direccion</th>
            <td><input type="text" name="direccion" class="form-control" placeholder="Dirección" value="{{$pqr->DireccionNotificacion}}"> </td>


        </tr>

        <tr>
            
            <th class="bg-gray">Departamento</th>
            <td>
                <select name="departamento" id="departamento" class="form-control" required>
                    <option value="">Elija un departamento</option>
                    @foreach($departamentos as $departamento)
                        @if(!empty($pqr->MunicipioId))
                            <option value="{{$departamento->DeptId}}" {{($departamento->DeptId == $pqr->municipio->DeptId)? 'selected' : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                        @else
                            <option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
                        @endif
                    @endforeach
                </select>
            </td>

            <th class="bg-gray">Municipio</th>
            <td>
                <select name="municipio" id="municipio" class="form-control" required></select>
            </td>
            
        </tr>

        <tr>
            <th class="bg-gray">Canal de Atención</th>
            <td>
                <select name="canal_atencion" id="canal_atencion" class="form-control" required>
                    <option value="">Elija una opción</option>
                    @foreach($medios_atencion as $medio_atencion)
                    <option value="{{$medio_atencion->TipoEntradaTicket}}" {{($pqr->TipoEntrada == $medio_atencion->TipoEntradaTicket)? 'selected' : ''}}>{{$medio_atencion->Descripcion}}</option>
                    @endforeach
                </select>
            </td>

            <th class="bg-gray">Tipo de Solicitud</th>
            <td>
                <select class="form-control" name="tipo_solicitud" required>
                    <option value="">Elija una opcion</option>
                    @foreach($tipos_pqrs as $tipo_pqr)
                    <option {{($pqr->TipoSolicitud == $tipo_pqr->tipo)? 'selected' : ''}}>{{$tipo_pqr->tipo}}</option>
                    @endforeach
                </select>
            </td>
            
        </tr>

        <tr>
            <th class="bg-gray"> Clasificación</th>
            <td>
                <select name="clasificacion" class="form-control"></select>
            </td>

            <th class="bg-gray">Tipo de Evento</th>
            <td>
                <select name="tipo_evento" class="form-control" required>
                    <option value="">Elija una opción</option>
                    @foreach($eventos as $evento)
                        <option value="{{$evento->IdTipoEvento}}" {{($pqr->TipoDeEvento == $evento->IdTipoEvento)? 'selected' : ''}}>{{$evento->TipoEvento}}</option>
                    @endforeach
                </select>
            </td>
            
        </tr>

        <tr>
            <th class="bg-gray"> Prioridad</th>
            <td>
                <select name="prioridad" id="prioridad" class="form-control" required>
                    <option value="">Elija una opción</option>                
                    @foreach($prioridades as $key => $value)
                        <option value="{{$key}}" {{($pqr->Prioridad == $key)? 'selected' : ''}}>{{$value}}</option>
                    @endforeach
                </select>
            </td>
            <th class="bg-gray"> Aceptación</th>
            <td>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="tratamiento_datos" {{($pqr->AutorizaTratamientoDatos)? 'checked':''}} required>
                            <span class="margin-l-5">Privacidad y Autorización de tratamiento de datos</span>
                        </label>
                    </div>
                </div>
            </td>
            
        </tr>

        @if($accion == 'EDITAR')

        <tr>
            <th class="bg-gray"> Fecha de apertura</th>
            <td>
                <input type="datetime-local" class="form-control" name="fecha_apertura" value="{{$pqr->FechaApertura}}" required>                                
            </td>

            <th class="bg-gray"> Fecha Límite</th>
            <td>
                <input type="datetime-local" class="form-control" name="fecha_limite" value="{{ (!empty($pqr->FechaMaxima))? date('Y-m-d H:i:s', strtotime($pqr->FechaMaxima)) : ''}}" required>                                
            </td>

        </tr>

        <tr>                                
            <th class="bg-gray"> Fecha de Cierre</th>
            <td>
                <input type="datetime-local" class="form-control" name="fecha_cierre" value="{{{$pqr->FechaCierre}}}">                                
            </td>
        </tr>

        <tr>

            <th class="bg-gray"><i class="fa fa-user-o margin-r-5"></i> Creado por</th>
            <td>
                <select class="form-control" name="creado_por">
                <option value="">Elija una opción</option>
                @foreach($agentes as $agente)
                    <option value="{{$agente->id}}" {{($pqr->user_crea == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                @endforeach
                </select>
            </td>


            <th class="bg-gray"><i class="fa fa-user margin-r-5"></i>Cerrado por</th>
            <td>
                <select class="form-control" name="cerrado_por">
                    <option value="">Elija una opción</option>
                    @foreach($agentes as $agente)
                        @if(empty($pqr->user_cerro))
                        <option value="{{$agente->id}}" {{(Auth::user()->id == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                        @else
                        <option value="{{$agente->id}}" {{($pqr->user_cerro == $agente->id) ? 'selected' : ''}}>{{$agente->name}}</option>
                        @endif
                    @endforeach
                </select>
            </td>


            

                                            
        </tr>
        @endif
        
        <tr>
            <th class="bg-gray">Hechos</th>
            <td colspan="3">
                <textarea class="form-control" name="hechos" required>{{$pqr->Hechos}}</textarea>
            </td>
        </tr>

        <tr>
            <th class="bg-gray">Solución</th>
            <td colspan="3">
                <textarea class="form-control" name="solucion" required>{{$pqr->Solucion}}</textarea>
            </td>
        </tr>

        <tr>
            <th class="bg-gray">Observaciones</th>
            <td colspan="3">
                <textarea class="form-control" name="observaciones">{{$pqr->Observacion}}</textarea>
            </td>
        </tr>

    </tbody>
</table>