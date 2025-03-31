@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-comments-o"></i>  pqr #{{$pqr->CUN}}</h1>
@endsection

@section('main-content')
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border bg-blue">
          <h3 class="box-title">Detalles</h3>
          <div class="box-tools">
            @permission('pqrs-editar')
              <a href="{{route('pqr.edit', $pqr->PqrId)}}" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Editar</a>
            @endpermission
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table">
            <tbody>

                <tr>
                    <th class="bg-gray">CUN</th>
                    <td>
                        <h4>{{$pqr->CUN}}</h4>
                    </td>

                    <th class="bg-gray">Estado</th>
                    <td>{{$pqr->Status}}</td>
                </tr>

                <tr>
                    <th class="bg-gray">Cedula</th>
                    <td>
                      @if(!empty($pqr->ClienteId))
                        <a href="{{route('clientes.show', $pqr->ClienteId)}}">{{$pqr->IdentificacionCliente}}</a>
                      @else
                      {{$pqr->IdentificacionCliente}}
                      @endif
                    </td>
                    
                    <th class="bg-gray">Nombre</th>
                    <td>{{$pqr->NombreBeneficiario}}</td>
                </tr>

                <tr>
                    <th class="bg-gray">Correo</th>
                    <td>{{$pqr->CorreoElectronico}}</td>

                    <th class="bg-gray">Celular</th>
                    <td>{{$pqr->NumeroDeCelular}}</td>
                </tr>

                <tr>
                    <th class="bg-gray">Telefono</th>
                    <td>{{$pqr->NumeroDeTelefono}}</td>

                    <th class="bg-gray">Direccion</th>
                    <td>{{$pqr->DireccionNotificacion}}</td>
                </tr>

                <tr>
                    <th class="bg-gray">Departamento</th>
                    <td>{{$pqr->municipio->NombreDepartamento}}</td>

                    <th class="bg-gray">Municipio</th>
                    <td>{{$pqr->municipio->NombreMunicipio}}</td>
                    
                </tr>

                <tr>
                    <th class="bg-gray">Canal de Atención</th>
                    <td>{{(!empty($pqr->TipoEntrada))? $pqr->medio_atencion->Descripcion : 'SIN DEFINIR'}}</td>

                    <th class="bg-gray">Tipo de Solicitud</th>
                    <td>{{$pqr->TipoSolicitud}}</td>
                    
                </tr>

                <tr>
                    <th class="bg-gray"> Clasificación</th>
                    <td>{{(!empty($pqr->TipoTicket))? $pqr->tipo_pqr->Descripcion : 'SIN DEFINIR' }}</td>

                    <th class="bg-gray">Tipo de Evento</th>
                    <td>{{(!empty($pqr->TipoDeEvento)) ? $pqr->evento->TipoEvento : 'SIN DEFINIR'}}</td>
                    
                </tr>

                <tr>
                    <th class="bg-gray"> Prioridad</th>
                    <td>{{$pqr->Prioridad}}</td>
                    <th class="bg-gray"> Aceptación</th>
                    <td>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="tratamiento_datos" {{($pqr->AutorizaTratamientoDatos)? 'checked':''}} disabled>
                                    <span class="margin-l-5">Privacidad y Autorización de tratamiento de datos</span>
                                </label>
                            </div>
                        </div>
                    </td>
                    
                </tr>


                <tr>
                    <th class="bg-gray"> Fecha de apertura</th>
                    <td>{{(!empty($pqr->FechaApertura))? date('Y-m-d H:s:i', strtotime($pqr->FechaApertura)) : 'SIN DEFINIR'}}</td>

                    <th class="bg-gray"> Fecha Límite</th>
                    <td>{{ (!empty($pqr->FechaMaxima))? date('Y-m-d H:i:s', strtotime($pqr->FechaMaxima)) : ''}}</td>

                </tr>

                <tr>                                
                    <th class="bg-gray"> Fecha de Cierre</th>
                    <td>{{ (!empty($pqr->FechaCierre))? date('Y-m-d H:i:s', strtotime($pqr->FechaCierre)) : ''}}</td>
                </tr>

                <tr>

                    <th class="bg-gray"><i class="fa fa-user-o margin-r-5"></i> Creado por</th>
                    <td>{{(!empty($pqr->user_crea))? $pqr->usuario_crea->name : ''}}</td>


                    <th class="bg-gray"><i class="fa fa-user margin-r-5"></i>Cerrado por</th>
                    <td>{{(!empty($pqr->user_cerro))? $pqr->usuario_cierra->name : 'SIN DEFINIR'}}</td>
                                                    
                </tr>
                
                <tr>
                    <th class="bg-gray">Hechos</th>
                    <td colspan="3">{{$pqr->Hechos}}</td>
                </tr>

                <tr>
                    <th class="bg-gray">Solución</th>
                    <td colspan="3">{{$pqr->Solucion}}</td>
                </tr>

                <tr>
                    <th class="bg-gray">Observaciones</th>
                    <td colspan="3">{{$pqr->Observacion}}</td>
                </tr>
            </tbody>
          </table>           

          <table class="table table-bordered">
            <tr class="bg-gray">
              <th rowspan="2">
                TIEMPO DE INDISPONIBILIDAD
              </th>
              <th>En Días</th>
              <th>En Horas</th>
              <th>En Minutos</th>
            </tr>
            <tr>
              <td>                
                <span id="total_dias">{{$indisponibilidad['dias']}}</span> Días
              </td>
              <td>
                <span id="total_horas">{{$indisponibilidad['horas']}}</span> Horas
              </td>
              <td> <span id="total_minutos">{{$indisponibilidad['minutos']}}</span> Minutos</td>
            </tr>
          </table>
        
        </div>
      </div>

      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab_1" data-toggle="tab" aria-expanded="true" style="color: #444 !important;"> <i class="fa fa-file-o"></i> Archivos</a>
          </li>
          <li class="">
            <a href="#tab_3" data-toggle="tab" aria-expanded="false" style="color: #444 !important;"> <i class="fa fa-clock-o"></i>  Paradas de Reloj</a>
          </li>              
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1"> 
            @include('adminlte::pqr.archivos.show')
          </div>
          <div class="tab-pane" id="tab_3">
            @include('adminlte::pqr.paradas_reloj.index')
          </div>
        </div>
        <!-- /.tab-content -->
      </div>
    </div>
  </div>

  @permission('pqrs-archivos-crear')
    @include('adminlte::pqr.archivos.add')
  @endpermission
  @section('mis_scripts')

  <script type="text/javascript" src="{{asset('js/mantenimientos/fotos.js')}}"></script>
    
  @endsection
@endsection