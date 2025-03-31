@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-calendar-check-o"></i>  Mantenimiento Preventivos #{{$mantenimiento->ProgMantid}}</h1>
@endsection

@section('main-content')
  <div class="row">

    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border bg-blue">
          <h3 class="box-title">Detalles</h3>
          <div class="box-tools">
          @if($mantenimiento->estado == 'CERRADO')
            @permission('mantenimientos-preventivos-generar-acta')
            <a target="_blank" href="{{route('preventivos.acta', ['id' => $mantenimiento->ProgMantid])}}" class="btn btn-sm btn-default" title="Generar Acta de Mantenimiento"><i class="fa fa-file-pdf-o"></i> Acta</a>
            @endpermission
          @endif
          @permission('mantenimientos-preventivos-editar')
            <a href="{{route('preventivos.edit', $mantenimiento->ProgMantid)}}" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Editar</a>
          @endpermission
          </div>
          
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th class="bg-gray"># Mantenimiento</th>
                <td colspan="3"><h4>{{$mantenimiento->NumeroDeMantenimiento}}</h4></td>
              </tr>
              <tr>
                <th class="bg-gray">Cantidad de Clientes Afectados</th>
                <td>{{$mantenimiento->CantidadUsuariosAfectados}}</td>

                <th class="bg-gray">
                  Estado
                </th>
                <td>
                  {{$mantenimiento->estado}}
                </td>
              </tr>
              <tr>
                <th class="bg-gray">
                  Tipo Mantenimiento
                </th>
                <td>
                  @if(!empty($mantenimiento->Tipo))
                  {{$mantenimiento->tipo_mantenimiento->tipo}} - {{$mantenimiento->tipo_mantenimiento->Descripcion}}
                  @endif
                </td>

                <th class="bg-gray">
                  Proyecto
                </th>
                <td>
                  @if(!empty($mantenimiento->ProyectoId))
                  {{$mantenimiento->proyecto->DescripcionProyecto}}
                  @endif
                </td>

              </tr>
              <tr>
                <th class="bg-gray">
                  Departamento
                </th>
                <td>
                  {{$mantenimiento->municipio->NombreDepartamento}}
                </td>
                <th class="bg-gray">
                  Municipio
                </th>
                <td>
                  {{$mantenimiento->municipio->NombreMunicipio}}
                </td>
              </tr>

              <tr>
                <th class="bg-gray">
                  <i class="fa fa-user-o margin-r-5"></i> Creado por
                </th>
                <td>
                  @if(isset($mantenimiento->usuario_crea))
                    {{$mantenimiento->usuario_crea->name}}
                  @endif
                </td>

                <th class="bg-gray">
                      <i class="fa fa-user margin-r-5"></i>Cerrado por
                  </th>
                  <td colspan="3">
                    @if(isset($mantenimiento->usuario_cierra))
                      {{$mantenimiento->usuario_cierra->name}}
                    @endif
                  </td>
                
              </tr>
              <tr>
                <th class="bg-gray">Fecha de Creación</th>
                <td>{{$mantenimiento->Fecha}}</td>
                <th class="bg-gray">Fecha Agendado</th>
                <td>
                    {{$mantenimiento->fecha_programada}}
                </td> 
              </tr>              
            </tbody>
          </table>

          <table class="table table-bordered">
            <tr class="dark"> 
              <th class="bg-blue text-center" colspan="4">DATOS DE CIERRE</th>
            </tr>
            <tr>
              <th class="bg-gray">Fecha Inicio</th>
              <th class="bg-gray">Fecha Fin</th>
              <th class="bg-gray">Dias sin Solución</th>
              <th class="bg-gray">Red</th>
            </tr>
            <tr>
              <td>
                @if(!empty($mantenimiento->fecha_cierre_hora_inicio))
                  {{date('Y-m-d H:i:s', strtotime($mantenimiento->fecha_cierre_hora_inicio))}}
                @else
                  Sin definir.
                @endif
              </td>
              <td>
                @if(!empty($mantenimiento->fecha_cierre_hora_fin))
                  {{date('Y-m-d H:i:s', strtotime($mantenimiento->fecha_cierre_hora_fin))}}
                @else
                  Sin definir.
                @endif
              </td>
              <td>
                {{$indisponibilidad['dias']}} Días
              </td>
              <td>
                {{$mantenimiento->IdentificacionDeLaRed}}
              </td>
            </tr>
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
                {{$indisponibilidad['dias']}} Días
              </td>
              <td>
                {{$indisponibilidad['horas']}} Horas
              </td>
              <td>{{$indisponibilidad['minutos']}} Minutos</td>
            </tr>
            <tr>
              <th class="bg-gray" colspan="2">Tipo Tecnologia</th>
              <th class="bg-gray">Retornó el servicio?</th>
              <th class="bg-gray">Servicios Activos</th>
            </tr>
            <tr>
              <td colspan="2">
                {{$mantenimiento->TipoDeTecnologiaImplementada}}
              </td>              
              <td>
                <p>{{$mantenimiento->SeRetornoServicio}}</p>
              </td>
              <td>
                <p>{{$mantenimiento->ServicioQuedaActivo}}</p>
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Velocidad Bajada</th>
              <td>
                <p>{{$mantenimiento->VelocidadDeBajada}} Mb</p>
              </td>
              <th class="bg-gray">Velocidad Subida</th>
              <td>
                <p>{{$mantenimiento->VelocidadDeSubida}} Mb</p>
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Observaciones</th>
              <td colspan="3">
                <p class="text-justify">              
                  {{$mantenimiento->Observaciones}}
                </p>
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Hallazgos</th>
              <td colspan="3">
                <p class="text-justify">              
                  {{$mantenimiento->ObservacionesHallazgos}}
                </p>
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Procedimiento</th>
              <td colspan="3">
                <p class="text-justify">              
                  {{$mantenimiento->Procedimiento}}
                </p>
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Tecnico Atiende</th>
              <td colspan="3">

                @if(isset($mantenimiento->usuario_atiende))
                  @if(!empty($mantenimiento->usuario_atiende->firma))
                    <img src="{{Storage::url($mantenimiento->usuario_atiende->firma)}}" height="100px">
                  @endif

                
                  <p>{{$mantenimiento->usuario_atiende->name}}</p>
                @endif
              </td>
            </tr>
          </table>

          <br>

          <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding">
            <!-- required for floating -->
            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-left">
              <li class="active">
                <a href="#clientes" data-toggle="tab">
                  <i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Clientes Afectados</span>
                </a>
              </li>              
                <li>
                  <a href="#fotos" data-toggle="tab">
                    <i class="fa fa-image"></i> <span class="hidden-xs hidden-sm hidden-md">Fotos</span>
                  </a>
                </li>
                @role('interventoria')
                @else
                <li>
                  <a href="#equipos" data-toggle="tab">
                    <i class="fa fa-hdd-o"></i>  <span class="hidden-xs hidden-sm hidden-md">Equipos</span>
                  </a>
                </li>
                <li>
                  <a href="#diagnosticos" data-toggle="tab">
                    <i class="fa fa-exclamation"></i>  <span class="hidden-xs hidden-sm hidden-md">Diagnostico</span>
                  </a>
                </li>
                <li>
                  <a href="#direcciones" data-toggle="tab">
                    <i class="fa fa-map-marker"></i>  <span class="hidden-xs hidden-sm hidden-md">Direcciones</span>
                  </a>
                </li>
                <li>
                  <a href="#pruebas" data-toggle="tab">
                    <i class="fa fa-list"></i>  <span class="hidden-xs hidden-sm hidden-md">Pruebas</span>
                  </a>
                </li>
                <li>
                  <a href="#paradas" data-toggle="tab">
                    <i class="fa fa-clock-o"></i> <span class="hidden-xs hidden-sm hidden-md">Paradas de Reloj</span>
                  </a>
                </li>                
              @endrole
            </ul>
          </div>
          <div class="col-xs-10 col-sm-10  col-md-10 col-lg-10">
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="clientes">
                @include('adminlte::soporte-tecnico.mantenimientos.partials.clientes.show')
              </div>
              
                <div class="tab-pane" id="fotos">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.fotos.show')
                </div>
                @role('interventoria')

                @else
                <div class="tab-pane" id="equipos">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.equipos.show')
                </div>
                <div class="tab-pane" id="diagnosticos">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.diagnosticos.show')
                </div>
                <div class="tab-pane" id="direcciones">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.direcciones.show')
                </div>
                <div class="tab-pane" id="pruebas">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.pruebas.show')
                </div>
                <div class="tab-pane" id="paradas">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.paradas-reloj.show')
                </div>                
              @endrole
            </div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>

  @permission('mantenimientos-clientes-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.clientes.add')
  @endpermission

  @permission('mantenimientos-archivos-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.fotos.add')
  @endpermission

  @permission('mantenimientos-equipos-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.equipos.add')
  @endpermission

  @permission('mantenimientos-diagnosticos-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.diagnosticos.add')
  @endpermission

  @permission('mantenimientos-direcciones-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.direcciones.add')
  @endpermission

  @permission('mantenimientos-pruebas-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.pruebas.add')
  @endpermission 

  @permission('mantenimientos-paradas-reloj-crear')
    @include('adminlte::soporte-tecnico.mantenimientos.partials.paradas-reloj.add')
  @endpermission

  @section('mis_scripts')

  <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/mantenimientos/fotos.js')}}"></script>
  <script type="text/javascript">
    var modal = $('#addCliente');
    $('#departamento').on('change', function(){
      buscarmunicipios(null);       
    });
      

    $(document).ready(function() {
      /*validarEmail();*/
      buscarmunicipios({{$mantenimiento->Municipio}});
    });

    </script>
    <script type="text/javascript" src="/js/clientes/show.js"></script>
    <script type="text/javascript">
      var tipo = modal.find('select[name=tipo]');

      tipo.on('change', function(){
        if ($(this).val() == 'INDIVIDUAL') {
          modal.find('#individual').removeClass('hide');
          modal.find('#masivo').addClass('hide');
          modal.find('#btn_guardar').attr('disabled', true);
          modal.find('#documento').attr('required', true);
        }else{
          modal.find('#masivo').removeClass('hide');
          modal.find('#individual').addClass('hide');
          modal.find('#btn_guardar').attr('disabled', false);
          modal.find('#documento').attr('required', false);
        }
      });
  </script>
  
  <script type="text/javascript">   

    $( "#form-novedades-masivas" ).submit(function( event ) {
        $('#btn_masivas_crear').attr("disabled", true);
        $('#btn_masivas_crear').find('i').removeClass('fa-floppy-o');
        $('#btn_masivas_crear').find('i').addClass('fa-refresh fa-spin');
      });
  </script>
    
  <script type="text/javascript">

    let baseUrlArchivosUpdate = "{{ route('mantenimientos.archivos.update', ['mantenimiento' => '__mantenimiento__', 'archivo' => '__archivo__']) }}";
    let baseUrlArchivosStore = "{{ route('mantenimientos.archivos.store', ['mantenimiento' => '__mantenimiento__']) }}";
    let baseUrlEquiposUpdate = "{{ route('mantenimientos.equipos.update', ['mantenimiento' => '__mantenimiento__', 'equipo' => '__equipo__']) }}";
    let baseUrlEquiposStore = "{{ route('mantenimientos.equipos.store', ['mantenimiento' => '__mantenimiento__']) }}";
    let baseUrlDireccionUpdate = "{{ route('mantenimientos.direcciones.update', ['mantenimiento' => '__mantenimiento__', 'direccione' => '__direccion__']) }}";
    let baseUrlDreccionStore = "{{ route('mantenimientos.direcciones.store', ['mantenimiento' => '__mantenimiento__']) }}";
    let baseUrlParadasRelojUpdate = "{{ route('mantenimientos.paradas-reloj.update', ['mantenimiento' => '__mantenimiento__', 'paradas_reloj' => '__paradas_reloj__']) }}";
    let baseUrlParadasRelojStore = "{{ route('mantenimientos.paradas-reloj.store', ['mantenimiento' => '__mantenimiento__']) }}";
    let baseUrlMaterialesUpdate = "{{ route('mantenimientos.materiales.update', ['mantenimiento' => '__mantenimiento__', 'materiale' => '__materiale__']) }}";
    let baseUrlMaterialesStore = "{{ route('mantenimientos.materiales.store', ['mantenimiento' => '__mantenimiento__']) }}";

  </script>
  
  <script type="text/javascript" src="/js/mantenimientos/submodulos.js"></script>

  @endsection
@endsection