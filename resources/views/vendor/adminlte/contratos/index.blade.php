@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-sticky-note-o"></i>  Contratos</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">

        @include('adminlte::contratos.partials.contador-contratos')

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" action="{{route('contratos.index')}}" role="search" method="GET">  
                            @permission('contratos-exportar')
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                </ul>
                            </div>
                            @endpermission                          
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">
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
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select> 
                                        </div>
                                    
                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="estado">
                                                <option value="">Elija un estado</option>
                                                @foreach($estados as $estado)
                                                    <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{$estado}}</option>
                                                @endforeach
                                                
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="tipo_contrato">
                                                <option value="">Elija un tipo</option>
                                                @foreach($tipos_contratos as $tipo)
                                                    <option value="{{$tipo}}" {{(isset($_GET['tipo_contrato'])) ? (($_GET['tipo_contrato'] == $tipo) ? 'selected' : '') : ''}}>{{$tipo}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i></button>
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
                                    <th scope="col">#</th> 
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Tipo Cobro</th>
                                    <th scope="col">Vigencia</th>                                    
                                    <th scope="col">Fecha Inicio</th>
                                    <th scope="col">Estado</th>
                                </tr>
                                @foreach($contratos as $contrato)
                                <tr>
                                    <td>
                                        <a href="{{route('clientes.contratos.show', [$contrato->ClienteId, $contrato->id])}}">{{$contrato->id}}</a>     
                                    </td>
                                    <td>{{$contrato->cliente->Identificacion}}</td>
                                    <td>{{$contrato->tipo_cobro}}</td>
                                    <td>
                                        {{$contrato->vigencia_meses}}                                        
                                    </td>
                                    <td>
                                        {{$contrato->fecha_inicio}}
                                    </td>
                                    <td>{{$contrato->estado}}</td>                                 
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$contratos->currentPage()}} de {{$contratos->lastPage()}}. Total registros {{$contratos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $contratos->appends(Request::only(['departamento','proyecto','municipio']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    @section('mis_scripts')

    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>

    @permission('contratos-exportar')
    <script type="text/javascript">
        $('#exportar').on('click',function(){
            var parametros = {
                documento : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                tipo_contrato : "{!! (isset($_GET['tipo_contrato'])? $_GET['tipo_contrato']:'') !!}",
                '_token' : $('input:hidden[name=_token]').val() 
            }

            $('#opciones').attr('disabled',true);
            $('#icon-opciones').removeClass('fa-gears');
            $('#icon-opciones').addClass('fa-refresh fa-spin');

            $.ajax({
                type: "POST",
                url: '/contratos/exportar',
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