@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-retweet"></i>  Cambios y Reemplazos # {{$cambio_reemplazo->id}}</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
        	<div class="col-md-6">
        		<div class="box box-primary">
		            <div class="box-header with-border bg-gray">
		              <h3 class="box-title">Cliente Antiguo</h3>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <th>Documento Identificacion</th>
                                <td>
                                    <a href="{{route('clientes.show', $cambio_reemplazo->meta_cliente->ClienteId)}}">{{$cambio_reemplazo->meta_cliente->cliente->TipoDeDocumento}} {{$cambio_reemplazo->meta_cliente->cliente->Identificacion}}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Nombre</th>
                                <td>{{$cambio_reemplazo->meta_cliente->cliente->NombreBeneficiario}} {{$cambio_reemplazo->meta_cliente->cliente->Apellidos}}</td>
                            </tr>
                            <tr>
                                <th>Municipio</th>
                                <td>{{$cambio_reemplazo->meta_cliente->cliente->municipio->NombreMunicipio}} - {{$cambio_reemplazo->meta_cliente->cliente->municipio->NombreDepartamento}}</td>
                            </tr>
                            <tr>
                                <th>Fecha Instalacion</th>
                                <td>{{$cambio_reemplazo->contrato_antiguo->fecha_instalacion}}</td>
                            </tr>
                            <tr>
                                <th>Fecha Operacion</th>
                                <td>{{$cambio_reemplazo->contrato_antiguo->fecha_operacion}}</td>
                            </tr>
                            <tr>
                                <th>Fecha Finalizacion</th>
                                <td>{{$cambio_reemplazo->contrato_antiguo->fecha_final}}</td>
                            </tr>
                            <tr>
                                <th>Total Meses Servicio</th>
                                <?php 
                                $date1 = new \DateTime($cambio_reemplazo->contrato_antiguo->fecha_operacion);
                                $date2 = new \DateTime($cambio_reemplazo->contrato_antiguo->fecha_final);
                                $diferencia_antiguo = $date1->diff($date2);
                                 ?>
                                <td>
                                    {{($diferencia_antiguo->m) + ($diferencia_antiguo->y * 12)}} Meses y {{$diferencia_antiguo->d}} Días
                                </td>
                            </tr>
                            <tr>
                                <th>Estado</th>
                                <td>{{$cambio_reemplazo->meta_cliente->cliente->Status}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border bg-green">
                      <h3 class="box-title">Cliente Nuevo</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <tr>
                                <th>Documento Identificacion</th>
                                <td>
                                    <a href="{{route('clientes.show', $cambio_reemplazo->cliente_nuevo_id)}}">
                                        {{$cambio_reemplazo->cliente->TipoDeDocumento}} {{$cambio_reemplazo->cliente->Identificacion}}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Nombre</th>
                                <td>{{$cambio_reemplazo->cliente->NombreBeneficiario}} {{$cambio_reemplazo->cliente->Apellidos}}</td>
                            </tr>
                            <tr>
                                <th>Municipio</th>
                                <td>{{$cambio_reemplazo->cliente->municipio->NombreMunicipio}} - {{$cambio_reemplazo->cliente->municipio->NombreDepartamento}}</td>
                            </tr>
                            <tr>
                                <th>Fecha Instalacion</th>
                                <td>{{$cambio_reemplazo->contrato_nuevo->fecha_instalacion}}</td>
                            </tr>
                            <tr>
                                <th>Fecha Operación</th>
                                <td>{{$cambio_reemplazo->contrato_nuevo->fecha_operacion}}</td>
                            </tr>
                            <tr>
                                <th>Fecha Finalizacion</th>
                                <td>{{$cambio_reemplazo->contrato_nuevo->fecha_final}}</td>
                            </tr>
                            <tr>
                                <th>Total Meses Servicio</th>
                                <?php 
                                $date1 = new \DateTime($cambio_reemplazo->contrato_nuevo->fecha_instalacion);
                                $date2 = new \DateTime(date('Y-m-d'));

                                if (!empty($cambio_reemplazo->contrato_nuevo->fecha_final)) {
                                   $date2 = new \DateTime(date($cambio_reemplazo->contrato_nuevo->fecha_final));
                                }

                                
                                $diff = $date1->diff($date2);
                                 ?>
                                <td>
                                    {{($diff->m) + ($diff->y * 12)}} Meses y {{$diff->d}} Días
                                </td>
                            </tr>
                            <tr>
                                <th>Estado</th>
                                <td>{{$cambio_reemplazo->cliente->Status}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border bg-blue">
                      <h3 class="box-title">Detalles</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Fecha Reemplazo</label>
                                <p>{{$cambio_reemplazo->fecha_reemplazo}}</p>
                            </div>                            
                            <div class="col-md-3">
                                <label>Fecha estimada de Finalización</label>
                                <?php 
                                    $fecha_estimada = date("Y-m-d",strtotime($cambio_reemplazo->fecha_reemplazo."+ ". $cambio_reemplazo->contrato_antiguo->vigencia_meses ." month"));
                                    $fecha_estimada = date("Y-m-d",strtotime($fecha_estimada."- ". $diferencia_antiguo->y ." year"));                                    
                                    $fecha_estimada = date("Y-m-d",strtotime($fecha_estimada."- ". $diferencia_antiguo->m ." month"));
                                    $fecha_estimada = date("Y-m-d",strtotime($fecha_estimada."- ". $diferencia_antiguo->d ." days"));
                                 ?>

                                 <ul type="none">
                                    <li>
                                        <b>DEL CONTRATO:</b> {{$fecha_estimada}}
                                    </li>
                                    @if(!empty($cambio_reemplazo->meta_cliente->cliente->proyecto->fecha_fin_proyecto))
                                    <li>
                                        <b>DEL PROYECTO:</b> {{$cambio_reemplazo->meta_cliente->cliente->proyecto->fecha_fin_proyecto}}
                                    </li>
                                    @endif
                                 </ul>
                                
                            </div>
                            <div class="col-md-2">
                                <label>Meses A disfrutar</label>
                                <?php 
                                    $date1 = new \DateTime($cambio_reemplazo->fecha_reemplazo);
                                    $date2 = new \DateTime((!empty($cambio_reemplazo->meta_cliente->cliente->proyecto->fecha_fin_proyecto))? $cambio_reemplazo->meta_cliente->cliente->proyecto->fecha_fin_proyecto : $fecha_estimada);
                                    $diff = $date1->diff($date2);
                                ?>
                                <p>{{($diff->m) + ($diff->y * 12)}} Meses y {{$diff->d}} Días</p>
                            </div>
                            <div class="col-md-2">
                                <label>Meta</label>
                                <p>{{$cambio_reemplazo->meta_cliente->meta->nombre}}</p>
                            </div>
                            <div class="col-md-2">
                                <label>ID-PUNTO</label>
                                <p>{{$cambio_reemplazo->meta_cliente->idpunto}}</p>
                            </div>
                        </div>

                        <p><label>Observacion:</label> {{$cambio_reemplazo->observacion}}<p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection