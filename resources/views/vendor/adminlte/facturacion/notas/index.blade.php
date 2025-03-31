@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-sticky-note-o"></i>  Notas Contables</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" action="{{route('notas.index')}}" role="search" method="GET">  
                            @permission('facturacion-notas-exportar')
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

                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="">Elija un proyecto</option>
                                                @foreach($proyectos as $proyecto)

                                                    <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    
                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="tipo_nota">
                                                <option value="">Elija una opcion</option>
                                                <option value="CREDITO">CREDITO</option>
                                                <option value="DEBITO">DEBITO</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="month" name="periodo" class="form-control" value="{{(isset($_GET['periodo'])? $_GET['periodo']:'')}}">
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i></button>
                                </div>
                            </div>                              
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>                                    
                                    <th scope="col">#</th> 
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Valor Total</th>
                                    <th scope="col">Cedula Cliente</th>
                                    <th scope="col">Factura</th>
                                    <th scope="col">Periodo</th>                      
                                    <th scope="col">Acciones</th>
                                </tr>
                                @foreach($notas as $nota)
                                <tr>
                                    <td>{{$nota->id}}</td>
                                    <td>{{$nota->tipo_nota}}</td>
                                    <td>${{number_format($nota->valor_total,0,'.',',')}}</td>
                                    <td>
                                        {{$nota->factura->cliente->Identificacion}}
                                        
                                    </td>
                                    <td>
                                        <a href="{{route('facturacion.show', [$nota->factura->Periodo, $nota->factura_id])}}">
                                            {{$nota->factura_id}}
                                        </a>
                                    </td>
                                    <td>{{$nota->factura->Periodo}}</td>
                                    <td>
                                      
                                        @if($nota->reportada) 

                                            @if(!empty($nota->archivo))
                                            <a href="{{$nota->archivo}}" class="btn btn-success btn-xs" title="Descargar" target="_black"><i class="fa fa-download"></i></a>                                                    
                                            @endif
                                        @endif

                                        <button class="btn btn-xs bt-default" onclick="traer_nota({!!$nota->id!!});return false;"><i class="fa fa-eye"></i></button>
                                    </td>                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$notas->currentPage()}} de {{$notas->lastPage()}}. Total registros {{$notas->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $notas->appends(Request::only(['documento','proyecto','tipo_nota', 'periodo']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('facturacion-notas-ver')    
        @include('adminlte::facturacion.partials.show-nota')
    @endpermission

    @section('mis_scripts')

    <script type="text/javascript" src="/js/notas/show.js"></script>

    @permission('facturacion-notas-exportar')
    <script type="text/javascript">
        $('#exportar').on('click',function(){
            var parametros = {
                documento : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                tipo_nota : "{!! (isset($_GET['tipo_nota'])? $_GET['tipo_nota']:'') !!}",
                periodo : "{!! (isset($_GET['periodo'])? $_GET['periodo']:'') !!}",
                '_token' : $('input:hidden[name=_token]').val() 
            }

            $('#opciones').attr('disabled',true);
            $('#icon-opciones').removeClass('fa-gears');
            $('#icon-opciones').addClass('fa-refresh fa-spin');

            $.ajax({
                type: "POST",
                url: '/notas/exportar',
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