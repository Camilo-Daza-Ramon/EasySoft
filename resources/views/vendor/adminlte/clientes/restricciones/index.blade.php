@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-users"></i>  Clientes Restricciones</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('restricciones.index')}}" role="search" method="GET">

                            
                                <div class="btn-group pull-right">

                                    <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">                                    
                                        <span id="icon-opciones" class="fa fa-gears"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    
                                    <ul class="dropdown-menu" role="menu">
                                    
                                        @permission('clientes-restricciones-crear')
                                            <li><a href="#" data-toggle="modal" data-target="#addCedulas"><i class="fa fa-plus"></i> Crear</a></li>
                                        @endpermission 
                                        
                                        @permission('clientes-restricciones-exportar')
                                            <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                        @endpermission 
                                    </ul>
                                    
                                </div>
                            

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <input type="number" class="form-control" name="cedula" placeholder="Número documento" value="{{(isset($_GET['cedula'])? $_GET['cedula']:'')}}" autocomplete="off">
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fa fa-search"></i>  Buscar
                                    </button>
                                </div>
                            </div>
                        </form>                        


                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Proyecto</th>
                                    <th>Estado</th>
                                    @permission('clientes-restricciones-eliminar')                                
                                        <th>Acciones</th>
                                    @endpermission
                                </tr>
                                @foreach($clientes as $dato)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        @if($dato->observaciones != null)
                                            <td class="tooltip">                                           
                                                <p>{{$dato->cliente->Identificacion}}</p>
                                                <div class="tooltiptext">{{ $dato->observaciones }}</div>                                            
                                            </td>
                                        @else
                                            <td>
                                                <p>{{$dato->cliente->Identificacion}}</p> 
                                            </td>
                                        @endif
                                        <td>{{mb_convert_case($dato->cliente->NombreBeneficiario . ' ' . $dato->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>                                    
                                        
                                            @if(!empty($dato->cliente->municipio))
                                                <td>{{$dato->cliente->municipio->NombreMunicipio}}</td>
                                                <td>{{$dato->cliente->municipio->departamento->NombreDelDepartamento}}</td>
                                            @else
                                                <td>{{$dato->cliente->ubicacion->municipio->NombreMunicipio}}</td>
                                                <td>{{$dato->cliente->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                            @endif
                                        <td>{{$dato->cliente->proyecto->NumeroDeProyecto}}</td>

                                        <td>{{$dato->cliente->Status}}</td>
                                        <td>
                                            @permission('clientes-restricciones-editar')
                                                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#clienteRestEdit" data-id="{{$dato->id}}"> <i class="fa fa-edit"></i> </button>
                                            @endpermission  
                                            @permission('clientes-restricciones-eliminar')
                                            
                                                <form action="{{route('restricciones.destroy', $dato->id)}}" method="post" style="display:inline-block;">
                                                    <input type="hidden" name="_method" value="delete">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                        <i class="fa fa-trash-o"></i>   
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
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$clientes->currentPage()}} de {{$clientes->lastPage()}}. Total registros {{$clientes->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $clientes->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('clientes-restricciones-crear')
        @include('adminlte::clientes.restricciones.create')
    @endpermission

    @permission('clientes-restricciones-editar')
        @include('adminlte::clientes.restricciones.edit')
    @endpermission
    @section('mis_scripts') 
        @permission('clientes-restricciones-editar')
                <script src="{{asset('js/myfunctions/editar_restriccion.js')}}" type="text/javascript"></script>
        @endpermission

        @permission('clientes-restricciones-listar')
            <script type="text/javascript">
            
                $('#exportar').on('click',function(){
                    var parametros = {                
                        '_token' : $('input:hidden[name=_token]').val() 
                    }

                    $('#opciones').attr('disabled',true);
                    $('#icon-opciones').removeClass('fa-gears');
                    $('#icon-opciones').addClass('fa-refresh fa-spin');

                    $.ajax({
                        type: "POST",
                        url: '/clientes/restricciones/exportar',
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