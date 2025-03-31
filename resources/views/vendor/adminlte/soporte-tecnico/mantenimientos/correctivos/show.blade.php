@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-wrench"></i>  Mantenimiento Correctivo #{{$mantenimiento->MantId}}</h1>
@endsection

@section('main-content')
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border bg-blue">
          <h3 class="box-title">Detalles</h3>
          
          <div class="box-tools">
          @if($mantenimiento->estado == 'CERRADO')
            @permission('mantenimientos-generar-acta')
                <a target="_blank" href="{{route('correctivos.acta', ['id' => $mantenimiento->MantId])}}" class="btn btn-sm btn-default" title="Generar Acta de Mantenimiento"><i class="fa fa-file-pdf-o"></i> Acta</a>
            @endpermission
          @endif
          @permission('mantenimientos-editar')
            <a href="{{route('correctivos.edit', $mantenimiento->MantId)}}" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Editar</a>
          @endpermission
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th class="bg-gray"># Mantenimiento</th>
                <td colspan="3"><h4>{{$mantenimiento->NumeroDeTicket}}</h4></td>
              </tr>

              <tr>
                <th class="bg-gray">Cantidad de Clientes Afectados</th>
                <td>
                  @if(!empty($mantenimiento->ClienteId))
                    1
                  @else
                    {{$mantenimiento->clientes()->count()}}
                  @endif
                </td>

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
                  @if(!empty($mantenimiento->TipoMantenimiento))
                  {{$mantenimiento->tipo_mantenimiento->tipo}} - {{$mantenimiento->tipo_mantenimiento->Descripcion}}
                  @endif
                </td>

                <th class="bg-gray">
                  Proyecto
                </th>
                <td>
                  @if(!empty($mantenimiento->ProyectoId))
                    {{$mantenimiento->proyecto->NumeroDeProyecto}}
                  @else
                    SIN DEFINIR
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
                <th class="bg-gray">Fecha de apertura</th>
                <td>{{date('Y-m-d H:i:s', strtotime($mantenimiento->Fecha))}}</td>
                <th class="bg-gray">Fecha Límite</th>
                <td>
                  @if(!empty($mantenimiento->FechaMaxima))
                    {{date('Y-m-d', strtotime($mantenimiento->FechaMaxima))}}
                  @endif
                </td> 
              </tr>

              <tr>
                <th class="bg-gray">
                    Tipo Falla
                </th>
                <td>
                  @if(!empty($mantenimiento->TipoFalloID))
                    {{$mantenimiento->tipo_fallo->DescipcionFallo}}
                  @endif
                </td>

                <th class="bg-gray">Prioridad </th>
                <td>
                  @if(!empty($mantenimiento->Prioridad))
                    {{$mantenimiento->Prioridad}}
                  @else
                    Sin definir.
                  @endif
                </td>   
              </tr>

              <tr>
                <th class="bg-gray">
                  Canal de Atención
                </th>
                <td>
                  @if(!empty($mantenimiento->TipoEntrada))
                  {{$mantenimiento->medio_atencion->Descripcion}}
                  @else
                  sin definir
                  @endif
                </td>

                <th class="bg-gray">
                  Tikect
                </th>
                <td>
                  @if(!empty($mantenimiento->TicketId))
                    <a href="{{route('tickets.show', $mantenimiento->TicketId)}}">{{$mantenimiento->TicketId}}</a>
                  @else
                    sin definir
                  @endif
                </td>

                             
              </tr>              
              
              <tr>
                <th class="bg-gray">Descripcion del problema</th>
                <td colspan="3">{{$mantenimiento->DescripcionProblema}}</td>
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
                @if(!empty($mantenimiento->Red))
                  {{$mantenimiento->Red}}
                @else
                  Sin definir.
                @endif
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
              <th class="bg-gray">Tipo Tecnologia</th>
              <th class="bg-gray">Tipologia</th>
              <th class="bg-gray">Retornó el servicio?</th>
              <th class="bg-gray">Servicios Activos</th>
            </tr>
            <tr>
              <td>
                @if(!empty($mantenimiento->TipoDeTecnologiaImplementada))                
                  <p>{{$mantenimiento->TipoDeTecnologiaImplementada}}</p>
                @else
                  Sin definir.
                @endif
              </td>
              <td>
                @if(!empty($mantenimiento->TipologiaImplementada))                
                  <p>{{$mantenimiento->TipologiaImplementada}}</p>
                @else
                  Sin definir.
                @endif
              </td>
              <td>
                @if(!empty($mantenimiento->SeRetornoServicio))                
                  <p>{{$mantenimiento->SeRetornoServicio}}</p>
                @else
                  Sin definir.
                @endif
              </td>
              <td>
                @if(!empty($mantenimiento->ServicioQuedaActivo))                
                  <p>{{$mantenimiento->ServicioQuedaActivo}}</p>
                @else
                  Sin definir.
                @endif
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Velocidad Bajada</th>
              <td>
                @if(!empty($mantenimiento->VelocidadDeBajada))
                <p>{{$mantenimiento->VelocidadDeBajada}} Mb</p>
                @else
                  Sin definir.
                @endif                
              </td>
              <th class="bg-gray">Velocidad Subida</th>
              <td>
                @if(!empty($mantenimiento->VelocidadDeSubida))
                <p>{{$mantenimiento->VelocidadDeSubida}} Mb</p>
                @else
                  Sin definir.
                @endif
              </td>
              
            </tr>
            <tr>
              <th class="bg-gray">Observaciones</th>
              <td colspan="3">
                @if(!empty($mantenimiento->Observaciones))
                  <p class="text-justify">{{$mantenimiento->Observaciones}}</p>
                @else
                  Sin definir.
                @endif
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Solucion</th>
              <td colspan="3">
                @if(!empty($mantenimiento->Solucion))
                  <p class="text-justify">{{$mantenimiento->Solucion}}</p>
                @else
                  Sin definir.
                @endif
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Procedimiento</th>
              <td colspan="3">
                @if(!empty($mantenimiento->Procedimiento))
                  <p class="text-justify">{{$mantenimiento->Procedimiento}}</p>
                @else
                  Sin definir.
                @endif
              </td>
            </tr>
            <tr>
              <th class="bg-gray">Observaciones de Cierre</th>
              <td colspan="3">
                @if(!empty($mantenimiento->ObservacionDeCierre))
                  <p class="text-justify">{{$mantenimiento->ObservacionDeCierre}}</p>
                @else
                  Sin definir.
                @endif
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

          @if(!empty($mantenimiento->ClienteId))
              <table class="table table-bordered">
                  <thead>
                      <tr class="dark"> 
                          <th class="bg-blue text-center" colspan="3">PERSONA QUIEN RECIBE EL MANTENIMIENTO</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <th class="bg-gray">Parentezco</th>
                          <td>{{$mantenimiento->parentezco}}</td>
                          <th class="bg-gray">Firma</th>
                      </tr>
                      <tr>
                          <th class="bg-gray">Nombre</th>
                          <td>{{$mantenimiento->nombre}}</td>
                          <td rowspan="2" class="text-center">
                            @if(!empty($mantenimiento->firma))
                              <img src="{{Storage::url($mantenimiento->firma)}}" height="100px">
                            @endif
                          </td>
                      </tr>
                      <tr>
                          <th class="bg-gray">Cedula</th>
                          <td>{{$mantenimiento->cedula}}</td>
                      </tr>
                      
                  </tbody>
                  
              </table>
          @endif

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
              @role('interventoria')
              @else
                <li>
                  <a href="#fotos" data-toggle="tab">
                    <i class="fa fa-image"></i> <span class="hidden-xs hidden-sm hidden-md">Fotos</span>
                  </a>
                </li>
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
                  <a href="#soluciones" data-toggle="tab">
                    <i class="fa fa-check-square-o"></i>  <span class="hidden-xs hidden-sm hidden-md">Soluciones</span>
                  </a>
                </li>
                <li>
                  <a href="#fallas" data-toggle="tab">
                    <i class="fa fa-exclamation-triangle"></i>  <span class="hidden-xs hidden-sm hidden-md">Fallas</span>
                  </a>
                </li>
                <li>
                  <a href="#paradas" data-toggle="tab">
                    <i class="fa fa-clock-o"></i> <span class="hidden-xs hidden-sm hidden-md">Paradas de Reloj</span>
                  </a>
                </li>
                <li>
                  <a href="#materiales" data-toggle="tab">
                    <i class="fa fa-wrench"></i>  <span class="hidden-xs hidden-sm hidden-md">Materiales</span>
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
              @role('interventoria')

              @else
                <div class="tab-pane" id="fotos">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.fotos.show')
                </div>
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
                <div class="tab-pane" id="soluciones">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.soluciones.show')
                </div>
                <div class="tab-pane" id="fallas">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.fallas.show')
                </div>
                <div class="tab-pane" id="paradas">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.paradas-reloj.show')
                </div>
                <div class="tab-pane" id="materiales">
                  @include('adminlte::soporte-tecnico.mantenimientos.partials.materiales.show')
                </div>
              @endrole
            </div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>

   @if(($mantenimiento->TipoMantenimiento == 'REDN' || $mantenimiento->TipoMantenimiento == 'REDT') && $mantenimiento->estado == 'CERRADO' && !empty($cedulas) && $indisponibilidad['compensar'])
      @permission('mantenimientos-novedades-crear')
      @include('adminlte::soporte-tecnico.mantenimientos.partials.clientes.novedades')
      @endpermission
    @endif

  @if($mantenimiento->estado != 'CERRADO')   

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

    @permission('mantenimientos-soluciones-crear')
      @include('adminlte::soporte-tecnico.mantenimientos.partials.soluciones.add')
    @endpermission

    @permission('mantenimientos-fallos-crear')
      @include('adminlte::soporte-tecnico.mantenimientos.partials.fallas.add')
    @endpermission

    @permission('mantenimientos-paradas-reloj-crear')
      @include('adminlte::soporte-tecnico.mantenimientos.partials.paradas-reloj.add')
    @endpermission

    @permission('mantenimientos-materiales-crear')
      @include('adminlte::soporte-tecnico.mantenimientos.partials.materiales.add')
    @endpermission

  @endif

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
      buscarmunicipios({{$mantenimiento->MunicipioId}});
    });

    </script>
    <script type="text/javascript" src="/js/clientes/show.js"></script>
    @if($mantenimiento->estado != 'CERRADO')
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
    @endif

  @endsection
@endsection