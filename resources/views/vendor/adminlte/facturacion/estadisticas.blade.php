@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-bar-chart"></i>  Estadisticas</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
      <div class="row">

            <div class="col-md-8">
              <div class="box box-info" id="espera">
                <div class="box-header bg-blue">
                  <h2 class="box-title">Recaudos día VS Municipios</h2>

                  <div class="box-tools">                    
                  </div>
                </div>
                <div class="box-body table-responsive">
                  <div class="charts-chart">
                      <div id="grafica_por_dia" style="height: 100%; width: 100%;"></div>
                  </div>
                  <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 30px;">#</th>
                            <th>Municipio</th>
                            <th>Departamento</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="tabla_por_dia">
                        
                    </tbody>
                      
                    <tfoot>
                        <tr>
                            <td colspan="3"> <span class="pull-right"><b>TOTAL</b></span></th>
                            <td>
                                <span class="pull-right" id="total_por_dia">$0</span>
                            </td>
                        </tr>
                    </tfoot>

                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="box box-info" id="espera">
                <div class="box-header bg-blue">
                  <h2 class="box-title">Filtro</h2>

                  <div class="box-tools">                    
                  </div>
                </div>
                <div class="box-body">
                  <div class="row">
                        <div class="form-group col-md-6">
                            <label>Fecha desde</label>
                            <input type="date" name="fecha_desde" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Fecha hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control">
                        </div>
                        <div class="form-group col-md-12">

                            <select class="form-control" name="proyecto" id="proyecto">
                                <option value="">Elija un proyecto</option>
                                @foreach($proyectos as $proyecto)
                                    <option value="{{$proyecto->ProyectoID}}">{{$proyecto->NumeroDeProyecto}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <select class="form-control" name="departamento" id="departamento">
                                <option value="">Elija un departamento</option>
                                @foreach($departamentos as $departamento)
                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <select class="form-control" name="municipio" id="municipio">
                                <option value="">Elija un municipio</option>
                            </select> 
                        </div>                        
                    </div>
                    <button type="submit" id="filtrar" class="btn btn-default btn-block"> <i class="fa fa-search"></i>  Filtrar</button>
                    @permission('estadisticas-exportar')
                    <button type="button" id="exportar" class="btn btn-success btn-block"><i id="icon-opciones" class="fa fa-file-excel-o"></i>  Descargar</button>
                    @endpermission
                </div>
              </div>
            </div>

        <div class="col-md-12">
            <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <h3 class="box-title"><i class="fa fa-institution"></i> Grafica</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="charts-chart">
                                    <div id="grafica_recaudo_factura" style="height: 100%; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="box-footer clearfix table-responsive">   
                        <table class="table" id="tabla-facturado-recaudado">
                            <thead>
                                <tr>
                                    <th>Municipios</th>
                                    <th>Cant. Facturas</th>
                                    <th>Facturado</th>
                                    <th>Cant. Recaudos</th>
                                    <th>Recaudado</th>
                                    <th>% Efectividad</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>                                           
                    </div>
                </div>
        </div>

        <div class="col-md-12">
            <div class="box box-info">
                    <div class="box-header bg-default with-border">
                        <h3 class="box-title"><i class="fa fa-institution"></i> Ingresos</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-condensed table-striped" id="tabla-ingresos">
                            <thead>
                                <tr>
                                    <th colspan="15" class="text-center bg-blue">
                                        PROYECTO: <span id="txt_proyecto"></span> <br>
                                        PERIODO: <span id="txt_periodo"></span>
                                    </th>
                                </tr>
                                <tr class="bg-info">
                                    <th>#</th>
                                    <th class="text-center">MUNICIPIOS</th>
                                    <th class="text-center">INTERNET</th>
                                    <th class="text-center">INTERNET IVA</th>
                                    <th class="text-center">DESCUENTOS</th>
                                    <th class="text-center">FACTURADO</th>
                                    <th class="text-center">IMPUESTO <small>2.2%</small></th>                                    
                                    <th class="text-center">TRASLADOS</th>
                                    <th class="text-center">TRASLADOS IVA</th>
                                    <th class="text-center">TRASLADO IMPUESTO <small>2.2%</small></th>

                                    <th class="text-center">EQUIPOS</th>
                                    <th class="text-center">EQUIPOS IVA</th>
                                    <th class="text-center">EQUIPOS IMPUESTO 0.55%</th>

                                     <th class="text-center">RECONEXION</th>
                                    <th class="text-center">RECONEXION IMPUESTO <small>2.2%</small></th>

                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>TOTALES:</th>
                                </tr>
                            </tfoot>
                        </table> 
                        
                    </div>
                    <div class="box-footer clearfix">   
                                                                  
                    </div>
                </div>
        </div>

        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header bg-blue with-border">
                    <h3 class="box-title"><i class="fa fa-ban"></i> Suspendidos vs Recaudos</h3>

                    <div class="box-tools">

                        <form id="form-suspendidos" action="" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="month" name="mes" class="form-control pull-right" placeholder="Search" value="{{date('Y-m')}}">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i id="icono" class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="box-body table-responsive">

                    <table class="table table-dashed table-bordered" id="table-suspendidos">
                        <thead>
                            <th>MUNICIPIO</th>
                            <th>TOTAL SUSPENDIDOS</th>
                            <th>TOTAL FACTURADO</th>
                            <th>CANTIDAD RECAUDOS</th>
                            <th>RECAUDOS</th>
                            <th>RECONECTADOS</th>
                            <th>FALTANTES</th>
                        </thead>
                        <tbody>

                            @if($recuados_suspendidos->count() > 0)

                                @foreach($recuados_suspendidos as $rs)
                                <tr>
                                    <td>{{$rs->municipio}}</td>
                                    <td>
                                        <a href="/novedades?fecha_inicio={{date('Y-m') . '-01'}}&municipio={{$rs->MunicipioId}}&concepto=Suspensión por Mora">{{$rs->total_clientes}}</a>
                                    </td>
                                    <td>${{number_format($rs->total_facturado, 2, ",", ".")}}</td>
                                    <td>
                                        @if($rs->reconectados > 0)
                                            <a href="/recaudos?fecha_desde={{date('Y-m') . '-01'}}&fecha_hasta={{date('Y-m-t')}}&municipio={{$rs->MunicipioId}}">{{$rs->cantidad_recaudos}}</a>
                                        @else
                                            {{$rs->cantidad_recaudos}}
                                        @endif
                                    </td>
                                    <td>${{number_format($rs->recaudo_mes, 2, ",", ".")}}</td>
                                    <td>
                                        @if($rs->reconectados > 0)
                                            <a href="/novedades?fecha_inicio={{date('Y-m') . '-01'}}&municipio={{$rs->MunicipioId}}&estado=FINALIZADA&concepto=Suspensión por Mora">{{$rs->reconectados}}</a>
                                        @else
                                            {{$rs->reconectados}}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/clientes-suspensiones?fecha_inicio={{date('Y-m') . '-01'}}&municipio={{$rs->MunicipioId}}">{{($rs->total_clientes - $rs->reconectados)}}</a>
                                    </td>
                                </tr>

                                @endforeach
                            @else
                            <tr>
                                <td colspan="7" class="text-center text-gray">No hay datos.</td>
                            </tr>

                            @endif                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

      	<div class="col-md-12">
      		<div class="box box-info">
                    <div class="box-header bg-blue with-border">
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
    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
    <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });
    </script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>

    {!! $grafica_recaudos_mes->script() !!}}

    <script type="text/javascript">
        $('#filtrar').on('click', function(){

            var parametros = {
                fecha_desde : $('input[name=fecha_desde]').val(),
                fecha_hasta : $('input[name=fecha_hasta]').val(),
                proyecto : $('#proyecto').val(),
                municipio : $('#municipio').val(),
                departamento : $('#departamento').val(),
                '_token' : $('input:hidden[name=_token]').val() 
            }

            $.post('/estadisticas/recaudos', parametros).done(function(data){

                $('#tabla_por_dia').empty();
                $('#tabla-facturado-recaudado tbody').empty();
                $('#tabla-facturado-recaudado tfoot').empty();

                $('#tabla-ingresos tbody').empty();
                $('#tabla-ingresos tfoot').empty()
                $('#txt_proyecto').text('');
                $('#txt_periodo').text('');

                

                if(!jQuery.isEmptyObject(data.data_ingresos)){

                    $('#txt_proyecto').text($('#proyecto option:selected').text());
                    $('#txt_periodo').text($('input[name=fecha_desde]').val().substring(0,7))
                    tabla_ingresos(data.data_ingresos);
                }


                if(!jQuery.isEmptyObject(data.labels)){      
                    graficarDia(data.labels, data.data, $('input[name=fecha_desde]').val(), $('input[name=fecha_hasta]').val());
                    grafica_recaudo_factura(data.facturado_recaudado['titulos'], data.facturado_recaudado['facturado'],data.facturado_recaudado['recaudado'],$('input[name=fecha_desde]').val(), $('input[name=fecha_hasta]').val())
                    
                    
                    var sum_total = 0, 
                        total_facturas = 0, 
                        total_recaudos = 0, 
                        total_facturado = 0, 
                        total_recaudado = 0, 
                        i = 0;

                    $.each(data.facturado_recaudado['datos'], function(index1, obj1){

                        var factura = 0,
                            recaudo = 0,
                            facturado = 0,
                            recaudado = 0;

                        if (typeof(obj1['cantidad_fac']) != 'undefined') {
                            factura = parseInt(obj1['cantidad_fac']);
                        }

                        if(typeof(obj1['cantidad_rec']) != 'undefined'){
                            recaudo = parseInt(obj1['cantidad_rec']);
                        }

                        if(typeof(obj1['facturado']) != 'undefined'){
                            facturado = parseInt(obj1['facturado']);
                        }

                        if(typeof(obj1['recaudado']) != 'undefined'){
                            recaudado = parseInt(obj1['recaudado']);
                        }

                        console.log(obj1['cantidad_rec']);


                        $('#tabla-facturado-recaudado tbody').append('<tr><td>'+index1+'</td><td>'+factura+'</td><td>$'+new Intl.NumberFormat("es-CO").format(facturado)+'</td><td>'+recaudo+'</td><td>$'+new Intl.NumberFormat("es-CO").format(recaudado)+'</td><td>'+((recaudado/facturado) * 100).toFixed(2)+'%</td></tr>');

                        total_facturas += factura;
                        total_recaudos += recaudo;
                        total_facturado += facturado;
                        total_recaudado += recaudado;
                    });

                    $('#tabla-facturado-recaudado tfoot').append('<tr><th>TOTAL</th><th>'+total_facturas+'</th><th>$'+new Intl.NumberFormat("es-CO").format(total_facturado)+'</th><th>'+total_recaudos+'</th><th>$'+new Intl.NumberFormat("es-CO").format(total_recaudado)+'</th><th>'+((total_recaudado/total_facturado) * 100).toFixed(2)+'%</th></tr>');

                    $.each(data.recaudos, function(index, obj){
                        $('#tabla_por_dia').append('<tr><td>'+(i += 1)+'</td><td>'+obj.municipio+'</td><td>'+obj.departamento+'</td><td><span class="pull-right">$'+ new Intl.NumberFormat("es-CO").format(obj.total)+'</span></td></tr>');
                         sum_total += parseInt(obj.total);
                    });               

                    $('#total_por_dia').text('$' + new Intl.NumberFormat("es-CO").format(sum_total));
                }else{
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning("No hay datos");
                }
            }).fail(function(e){
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.error(e.statusText);
            });
        });

        function grafica_recaudo_factura(label1, dataset1, dataset2,desde,hasta){
            var grafica = new Highcharts.Chart({
                chart: {
                    renderTo: "grafica_recaudo_factura",
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'column'
                },
                title: {
                    text:  " Desde " + desde + " Hasta " + hasta,
                    x: -20 //center
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                   column: {
                       pointPadding: 0.2,
                       borderWidth: 0
                   }
               },
                xAxis: {
                    title: {
                        text: ""
                    },
                    categories: label1,
                },
                yAxis: {
                    title: {
                        text: "Element"
                    }
                },
                legend: {},
                series: [
                    {
                        name: "Facturado",
                        color: "#f44336",
                        data: dataset1
                    },
                    {
                        name: "Recaudado",
                        color: "#2196F3",
                        data: dataset2
                    }
                ]
            });
        }

        function graficarDia(label1, dataset1, desde, hasta){
            var grafica_ventas = new Highcharts.Chart({
                chart: {
                    renderTo: "grafica_por_dia",
                },
                title: {
                    text:  " Desde " + desde + " Hasta " + hasta,
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

        function tabla_ingresos(data){

            var total_internet = 0;
            var total_internet_iva = 0;
            var total_descuentos = 0;
            var total_facturado = 0;
            var total_impuesto = 0;
            var total_traslados = 0;
            var total_traslados_iva = 0;
            var total_traslados_impuesto = 0;
            var total_equipos = 0;
            var total_equipos_iva = 0;
            var total_equipos_impuesto = 0;

            var total_reconexion = 0;
            var total_reconexion_impuesto = 0;

            $.each(data, function(index, obj){

                var filas = `<tr>
                                <td>${index+1}</td>
                                <td>${obj.municipio}</td>
                                <td class="text-right">$${formatoMoneda(obj.internet)}</td>
                                <td class="text-right">$${formatoMoneda(obj.internet_iva)}</td>
                                <td class="text-right">$${formatoMoneda(obj.descuentos)}</td>
                                <td class="text-right">$${formatoMoneda(obj.internet - (obj.descuentos * -1))}</td>
                                <td class="text-right">$${formatoMoneda((obj.internet - (obj.descuentos * -1))*0.022)}</td>
                                <td class="text-right">$${formatoMoneda(obj.traslados)}</td>
                                <td class="text-right">$${formatoMoneda(obj.traslados_iva)}</td>
                                <td class="text-right">$${formatoMoneda(obj.traslados * 0.022)}</td>
                                <td class="text-right">$${formatoMoneda(obj.equipos)}</td>
                                <td class="text-right">$${formatoMoneda(obj.equipos_iva)}</td>
                                <td class="text-right">$${formatoMoneda(obj.equipos * 0.0055)}</td>
                                <td class="text-right">$${formatoMoneda(obj.reconexion)}</td>
                                <td class="text-right">$${formatoMoneda(obj.reconexion * 0.022)}</td>
                            </tr>
                `;

                $('#tabla-ingresos tbody').append(filas);

                total_internet += parseFloat(obj.internet);
                total_internet_iva += parseFloat(obj.internet_iva);
                total_descuentos += parseFloat(obj.descuentos);
                total_facturado += (obj.internet - (obj.descuentos * -1));
                total_impuesto += ((obj.internet - (obj.descuentos * -1))*0.022);
                total_traslados += parseFloat(obj.traslados);
                total_traslados_iva += parseFloat(obj.traslados_iva);
                total_traslados_impuesto += parseFloat(obj.traslados * 0.022);
                total_equipos += parseFloat(obj.equipos);
                total_equipos_iva += parseFloat(obj.equipos_iva);
                total_equipos_impuesto += parseFloat(obj.equipos * 0.0055);
                total_reconexion += parseFloat(obj.reconexion);
                total_reconexion_impuesto += parseFloat(obj.reconexion * 0.022);

            });

            var filas_footer = `<tr>
                            <td colspan="2" class="text-right">TOTALES:</td>
                            <td class="text-right">$${formatoMoneda(total_internet)}</td>
                            <td class="text-right">$${formatoMoneda(total_internet_iva)}</td>
                            <td class="text-right">$${formatoMoneda(total_descuentos)}</td>
                            <td class="text-right">$${formatoMoneda(total_facturado)}</td>
                            <td class="text-right">$${formatoMoneda(total_impuesto)}</td>
                            <td class="text-right">$${formatoMoneda(total_traslados)}</td>
                            <td class="text-right">$${formatoMoneda(total_traslados_iva)}</td>
                            <td class="text-right">$${formatoMoneda(total_traslados_impuesto)}</td>
                            <td class="text-right">$${formatoMoneda(total_equipos)}</td>
                            <td class="text-right">$${formatoMoneda(total_equipos_iva)}</td>
                            <td class="text-right">$${formatoMoneda(total_equipos_impuesto)}</td>
                            <td class="text-right">$${formatoMoneda(total_reconexion)}</td>
                            <td class="text-right">$${formatoMoneda(total_reconexion_impuesto)}</td>
                        </tr>
            `;

            $('#tabla-ingresos tfoot').append(filas_footer);
        }


        const formatoMoneda = (valor) => {

            if(valor === undefined || valor === null || valor === ''){
                valor = 0;
            }else{
                valor = parseFloat(valor);
            }

            
            return new Intl.NumberFormat("es-CO").format(valor);

            //return valor.toLocaleString('es-CO',{style:'currency', currency:'COP', minimumFractionDigits:2, maximumFractionDigits:2});

        }

        const formatoPorcentaje = (valor) =>{

            if(valor >= 0) {
                return valor.toLocaleString('es-CO',{style:'percent', minimumFractionDigits:2});
            }else{
                return (0).toLocaleString('es-CO',{style:'percent', minimumFractionDigits:2});
            }
        }
    </script>

    <script type="text/javascript">

        $('#form-suspendidos').on('submit', function(e){
            e.preventDefault();

            var formData = new FormData(this);
            var fila = $('#table-suspendidos tbody');

            const mes = $(this).find('input[name=mes]').val();

            const [anno, month] = mes.split('-').map(Number);

            const ultimo_dia = new Date(anno, month, 0).getDate();

            $(this).find('button').attr('disabled',true);
            $(this).find('#icono').removeClass('fa-search');
            $(this).find('#icono').addClass('fa-refresh fa-spin');

            $.ajax({
                    url: "/estadisticas/suspendidos-recaudos",
                    type: "post",
                    dataType: "json",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                })
                .done(function(res){

                    if(res.datos.length > 0){

                        fila.empty();

                        $.each(res.datos, function(index, rsObj){
                           fila.append(`
                            <tr>
                                <td>${rsObj.municipio}</td>
                                <td>
                                    <a href="/novedades?fecha_inicio=${mes + '-01'}&municipio=${rsObj.MunicipioId}&concepto=Suspensión por Mora">${rsObj.total_clientes}</a>
                                </td>
                                <td>$${formatoMoneda(rsObj.total_facturado)}</td>
                                <td>
                                    <a href="/recaudos?fecha_desde=${mes + '-01'}&fecha_hasta=${mes +'-'+ultimo_dia}&municipio=${rsObj.MunicipioId}">${rsObj.cantidad_recaudos}</a>
                                </td>
                                <td>$${formatoMoneda(rsObj.recaudo_mes)}</td>
                                <td>
                                    <a href="/novedades?fecha_inicio=${mes + '-01'}&municipio=${rsObj.MunicipioId}&estado=FINALIZADA&concepto=Suspensión por Mora">${rsObj.reconectados}</a>
                                </td>
                                <td>
                                    <a href="/clientes-suspensiones?fecha_inicio=${mes + '-01'}&municipio=${rsObj.MunicipioId}">${(rsObj.total_clientes - rsObj.reconectados)}</a>
                                </td>
                            <tr>`)
                        });
                    }

                    $(this).find('button').attr('disabled',false);
                    $(this).find('#icono').removeClass('fa-refresh fa-spin');
                    $(this).find('#icono').addClass('fa-search');

                    
                }).fail(function( jqXHR, textStatus, errorThrown ) {                    

                    if(jqXHR.status == 422){

                        $.each(jqXHR.responseJSON.errors, function(index, respuestaObj){
                            toastr.error(respuestaObj);
                        });

                    }else{
                        toastr.error(errorThrown);
                    }

                    $(this).find('button').attr('disabled',false);
                    $(this).find('#icono').removeClass('fa-refresh fa-spin');
                    $(this).find('#icono').addClass('fa-search');
                });


        });
        
    </script>

    @permission('estadisticas-exportar')
    <script type="text/javascript">
        $('#exportar').on('click',function(){
            var parametros = {
                fecha_desde : $('input[name=fecha_desde]').val(),
                fecha_hasta : $('input[name=fecha_hasta]').val(),
                proyecto : $('#proyecto').val(),
                municipio : $('#municipio').val(),
                departamento : $('#departamento').val(),
                '_token' : $('input:hidden[name=_token]').val() 
            }

            $('#exportar').attr('disabled',true);
            $('#icon-opciones').removeClass('fa-file-excel-o');
            $('#icon-opciones').addClass('fa-refresh fa-spin');

            $.ajax({
                type: "POST",
                url: '/estadisticas/exportar',
                data: parametros,
                xhrFields: {
                    responseType: 'blob' // to avoid binary data being mangled on charset conversion
                },
                success: function(blob, status, xhr) {
                    // check for a filename
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    }

                    if (typeof window.navigator.msSaveBlob !== 'undefined') {
                        // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        var URL = window.URL || window.webkitURL;
                        var downloadUrl = URL.createObjectURL(blob);

                        if (filename) {
                            // use HTML5 a[download] attribute to specify filename
                            var a = document.createElement("a");
                            // safari doesn't support this yet
                            if (typeof a.download === 'undefined') {
                                window.location.href = downloadUrl;
                            } else {
                                a.href = downloadUrl;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();
                            }
                        } else {
                            window.location.href = downloadUrl;
                        }

                        setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                    }

                    $('#exportar').attr('disabled',false);
                    $('#icon-opciones').removeClass('fa-refresh fa-spin');
                    $('#icon-opciones').addClass('fa-file-excel-o');
                },
                error: function(blob, status, xhr){
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.error(xhr);

                    $('#exportar').attr('disabled',false);
                    $('#icon-opciones').removeClass('fa-refresh fa-spin');
                    $('#icon-opciones').addClass('fa-file-excel-o');
                }

            });

        });
    </script>
    @endpermission
  @endsection
@endsection