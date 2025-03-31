@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-home"></i>  Facturas - {{$periodo}}</h1>
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{$facturacion->total()}}</h3>

                  <p>Total Facturas</p>
                </div>
                <div class="icon">
                  <i class="fa fa-file-text-o"></i>
                </div>
                <a href="{{route('facturacion.view',$periodo)}}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{$facturacion->total() - count($fact)}}</h3>

                  <p>Facturas Reportadas</p>
                </div>
                <div class="icon">
                  <i class="fa fa-qrcode"></i>
                </div>
                <a href="{{route('facturacion.view',$periodo)}}?estado=true" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{$facturas_encero_favor}}</h3>

                  <p>Saldo a Favor o En ceros</p>
                </div>
                <div class="icon">
                  <i class="fa fa-dollar"></i>
                </div>
                <a href="#" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{$total_errores}}</h3>

                  <p>Facturas con Errores</p>
                </div>
                <div class="icon">
                  <i class="fa fa-ban"></i>
                </div>
                <a href="{{route('facturacion.view',$periodo)}}?estado=false" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
        </div>

        <div class="row">

            <div class="col-md-12">            	
            	<div class="box box-info" id="espera">
					<div class="box-header bg-blue">

                        <form id="form-buscar" action="{{route('facturacion.view', $periodo)}}" role="search" method="GET">  
                            @role(['admin','contador'])
                            <div class="btn-group pull-right">

                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="#" id="reportar_facturas"><i class="fa fa-cloud-upload"></i> Reportar</a>
                                    </li>
                                    @permission('facturacion-exportar')
                                        <li>
                                            <a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a>
                                        </li>
                                    @endpermission
                                </ul>
                            </div>
                            @endrole                         
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" name="palabra" placeholder="Buscar" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}" autocomplete="off">
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="clasificacion" >
                                                <option value="">Elija una clasificación</option>
                                                <option value="CASMOT" {{(isset($_GET['clasificacion'])) ? (($_GET['clasificacion'] == 'CASMOT') ? 'selected' : '') : ''}}>CASMOT</option>
                                                <option value="DIALNET" {{(isset($_GET['clasificacion'])) ? (($_GET['clasificacion'] == 'DIALNET') ? 'selected' : '') : ''}}>DIALNET</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="">Elija un proyecto</option>
                                                @foreach($proyectos as $proyecto)

                                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="departamento" id="departamento">
                                                <option value="">Elija un departamento</option>
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default" style="height: 85px;"> <i class="fa fa-search"></i>  Buscar</button>
                                </div>
                            </div>                              
                        </form>

					</div>

					
					<div class="box-body table-responsive">
						<table  id="areas" class="table table-bordered table-striped dataTable">
							<tbody>
								<tr>
									<th>ID</th>
									<th>Documento</th>
									<th>Nombre</th>
									<th>Municipio</th>
									<th>Valor Total</th>
									<th>Estado</th>									
									<th>Acciones</th>
								</tr>
								@foreach($facturacion as $dato)
									<tr style="{!!($dato->estado == 'ANULADA')? 'text-decoration: line-through;' : ''!!}">
										<td><a href="{{route('facturacion.show', array('periodo' => $periodo,'id' =>$dato->FacturaId))}}">{{$dato->FacturaId}}</a></td>
										<td>{{$dato->cliente->Identificacion}}</td>
										<td>{{mb_convert_case($dato->cliente->NombreBeneficiario . ' ' . $dato->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
                                     

                                        @if(!empty($dato->cliente->municipio))
                                            <td>{{$dato->cliente->municipio->NombreMunicipio}} - {{$dato->cliente->municipio->departamento->NombreDelDepartamento}}</td>
                                        @else
                                            <td>{{$dato->cliente->ubicacion->municipio->NombreMunicipio}} - {{$dato->cliente->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                        @endif


										<td>${{number_format($dato->ValorTotal, 2, ',', '.')}}</td>
										<td class="text-center">
                                            @if(!empty($dato->factura_electronica))
                                                @if($dato->factura_electronica->reportada)
                                                    <i class="fa fa-check text-success"></i>
                                                @else
                                                    <i class="fa fa-ban text-danger"></i>
                                                @endif
                                            @else
                                                PENDIENTE
                                            @endif
                                        </td>
										<td>                                            

                                            @if(!empty($dato->factura_electronica))
                                                @if($dato->factura_electronica->reportada) 

                                                    @if(!empty($dato->factura_electronica->archivo))
                                                    <a href="{{$dato->factura_electronica->archivo}}" class="btn btn-success btn-xs" title="Descargar" target="_black"><i class="fa fa-download"></i></a>                                                    
                                                    @endif
                                                @else

                                                    @if(empty($dato->factura_electronica->archivo))
                                                        @permission('facturacion-eliminar')
                                                            <form action="{{route('facturacion.destroy', $dato->FacturaId)}}" method="post" style="display:inline-block;">
                                                                <input type="hidden" name="_method" value="delete">
                                                                <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                                    <i class="fa fa-trash-o"></i>   
                                                                </button>
                                                            </form>
                                                        @endpermission
                                                    @endif
                                                @endif
                                            @else

                                                <form action="{{route('facturacion.destroy', $dato->FacturaId)}}" method="post">
                                                    <input type="hidden" name="_method" value="delete">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">                                                
                                                    @role('admin')

                                                    <a href="#" class="btn btn-primary btn-xs" title="Reportar" onclick="reportarFactura({{$dato->FacturaId}});"><i class="fa fa-upload"></i></a>

                                                    <a href="{{route('facturacion.edit', $dato->FacturaId)}}" class="btn btn-primary btn-xs" title="Editar"><i class="fa fa-edit"></i></a>
                                                    @endrole

                                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                        <i class="fa fa-trash-o"></i>   
                                                    </button>
                                                </form>
                                                
                                            @endif

                                            @role('admin')

                                                    <a href="#" class="btn btn-primary btn-xs" title="Reportar" onclick="reportarFactura({{$dato->FacturaId}});"><i class="fa fa-upload"></i></a>
                                            @endrole

											
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					

					<div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$facturacion->currentPage()}} de {{$facturacion->lastPage()}}. Total registros {{$facturacion->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $facturacion->appends(Request::only(['proyecto', 'municipio']))->links() !!}
                    </div>
                </div>
            </div>

            @role('admin')
            <div class="col-md-6">
                <div class="box box-solid box-default">
                    <div class="box-header bg-blue">
                    </div>
                    <div class="box-body table-responsive">
                        <div id="mainBlock">
                            <br>
                           
                            <br>
                            <br>
                            <span id="lblMensaje" style="color: red;">Aquí va el mensaje recibido</span>
                            <br>
                            <br>
                            <div id="dvDetalle"></div>
                            <br>
                            <br>
                            <span>Documento a Enviar</span>
                            <textarea id="txtDocumento" style="width: 100%; height: 250px;"></textarea>
                             <input id="btnEnviar" type="button" value="Enviar Documento" onclick="javascript:funEnviarDocumento();" />
                        </div>
                    </div>
                </div>                
            </div>
            @endrole

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

        <script type="text/javascript">
            var ids = [];
            toastr.options.positionClass = 'toast-bottom-right';
            
            @foreach($fact as $factura_pendiente)
                ids.push({{$factura_pendiente->FacturaId}});
            @endforeach

            const setText = data => {
                consecutivo.textContent = data
            }

            const getData = (value) => {
                return new Promise((resolve, reject) => {
                    var parameters = {
                        factura_id : value,
                        '_token' : $('input:hidden[name=_token]').val()
                    }

                    $.post('/facturacion/{{$periodo}}', parameters).done(function(data){
                        resolve(data)
                    }).fail(function(){
                        reject('error in get');
                    });
                })
            }

            const sendData = (urlApi, parametros) => {
                return new Promise ((resolve, reject) =>{
                    // Datos particulares
                    var paramListData = JSON.stringify(parametros);

                    try {
                        jQuery.support.cors = true;

                        $.ajax({
                            url: urlApi,
                            type: 'POST',
                            data: paramListData,
                            async: true,
                            contentType: 'application/json; utf-8',
                            dataType: 'json'
                        })
                        .done(function (data) {
                            resolve(data.Data)
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            reject(jqXHR);
                        });

                    } catch (e) {
                        var errorTitle = "API call failed";
                        reject(e.message)
                    }
                })
            }

            const saveData = (reportada, numero_factura_dian, factura_id, documento_id_feel, archivo) => {
                return new Promise((resolve, reject) =>{

                    var parameters = {
                        reportada : reportada,
                        numero_factura_dian : numero_factura_dian,
                        factura_id : factura_id,
                        documento_id_feel : documento_id_feel,
                        archivo : archivo,
                        '_token' : $('input:hidden[name=_token]').val()
                    }

                    $.post('/facturacion', parameters).done(function(data){
                        resolve(data)

                    }).fail(function(){
                       reject('error in save');
                    });
                })
            }

            const saveDetailsData = (factura_electronica_id,fecha,concepto,detalles) => {
                return new Promise((resolve, reject) =>{

                    var parameters = {
                        factura_electronica_id : factura_electronica_id,
                        fecha : fecha,
                        concepto: concepto,
                        detalles : detalles,                    
                        '_token' : $('input:hidden[name=_token]').val()
                    }

                    $.post('/facturacion/{{$periodo}}/detalles', parameters).done(function(data){
                        resolve(data)

                    }).fail(function(){
                       reject('error in save');
                    });
                })
            }

            reportar_facturas.addEventListener('click', async () => {
                if(confirm('Desea generar el envío de '+ ids.length +' facturas a la DIAN?')){
                    $('#espera').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i><div class="progress-group" style="position: absolute;top: 55%;left: 40%;margin-left: -15px;width: 30%;margin-top: -15px;"><span class="progress-text">Facturas Reportadas a la DIAN</span><span class="progress-number"><b id="consecutivo">0</b>/{{count($fact)}}</span><div class="progress active"><div id="myBar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="{{count($fact)}}"></div></div></div></div>');

                    for (var i = 0; i <= ids.length; i++) {                

                        if (i == ids.length) {
                            $('.overlay').remove();
                            $('.progress').removeClass('active');
                            //setText(i);
                        }else{

                            $('#myBar')
                            .css('width', (((i + 1) * 100) / ids.length) +'%')
                            .attr('aria-valuenow', (i + 1))
                            .text((((i + 1) * 100) / ids.length).toFixed(2)  + '%');

                            setText(i + 1);

                            const datosFactura = await getData(ids[i]) 

                            var factura_id = ids[i];

                            var reportada = false;
                            var numero_factura_dian = '';
                            var documento_id_feel = '';
                            var archivo = '';

                            var concepto = '';
                            var detalles = '';
                            var fecha = '';


                            if (datosFactura.reportar) {

                                const api = datosFactura.api;
                                
                                let parametros = {
                                    Controller : api.controlador,
                                    Action : api.accion,
                                    ParamValues : {
                                        TokenIdentificador: api.token_identificador,
                                        DocumentoObject: datosFactura.datosjson
                                    }
                                }

                                let jsonData = await sendData(api.url_api, parametros)

                                //jsonData.Summary: Objeto con resumen del resultado
                                //jsonData.DataResult[0]: Objeto con detalle del proceso

                                
                                // Si hubo error
                                if (jsonData.Summary !== null && jsonData.Summary.Message !== "") {

                                    if (jsonData.DataResult.length > 0 && jsonData.Summary.Success == false) {
                                        //No se pudo reportar error en el json
                                        reportada = jsonData.Summary.Success;
                                        concepto = 'Error en la estructura JSON enviada';
                                        detalles = jsonData.Summary.Message;

                                        toastr.error(detalles + '. FACTURA ID: ' + factura_id);

                                        if (jsonData.DataResult[0].DocumentoEstadoDianNombre == "Exitosa") {
                                            factura_id = jsonData.DataResult[0].DocumentoRelacionado;
                                            numero_factura_dian = jsonData.DataResult[0].DocumentoGenerado;
                                            archivo = jsonData.DataResult[0].DocumentoLink;
                                            documento_id_feel = jsonData.DataResult[0].DocumentoId;
                                            reportada = true;
                                        }

                                    }else{
                                        //El documento ya existe
                                        //Obtener el id de la factura que ya existe y guardar el mensaje retornado

                                        if(jsonData.DataResult.length === 0){
                                            //Consulta documento FEEL
                                            
                                            let parametros = {
                                                Controller : "Facturas",
                                                Action : "GetDocument",
                                                ParamValues : {
                                                    TokenIdentificador: api.token_identificador,
                                                    TipoDocumento: 1,
                                                    DocumentoRelacionado: factura_id
                                                }
                                            }

                                            toastr.error(jsonData.Summary.Message + '. FACTURA ID: ' + factura_id);

                                            jsonData = await sendData(api.url_api, parametros)

                                            factura_id = jsonData.DataResult[0].DocumentoRelacionado;

                                            concepto = 'FacturaOrigenExistente';
                                            detalles = "Actualizado usando la consulta de documentos de FEEL";
                                            //tengo mis dudas
                                            numero_factura_dian = jsonData.DataResult[0].DocumentoNumero;
                                            archivo = jsonData.DataResult[0].DocumentoLinkPdf;
                                            documento_id_feel = jsonData.DataResult[0].EstadoDianIdentificador;
                                            reportada = true;

                                        }else{                                                                             

                                            factura_id = jsonData.DataResult[0].DocumentoRelacionado;

                                            concepto = 'FacturaOrigenExistente';
                                            detalles = jsonData.Summary.Message;
                                            //tengo mis dudas
                                            numero_factura_dian = jsonData.DataResult[0].DocumentoGenerado;
                                            archivo = jsonData.DataResult[0].DocumentoLink;
                                            documento_id_feel = jsonData.DataResult[0].DocumentoId;
                                            reportada = true;                                           

                                        }
                                        
                                        
                                    }

                                    //Guardar resultado de la API
                                    const resultado = await saveData(reportada, numero_factura_dian, factura_id, documento_id_feel, archivo)

                                    if (resultado.result) {
                                        console.log(resultado.id);

                                        await saveDetailsData(resultado.id,'',concepto,detalles)
                                    }

                                }else {

                                    //El documento es reportado satisfactoriamente
                                    reportada = jsonData.Summary.Success;
                                    numero_factura_dian = jsonData.DataResult[0].DocumentoGenerado;
                                    documento_id_feel = jsonData.DataResult[0].DocumentoId;
                                    archivo = jsonData.DataResult[0].DocumentoLink;

                                    //Guardar resultado de la API
                                    const resultado = await saveData(reportada, numero_factura_dian, factura_id, documento_id_feel, archivo)

                                    if (resultado.result) {
                                        console.log(resultado.id);

                                        jsonData.DataResult[0].DocumentoLog.forEach(async (value) => {
                                            await saveDetailsData(resultado.id,value.LogFechaHora,value.LogProceso,value.LogProcesoDetalle)
                                        });

                                        console.log(i);
                                    }
                                }

                                //if (reportada !== false && Object.keys(jsonData.DataResult[0].DocumentoLog).length < 3) {}

                            }else{
                                //El valor total de la factura es diferente a la suma de todos los items de los detalles.
                                const resultado = await saveData(reportada, numero_factura_dian, factura_id, documento_id_feel)

                                if (resultado.result) {
                                    console.log(resultado.id);
                                    await saveDetailsData(resultado.id,'','Mal cálculo del total de la factura','La suma de los items que se estan cobrando es diferente al valor total que se calculó.')
                                }
                            }
                            
                        }
                        
                    }
                }
            });        
        </script>

        @role('admin')
            <script type="text/javascript">

                var se_puede_reportar = true;

                function reportarFactura(id){
            		
                    var parameters = {
                        factura_id : id,
                        '_token' : $('input:hidden[name=_token]').val()
                    }

                    $.post('/facturacion/202002', parameters).done(function(data){ 
                        $('#txtDocumento').val(JSON.stringify(data.datosjson));

                        se_puede_reportar = data.reportar;
                        //console.log();
                    }).fail(function(){
                        alert('Error');
                    });
            	}


                function funEnviarDocumento() {
                    var reportada = false;
                    var observaciones = 'La suma de los items que se estan cobrando es diferente al valor total que se calculó.';
                    var fecha_reporte = '';
                    var numero_factura_dian = '';
                    var factura_id = '';
                    var detalles_correo = '';


                    // Datos particulares
                    var urlApi = "https://feelfactura.com/FeelTestV2Api/api/MainExternal";
                    var tokenIdentificador = "PruebasFeel";

                    $('#lblMensaje').text("Enviando Documento...");
                    $('#dvDetalle').html('');

                    // Define parámetros
                    var contentDocumentoText = $("#txtDocumento").val();
                    var paramList = {
                        Controller : "Facturas",
                        Action : "SendDocument",
                        ParamValues : {
                            TokenIdentificador: tokenIdentificador,
                            DocumentoObject: JSON.parse(contentDocumentoText)
                        }
                    }; 
                    var paramListData = JSON.stringify(paramList);

                    if (se_puede_reportar) {

                        try {
                            jQuery.support.cors = true;

                            $.ajax({
                                url: urlApi,
                                type: 'POST',
                                data: paramListData,
                                async: true,
                                contentType: 'application/json; utf-8',
                                dataType: 'json'
                            })
                                .done(function (data) {
                                    var jsonData = data.Data;
                                    /*
                                     jsonData.Summary: Objeto con resumen del resultado
                                     jsonData.DataResult[0]: Objeto con detalle del proceso
                                    */

                                    console.log(jsonData);
                                    // Si hubo error
                                    if (jsonData.Summary !== null && jsonData.Summary.Message !== "") {
                                        $('#lblMensaje').text(jsonData.Summary.Message);
                                        console.log('entró1');

                                        if(jsonData.DataResult[0].DocumentoGenerado !== null){
                                            console.log('entró2');
                                            reportada = jsonData.Summary.Success;
                                            observaciones = jsonData.Summary.Message; 
                                        }
                                                                   
                                    }
                                    else {
                                        console.log('entró3');
                                        reportada = jsonData.Summary.Success;
                                        numero_factura_dian = jsonData.DataResult[0].DocumentoGenerado;
                                        fecha_reporte = jsonData.DataResult[0].DocumentoLog[0].LogFechaHora;
                                        observaciones = jsonData.DataResult[0].DocumentoLog[0].LogProceso + ' ' + jsonData.DataResult[0].DocumentoLog[0].LogProcesoDetalle;

                                        factura_id = jsonData.DataResult[0].DocumentoRelacionado;
                                        detalles_correo = jsonData.DataResult[0].DocumentoLog[1].LogProceso + ' ' + jsonData.DataResult[0].DocumentoLog[1].LogProcesoDetalle;

                                        $('#lblMensaje').text("Documento Generado " + jsonData.DataResult[0].DocumentoGenerado);
                                        jsonData.DataResult[0].DocumentoLog.forEach(function (value) {
                                            $('#dvDetalle').append($('<span>').text(value.LogFechaHora))
                                            $('#dvDetalle').append($('<br>'))
                                            $('#dvDetalle').append($('<span>').text(value.LogProceso))
                                            $('#dvDetalle').append($('<br>'))
                                            $('#dvDetalle').append($('<span>').text(value.LogProcesoDetalle))
                                            $('#dvDetalle').append($('<br>'))
                                            $('#dvDetalle').append($('<hr>'))
                                        });
                                    }

                                    if (reportada !== false && Object.keys(jsonData.DataResult[0].DocumentoLog).length < 3) {
                                        console.log('entró4');

                                        var parameters = {
                                            reportada : reportada,
                                            observaciones : observaciones,
                                            fecha_reporte: fecha_reporte,
                                            numero_factura_dian : numero_factura_dian,
                                            factura_id : factura_id,
                                            detalles_correo : detalles_correo,
                                            '_token' : $('input:hidden[name=_token]').val()
                                        }

                                        $.post('/facturacion', parameters).done(function(data){
                                            console.log(data);

                                        }).fail(function(){
                                           console.log('error');
                                        });
                                    }

                                    console.log('entró5');
                                    
                                    

                                })
                                .fail(function (jqXHR, textStatus, errorThrown) {
                                    console.log(jqXHR);

                                    var failText = jqXHR.status + " : " + jqXHR.responseText;
                                    $('#lblMensaje').text(failText);
                                });

                        } catch (e) {
                            var errorTitle = "API call failed";

                            $('#lblMensaje').text(errorTitle + ": " + e.message);
                            console.log(errorTitle + ": " + e.message);
                            console.log(errorTitle + ": " + e.stack);
                        }

                    }else{
                        var parameters = {
                            reportada : reportada,
                            observaciones : observaciones,
                            fecha_reporte: fecha_reporte,
                            numero_factura_dian : numero_factura_dian,
                            factura_id : factura_id,
                            detalles_correo : detalles_correo,
                            '_token' : $('input:hidden[name=_token]').val()
                        }

                        $.post('/facturacion', parameters).done(function(data){
                            console.log(data);

                        }).fail(function(){
                           console.log('error');
                        });
                    }
                }
            </script>
        @endrole

        

        @permission('facturacion-exportar')
            <script type="text/javascript" src="/js/facturacion/export.js"></script>
            <script type="text/javascript">
                $('#exportar').on('click',function(){
                    var parametros = {
                        periodo : "{{$periodo}}",
                        palabra : "{!! (isset($_GET['palabra'])? $_GET['palabra']:'') !!}",
                        proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                        municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                        departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                        estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                        '_token' : $('input:hidden[name=_token]').val() 
                    }

                    $('#opciones').attr('disabled',true);
                    $('#icon-opciones').removeClass('fa-gears');
                    $('#icon-opciones').addClass('fa-refresh fa-spin');

                    $.ajax({
                        type: "POST",
                        url: '/facturacion/exportar',
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

                            $('#opciones').attr('disabled',false);
                            $('#icon-opciones').removeClass('fa-refresh fa-spin');
                            $('#icon-opciones').addClass('fa-gears');
                        },
                        error: function(blob, status, xhr){
                            toastr.options.positionClass = 'toast-bottom-right';
                            toastr.error(xhr);

                            $('#opciones').attr('disabled',false);
                            $('#icon-opciones').removeClass('fa-refresh fa-spin');
                            $('#icon-opciones').addClass('fa-gears');
                        }

                    });
                });
            </script>
        @endpermission
    @endsection
@endsection