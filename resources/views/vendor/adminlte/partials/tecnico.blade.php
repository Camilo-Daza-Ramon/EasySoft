<?php 
  $total = 0;
  $aprobados = 0;
  $pendientes = 0;
  $rechazados = 0;

  foreach ($instalaciones as $instalacion) {
    switch ($instalacion->estado) {
      case 'PENDIENTE':
        $pendientes = $pendientes + $instalacion->cantidad;        
        break;
      case 'RECHAZADO':
        $rechazados = $rechazados + $instalacion->cantidad;
        break;
      case 'ANULADO':
        $rechazados = $rechazados + $instalacion->cantidad;
        break;
      default:
        $aprobados = $aprobados + $instalacion->cantidad;
        break;
    }

    $total += $instalacion->cantidad;
  }

 ?>

<div class="row">
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$total}}</h3>

        <p>Total instalaciones</p>
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
      <a href="" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>
<div class="row">
  <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Grafica</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              {!! $grafica_fecha_instalaciones->html() !!}
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
</div>
@section('mis_scripts')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>
  {!! $grafica_fecha_instalaciones->script() !!}
@endsection