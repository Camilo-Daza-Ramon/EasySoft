@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-edit"></i>  Novedades</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">

                        <form id="form-buscar" action="{{route('novedades.index')}}" role="search" method="GET">  
                            
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('novedades-crear')
                                    <li>
                                        <a href="{{route('novedades.create')}}"><i class="fa fa-plus"></i> Agregar</a>
                                    </li>
                                    @endpermission
                                    @permission('novedades-masivas')
                                    <li>
                                        <a href="{{route('novedades.masivas')}}"><i class="fa fa-plus-circle"></i> Agregar masivas</a>
                                    </li>
                                    @endpermission
                                    @permission('novedades-exportar')
                                    <li>
                                        <a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a>
                                    </li>
                                    @endpermission
                                </ul>
                            </div>
                                                      
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="date" name="fecha_inicio" class="form-control" value="{{(isset($_GET['fecha_inicio'])? $_GET['fecha_inicio']:'')}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="date" name="fecha_fin" class="form-control" value="{{(isset($_GET['fecha_fin'])? $_GET['fecha_fin']:'')}}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="">Elija un proyecto</option>
                                                @foreach($proyectos as $proyecto)

                                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" name="estado" id="estado">
                                                <option value="">Elija un estado</option>
                                                @foreach($estados as $estado)
                                                    <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{$estado}}</option>
                                                @endforeach
                                            </select> 
                                        </div>

                                        <div class="col-md-4">
                                            <select class="form-control" name="concepto">
                                                <option value="">Elija un concepto</option>
                                                @foreach($conceptos as $concepto)
                                                    <option value="{{$concepto->concepto}}" {{(isset($_GET['concepto'])) ? (($_GET['concepto'] == $concepto->concepto) ? 'selected' : '') : ''}}>{{$concepto->concepto}}</option>
                                                @endforeach
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
                                    <th>Documento</th>
                                    <th>Cliente</th>
                                    <th>Municipio</th>
                                    <th>Concepto</th>                                    
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                @foreach($novedades as $novedad)
                                <tr>
                                    <td>{{$novedad->cliente->Identificacion}}</td>
                                    <td>{{mb_convert_case($novedad->cliente->NombreBeneficiario . ' ' . $novedad->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
                                    <td>{{$novedad->cliente->municipio->NombreMunicipio}} - {{$novedad->cliente->municipio->NombreDepartamento}}</td>
                                    <td>{{$novedad->concepto}}</td>                                    
                                    <td>{{$novedad->fecha_inicio}}</td>
                                    <td>{{$novedad->fecha_fin}}</td>
                                    <td>
                                      @if($novedad->estado == 'PENDIENTE')
                                        <span class="label label-warning">{{$novedad->estado}}</span>
                                      @else
                                        <span class="label label-default">{{$novedad->estado}}</span>
                                      @endif
                                    </td>
                                    <td>
                                        @permission('novedades-edit')
                                            <a href="{{route('novedades.edit', $novedad->id)}}" class="btn btn-primary btn-xs"  target="_blank"> <i class="fa fa-edit"></i></a>
                                        @endpermission
                                        @permission('novedades-cerrar')
                                            @if($novedad->concepto == 'Ajustes por falta de servicio' && empty($novedad->fecha_fin))
                                                <button type="button" title="Cerrar" class="btn btn-xs bg-purple" onclick="traer_novedad({!!$novedad->id!!});"> <i class="fa fa-calendar-check-o"></i></button>
                                            @endif
                                        @endpermission
                                        @permission('novedades-eliminar')
                                            @if(count($novedad->factura_novedad) == 0 && count($novedad->ticket) == 0)
                                            <form action="{{route('novedades.destroy', $novedad->id)}}" method="post">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                    <i class="fa fa-trash-o"></i>   
                                                </button>
                                            </form>
                                            @endif
                                        @endpermission
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$novedades->currentPage()}} de {{$novedades->lastPage()}}. Total registros {{$novedades->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $novedades->appends(Request::only(['fecha_inicio', 'fecha_fin', 'proyecto', 'departamento', 'municipio', 'estado', 'concepto']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('novedades-cerrar')
        @include('adminlte::facturacion.novedades.partials.cerrar')
    @endpermission
    @section('mis_scripts')
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });
        </script>

        <script type="text/javascript" src="js/novedades/show.js"></script>
        <script type="text/javascript">

            function validar_fecha_cierre(){
                var hoy = Date.now();
                var fecha_fin = Date.parse($('#fecha_fin').val());
                var fecha_inicio = Date.parse($('#fecha_inicio_txt').val());

                if (fecha_fin > hoy) {                
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning('La fecha de finalizacion no puede ser mayor al día de hoy');

                    return false;
                }else if (fecha_fin < fecha_inicio ){
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning('La fecha de finalizacion no puede ser menor a la fecha de inicio.');

                    return false;
                }else{
                    return true;
                }
            }
            

        </script>

        @permission('novedades-exportar')
        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    fecha_inicio : "{!! (isset($_GET['fecha_inicio'])? $_GET['fecha_inicio']:'') !!}",
                    fecha_fin : "{!! (isset($_GET['fecha_fin'])? $_GET['fecha_fin']:'') !!}",
                    proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                    departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                    estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                    concepto : "{!! (isset($_GET['concepto'])? $_GET['concepto']:'') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $('#opciones').attr('disabled',true);
                $('#icon-opciones').removeClass('fa-gears');
                $('#icon-opciones').addClass('fa-refresh fa-spin');

                $.ajax({
                    type: "POST",
                    url: '/novedades/exportar',
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