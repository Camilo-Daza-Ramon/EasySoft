@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-bar-chart"></i>  Estadisticas</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
      <div class="row">
      	<div class="col-md-6">
      		<div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <h3 class="box-title"><i class="fa fa-institution"></i> Estado del Servicio de Clientes</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! $grafica_estado_servicio_clientes->html() !!}
                            </div>
                        </div>
                        
                    </div>
                    <div class="box-footer clearfix">                                              
                    </div>
                </div>
      	</div>

      	<div class="col-md-6">
      		<div class="box box-info">
                <div class="box-header bg-green with-border">
                    <h3 class="box-title"><i class="fa fa-institution"></i> Estado de Clientes</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            {!! $grafica_estado_clientes->html() !!}
                        </div>
                    </div>
                    
                </div>
                <div class="box-footer clearfix">
                    <span>Tener en cuenta que las cedulas o Nit (9003657507, 1140838564) </span>

                    <ol>
                    	<li><b>Estado del Servicio</b> como Activo (Para que mesa de ayuda pueda gestionar los debidos mantenimentos y soporte tecnico)</li>
                    	<li><b>Estado del Cliente</b> como Inactivo (Para evitar que se genere facturación por el C3000).</li>
                    </ol>

                    <span>Por lo tanto al valor de <b>Activos</b> se le debe restar las 2 cedulas mencionadas y al total de registros que aparecen <b>Inactivos</b> se le debe sumar tambien las 2 cedulas.</span>                       
                </div>
            </div>
      	</div>

        <div class="col-md-12">
            <div class="box box-info">
                    <div class="box-header bg-default with-border">
                        <h3 class="box-title"><i class="fa fa-institution"></i> Recaudos</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! $chart->html() !!}
                            </div>
                        </div>
                        
                    </div>
                    <div class="box-footer clearfix">   
                        <table class="table">
                            <tr>
                                <th>Municipios</th>
                                <th>Cant. Facturas</th>
                                <th>Facturado</th>
                                <th>Cant. Recaudos</th>
                                <th>Recaudado</th>
                                <th>% Efectividad</th>
                            </tr>                            
                            
                            @foreach($datos as $dato => $value)
                                <tr>
                                    <td>{{$dato}}</td>
                                    <td>{{$value['cantidad_fac']}}</td>
                                    <td>${{number_format($value['facturado'], 0, ',', '.')}}</td>
                                    <td>
                                        @if(isset($value['cantidad_rec']))
                                            {{$value['cantidad_rec']}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($value['recaudado']))
                                            ${{number_format($value['recaudado'], 0, ',', '.')}}
                                        @else
                                            $0
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($value['recaudado']))
                                            @if($value['facturado'] > 0)
                                                {{number_format((($value['recaudado']/$value['facturado']) * 100), 2)}}%
                                            @else
                                                No aplica
                                            @endif
                                        @else
                                            0
                                        @endif
                                    </td>
                                </tr>                                
                            @endforeach
                            
                        </table>                                           
                    </div>
                </div>
        </div>

        

      	<div class="col-md-12">
      		<div class="box box-info">
                    <div class="box-header bg-default with-border">
                        <h3 class="box-title"><i class="fa fa-institution"></i> Recaudos</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! $grafica_recaudos_mes->html() !!}
                            </div>
                        </div>
                        
                    </div>
                    <div class="box-footer clearfix">                        
                    </div>
                </div>
      	</div>
      </div>
  </div>
  @section('mis_scripts')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
    <script type="text/javascript" src="https://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/js/modules/offline-exporting.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.2/justgage.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.8.0/plottable.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/echarts/3.6.2/echarts.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/amcharts.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/serial.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/plugins/export/export.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/themes/light.js"></script>

    {!! $grafica_estado_servicio_clientes->script() !!}
    {!! $grafica_estado_clientes->script() !!}

    {!! $grafica_recaudos_mes->script() !!}}

    {!! $chart->script() !!}}
  @endsection
@endsection