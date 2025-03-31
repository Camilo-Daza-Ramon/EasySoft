@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-user">  </i> Cliente - {{mb_convert_case($cliente->NombreBeneficiario . ' ' . $cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}} 
      @if($cliente->reporte == 'GENERADO')
        @if($cliente->ProyectoId == 7)
          <i class="fa fa-check-circle text-success" title="Reportado a DIALNET"></i>
        @else
          <i class="fa fa-check-circle text-success" title="Reportado a INTERVENTORIA"></i>
        @endif
      @endif</h1>
@endsection


@section('other-notifications')
  @if($mantenimimientos_masivos->count() > 0)  
  <div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-warning"></i> Atención!</h4>     
  
      @foreach($mantenimimientos_masivos as $mantenimiento_masivo)
        <p>El cliente tiene un manteninimiento masivo abierto <a href="/mantenimientos/correctivos/{{$mantenimiento_masivo->Mantid}}" target="_black"><b>#{{$mantenimiento_masivo->mantenimiento->NumeroDeTicket}}</b></a></p>
      @endforeach
    </div>
  @endif
@endsection

@section('main-content')
	<div class="container-fluid spark-screen">

		<div class="row">
      <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom dark" style="-webkit-box-shadow: 1px 6px 51px -5px rgba(0,0,0,0.75);-moz-box-shadow: 1px 6px 51px -5px rgba(0,0,0,0.75);box-shadow: 1px 6px 51px -5px rgba(0,0,0,0.75);">
          <ul class="nav nav-tabs bg-blue">

            	<li class="active">
            		<a href="#tab_1" data-toggle="tab" aria-expanded="true">
            			<label> <i class="fa fa-user"></i>  <span class="hidden-xs">Datos Personales</span></label>
            		</a>
            	</li>

              @permission('contratos-ver')
              <li class="">
                <a href="#tab_2" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-briefcase"></i>  <span class="hidden-xs">Contrato</span></label>
                </a>
              </li>
              @endpermission

              @permission('instalaciones-ver')
              <li class="">
                <a href="#instalaciones" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-hdd-o"></i>  <span class="hidden-xs">Instalaciones</span></label>
                </a>
              </li>
              @endpermission

              @permission('facturacion-ver')
            	<li class="">
            		<a href="#tab_4" data-toggle="tab" aria-expanded="false">
            			<label> <i class="fa fa-file-text-o"></i>  <span class="hidden-xs">Facturacion</span></label>
            		</a>
            	</li>
              @endpermission

              @permission('tickets-ver')
              <li class="">
                <a href="#tab_5" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-wrench"></i>  <span class="hidden-xs">Tickets</span></label>
                </a>
              </li>
              @endpermission

              @permission('pqrs-ver')
              <li class="">
                <a href="#tab_9" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-comments-o"></i>  <span class="hidden-xs">PQR</span></label>
                </a>
              </li>
              @endpermission

              @permission('novedades-ver')
              <li class="">
                <a href="#tab_6" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-exclamation-circle"></i>  <span class="hidden-xs">Novedades</span></label>
                </a>
              </li>
              @endpermission

              @permission('clientes-archivos-ver')
              <li class="">
                <a href="#tab_7" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-folder-open-o"></i>  <span class="hidden-xs">Archivos</span></label>
                </a>
              </li>
              @endpermission

              @permission('atencion-clientes-ver')
              <li class="">
                <a href="#tab_8" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-smile-o"></i>  <span class="hidden-xs">Atencion Cliente</span></label>
                </a>
              </li>
              @endpermission

          </ul>
          <div class="tab-content">            	
            <div class="tab-pane active table-responsive" id="tab_1">
              @include('adminlte::clientes.partials.datospersonales')
            </div>

            @permission('contratos-ver')
              <div class="tab-pane table-responsive" id="tab_2">
                @include('adminlte::clientes.partials.contrato')
              </div>
            @endpermission
            
            @permission('instalaciones-ver')
              <div class="tab-pane table-responsive" id="instalaciones">
                @include('adminlte::clientes.partials.instalacion')
              </div>
            @endpermission 

            @permission('facturacion-ver')
              <div class="tab-pane table-responsive no-padding" id="tab_4">              
                @include('adminlte::clientes.partials.facturacion')
              </div>           
            @endpermission

            @permission('tickets-ver')
              <div class="tab-pane table-responsive" id="tab_5">
                @include('adminlte::clientes.partials.tickets')
              </div>
            @endpermission

            @permission('pqrs-ver')
              <div class="tab-pane table-responsive" id="tab_9">
                @include('adminlte::clientes.partials.pqr')
              </div>
            @endpermission

            @permission('novedades-ver')
              <div class="tab-pane table-responsive" id="tab_6">
                @include('adminlte::clientes.partials.novedades')
              </div>
            @endpermission

            @permission('clientes-archivos-ver')
              <div class="tab-pane table-responsive" id="tab_7">
                @include('adminlte::clientes.partials.archivos')
              </div>
            @endpermission

            @permission('atencion-clientes-ver')
              <div class="tab-pane table-responsive" id="tab_8">
                @include('adminlte::clientes.partials.atencion')
              </div>
            @endpermission
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
      </div>

      @if($cliente->Status == 'RECHAZADO')
        @role('vendedor')
        <div class="col-md-3" style="position: fixed; bottom: 0px; right: 10px; z-index: 999999;">
          <div class="box box-warning" style="box-shadow: rgba(0, 0, 0, 0.5) 1px 2px 20px;">
            <div class="box-header with-border" style="background-color: #f39c12; color: #fff; ">
              <h3 class="box-title">Subsanar</h3>

              <div class="box-tools pull-right">                    
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <form action="{{route('clientes.subsanar', $cliente->ClienteId)}}" method="post">
              <input type="hidden" name="_method" value="PUT">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">                      
                      <p>Selecciones el estado del cliente <b>PENDIENTE</b> solo cuando haya terminado de subir la documentación requerida.</p>
                      <label>Estado del cliente</label>
                      <select class="form-control" name="estado" required>
                        <option value="RECHAZADO" selected>RECHAZADO</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-block btn-warning btn-flat">Guardar</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        @endrole
      @endif
    

        @if(!empty($cliente->archivo))
        <div class="col-md-8">
          <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Auditado</h3>

                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <th>Estado</th>
                          <th>Observaciones</th>
                          <th>Auditor</th>
                        </tr>
                        <tr>                            
                          <td>
                            @if($cliente->Status == 'APROBADO')
                              <span class="label label-success">{{$cliente->Status}}</span>
                            @elseif($cliente->Status == 'RECHAZADO')
                              <span class="label label-danger">{{$cliente->Status}}</span>                            
                            @endif
                          </td>
                          <td>
                            <p>
                              <b>{{$cliente->MotivoDeRechazo}}</b>
                              <br>
                              {{$cliente->ComentarioRechazo}}
                            </p>
                          </td>
                          <td>
                            <p>{{$cliente->auditor->name}}</p>
                          </td>                       
                        </tr>
                      </tbody>
                    </table>                        
                  </div>                  
                </div>
                <!-- /.row -->
            </div>
              <!-- /.box-body -->
          </div>
        </div>
        @endif   
		</div>

  @permission('novedades-ver') 
    @include('adminlte::clientes.partials.novedades.mostrar')
  @endpermission
   

    
	</div>
  @section('mis_scripts')

    <script>

      var imagen = '';
      var estado_cliente =  "{{$cliente->Status}}";

      imagen = '/img/marker-'+(estado_cliente.toLowerCase()).replace(' ', '-')+'.png';

      function initMap() {

        var uluru = {lat: {{(isset($cliente->Latitud) ? $cliente->Latitud: 0.0)}}, lng: {{(isset($cliente->Longitud))? $cliente->Longitud : 0.0}} };

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
          center: uluru,
          disableDefaultUI: true,
          styles: [
                    {
                      "featureType": "poi.business",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    },
                    {
                      "featureType": "poi.park",
                      "elementType": "labels.text",
                      "stylers": [
                        {
                          "visibility": "off"
                        }
                      ]
                    }
                  ]
        });

        var marker = new google.maps.Marker({
          position: uluru,
          map: map,
          icon: imagen
        });

        var infowindow = new google.maps.InfoWindow({
          content: "{{$cliente->DireccionDeCorrespondencia}} <br> <b><b>"
        });

        infowindow.open(map, marker);
      }

      $("#form-archivo-upload").submit(function(e) {
        $('#btn-archivo').attr('disabled', 'disabled');
        $('#btn-archivo').append('  <i class="fa fa-refresh fa-spin"></i>');
      });

    </script>


    <script type="text/javascript" src="/js/atencion-cliente/show.js"></script>

    <script type="text/javascript">
           $('#vernovedad').on('show.bs.modal', function (event) {
             var a = $(event.relatedTarget) // Button that triggered the modal
             var id = a.data('id');         
             var url = '/novedades/'+id;
             var modal = $(this);
               modal.find('#facturas').empty();

              $.get(url +'/ver'  ,null, function(data){
                modal.find('#concepto').text(data.novedad['concepto']);
                modal.find('#cantidad').text(data.novedad['cantidad']);
                modal.find('#valor_unidad').text(data.novedad['valor_unidad']);
                modal.find('#unidad_medida').text(data.novedad['unidad_medida']);
                modal.find('#iva').text(data.novedad['iva']);
                modal.find('#fecha_inicio').text(data.novedad['fecha_inicio']);
                modal.find('#fecha_fin').text(data.novedad['fecha_fin']); 
                modal.find('#estado').text(data.novedad['estado']);
                modal.find('#name').text(data.novedad.user['name']);
                modal.find('#fecha_real').text(data.novedad['created_at']);                     
                $.each(data.facturas_novedades, function(index, facturaObj){   
                  modal.find('#facturas').append( '<tr><td><a  href="/facturacion/'+facturaObj.periodo+'/'+facturaObj.factura_id+'" target="_blank">'+facturaObj.factura_id+'</a></td><td>'+facturaObj.periodo+'</td><td>'+"$"+facturaObj.valor_total+'</td></tr>')
                });
          
              });
          });
     </script>

     <script>
      
      $("#formContrato").submit(function(e) {
        $('#btnSend').attr('disabled', 'disabled');
        $('#btnSend').append('  <i class="fa fa-refresh fa-spin"></i>');
      });
     </script>




    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs&callback=initMap"></script>


  @endsection
@endsection


