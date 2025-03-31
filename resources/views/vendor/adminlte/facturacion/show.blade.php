@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-home"></i>  Factura #{{$factura->FacturaId}}</h1>
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
    <section class="invoice" style="{!!($factura->estado == 'ANULADA')? 'background: url(/img/anulada.jpg) #FFF;
    background-size: cover;' : ''!!}">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <img src="/img/amigored.png" height="80px">
            <div class="pull-right">
              <small>Fecha de Emisión: {{$factura->FechaEmision}}</small>
              <br>
            </div>
            
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Remite
          <address>
            <strong>SISTEMAS Y TELECOMUNICACIONES DEL ORIENTE S.A.S.</strong><br>
            <b>NIT.</b> 804.003.326-6<br>
            Calle 35 No. 17-77 Oficina 301<br>
            Bucaramanga - Santander <br>
            (607)6335080<br>
            <b>Email:</b> servicioalcliente@sisteco.com.co
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 col-xs-6 invoice-col">
          Cliente
          <address>
            <strong>{{$factura->cliente->NombreBeneficiario}} {{$factura->cliente->Apellidos}}</strong><br>
            <b>C.C.</b> <a href="{{route('clientes.show', $factura->ClienteId)}}">{{$factura->cliente->Identificacion}}</a> <br>
            {{$factura->cliente->DireccionDeCorrespondencia}}<br>
            @if(!empty($factura->cliente->municipio))
              {{$factura->cliente->municipio->NombreMunicipio}} - {{$factura->cliente->municipio->departamento->NombreDelDepartamento}}
            @else
              {{$factura->cliente->ubicacion->municipio->NombreMunicipio}} - {{$factura->cliente->ubicacion->municipio->departamento->NombreDelDepartamento}}
            @endif
            <br>
            {{$factura->cliente->TelefonoDeContactoFijo}} - {{$factura->cliente->TelefonoDeContactoMovil}}<br>
            <b>Email:</b> {{$factura->cliente->CorreoElectronico}}
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 col-xs-6 invoice-col">          
          <b>Factura Electronica: {{isset($factura->factura_electronica->numero_factura_dian)? $factura->factura_electronica->numero_factura_dian : 'SIN REPORTAR'}}</b><br>
          <br>
          <b>Proyecto:</b> {{!empty($factura->ProyectoId)? $factura->proyecto->NumeroDeProyecto : 'SIN PROYECTO'}}<br>
          <b>Periodo Facturado:</b> {{$factura->PeriodoFacturado}}<br>
          <b>Fecha límite de pago:</b> 
          @if(intval($factura->SaldoEnMora) > 100)
            <label class="label label-danger">INMEDIATO</label>
          @else
            {{$factura->FechaDePago}}
          @endif
          <br>
          <b>Factura ID:</b> {{$factura->FacturaId}}
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
        	<table class="table table-striped">
	            <thead>
		            <tr>
						<th>#</th>
						<th>Descripcion</th>
						<th>Cantidad</th>
            <th>Unidad Medida</th>
						<th>Val. Uni.</th>
						<th>IVA</th>
						<th>Total</th>
		            </tr>
	            </thead>
	            <tbody>
	            	<?php $i = 1; $total = 0; ?>

                @if($factura->Periodo >= 202105)

                  @foreach($factura->item as $item)
                    <tr>
                    <td>{{$i}}</td>
                    <td>{{$item->concepto}}</td>
                    <td>{{$item->cantidad}}</td>
                    <td>{{$item->unidad_medida}}</td>
                    <td>${{number_format($item->valor_unidad, 2, ',', '.')}}</td>
                    <td>${{number_format($item->valor_iva, 2, ',', '.')}}</td>
                    <td>${{number_format($item->valor_total, 2, ',', '.')}}</td>
                    <?php $i += 1; $total = $total + $item->valor_total; ?>
                  </tr>
                  @endforeach

                @else
                  @include('adminlte::facturacion.partials.202105')  	            	
                @endif
	            </tbody>
	        </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead">Metodos de Pago:</p>
          <table>
          	<tr>
          		<td><img src="/img/efecty.jpg" height="80px"></td>
          		<!--<td><img src="/img/baloto.jpg" height="80px"></td>-->
          	</tr>
          	<tr>
          		<td bgcolor="#ffd600" class="text-center"><b>CONVENIO: 111008</b></td>
          		<!--<td bgcolor="#003b87" class="text-center"><b style="color: #fff;">CONVENIO: 950693</b></td>-->
          	</tr>
          </table>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Resumen</p>

          <div class="table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width:50%">Subtotal:</th>
                  <td>${{number_format($total - $factura->Iva, 2, ',', '.')}}</td>
                </tr>
                <tr>
                  <th>IVA (19%)</th>
                  <td>${{number_format($factura->Iva, 2, ',', '.')}}</td>
                </tr>
                <?php 

                $total = $factura->ValorTotal;

                $nota_debido = 0;
                $nota_credito = 0;

                foreach ($factura->nota as $nota) {
                  if ($nota->tipo_nota == 'DEBITO') {
                    $nota_debido = $nota_debido + $nota->valor_total;
                  }else{
                    $nota_credito = $nota_credito + $nota->valor_total - (($nota->descuento / 100) * $nota->valor_total);
                  }
                }

                ?>
                @if(isset($factura->nota))                  
                    <tr>
                      <th>Total Notas Debito</th>
                      <td>${{number_format($nota_debido, 2, ',', '.')}}</td>
                    </tr>
                    <tr>
                      <th>Total Notas Credito</th>
                      <td>-${{number_format($nota_credito, 2, ',', '.')}}</td>
                    </tr>                   
                @endif
                <tr>
                  <th>Total a Pagar:</th>
                  <td>${{number_format(($total + $nota_debido - $nota_credito), 2, ',', '.')}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>

    <div class="row">
      <div class="col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Facturacion Electronica</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            @if(isset($factura->factura_electronica))
            <div class="row">
              <div class="col-sm-4 col-xs-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-green"><i class="fa fa-check"></i> </span>                    
                  <span class="description-text">
                    @if($factura->factura_electronica->reportada)
                      <span class="text-success">REPORTADA</span>
                    @else
                      <span class="text-danger">SIN REPORTAR</span>
                    @endif
                  </span>
                  <h5 class="description-header">ESTADO</h5>
                </div>
                <!-- /.description-block -->
              </div>

              <div class="col-sm-4 col-xs-6">
                <div class="description-block border-right">
                  <span class="description-percentage"><i class="fa fa-qrcode"></i></span>                    
                  <span class="description-text">
                    {{$factura->factura_electronica->numero_factura_dian}}
                  </span>
                  <h5 class="description-header">Factura DIAN</h5>
                </div>
                <!-- /.description-block -->
              </div>

              <div class="col-sm-4 col-xs-6">
                <div class="description-block border-right">
                  <span class="description-percentage"><i class="fa fa-tag"></i></span>                    
                  <span class="description-text">
                    {{$factura->factura_electronica->documento_id_feel}}
                  </span>
                  <h5 class="description-header">ID Documento FEEL</h5>
                </div>
                <!-- /.description-block -->
              </div>
            </div>
            <hr width="90%">
            <div class="table-responsive">
              <table class="table no-margin">
              <thead>
                <tr>
                  <th>Concepto</th>
                  <th>Fecha</th>
                  <th>Detalles</th>
                </tr>
              </thead> 
                <tbody>
                  @foreach($factura->factura_electronica->detalles_factura_electronica as $datos)
                  <tr>
                    <td>{{$datos->concepto}}</td>
                    <td>{{$datos->fecha}}</td>
                    <td>{{$datos->detalles}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
            @else
            SIN DATOS
            @endif
          </div>
        </div>
      </div>
      @permission('facturacion-notas-ver')
      <div class="col-md-6">
        <div class="box box-info">
          <div class="box-header with-border bg-blue">
            <h3 class="box-title">Facturacion Notas </h3>

            @permission('facturacion-notas-crear')
              @if($ultimo_periodo->Periodo == $factura->Periodo)
                <div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addNota"> <i class="fa fa-plus"></i> Crear</button>
                </div>
              @endif
            @endpermission
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            @if(isset($factura->nota))
            <div class="table-responsive">
              <table class="table no-margin">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tipo</th>
                  <th>Fecha</th>
                  <th>Valor</th>
                  <th>Accion</th>
                </tr>
              </thead> 
                <tbody>
                  @foreach($factura->nota as $nota)
                  <tr>
                    <td>
                      <a style="cursor: pointer;" onclick="traer_nota({!!$nota->id!!});return false;">
                        @if(empty($nota->numero_nota_dian))
                          {{$nota->id}}
                        @else
                          {{$nota->numero_nota_dian}}
                        @endif
                      </a>
                    </td>
                    <td>{{$nota->tipo_nota}}</td>
                    <td>{{$nota->fecha_expedision}}</td>
                    <td>${{number_format($nota->valor_total,2,',','.')}}</td>
                    <td>
                      @if($nota->reportada)
                        @if(!empty($nota->archivo))
                        <a href="{{$nota->archivo}}" class="btn btn-success btn-xs" title="Descargar" target="_blank"><i class="fa fa-download"></i></a>
                        @endif
                      @else


                        @if($ultimo_periodo->Periodo == $factura->Periodo || $factura->Periodo == date('Ym'))

                          @permission('facturacion-notas-reportar')

                            @if(isset($factura->factura_electronica))
                              @if(!empty($factura->factura_electronica->documento_id_feel))

                              <button type="button"  id="reportar-{!!$nota->id!!}" class="btn btn-primary btn-xs" title="Reportar" onclick="reportar({!!$nota->id!!})"><i class="fa fa-upload"></i></button>
                              @endif
                            @endif
                          
                          @endpermission
                          @permission('facturacion-notas-eliminar')
                            <form action="{{route('notas.destroy',$nota->id)}}" method="post" style="display:inline-block;">
                              <input type="hidden" name="_method" value="delete">
                              <input type="hidden" name="_token" value="{{csrf_token()}}">                           

                              <button type="submit" id="eliminar-{!!$nota->id!!}" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                  <i class="fa fa-trash-o"></i>   
                              </button>
                            </form>
                          @endpermission
                        @endif
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.table-responsive -->
            @else
            SIN DATOS
            @endif
          </div>
        </div>
      </div>
      @endpermission
    </div>
  </div>

  @permission('facturacion-notas-crear')
    @if($ultimo_periodo->Periodo == $factura->Periodo)
       @include('adminlte::facturacion.partials.nota')
    @endif
  @endpermission

  @permission('facturacion-notas-ver')    
    @include('adminlte::facturacion.partials.show-nota')
  @endpermission
  @section('mis_scripts')
  <script type="text/javascript" src="/js/notas/show.js"></script>
  <script type="text/javascript">
    
    var concepto;
    var cantidad;
    var valor_unidad;
    var iva;
    var valor_total_label;
    var total = 0;
    var conceptos_nota_debito = {!!$tipos_conceptos_debito!!};
    var conceptos_nota_credito = {!!$tipos_conceptos_credito!!};
    var j = 1;
    var conceptos = [];

    $('#tipo_nota').on('change', function(){
      $('#tipo_concepto').empty();
      $('#tipo_concepto').append('<option value="">Elija una opción</option>');

      if($(this).val() == 'CREDITO'){
        $.each(conceptos_nota_credito, function(index, conceptos_credito){
          $('#tipo_concepto').append('<option value="'+conceptos_credito.id+'">'+conceptos_credito.nombre+'</option>');
        });

  
      }else if($(this).val() == 'DEBITO'){
        $.each(conceptos_nota_debito, function(index, conceptos_debito){
          $('#tipo_concepto').append('<option value="'+conceptos_debito.id+'">'+conceptos_debito.nombre+'</option>');
        });
      }
    });

    $('#tipo_concepto').on('change', function(){
      if ($(this).val() == 6) {
        $('#panel_anulacion').removeClass('hide');
        $('#anular').attr('required', true);
        $('#descuento').attr('required', true);
        $('#motivo_descuento').attr('required', true);
      }else{
        $('#panel_anulacion').addClass('hide');
        $('#anular').attr('required', false);
        $('#descuento').attr('required', false);
        $('#motivo_descuento').attr('required', false);
      }
    });

    $('#cantidad,#valor_unidad,#iva').keyup(function(){
      calcular_total();
    }).blur(function() {
      calcular_total();
    }).on('input', function() {
      calcular_total();
    });

    function calcular_total(){      
      cantidad = $('input[name=cantidad]').val();
      valor_unidad = $('input[name=valor_unidad]').val();
      iva = $('input[name=iva]').val();      
      valor_total_label = $('#valor_total');

      total_concepto = ((cantidad * valor_unidad) * (iva / 100)) + (cantidad * valor_unidad);
      valor_total_label.text('$'+total_concepto);
    }
    
    function addConcepto(){
      toastr.options.positionClass = 'toast-bottom-right';

      concepto = $('input[name=concepto]').val();  

      if ($('input[name=concepto]').val().length > 0) {

        if(cantidad > 0 && valor_unidad > 0){
          var item = {};
          total_concepto = ((cantidad * valor_unidad) * (iva / 100)) + (cantidad * valor_unidad);
          valor_total_label.text(total_concepto);

          item.id = j;
          item.concepto = concepto;
          item.cantidad = cantidad;
          item.valor_unidad = valor_unidad;
          item.iva = iva;
          item.valor_iva = (cantidad * valor_unidad) * (iva / 100);
          item.total = ((cantidad * valor_unidad) * (iva / 100)) + (cantidad * valor_unidad);

          $('#conceptos').append('<tr id="concepto-' + j +'"><td>' + j + '</td><td>' + concepto + '</td><td>' + cantidad + '</td><td>$' + valor_unidad + '</td><td>'+iva+'%</td><td>$'+total_concepto+'</td><td><a class="btn text-danger" onclick="removeConcepto('+ j +')"><i class="fa fa-remove"></i></a></td></tr>');

          
          total = total + total_concepto;        
          $('#total').text('$'+total);

          conceptos.push(item);

          $('input[name=concepto]').val('');
          $('input[name=cantidad]').val('');
          $('input[name=valor_unidad]').val('');
          $('input[name=iva]').val('');
          valor_total_label.text('$0.00');
          j = j+1;

        }else{
          toastr.warning("La cantidad y el valor debe ser mayor que cero.");
        }        
          
      }else{
        
        toastr.warning("Debe agregar un concepto");
      }
    }

    function removeConcepto(id){
      if (confirm("Desea Eliminar el concepto " + $('#concepto-' + id).find('td').eq(1).text())) {
        $('#concepto-' + id).remove();
        //se reasigna la variable sin el array que contiene el id que se esta eliminando.
        
        conceptos = $.grep(conceptos, function(e){ 
                      if (e.id == id) {
                        total = total - e.total;
                        $('#total').text('$'+total);
                      }

                      return e.id != id; 
                    });
        //Se obtiene el id del array que contiene el elemento id igual al indicado
        //productos.findIndex(x => x.id === id);
      }
    }
  </script>
  <script type="text/javascript">

    const getData = () => {
      return new Promise((resolve, reject) => {
          var parameters = {
            factura_id : $('input:hidden[name=factura_id]').val(),
            'conceptos' : conceptos,
            'tipo_nota' : $('#tipo_nota').val(),
            'tipo_concepto' : $('#tipo_concepto').val(),
            'tipo_operacion' : $('#tipo_operacion').val(),
            'tipo_negociacion' : $('#tipo_negociacion').val(),
            'tipo_medio_pago' : $('#tipo_medio_pago').val(),
            'total' : total,
            'anular' : $('#anular').val(),
            'descuento' : $('#descuento').val(),
            'motivo_descuento' : $('#motivo_descuento').val(),
            'reportar' : $('input[name=reportar_dian]:checked').val(),
            '_token' : $('input:hidden[name=_token]').val()
          }

          $.post('/notas', parameters).done(function(data){
              resolve(data)
          }).fail(function(e){
              reject('error in get');

              toastr.options.positionClass = 'toast-bottom-right';
              toastr.error(e.statusText);

              $('#guardar_nota').attr('disabled', false);
              $('#guardar_nota').empty('');
              $('#guardar_nota').append('Guardar');
          });
      })
    }

    const getData2 = (id) => {
      return new Promise((resolve, reject) => {
          var parameters = {
            nota_id : id,            
            '_token' : $('input:hidden[name=_token]').val()
          }

          $.post('/facturas-notas/reportar', parameters).done(function(data){
              resolve(data)
          }).fail(function(e){
              reject('error in get');

              toastr.options.positionClass = 'toast-bottom-right';
              toastr.error(e.statusText);

              $('#reportar-'+id).empty('');
              $('#reportar-'+id).attr('disabled', false);
              $('#reportar-'+id).append('  <i class="fa fa-upload"></i>');
              $('#eliminar-'+id).attr('disabled', false);
              
          });
      })
    }

    const sendData = (datosjson, api) => {
      return new Promise ((resolve, reject) =>{
        // Datos particulares
        var urlApi = api.url_api;
        var tokenIdentificador = api.token_identificador;

        //var urlApi = "http://test.feelfactura.com/FeelTestV2Api/api/MainExternal";
        //var tokenIdentificador = "PruebasFeel";

        var paramList = {
          Controller : api.controlador,
          Action : api.accion,
          ParamValues : {
              TokenIdentificador: tokenIdentificador,
              DocumentoObject: datosjson
          }
        };

        var paramListData = JSON.stringify(paramList);

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
              toastr.options.positionClass = 'toast-bottom-right';
              toastr.error(textStatus);
            });

        } catch (e) {
          var errorTitle = "API call failed";
          reject(e.message)
        }
      })
    }

    const saveData = (reportada, numero_nota_dian, nota_id, documento_id_feel, archivo) => {
      return new Promise((resolve, reject) =>{

        var parameters = {
          reportada : reportada,
          numero_nota_dian : numero_nota_dian,
          documento_id_feel : documento_id_feel,
          archivo : archivo,
          '_method' : 'PUT',
          '_token' : $('input:hidden[name=_token]').val()
        }

        $.post('/notas/'+nota_id, parameters).done(function(data){
          resolve(data);

        }).fail(function(e){
          reject('error in save');
          toastr.options.positionClass = 'toast-bottom-right';
          toastr.error(e.statusText);
        });
      })
    }

    const saveDetailsData = (nota_id,fecha,concepto,detalles) => {
      return new Promise((resolve, reject) =>{

        var parameters = {
          nota_id : nota_id,
          fecha : fecha,
          concepto: concepto,
          detalles : detalles,                    
          '_token' : $('input:hidden[name=_token]').val()
        }

        $.post('/facturas-notas/detalles', parameters).done(function(data){
          resolve(data)

        }).fail(function(e){
          reject('error in save');
          toastr.options.positionClass = 'toast-bottom-right';
          toastr.error(e.statusText);
        });
      })
    }

    guardar_nota.addEventListener('click', async () => {

      if(conceptos.length > 0){

        $('#guardar_nota').attr('disabled', 'disabled');
        $('#guardar_nota').append('  <i class="fa fa-refresh fa-spin"></i>');

        const datosNota = await getData();

        console.log(datosNota);

        var reportada = false;
        var numero_factura_dian = '';
        var documento_id_feel = '';
        var archivo = '';

        var concepto = '';
        var detalles = '';
        var fecha = '';
        var nota_id = datosNota.nota_id;

        if (datosNota.reportar) {

          const jsonData = await sendData(datosNota.nota_electronica, datosNota.api)
          console.log(jsonData);
          
          // Si hubo error
          if (jsonData.Summary !== null && jsonData.Summary.Message !== "") {

              if (jsonData.DataResult !== null) {
                  //El documento ya existe
                  //Obtener el id de la factura que ya existe y guardar el mensaje retornado
                  
                  nota_id = jsonData.DataResult[0].DocumentoRelacionado;

                  concepto = 'FacturaOrigenExistente';
                  detalles = jsonData.Summary.Message;
                  //tengo mis dudas
                  numero_nota_dian = jsonData.DataResult[0].DocumentoGenerado;
                  archivo = jsonData.DataResult[0].DocumentoLink;
                  documento_id_feel = jsonData.DataResult[0].DocumentoId;
                  reportada = true;

              }else{
                  //No se pudo reportar error en el json
                  reportada = jsonData.Summary.Success;
                  concepto = 'Error en la estructura JSON enviada';
                  detalles = jsonData.Summary.Message;
              }

              //Guardar resultado de la API
              const resultado = await saveData(reportada, numero_nota_dian, nota_id, documento_id_feel, archivo)

              if (resultado.result) {             

                await saveDetailsData(nota_id,'',concepto,'')
              }

          }else {
            //El documento es reportado satisfactoriamente
            reportada = jsonData.Summary.Success;
            numero_nota_dian = jsonData.DataResult[0].DocumentoGenerado;
            documento_id_feel = jsonData.DataResult[0].DocumentoId;
            archivo = jsonData.DataResult[0].DocumentoLink;

            //Guardar resultado de la API
            const resultado = await saveData(reportada, numero_nota_dian, nota_id, documento_id_feel, archivo)
            console.log(resultado);
            
            if (resultado.result) {
              console.log(nota_id);

              jsonData.DataResult[0].DocumentoLog.forEach(async (value) => {
                await saveDetailsData(nota_id,value.LogFechaHora,value.LogProceso,value.LogProcesoDetalle)
              });
            }
          }

          toastr.options.positionClass = 'toast-bottom-right';
          toastr.success('Nota reportada satisfactoriamente');

          setInterval(function(){
            location.reload();
          }, 3000);

        }else{
          toastr.options.positionClass = 'toast-bottom-right';
          toastr.success(datosNota.respuesta);

          setInterval(function(){
            location.reload();
          }, 3000);
        }
      }else{
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning("Debe agregar los conceptos.");
      }
    });

    async function reportar(id){

      if (confirm('Desea reportar la NOTA #'+ id)) {

        $('#reportar-'+id).empty('');
        $('#reportar-'+id).attr('disabled', 'disabled');
        $('#reportar-'+id).append('  <i class="fa fa-refresh fa-spin"></i>');
        $('#eliminar-'+id).attr('disabled', true);

        const datosNota = await getData2(id);

        console.log(datosNota);

        var reportada = false;
        var numero_factura_dian = '';
        var documento_id_feel = '';
        var archivo = '';

        var concepto = '';
        var detalles = '';
        var fecha = '';
        var nota_id = datosNota.nota_id;

        if (datosNota.reportar) {

          const jsonData = await sendData(datosNota.nota_electronica, datosNota.api)
          console.log(jsonData);
          
          // Si hubo error
          if (jsonData.Summary !== null && jsonData.Summary.Message !== "") {

              if (jsonData.DataResult !== null) {
                  //El documento ya existe
                  //Obtener el id de la factura que ya existe y guardar el mensaje retornado
                  
                  nota_id = jsonData.DataResult[0].DocumentoRelacionado;

                  concepto = 'FacturaOrigenExistente';
                  detalles = jsonData.Summary.Message;
                  //tengo mis dudas
                  numero_nota_dian = jsonData.DataResult[0].DocumentoGenerado;
                  archivo = jsonData.DataResult[0].DocumentoLink;
                  documento_id_feel = jsonData.DataResult[0].DocumentoId;
                  reportada = true;

              }else{
                  //No se pudo reportar error en el json
                  reportada = jsonData.Summary.Success;
                  concepto = 'Error en la estructura JSON enviada';
                  detalles = jsonData.Summary.Message;
              }

              //Guardar resultado de la API
              const resultado = await saveData(reportada, numero_nota_dian, nota_id, documento_id_feel, archivo)

              if (resultado.result) {

                await saveDetailsData(nota_id,'',concepto,'')
              }

          }else {
            //El documento es reportado satisfactoriamente
            reportada = jsonData.Summary.Success;
            numero_nota_dian = jsonData.DataResult[0].DocumentoGenerado;
            documento_id_feel = jsonData.DataResult[0].DocumentoId;
            archivo = jsonData.DataResult[0].DocumentoLink;

            //Guardar resultado de la API
            const resultado = await saveData(reportada, numero_nota_dian, nota_id, documento_id_feel, archivo)
            console.log(resultado);
            
            if (resultado.result) {
              console.log(nota_id);

              jsonData.DataResult[0].DocumentoLog.forEach(async (value) => {
                await saveDetailsData(nota_id,value.LogFechaHora,value.LogProceso,value.LogProcesoDetalle)
              });
            }
          }

          toastr.options.positionClass = 'toast-bottom-right';
          toastr.success('Nota reportada satisfactoriamente');

          setInterval(function(){
            location.reload();
          }, 3000);

        }else{
          toastr.options.positionClass = 'toast-bottom-right';
          toastr.success(datosNota.respuesta);

          setInterval(function(){
            location.reload();
          }, 3000);
        }

      }
      
    }


  </script>
  @endsection
@endsection