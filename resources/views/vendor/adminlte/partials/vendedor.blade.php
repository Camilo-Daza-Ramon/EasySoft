<?php 
  $total = 0;
  $aprobados = 0;
  $pendientes = 0;
  $rechazados = 0;

  foreach ($clientes as $cliente) {
    switch ($cliente->Status) {
      case 'PENDIENTE':
        $pendientes = $pendientes + $cliente->cantidad;        
        break;
      case 'RECHAZADO':
        $rechazados = $rechazados + $cliente->cantidad;
        break;
      case 'ANULADO':
        $rechazados = $rechazados + $cliente->cantidad;
        break;
      default:
        $aprobados = $aprobados + $cliente->cantidad;
        break;
    }

    $total += $cliente->cantidad;
  }

 ?>

<div class="row">
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$total}}</h3>

        <p>Total Clientes</p>
      </div>
      <div class="icon">
        <i class="fa fa-tasks"></i>
      </div>
      <a href="#" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{$aprobados}}</h3>

        <p>Aprobados</p>
      </div>
      <div class="icon">
        <i class="fa fa-hdd-o"></i>
      </div>
      <a href="#" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>{{$pendientes}}</h3>

        <p>Pendientes</p>
      </div>
      <div class="icon">
        <i class="fa fa-upload"></i>
      </div>
      <a href="#" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{$rechazados}}</h3>

        <p>Rechazados</p>
      </div>
      <div class="icon">
        <i class="fa fa-download"></i>
      </div>
      <a href="{{route('clientes.index')}}?estado=RECHAZADO" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>
<div class="row">
  <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Grafica</h3>

              <div class="box-tools">
                <div class="navbar-form navbar-left">
                  <div class="form-group input-group-sm">

                    <select class="form-control" name="departamento" id="departamento">
                      <option value="">Elija un departamento</option>
                      @foreach($departamentos as $departamento)                            
                        <option value="{{$departamento->DeptId}}">{{$departamento->NombreDepartamento}}</option>
                      @endforeach
                    </select>

                    <select class="form-control" name="proyecto_municipio_id" id="proyecto_municipio_id">
                        <option value="">Elija un municipio</option>
                    </select>

                    <input type="month" name="mes" id="mes" class="form-control" value="{{date('Y-m')}}">
                  </div>
                  <button type="submit" id="filtrar_ventas" class="btn btn-default btn-sm"> <i class="fa fa-search"></i>  Filtrar</button>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="charts-chart">
                <div id="grafica_ventas" style="height: 100%; width: 100%;"></div>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>
@section('mis_scripts')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>

  <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipiosproyectos.js')}}"></script>
    <script type="text/javascript">
      var proyecto = null;

      $('#departamento').on('change', function(){
          var parameters = {
              departamento_id : $(this).val(),
              proyecto_id : proyecto,
              '_token' : $('input:hidden[name=_token]').val()
          };

          $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){

              $('#proyecto_municipio_id').empty();
              $('#proyecto_municipio_id').append('<option value="">Elija un municipio</option>');
              $.each(data, function(index, municipiosObj){                   
                  $('#proyecto_municipio_id').append('<option value="'+municipiosObj.MunicipioId+'">'+municipiosObj.NombreMunicipio+'</option>');                    
              });
          }).fail(function(e){
              alert('error');
          });
      });
    </script>
    <script type="text/javascript">

      $('#filtrar_ventas').on('click', function(){

        var municipio = $('#proyecto_municipio_id').val();
        var nombre_municipio = $('#proyecto_municipio_id option:selected').text();
        var mes = $('#mes').val();

        var parametros = {
          'proyecto' : proyecto,
          'municipio' : municipio,
          'mes' : mes,
          '_token' : $('input:hidden[name=_token]').val() 
        }

        $.post('/usuarios/ventas', parametros).done(function(data){

          if (municipio.length == 0) {
            nombre_municipio = '';
          }

          if(!jQuery.isEmptyObject(data.labels)){              
            graficarVentasMes(data.labels, data.ventas, mes, nombre_municipio);
          }else{
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.warning("No hay datos");
          }
        }).fail(function(){
            alert('error');
        });
      });

      $(function() {
          var proyecto = proyecto;
          var mes = $('#mes').val(); 

          var parametros = {
            'proyecto' : proyecto,
            'municipio' : null,
            'mes' : mes,
            '_token' : $('input:hidden[name=_token]').val() 
          }

          $.post('/usuarios/ventas', parametros).done(function(data){

            if(!jQuery.isEmptyObject(data.labels)){              
              graficarVentasMes(data.labels, data.ventas, mes, '');
            }else{
              toastr.options.positionClass = 'toast-bottom-right';
              toastr.warning("No hay datos");
            }
          }).fail(function(){
              alert('error');
          });
        });

      function graficarVentasMes(label1, dataset1, mes, municipio){
        var grafica_ventas = new Highcharts.Chart({
            chart: {
                renderTo: "grafica_ventas",
            },
            title: {
                text:  municipio + " Mes " + mes,
                x: -20 //center
            },
            credits: {
                enabled: false
            },
            xAxis: {
                title: {
                    text: ""
                },
                categories: label1,
            },
            yAxis: {
                title: {
                    text: "Total"
                },
                plotLines: [{
                    value: 0,
                    height: 1,
                    width: 1
                }]
            },
            legend: {},
            series: [{
                name: "Total",
                data: dataset1
            }]
        });
      }
    </script>
@endsection