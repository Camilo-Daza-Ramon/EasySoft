@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-dollar"></i>  Recaudos</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">

                        <form id="form-buscar" action="{{route('recaudos.index')}}" role="search" method="GET">  
                            @permission('recaudos-exportar')
                            <div class="btn-group pull-right">

                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    @if($clientes_inconsistencias > 0)
                                    <span class="badge bg-yellow badge-alert-notification">{{$clientes_inconsistencias}}</span>
                                    @endif
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('recaudos-crear')
                                        <li>
                                          <a href="#" data-toggle="modal" data-target="#recaudoAdd" ><i class="fa fa-plus"></i> Crear Recaudo</a>
                                        </li>
                                    @endpermission

                                    <li>
                                        <a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a>
                                    </li>
                                    @if($clientes_inconsistencias > 0)
                                        <li>
                                            <a href="?inconsistencia=true" class="bg-yellow"><i class="fa fa-exclamation-triangle"></i> Inconsistencias {{$clientes_inconsistencias}}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            @endpermission                          
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <input type="text" class="form-control" name="documento" placeholder="Cedula o Referencia" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="date" name="fecha_desde" class="form-control" value="{{(isset($_GET['fecha_desde'])? $_GET['fecha_desde']:'')}}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="date" name="fecha_hasta" class="form-control" value="{{(isset($_GET['fecha_hasta'])? $_GET['fecha_hasta']:'')}}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="medio_pago" id="medio_pago">
                                                <option value="">Medio de Pago</option>
                                                @foreach($medios_pagos as $medio_pago)
                                                    <option value="{{$medio_pago}}" {{(isset($_GET['medio_pago'])) ? (($_GET['medio_pago'] == $medio_pago) ? 'selected' : '') : ''}}>{{$medio_pago}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="">Elija un proyecto</option>
                                                @foreach($proyectos as $proyecto)

                                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="departamento" id="departamento">
                                                <option value="">Elija un departamento</option>
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select> 
                                        </div>

                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>                              
                        </form>

                        
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Entidad</th>
                                    <th>Acciones</th>
                                </tr>
                                @foreach($recaudos as $recaudo)
                                <tr>
                                    <td>@if(!isset($_GET['inconsistencia']))
                                        {{$recaudo->cliente->Identificacion}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!isset($_GET['inconsistencia']))
                                        {{mb_convert_case($recaudo->cliente->NombreBeneficiario . ' ' . $recaudo->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}
                                        @else
                                            {{$recaudo->nombres.' '. $recaudo->apellido1}} 
                                        @endif
                                    </td>
                                    <td>
                                        @if(!isset($_GET['inconsistencia']))
                                        {{$recaudo->cliente->municipio->NombreMunicipio}} - {{$recaudo->cliente->municipio->NombreDepartamento}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!isset($_GET['inconsistencia']))
                                        {{$recaudo->cliente->proyecto->NumeroDeProyecto}}
                                        @endif
                                    </td>
                                    <td>{{date('Y-m-d H:i:s', strtotime($recaudo->Fecha))}}</td>
                                    <td>${{number_format($recaudo->valor,0,'.',',')}}</td>
                                    <td>{{$recaudo->RecaudadoPor}}</td>
                                    <td>
                                        @if(empty($recaudo->ClienteId))
                                            @permission('recaudos-editar')
                                                <a href="{{route('recaudos.edit', $recaudo->RecaudoId)}}" class="btn btn-primary btn-xs"> <i class="fa fa-edit"></i></a>
                                            @endpermission
                                        @endif
                                        
                                        @permission('recaudos-eliminar')
                                            <form style="display: inline-block;" action="{{route('recaudos.destroy', $recaudo->RecaudoId)}}" method="post">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endpermission
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$recaudos->currentPage()}} de {{$recaudos->lastPage()}}. Total registros {{$recaudos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $recaudos->appends(Request::only(['documento','municipio','fecha_desde','fecha_hasta','proyecto','departamento', 'medio_pago']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @permission('recaudos-crear')
      @include('adminlte::facturacion.recaudos.create')
    @endpermission
    @section('mis_scripts')

        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });

            $('#form-recaudos-crear').on('submit', function(e){
                $(this).find('button').attr('disabled',true);
            });
        </script>

        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    documento : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                    fecha_desde : "{!! (isset($_GET['fecha_desde'])? $_GET['fecha_desde']:'') !!}",
                    fecha_hasta : "{!! (isset($_GET['fecha_hasta'])? $_GET['fecha_hasta']:'') !!}",
                    proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                    departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                    medio_pago : "{!! (isset($_GET['medio_pago'])? $_GET['medio_pago']:'') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $('#opciones').attr('disabled',true);
                $('#icon-opciones').removeClass('fa-gears');
                $('#icon-opciones').addClass('fa-refresh fa-spin');

                $.ajax({
                    type: "POST",
                    url: '/recaudos/exportar',
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
    @endsection
@endsection