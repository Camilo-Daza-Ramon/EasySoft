@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-users"></i>  Clientes {{(isset($_GET['estado'])? $_GET['estado']:'')}} {{(isset($_GET['accion'])? ' a '. $_GET['accion']:'')}}</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('clientes.index')}}" role="search" method="GET">
                              <div class="form-group">
                                <input type="text" class="form-control" name="palabra" placeholder="Buscar" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}" autocomplete="off" id="filter-text">
                                <!-- Esconder el Filtro Poryectos cuando sean los usuarios 274 y 275 -->
                                @auth
                                    @if(!in_array(auth()->user()->id, [274, 275]))
                                        <select class="form-control" name="proyecto" id="proyecto" data-unique="mi-select">
                                            <option value="">Elija un proyecto</option>
                                            @foreach($proyectos as $proyecto)
                                                <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                @endauth
                                <select class="form-control" name="departamento" id="departamento" data-unique="mi-select">
                                    <option value="">Elija un departamento</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                    @endforeach
                                </select>
                                
                                <select class="form-control" name="municipio" id="municipio" data-unique="mi-select">
                                    <option value="">Elija un municipio</option>
                                </select> 

                                <select class="form-control" name="estado" id="estado" data-unique="mi-select">
                                    <option value="">Elija un estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{$estado}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado) ? 'selected' : '') : ''}}>{{$estado}}</option>
                                    @endforeach
                                </select>
                                <select class="form-control" name="nodo_id" id="nodo_id"  data-unique="mi-select">
                                    <option value="">Elija Nodo</option>
                                    @foreach(DB::table('NODOS')->get() as $nodo)
                                        <option value="{{ $nodo->nodo_id }}" {{ request('nodo_id') == $nodo->nodo_id ? 'selected' : '' }}>
                                            {{ $nodo->NombreNodo }}
                                        </option>
                                    @endforeach
                                </select>
                                <select class="form-control" name="ComunidadID" id="ComunidadID" data-unique="mi-select">
                                    <option value="">Elija Comunidad</option>
                                    @foreach(DB::table('comunidades')->get() as $comunidad)
                                        <option value="{{ $comunidad->ComunidadID }}" {{ request('ComunidadID') == $comunidad->ComunidadID ? 'selected' : '' }}>
                                            {{ $comunidad->nombre_comunidad }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Filtro por Tipo de Comunidad -->
                                <select class="form-control" name="tipo_comunidad" id="tipo_comunidad" data-unique="mi-select">
                                    <option value="">Elija Tipo de Comunidad</option>
                                    @foreach(['HOGAR', 'ZONA WIFI'] as $tipoComunidad)
                                        <option value="{{ $tipoComunidad }}" {{ request('tipo_comunidad') == $tipoComunidad ? 'selected' : '' }}>
                                            {{ $tipoComunidad }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Filtro por Tipo de Servicio -->
                                <select class="form-control" name="tipo_servicio" id="tipo_servicio"  data-unique="mi-select">
                                    <option value="">Elija Tipo de Servicio</option>
                                    @foreach(['COMUNIDAD DE CONECTIVIDAD', 'PUNTO DE ACCESO COMUNITARIO'] as $tipoServicio)
                                        <option value="{{ $tipoServicio }}" {{ request('tipo_servicio') == $tipoServicio ? 'selected' : '' }}>
                                            {{ $tipoServicio }}
                                        </option>
                                    @endforeach
                                </select>



                                <!-- Filtro por Nodo -->
                              <button type="submit" class="btn btn-default" id="button-filter"> <i class="fa fa-search"></i>  Buscar</button>
                                           
                              </div>
                        </form>
                        
                        <div class="box-tools pull-right">
                           

                            
                            <div class="btn-group">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('clientes-crear')
                                    <li>
                                        <a href="{{route('clientes.create')}}">
                                            <i class="fa fa-plus"></i>  Agregar
                                        </a>
                                    </li>
                                    @endpermission
                                    @permission('clientes-exportar')
                                        
                                        @if(Auth::user()->proyectos()->count() == 0)
                                            <li>
                                                <a href="#" id="exportar" onclick="exportar('');">
                                                    <i class="fa fa-file-excel-o"></i> Exportar
                                                </a>
                                            </li>
                                        @endif

                                        <li>
                                            <a href="#" id="exportar" onclick="exportar('INTERVENTORIA');">
                                                <i class="fa fa-file-excel-o"></i> Exportar {{(Auth::user()->proyectos()->count() == 0)? 'para Interventoria' : ''}} 
                                            </a>
                                        </li>
                                    @endpermission
                                </ul>
                            </div>
                            
                        </div>
                        

                        @role('vendedor')
                        <div class="box-tools pull-right">
                            <a href="{{route('exportar.estado', Auth::user()->id)}}" class="btn btn-xs btn-default" title="Descargar Informacion Rechazados"> <i class="fa fa-file-excel-o text-green"></i></a>
                        </div>
                        @endrole
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>                                    
                                    <th scope="col">Documento</th> 
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Proyecto</th>                      
                                    <th>Estado</th>
                                </tr>
                                @if($clientes->count() > 0)
                                    @foreach($clientes as $dato)
                                    <tr>
                                        <th><a href="{{route('clientes.show', $dato->ClienteId)}}">{{$dato->Identificacion}}</a></th>

                                        <td>{{mb_convert_case($dato->NombreBeneficiario . ' ' . $dato->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>

                                            @if(!empty($dato->municipio))
                                                <td>{{$dato->municipio->NombreMunicipio}}</td>
                                                <td>{{$dato->municipio->departamento->NombreDelDepartamento}}</td>
                                            @else
                                                <td>{{$dato->ubicacion->municipio->NombreMunicipio}}</td>
                                                <td>{{$dato->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                            @endif
                                        <td>{{$dato->proyecto->NumeroDeProyecto}}</td>
                                        <td>
                                            @if($dato->Status == 'ACTIVO')
                                                {{$dato->EstadoDelServicio}}
                                            @elseif($dato->Status == 'APROBADO')
                                            <span class="label label-success">{{$dato->Status}}</span>
                                            @elseif($dato->Status == 'RECHAZADO')
                                            <span class="label label-danger">{{$dato->Status}}</span>
                                            @elseif($dato->Status == 'PENDIENTE')
                                            <span class="label label-warning">{{$dato->Status}}</span>
                                            @else
                                                {{$dato->Status}}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center text-muted"> <h4>NO HAY REGISTROS</h4></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$clientes->currentPage()}} de {{$clientes->lastPage()}}. Total registros {{$clientes->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $clientes->appends(Request::only(['accion','estado','municipio','proyecto','municipio', 'palabra']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('mis_scripts')
    @permission('clientes-exportar')
    <script type="text/javascript">

        const exportar = (formato) => {

            var parametros = {
                documento : "{!! (isset($_GET['palabra'])? $_GET['palabra']:'') !!}",
                proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                accion : "{!! (isset($_GET['accion'])? $_GET['accion']:'') !!}",
                formato : formato,
                '_token' : $('input:hidden[name=_token]').val() 
            }

            $('#opciones').attr('disabled',true);
            $('#icon-opciones').removeClass('fa-gears');
            $('#icon-opciones').addClass('fa-refresh fa-spin');

            $.ajax({
                type: "POST",
                url: '/clientes/exportar',
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

        }
    </script>
    @endpermission

    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>


    
    <script type="text/javascript">
        $(document).ready(function(){
            buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
            buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
        });
    </script>
    @endsection     
@endsection