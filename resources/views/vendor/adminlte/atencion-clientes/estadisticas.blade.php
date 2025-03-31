@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-pie-chart"></i> Estadisticas de Solicitudes</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>

                    </div>

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-9">
                                {!! $grafica->html() !!}
                            </div>
                            <div class="col-md-3">

                                <form action="{{route('atencion-clientes.estadisticas')}}" method="get">
                                    <div class="form-group">
                                        <label for="">Filtrar por mes</label>
                                        <div class="input-group">
                                            <input type="month" class="form-control" name="mes" value="{{(isset($_GET['mes']) ? $_GET['mes'] : '')}}">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-info">Filtrar</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>

                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3>{{number_format(((($sumatoria_cumplimiento->total_cumplida) / ($sumatoria_cumplimiento->total_cumplida + $sumatoria_cumplimiento->total_incumplida))*100), 1,",",".")}}<sup style="font-size: 20px">%</sup></h3>
                                        <p>Cumplimiento</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>

                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3>{{number_format(((($sumatoria_cumplimiento->total_incumplida) / ($sumatoria_cumplimiento->total_cumplida + $sumatoria_cumplimiento->total_incumplida))*100), 1,",",".")}}<sup style="font-size: 20px">%</sup></h3>
                                        <p>Incumplimiento</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                </div>

                            </div>
                        </div>
                        

                        <h3 class="text-center">Solicitudes Pendientes y Vencidas</h3>
                        <div class="row">
                            @foreach($atenciones2 as $atencion) 
                            <div class="col-md-3">                            
                                <div class="info-box bg-purple">
                                    <span class="info-box-icon">{{$atencion->cantidad}}</span>
                                    <div class="info-box-content">
                                        <a href="/solicitudes?motivo={{$atencion->motivo_id}}" target="_blank">
                                            <span class="info-box-number" style="font-size:16px; color:#fff;">{{$atencion->motivo}}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @section('mis_scripts')
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>
    
        {!! $grafica->script() !!}
    @endsection
@endsection