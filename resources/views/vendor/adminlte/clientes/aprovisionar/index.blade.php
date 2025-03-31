@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-hdd-o"></i>  Aprovisionar</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" action="{{route('aprovisionar.index')}}" role="search" method="GET">  
                            @permission('aprovisionar-exportar')
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
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
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
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Proyecto</th>
                                    <th scope="col">Departamento</th>                                    
                                    <th scope="col">Municipio</th>
                                </tr>
                                <?php $i=0; ?>
                                @foreach($clientes as $cliente)
                                <tr>
                                    <td>{{$i+=1}}</td>
                                    <th>
                                        <a href="{{route('clientes.contratos.show', [$cliente->ClienteId,$cliente->contrato_id])}}">{{$cliente->Identificacion}}</a>
                                    </th>
                                    <td>{{mb_convert_case($cliente->NombreBeneficiario . ' ' . $cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
                                    <td>{{$cliente->proyecto->NumeroDeProyecto}}</td>
                                    <td>{{$cliente->municipio->NombreMunicipio}}</td>
                                    <td>{{$cliente->municipio->departamento->NombreDelDepartamento}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$clientes->currentPage()}} de {{$clientes->lastPage()}}. Total registros {{$clientes->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $clientes->appends(Request::only(['departamento','proyecto','municipio']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    @section('mis_scripts')

    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>

    @permission('aprovisionar-exportar')
    <script type="text/javascript">
        $('#exportar').on('click',function(){
            var parametros = {
                proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                '_token' : $('input:hidden[name=_token]').val() 
            }

            $('#opciones').attr('disabled',true);
            $('#icon-opciones').removeClass('fa-gears');
            $('#icon-opciones').addClass('fa-refresh fa-spin');

            $.ajax({
                type: "POST",
                url: '/aprovisionar/exportar',
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