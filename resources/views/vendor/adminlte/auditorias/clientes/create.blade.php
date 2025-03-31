@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-user-secret">  </i> Auditar Cliente - {{mb_convert_case($cliente->NombreBeneficiario . ' ' . $cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}} 
      @if($cliente->reporte == 'GENERADO')
        @if($cliente->ProyectoId == 7)
          <i class="fa fa-check-circle text-success" title="Reportado a DIALNET"></i>
        @else
          <i class="fa fa-check-circle text-success" title="Reportado a INTERVENTORIA"></i>
        @endif
      @endif</h1>
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
              <li class="">
                <a href="#tab_2" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-briefcase"></i>  <span class="hidden-xs">Contrato</span></label>
                </a>
              </li>

              <li class="">
                <a href="#tab_7" data-toggle="tab" aria-expanded="false">
                  <label> <i class="fa fa-folder-open-o"></i>  <span class="hidden-xs">Archivos</span></label>
                </a>
              </li>
            </ul>

          <div class="tab-content">            	
            <div class="tab-pane active table-responsive" id="tab_1">
              @include('adminlte::auditorias.clientes.datospersonales')
            </div>
            <div class="tab-pane table-responsive" id="tab_2">
              @include('adminlte::auditorias.clientes.contrato')
            </div>

            @if(!empty($cliente->archivos))
              <div class="tab-pane table-responsive" id="tab_7">
                @include('adminlte::auditorias.clientes.archivos')
              </div>
            @endif

            
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
      </div>

      <div class="col-md-3" style="position: fixed; bottom: 0px; right: 10px; z-index: 999999;">
        <div class="box box-warning" style="box-shadow: rgba(0, 0, 0, 0.5) 1px 2px 20px;">
          <div class="box-header with-border" style="background-color: #f39c12; color: #fff; ">
            <h3 class="box-title">Auditar</h3>

            <div class="box-tools pull-right">                    
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.box-header -->

          <form action="{{route('auditorias.clientes.store')}}" method="post">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="tipo" value="cliente">
            <input type="hidden" name="cliente" value="$cliente->ClienteId">
            
            {{ csrf_field() }}
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <select class="form-control" id="estado" name="estado" required>
                      <option value="">Estado</option>
                      <option value="APROBADO">APROBADO</option>
                      <option value="RECHAZADO">RECHAZADO</option>
                    </select>
                  </div>
                    <div class="form-group">                      
                      <select class="form-control" name="vendedor" id="vendedor" required>
                        <option value="">Elija un Vendedor</option>
                        <?php $add = 0; ?>
                        @foreach($vendedores as $vendedor)
                          @if($cliente->user_id == $vendedor->id)
                            <?php $add = 1; ?>
                            <option value="{{$vendedor->id}}" selected>{{$vendedor->name}}</option>
                          @else
                            <option value="{{$vendedor->id}}">{{$vendedor->name}}</option>
                          @endif
                        @endforeach

                        @if($add == 0 && !empty($cliente->user_id))
                        <option value="{{$cliente->user_id}}" selected>{{$cliente->vendedor->name}}</option>
                        @endif
                      </select>
                    </div>
                  <div class="form-group">                      
                    <select class="form-control" id="motivo_rechazo" name="motivo_rechazo" id="motivo_rechazo">
                      <option value="">Motivo Rechazo</option>
                      @foreach($motivos_rechazo as $valor)
                        @if($valor == $cliente->MotivoDeRechazo)
                          <option value="{{$valor}}" selected>{{$valor}}</option>
                        @else
                          <option value="{{$valor}}">{{$valor}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <textarea placeholder="Observaciones" class="form-control" rows="5" id="observaciones" name="observaciones">
                      {{$cliente->ComentarioRechazo}}
                    </textarea>
                  </div>
                </div>
              </div>   
              <!-- /.direct-chat-pane -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">                  
                <button type="submit" id="auditar" class="btn btn-block btn-warning btn-flat" disabled>Auditar</button>
            </div>
          </form>
          <!-- /.box-footer-->
        </div>
      </div>

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

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd64ireVmM5dNgt4VK5KjwIKVwZidnHjs&callback=initMap"></script>


  @endsection
@endsection