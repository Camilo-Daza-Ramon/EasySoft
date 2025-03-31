@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-ban"></i>  Clientes Suspendidos</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" action="{{route('clientes-suspensiones.index')}}" role="search" method="GET">  
                            
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('clientes-suspensiones-agregar')
                                    <li><a href="#" data-target="#addModal" data-toggle="modal"> <i class="fa fa-plus"></i> Agregar</a></li>
                                    @endpermission
                                    @permission('clientes-suspensiones-exportar')
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
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
                                            <input type="date" class="form-control" name="fecha_inicio" placeholder="Fecha Inicio" value="{{(isset($_GET['fecha_inicio'])? $_GET['fecha_inicio']:'')}}" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <input type="date" class="form-control" name="fecha_fin" placeholder="Fecha Fin" value="{{(isset($_GET['fecha_fin'])? $_GET['fecha_fin']:'')}}" autocomplete="off">
                                        </div>



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
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                                </div>
                            </div>                              
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Dias suspendido</th>                                    
                                </tr>
                                @foreach($suspendidos as $dato)

                                <?php 
                                    $contador = date_diff(date_create($dato->fecha_inicio), date_create(date('Y-m-d H:i:s')));
                                    $total_dias = $contador->format('%a');
                                ?>
                                <tr>
                                    <th>
                                        @if($total_dias >= 60)
                                            <i class="fa fa-circle text-red"></i>
                                        @elseif($total_dias < 60 && $total_dias > 30)
                                            <i class="fa fa-circle text-yellow"></i>
                                        @elseif($total_dias < 30)
                                            <i class="fa fa-circle text-blue"></i>
                                        @endif
                                    </th>
                                    <th>
                                        <a href="{{route('clientes.show', $dato->cliente->ClienteId)}}">{{$dato->cliente->Identificacion}}</a>
                                    </th>

                                    <td>
                                        {{$dato->cliente->NombreBeneficiario}} {{$dato->cliente->Apellidos}}
                                    </td>

                                    <td>{{$dato->cliente->proyecto->NumeroDeProyecto}}</td>
                                    
                                    <td>{{$dato->cliente->municipio->NombreMunicipio}}</td>
                                    <td>{{$dato->cliente->municipio->departamento->NombreDelDepartamento}}</td>

                                    <td>                                        
                                        {{$contador->format('%a')}} Días Suspendido
                                    </td>                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$suspendidos->currentPage()}} de {{$suspendidos->lastPage()}}. Total registros {{$suspendidos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $suspendidos->appends(Request::only(['proyecto','departamento','municipio','estado', 'fecha_inicio', 'fecha_fin']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('adminlte::clientes.suspensiones.partials.add')

    @section('mis_scripts')
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>


        
        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });
        </script>
        
        <script type="text/javascript">
            
            $('#exportar').on('click',function(){
                var parametros = {
                    documento : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                    proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                    fecha_inicio : "{!! (isset($_GET['fecha_inicio'])? $_GET['fecha_inicio']:'') !!}",
                    fecha_fin : "{!! (isset($_GET['fecha_fin'])? $_GET['fecha_fin']:'') !!}",
                    departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $('#opciones').attr('disabled',true);
                $('#icon-opciones').removeClass('fa-gears');
                $('#icon-opciones').addClass('fa-refresh fa-spin');

                $.ajax({
                    type: "POST",
                    url: '/clientes-suspensiones/exportar',
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