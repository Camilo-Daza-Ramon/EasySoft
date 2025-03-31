@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-file-o"></i>  Inventarios - Insumo - {{$insumo->Codigo}} </h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">                        
                        <h2 class="box-title"> Detalles</h2>
                    </div>
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <p><b>Lista Origen:</b> {{$insumo->ListaOrigen}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Codigo:</b> {{$insumo->Codigo}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Descripcion:</b> {{$insumo->Descripcion}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Tipo:</b> {{$insumo->InsumoTipo}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Unidad Compra:</b> {{$insumo->UnidadCompra}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Unidad Uso:</b> {{$insumo->UnidadUso}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Valor Unitario:</b> ${{$insumo->ValorUnitario}}</p>
                        </div>
                        
                        <div class="col-md-12">
                          <p><b>Presentacion Compra:</b> {{$insumo->PresentacionCompra}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Cantidad Uso:</b> {{$insumo->CantidadUso}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Factor de Conversion:</b> {{$insumo->FactordeConversion}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Valor Unitario Unidad Compra:</b> ${{$insumo->ValorUnitarioUnidadCompra}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Fecha Actualizacion Precio:</b> {{$insumo->FechaActualizacionPrecio}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Codigo Equivalente:</b> {{$insumo->CodigoEquivalente}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Comentario:</b> {{$insumo->Comentario}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Marca:</b> {{$insumo->Marca}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Referencia:</b> {{$insumo->Referencia}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>ubicacion:</b> {{$insumo->ubicacion}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Precio U$:</b> {{$insumo->PrecioUS}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Tipo Tecnologia:</b> {{$insumo->TipoTecnologia}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Iva:</b> {{$insumo->Iva}}</p>
                        </div>

                        <div class="col-md-12">
                          <p><b>Es Activo?:</b> {{$insumo->EsActivo}}</p>
                        </div>
                      </div>                      
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">                        
                        <h2 class="box-title"> Activos Fijos</h2>
                        <form action="{{route('inventarios.insumos.show', $insumo->InsumoId)}}" method="get">
                            <div class="input-group input-group-sm hidden-xs" style="width: 160px;">
                              <input type="text" name="serial" class="form-control pull-right" placeholder="Serial" value="{{(isset($_GET['serial'])) ? $_GET['serial'] : ''}}">

                              <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                        <div class="box-tools">
                            <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                  <span id="icon-opciones" class="fa fa-gears"></span> Opciones
                                  <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                              @permission('inventarios-activos_fijos-crear')
                                @if($insumo->InsumoTipo == 'EQUIPO')
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#activoAdd">
                                            <i class="fa fa-plus"></i> Agregar ONT
                                        </a>
                                    </li>
                                @endif
                              @endpermission                              
                            </ul>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped dataTable">                    
                            <tbody>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Modelo</th>
                                    <th scope="col">Serial</th>
                                    <th scope="col">Estado</th>
                                    @role(['admin'])
                                        <th scope="col">Acciones</th>
                                    @endrole
                                </tr>
                                @foreach($activos_fijos as $dato)
                                <tr>
                                    <td>{{$dato->ActivoFijoId}}</td>
                                    <td><a href="{{route('insumos.activos-fijos.show', [$insumo->InsumoId,$dato->ActivoFijoId])}}">{{$dato->Descripcion}}</a></td>
                                    <td>{{$dato->Marca}} - {{$dato->Modelo}}</td>                                    
                                    <td>
                                        @if(!empty($dato->cliente_ont_olt))
                                        <a href="{{route('clientes.show',$dato->cliente_ont_olt->cliente->ClienteId)}}" target="_blanck">{{$dato->Serial}}</a>
                                        @else
                                        {{$dato->Serial}}
                                        @endif
                                    </td>
                                    <td>{{$dato->Estado}}</td>

                                    <td>
                                      @permission('inventarios-activos_fijos-editar')
                                        @empty($dato->cliente_ont_olt)
                                          <button type="button"  class="btn btn-primary btn-xs" data-toggle="modal" data-target="#activoEdit" data-id="{{$dato->ActivoFijoId}}" data-insumo="{{$insumo->InsumoId}}" title="Editar"><i class="fa fa-edit"></i></button>
                                        @endif
                                      @endpermission
                                            
                                      @role(['admin'])
                                        
                                        <form action="{{route('insumos.activos-fijos.destroy', [$insumo->InsumoId,$dato->ActivoFijoId])}}" method="post" style="display:inline;">
                                            <input type="hidden" name="_method" value="delete">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">

                                            <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        
                                      @endrole
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                       </table> 
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$activos_fijos->currentPage()}} de {{$activos_fijos->lastPage()}}. Total registros {{$activos_fijos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $activos_fijos->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @permission('inventarios-activos_fijos-crear')
        @if($insumo->InsumoTipo == 'EQUIPO')
            @include('adminlte::inventarios.insumos.activos-fijos.create')
        @endif
    @endpermission

    @permission('inventarios-activos_fijos-editar')
      @include('adminlte::inventarios.insumos.activos-fijos.edit')

      @section('mis_scripts')
          <script type="text/javascript">
            $('#activoEdit').on('show.bs.modal', function (event) {
              var a = $(event.relatedTarget) // Button that triggered the modal
              const id = a.data('id');
              const insumo = a.data('insumo');
              //var empresa = a.data('empresa');
              var url = `/inventarios/insumos/${insumo}/activos-fijos/${id}`;
              var modal = $(this)

              modal.find('select[name=documento_categoria_id]').prop('selectedIndex',0)

              $.get(url +'/edit'  ,null, function(data){
                modal.find('form').attr('action', url);
                modal.find('input[name=marca]').val(data.activos['Marca']);
                modal.find('input[name=modelo]').val(data.activos['Modelo']);
                modal.find('input[name=referencia]').val(data.activos['Referencia']);
                modal.find('input[name=serial]').val(data.activos['Serial']);
                modal.find('select[name=estado] option[value='+data.activos['Estado']+']').prop("selected", true);
              });
            });
          </script>
      @endsection
    @endpermission
@endsection