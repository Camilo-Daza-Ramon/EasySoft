@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-pie-chart"></i> Estadisticas</h1>
@endsection

@section('main-content')
    <div class="row">
        <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">                  
                            {!! $chart->html() !!}                                    
                        </div>
                        <div class="box-footer">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ESTADO</th>
                                        <th>TOTAL</th>
                                        <th>VALOR PORCENTUAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($estados_agrupados as $estado_agrupado)
                                    <tr>
                                        <td>{{$estado_agrupado->estado}}</td>
                                        <td>{{$estado_agrupado->total}}</td>
                                        <td>{{round(($estado_agrupado->total * 100) / $total, 2)}}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray">
                                        <th>TOTAL:</th>
                                        <td>{{$total}}</td>
                                        <td>100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>                         
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="box">
                        <div class="box-body">
                            {!! $solicitudes_creadas->html() !!}
                        </div>
                        <div class="box-footer">                  
                            <div class="description-block border-right">
                                <span class="description-percentage text-info">
                                    @if($total_solicitudes > 0)
                                        {{ round(($total_solicitudes * 100) / $total_solicitudes, 2) }}%
                                    @endif
                                </span>
                                <h5 class="description-header">{{ $total_solicitudes }}</h5>
                                <span class="description-text">Total Solicitudes</span>
                            </div>
                        </div> 
                    </div> 
                </div>

                <div class="col-md-7">
                    <div class="box">
                        <div class="box-header bg-blue">
                            <h2 class="box-title">Total registros por agente</h2>
                        </div>
                        <div class="box-body">

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>AGENTE</th>
                                    <th>EXITOSAS</th>
                                    <th>NO EXITOSAS</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                                @foreach($total_llamadas_por_agente as $llamada)
                                    <tr>
                                        <td>{{$llamada['name']}}</td>
                                        <td>{{$llamada['exitosas']}}</td>
                                        <td>{{$llamada['no_exitosas']}}</td>
                                        <td>{{$llamada['exitosas'] + $llamada['no_exitosas']}}</td>
                                    </tr>
                                @endforeach
                            <tbody>

                            </tbody>
                        </table>                            
                        </div>
                        <div class="box-footer">                  
                            
                        </div> 
                    </div> 
                </div>

            </div>
            

                                 
        </div>       
    </div>

    @section('mis_scripts')
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>
        {!! $chart->script() !!}
        {!! $solicitudes_creadas->script() !!}
    @endsection
@endsection